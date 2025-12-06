# MVC Architecture in Susi Laundry

The application is built using **Laravel** (MVC Framework) and **Livewire** (Interactive Components).

## 1. Model (M)
Represents the data structure and business logic.
- Located in `app/Models/`.
- Examples: `Order.php`, `Package.php`, `Customer.php`.
- **Role**: Interacts with the database, defines relationships (e.g., `Order::payments()`), and contains domain logic (e.g., `Order::nextStatus()`).

## 2. View (V)
Represents the user interface.
- Located in `resources/views/`.
- **Blade Templates**: `layouts/admin.blade.php`, `landing.blade.php`.
- **Livewire Views**: `resources/views/livewire/admin/order/create.blade.php`.
- **Role**: Displays data to the user using HTML/TailwindCSS. Livewire views are dynamic and update without full page reloads.

## 3. Controller (C)
Handles incoming requests and returns responses.
- **Traditional Controllers**: `OrderPrintController` (handles PDF generation).
- **Livewire Components (Modern "C")**:
    - Located in `app/Livewire/`.
    - Examples: `Admin\Order\Index.php`, `TrackOrder.php`.
    - **Role**:
        - Acts as a Controller/ViewModel hybrid.
        - Handles state (public properties like `$order_code`).
        - Handles events/actions (`save()`, `refreshStatus()`).
        - Binds data directly to the View.

---

## Request Lifecycle (Example: Tracking Page)

1.  **Request**: User visits `/tracking?code=ABC12345`.
2.  **Route**: `routes/web.php` maps `/tracking` to `TrackOrder` class.
3.  **Component (Controller)**: `TrackOrder::mount()` reads the `code` query parameter.
4.  **Model**: `TrackOrder` calls `Order::where(...)` to fetch data from DB.
5.  **View**: `TrackOrder` renders `track-order.blade.php` with the fetched Order data.
6.  **Response**: Browser receives HTML and renders the page.
7.  **Interaction**:
    - User waits.
    - `wire:poll` triggers `refreshStatus()` action.
    - Component logic checks `expiry_time`.
    - If expired, Component uses Model (Payment) to create new row.
    - View updates seamlessly via AJAX.
