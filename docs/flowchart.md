# Order Process Flowchart

```mermaid
flowchart TD
    Start([Customer Arrives]) --> Type{Order Type?}
    
    %% Walk-in Flow
    Type -- Walk-in --> AdminInput[Admin Inputs Order\n(Wizard Form)]
    AdminInput --> PkgSelect[Select Package]
    PkgSelect --> Details[Input Customer Details]
    Details --> Weight[Input/Est Weight]
    
    Weight --> PayMethod{Payment?}
    
    %% Cash Payment
    PayMethod -- Cash --> CashConfirm{Paid Now?}
    CashConfirm -- Yes --> MarkPaid[Status: Paid]
    CashConfirm -- No --> MarkUnpaid[Status: Unpaid]
    
    %% QRIS Payment
    PayMethod -- QRIS --> GenQR[Generate QRIS Code]
    GenQR --> ShowQR[Show QR on Check/Trace]
    
    MarkPaid --> SaveOrder[Save Order]
    MarkUnpaid --> SaveOrder
    ShowQR --> SaveOrder
    
    SaveOrder --> Process[Status: Processing]
    
    %% Processing Loop & Payment Check
    Process --> WashDry[Laundering...]
    WashDry --> Ready[Status: Ready for Pickup]
    
    Ready --> Pickup{Customer Pickup}
    
    Pickup --> CheckPay{Is Paid?}
    CheckPay -- No --> PaymentAlert[Show Payment Alert]
    PaymentAlert --> PayNow[Receive Payment]
    PayNow --> CheckPay
    
    CheckPay -- Yes --> Handover[Handover Items]
    Handover --> Taken[Status: Taken\n(Final)]
    Taken --> End([Completed])
```

## Description
1.  **Creation**: Admin inputs order via 3-step wizard.
2.  **Payment Decision**:
    *   **Cash**: Can be marked paid immediately or deferred.
    *   **QRIS**: System generates a dynamic QR code.
3.  **Processing**: Order moves through laundry stages.
4.  **Strict Gate**: The order **cannot** move to "Taken" (Diambil) until the system confirms `payment_status = 'paid'`.
5.  **Completion**: Once handed over, the lifecycle ends.
