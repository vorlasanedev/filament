---
name: skill-fix-sub-menu-to-topmenu
description: Zero-to-Hero instructions on moving Filament Cluster sub-navigation menus to the top-center of the topbar, without affecting the main sidebar.
---

# Skill: Moving Sub-Navigation to the Topbar

When working with Filament Clusters, you might want the sub-navigation (which lets you switch between cluster resources) to appear in the center of the main top navigation bar, rather than showing up as a sidebar or as tabs within the page content area. This keeps the layout clean and ensures your main sidebar remains unaffected.

Follow these steps to perfectly move and center the sub-navigation menus into the topbar.

## Phase 1: Creating the Teleport Target

We need to inject a container into the center of the topbar that will receive the teleported menus. We do this in your Panel Provider (e.g., `app/Providers/Filament/AdminPanelProvider.php`).

1. Ensure you do **NOT** have `->topNavigation()` enabled, as that moves the *entire* main sidebar to the top, which is not what we want.
2. Add the following render hooks to inject the target container and minimal styling:

```php
->renderHook(
    \Filament\View\PanelsRenderHook::TOPBAR_START,
    fn () => new \Illuminate\Support\HtmlString('<div id="topbar-sub-nav-target" style="position: absolute; left: 50%; transform: translateX(-50%); display: flex; align-items: center; justify-content: center; height: 100%; z-index: 10; width: max-content;"></div>'),
)
->renderHook(
    \Filament\View\PanelsRenderHook::STYLES_AFTER,
    fn () => new \Illuminate\Support\HtmlString('<style>
        .fi-topbar {
            position: relative;
        }
        .fi-page-sub-navigation-tabs {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }
    </style>'),
)
```

> **Note**: We use inline styles for absolute positioning and centering because Tailwind CSS often doesn't scan the `app/Providers` directory by default, which can cause standard utility classes to be ignored.

## Phase 2: Teleporting the Sub-Navigation

Next, we publish and modify Filament's sub-navigation view to "teleport" itself into the container we just made.

1. Publish the panel views:
   ```bash
   php artisan vendor:publish --tag=filament-panels-views
   ```
2. Open the published file: `resources/views/vendor/filament-panels/components/page/sub-navigation/tabs.blade.php`.
3. Wrap the entire contents of the file in an AlpineJS teleport tag:
   ```html
   <template x-teleport="#topbar-sub-nav-target">
       <!-- Original tabs code goes here -->
   </template>
   ```
4. **Mobile Bug Fix**: Inside `tabs.blade.php`, find the `<x-filament::dropdown>` component and add `teleport="true"`. This ensures the dropdown panel doesn't get cut off on mobile devices and fixes touch-event bubbling issues.
   ```html
   <x-filament::dropdown placement="bottom-start" teleport="true">
   ```

## Phase 3: Forcing the "Top" Position

For this logic to trigger, the Resources in your Cluster must have their sub-navigation position set to "Top". 

Usually, setting this in your Cluster class is enough:
```php
protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
```

However, if a Resource uses a third-party trait (like `HasNavigation` from Filament Shield/Essentials) that hardcodes the position to `Start` (left sidebar), you must override it directly inside your Resource class (e.g., `RoleResource.php`):

```php
public static function getSubNavigationPosition(): \Filament\Pages\Enums\SubNavigationPosition
{
    return \Filament\Pages\Enums\SubNavigationPosition::Top;
}
```

Once applied, the sub-navigation will seamlessly disappear from the page layout and magically appear centered inside your top navigation bar!
