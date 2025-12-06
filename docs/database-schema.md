# Database Schema

This document details the database structure for Susi Laundry.

## Tables

### 1. Users
Stores admin users.

| Column | Type | Notes |
| :--- | :--- | :--- |
| `id` | BigIncrements | Primary Key |
| `name` | String | |
| `email` | String | Unique |
| `password` | String | Hashed |
| `created_at` | Timestamp | |
| `updated_at` | Timestamp | |

### 2. Packages
Laundry service packages available for selection.

| Column | Type | Notes |
| :--- | :--- | :--- |
| `id` | BigIncrements | Primary Key |
| `package_name` | String | e.g. "Cuci Setrika Express" |
| `price_per_kg` | Decimal | |
| `turnaround_hours` | Integer | Est. time in hours |
| `billing_type` | Enum | `kg`, `item` |
| `description` | Text | |

### 3. Customers
Customer profiles (auto-saved from orders).

| Column | Type | Notes |
| :--- | :--- | :--- |
| `id` | BigIncrements | Primary Key |
| `name` | String | |
| `phone` | String | |
| `email` | String | |
| `address` | Text | |

### 4. Orders
The core transactional table.

| Column | Type | Notes |
| :--- | :--- | :--- |
| `id` | BigIncrements | Primary Key |
| `order_code` | String | Unique, 10-char tracking code |
| `customer_id` | FK | -> customers.id |
| `package_id` | FK | -> packages.id |
| `admin_id` | FK | -> users.id (Creator) |
| `status` | String | `pending_confirmation`, `processing`, `ready_for_pickup`, `taken`, `completed`, `cancelled` |
| `payment_status` | String | `pending`, `paid`, `unpaid` |
| `payment_method` | String | `cash`, `qris` |
| `service_type` | String | e.g. `reguler`, `express` |
| `estimated_weight` | Decimal | Initial weight input |
| `actual_weight` | Decimal | Final confirmed weight |
| `price_per_kg` | Decimal | Checkpoint price at time of order |
| `delivery_fee` | Decimal | |
| `total_price` | Decimal | `(weight * price) + fees` |
| `pickup_or_delivery` | String | `none`, `pickup`, `delivery` |
| `activity_log` | JSON | History of status changes/actions |

### 5. Payments
Tracks individual payment attempts (especially QRIS transactions).

| Column | Type | Notes |
| :--- | :--- | :--- |
| `id` | BigIncrements | Primary Key |
| `order_id` | FK | -> orders.id |
| `method` | String | `cash`, `qris` |
| `status` | String | `pending`, `paid`, `expired`, `failed` |
| `amount` | Decimal | |
| `qris_url` | String | Deep link for payment apps |
| `qris_image_url` | String | QR Code image source |
| `expiry_time` | Timestamp | |
| `midtrans_transaction_id`| String | External Gateway ID |

## Relationships
- **Order** belongs to **User** (Admin)
- **Order** belongs to **Customer**
- **Order** belongs to **Package**
- **Order** has many **Payments**
- **Customer** has many **Orders**
