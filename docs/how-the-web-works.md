# How The Web Works (Context: Susi Laundry)

This is a simplified explanation of how this web application operates for a beginner.

## 1. Client-Server Model
*   **Client (You/Browser)**: The device (Phone/Laptop) accessing the website. It sends **Requests**.
*   **Server (Susi Laundry App)**: The computer running the PHP/Laravel code and hosting the Database. It sends **Responses**.

## 2. The Journey of a Request
When you click "Cek Status" on the tracking page:

1.  **DNS Lookup**: The browser asks "Where is susilaundry.com?" and gets an IP address.
2.  **HTTP Request**: The browser sends a message to that IP:
    > "GET /tracking?code=XYZ"
3.  **Server Processing (Laravel)**:
    *   The server receives the message.
    *   It looks at the URL (`/tracking`).
    *   It wakes up the `TrackOrder` code.
    *   The code asks the **Database**: "Do we have an order with code XYZ?"
    *   The Database replies: "Yes, status is 'Processing'."
4.  **HTML Generation**: The server constructs a webpage (HTML) combining the template + the data ("Processing").
5.  **HTTP Response**: The server sends the HTML back to the browser.
6.  **Rendering**: Your browser reads the HTML and draws the text, colors, and buttons you see.

## 3. Dynamic Updates (Livewire / AJAX)
Modern web apps like this one don't reload the whole page for every little change (like checking if payment is done).

*   **AJAX**: The browser sends a "secret" background message to the server: "Any updates?"
*   **Server**: "Yes, payment received!"
*   **DOM Diffing**: The browser only changes the specific part of the screen (`Unpaid` -> `Paid`) without blinking or refreshing the whole page.

## 4. Database
Think of the database as a giant Excel sheet managed by the Server.
*   **Table `orders`**: Rows of orders.
*   **Table `customers`**: Rows of people.
*   We use **SQL** (Structured Query Language) to talk to it: `SELECT * FROM orders WHERE code = 'XYZ'`.
