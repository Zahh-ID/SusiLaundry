# Project Structure Guide

This guide explains where key files are located in the Susi Laundry codebase, helping developers understand the organization.

## Root Directory
*   `app/`: Core application code (Models, Controllers, Livewire).
*   `config/`: Configuration files (e.g., `orders.php` for status lists).
*   `database/`: Database tools.
    *   `migrations/`: Files to create/modify DB tables.
    *   `seeders/`: Fake data for testing.
*   `public/`: Accessible from the web (images, compiled assets).
*   `resources/`: Raw assets.
    *   `views/`: HTML Templates (Blade files).
*   `routes/`: URL definitions (`web.php`).
*   `tests/`: Automated tests.

## Key Application Directories (`app/`)
*   `app/Models/`: **M** (Model) - Database representations.
    *   `Order.php`, `Customer.php`.
*   `app/Livewire/`: **C** (Controller/Component) - Logic for dynamic pages.
    *   `Admin/`: Admin-specific logic (e.g., `Order/Create.php`).
    *   `TrackOrder.php`: Public tracking logic.

## Key View Directories (`resources/views/`)
*   `resources/views/layouts/`: Base HTML structures.
    *   `admin.blade.php`: Admin dashboard shell (sidebar, nav).
    *   `site.blade.php`: Public website shell.
*   `resources/views/livewire/`: **V** (View) - Component templates.
    *   `admin/order/create.blade.php`: The "Input Pesanan" form.
    *   `track-order.blade.php`: The tracking page UI.

## Flow Example
1.  **URL**: `/admin/orders`
2.  **Route**: Defined in `routes/web.php`.
3.  **Component**: `app/Livewire/Admin/Order/Index.php`.
4.  **View**: `resources/views/livewire/admin/order/index.blade.php`.
