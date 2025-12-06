# Features of Susi Laundry Management System

This document outlines the core features and functionalities of the Susi Laundry web application.

## 1. Landing Page (Public)
*   **Dynamic Package Display**: Shows available laundry packages (fetched from database).
*   **Order Tracking**: Public tracking form to check order status without login.
*   **Testimonials & Marketing**: Sections for user engagement (FAQ, features, etc.).

## 2. Order Management (Admin)
*   **Input Pesanan Offline (Walk-in)**:
    *   Wizard-based creation form (Service -> Details -> Confirmation).
    *   Supports immediate Cash payment confirmation.
    *   Supports QRIS generation for cashless payment.
*   **Order List**:
    *   Pagination and filtering by status, service type, and date.
    *   Search by Order Code or Customer Name.
*   **Order Workflow State Machine**:
    *   Strict status progression: `Pending Confirmation` -> `Processing` -> `Ready for Pickup` -> `Taken` -> `Completed`.
    *   **Payment Gates**: Prevents moving to `Taken` if order is unpaid.
    *   **QRIS Management**: Auto-generates QRIS for `Processing` orders; supports regeneration if expired.

## 3. Customer Tracking (Public)
*   **Real-time Status**: Users track orders via a unique 10-digit Order Code.
*   **Payment Integration**:
    *   Displays active QRIS code for unpaid orders.
    *   **Auto-Regeneration**: Automatically generates a new QR code if the previous one expired while viewing.
    *   Shows explicit "Paid" or "Pending" status.
*   **Timeline**: Visual progress bar of the laundry process.

## 4. Admin Dashboard
*   **Metrics**: Revenue, Active Orders, Completed Orders overview.
*   **Charts**: Visual representation of sales trends.

## 5. System Features
*   **Authentication**: Secure Admin login/logout.
*   **Email Notifications**:
    *   Order Created (with Tracking Code).
    *   Status Updates (e.g., "Ready for Pickup").
    *   Payment Confirmation Receipts.
*   **PDF Printing**: Generate thermal-printer friendly receipts/invoices.
*   **Export/Reports**: (Planned/Partial) Export order data for accounting.

## 6. Payment System
*   **Cash**: Manual confirmation by Admin.
*   **QRIS (Midtrans/Gateway)**:
    *   Dynamic generation based on order amount.
    *   Expiration handling (auto-expire old, regen new).
    *   Webhook integration (simulation available).
