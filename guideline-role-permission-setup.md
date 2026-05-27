# Step-by-Step Guideline: Setting up Users, Roles, and Permissions in Filament

If you want to implement this exact menu structure (Users, Roles, and Permissions under a single "User Management" menu) from scratch in another Filament project or recreate it yourself, follow these steps carefully.

## Step 1: Install Filament Shield
Make sure you have installed the `bezhan-salleh/filament-shield` package.
```bash
composer require bezhan-salleh/filament-shield
php artisan shield:install
```

## Step 2: Configure the User Resource
Open your `UserResource.php` file located at `app/Filament/Resources/UserResource.php`.

Remove any `$cluster` variable if it exists, and add the `$navigationGroup` and `$navigationSort` properties:
```php
// Remove this if it exists:
// protected static ?string $cluster = UserManagementCluster::class;

// Add these:
protected static \UnitEnum|string|null $navigationGroup = 'User Management';
protected static ?int $navigationSort = 1;
```

## Step 3: Override the Role Resource Group
Filament Shield publishes its own `RoleResource`. To place it under your custom group, open `app/Filament/Resources/Roles/RoleResource.php` (if it was published, otherwise you may need to publish it first or configure it in `config/filament-shield.php`).

Add the `getNavigationGroup()` method and set the sort order:
```php
protected static ?int $navigationSort = 2;

public static function getNavigationGroup(): ?string
{
    return 'User Management';
}
```

## Step 4: Create a Custom Permission Resource
Filament Shield doesn't include a dedicated "Permissions" page by default. You need to create one manually.

Create a new file: `app/Filament/Resources/PermissionResource.php`
```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use Spatie\Permission\Models\Permission;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-key';
    protected static \UnitEnum|string|null $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('guard_name')
                    ->required()
                    ->maxLength(255)
                    ->default('web'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('guard_name')->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
```

Then, you must create the page classes inside `app/Filament/Resources/PermissionResource/Pages/`:
- `ListPermissions.php`
- `CreatePermission.php`
- `EditPermission.php`
*(You can use the standard Filament Resource Page classes for these).*

## Step 5: Update AdminPanelProvider
To prevent the Shield plugin from creating its own separate "Roles and Permission" group in the sidebar, tell it to use your custom group.

Open `app/Providers/Filament/AdminPanelProvider.php` and update the plugin registration:
```php
->plugins([
    \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
        ->navigationGroup('User Management') // Match the exact string used in your resources
])
```

## Step 6: Generate Shield Permissions
Finally, now that you have introduced a new `PermissionResource`, you must generate permissions for it so that your Super Admin and other roles can interact with it.

Run the following command in your terminal:
```bash
php artisan shield:generate --resource=PermissionResource
```

That's it! When you refresh your Filament dashboard, you will have a perfectly grouped **User Management** menu containing **Users**, **Roles**, and **Permissions**.
