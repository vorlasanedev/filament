# Project Setup Guide

Follow these steps to resolve the errors and successfully run the project using `php artisan serve`.

## Step 1: Enable Required PHP Extensions
Since you are using XAMPP, you need to enable a few PHP extensions that are required by the project's dependencies (`zip`, `sodium`, and `gd`).

1. Open your `php.ini` file located at: `C:\xampp\php\php.ini` (which you currently have open).
2. Use `Ctrl + F` to find the following lines and **remove the semicolon (`;`)** at the beginning of each line to uncomment them:
   ```ini
   ;extension=gd
   ;extension=sodium
   ;extension=zip
   ```
   *Change them to:*
   ```ini
   extension=gd
   extension=sodium
   extension=zip
   ```
3. Save the `php.ini` file.
4. **Restart Apache** from your XAMPP Control Panel for the changes to take effect.

## Step 2: Install Composer Dependencies
The project is currently missing the `vendor` directory because dependencies haven't been installed. Furthermore, some packages in this project require PHP 8.3/8.4, but XAMPP is currently running PHP 8.2.

Open your terminal in `d:\development\filament` and run:

```bash
composer install --ignore-platform-reqs
```
*(The `--ignore-platform-reqs` flag tells Composer to ignore the PHP version mismatch for now so it can finish the installation).*

> **Note:** If you encounter weird bugs later on, it is highly recommended to upgrade your XAMPP installation to a version that supports **PHP 8.3**.

## Step 3: Setup Environment Variables
If you don't have a `.env` file yet, you need to create one and generate an application key.

Run the following commands in your terminal:
```bash
cp .env.example .env
php artisan key:generate
```

## Step 4: Run Database Migrations
Before starting the server, you need to set up your database tables by running migrations:

```bash
php artisan migrate
```

> **Troubleshooting: `'mysql' is not recognized` error**
> If you get an error saying the `mysql` command is not recognized, it's because XAMPP's MySQL binary isn't in your system's PATH. To fix this:
> 1. Press the Windows key, type **Environment Variables**, and select **"Edit the system environment variables"**.
> 2. Click the **Environment Variables...** button.
> 3. Under **System variables**, select `Path` and click **Edit**.
> 4. Click **New** and paste: `C:\xampp\mysql\bin`
> 5. Click **OK** on all windows, close your current terminal, open a new one, and run the command again.

## Step 5: Create Superadmin User
To access the Filament admin panel, you need a superadmin account. Run these commands to seed the initial superadmin and assign the correct role:

```bash
php artisan db:seed
php artisan shield:super-admin --user=1
```
*(When prompted for the panel, press `0` for `admin`)*

This creates admin accounts with the following credentials:
- **Email:** `admin@example.com` or `superuser@gmail.com`
- **Password:** `Root@mysql`

## Step 6: Start the Server
Now that everything is installed and configured, you can start the development server:

```bash
php artisan serve
```

Your application should now be accessible at `http://127.0.0.1:8000/admin/login`.
