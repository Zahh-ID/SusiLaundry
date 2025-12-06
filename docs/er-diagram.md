# Entity Relationship Diagram (ERD)

This diagram visualizes the relationships between the database tables.

```mermaid
erDiagram
    USERS ||--o{ ORDERS : "manages"
    CUSTOMERS ||--o{ ORDERS : "places"
    PACKAGES ||--o{ ORDERS : "contains"
    ORDERS ||--o{ PAYMENTS : "has"
    USERS ||--o{ ACTIVITY_LOGS : "causes"

    USERS {
        bigint id PK
        string name
        string email
        string password
    }

    CUSTOMERS {
        bigint id PK
        string name
        string phone
        string email
        text address
    }

    PACKAGES {
        bigint id PK
        string package_name
        decimal price_per_kg
        int turnaround_hours
        string billing_type
    }

    ORDERS {
        bigint id PK
        string order_code "Unique 10-char"
        foreignId customer_id FK
        foreignId package_id FK
        foreignId admin_id FK
        string status
        string payment_status
        string payment_method
        decimal total_price
        decimal actual_weight
    }

    PAYMENTS {
        bigint id PK
        foreignId order_id FK
        string method
        string status
        decimal amount
        string qris_image_url
        timestamp expiry_time
    }
```

## Relationship Logic
*   **One Admin (User)** can manage **Many Orders**.
*   **One Customer** can have **Many Orders**.
*   **One Package** can be applied to **Many Orders**.
*   **One Order** can have **Many Payment Attempts** (e.g., failed QRIS, then success).
