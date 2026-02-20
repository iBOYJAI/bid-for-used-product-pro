# 📊 Bid For Used Product - DFD & ER Analysis

This document provides a comprehensive breakdown of the **Data Flow Diagrams (DFDs)** at multiple levels and illustrates how the **Entity Relationship (ER)** diagram integrates with the system logic.

---

## 🔧 1. Entity Relationship (ER) Diagram
### Title: Entity Relationship Diagram (ERD) for Database Architecture
The ER diagram uses standard **Crow's Foot Notation** to define the relationship between database tables (Data Stores).

```mermaid
erDiagram
    USERS {
        int user_id PK
        string name
        string email
        string password
        string role
        string status
    }
    COMPANIES {
        int company_id PK
        int user_id FK
        string company_name
        string gst_number
        string verified_status
    }
    PRODUCTS {
        int product_id PK
        int company_id FK
        string product_name
        decimal base_price
        datetime bid_end
        string status
    }
    BIDS {
        int bid_id PK
        int product_id FK
        int client_id FK
        decimal bid_amount
        string bid_status
    }
    NOTIFICATIONS {
        int notification_id PK
        int user_id FK
        string title
        text message
        boolean is_read
    }
    MESSAGES {
        int message_id PK
        int sender_id FK
        int receiver_id FK
        int product_id FK
        text message
    }
    PRODUCT_GALLERY {
        int gallery_id PK
        int product_id FK
        string image_path
    }
    PRODUCT_REMINDERS {
        int reminder_id PK
        int user_id FK
        int product_id FK
    }
    SUBSCRIPTIONS {
        int subscription_id PK
        string email
    }
    CONTACT_MESSAGES {
        int message_id PK
        string name
        string email
        text message
    }

    USERS ||--o| COMPANIES : "registers as"
    USERS ||--o{ BIDS : "places"
    USERS ||--o{ NOTIFICATIONS : "receives"
    USERS ||--o{ PRODUCT_REMINDERS : "sets"
    USERS ||--o{ MESSAGES : "sends/receives"
    COMPANIES ||--o{ PRODUCTS : "lists"
    PRODUCTS ||--o{ BIDS : "receives"
    PRODUCTS ||--o{ PRODUCT_GALLERY : "has"
    PRODUCTS ||--o{ PRODUCT_REMINDERS : "belongs to"
    PRODUCTS ||--o{ MESSAGES : "referenced in"
```

---

## 🌐 2. DFD Level 0: Context Diagram
### Title: DFD Level 0 - System Context Diagram
Following standard DFD notation: **Rectangles** are External Entities, and **Circles** are the System Process.

```mermaid
graph TD
    %% External Entities (Rectangles)
    A[System Admin]
    C[Company / Seller]
    B[Client / Buyer]
    G[Guest / Public]

    %% System Process (Circle)
    P((0.0 Core Bidding System))

    %% Data Flows
    A -- "Verification / User Management" --> P
    P -- "System Analytics / Reports" --> A

    C -- "Post Listings / GST Info" --> P
    P -- "Listing Confirmation / Bid Alerts" --> C

    B -- "Bid Amount / Profile Info" --> P
    P -- "Bid Status / Winner Notification" --> B

    G -- "Browses Products" --> P
    P -- "Product Catalog" --> G
```

---

## 📂 3. DFD Level 1: System Overview
### Title: DFD Level 1 - Major System Processes & Data Flow
Processes are represented as **Circles**, External Entities as **Rectangles**, and Data Stores as **Cylinders/Database Icons**.

```mermaid
graph TD
    %% External Entities
    User[Authorized User]
    Admin[Administrator]
    
    %% Processes (Circles)
    P1((1.0 Auth & Profile))
    P2((2.0 Product Manager))
    P3((3.0 Bidding Engine))
    P4((4.0 Notification Service))
    P5((5.0 System Governance))

    %% Data Stores (Cylinders)
    D1[(D1: Users DB)]
    D2[(D2: Products & Gallery DB)]
    D3[(D3: Bids DB)]
    D4[(D4: Logs & Notifications DB)]

    %% Flows for P1
    User -- "Credentials" --> P1
    P1 <--> D1
    P1 -- "Session Info" --> User

    %% Flows for P2
    User -- "Product Data (Sellers)" --> P2
    P2 <--> D2
    P2 -- "Success" --> User

    %% Flows for P3
    User -- "Bid Amount (Buyers)" --> P3
    P3 <--> D3
    P3 -- "Price Check" --> D2
    P3 -- "New High Bid" --> D2

    %% Flows for P4
    P4 --> D4
    D4 -- "Alerts" --> User

    %% Flows for P5
    Admin -- "Approve IDs" --> P5
    P5 <--> D1
    P5 <--> D2
    P5 -- "Analytics" --> Admin
```

---

## 🔬 4. DFD Level 2: Bidding Process Detail
### Title: DFD Level 2 - Detailed Data Flow for Bidding Engine
This expanded view uses the same academic shapes for consistency.

```mermaid
graph TD
    %% Entity
    Client[Client / Buyer]
    
    %% Processes
    P3_1((3.1 Eligibility Check))
    P3_2((3.2 Increment Validation))
    P3_3((3.3 Transaction Logging))
    P3_4((3.4 Product Update))
    P3_5((3.5 Trigger Alerts))

    %% Data Stores
    D1[(D1: Users DB)]
    D2[(D2: Products DB)]
    D3[(D3: Bids DB)]
    
    %% Detailed Flow
    Client -- "Bid Action" --> P3_1
    P3_1 -- "Verify status" --> D1
    P3_1 -- "Valid" --> P3_2
    
    P3_2 -- "Read Price" --> D2
    P3_2 -- "Price > Max" --> P3_3
    
    P3_3 -- "Commit Bid" --> D3
    P3_3 -- "Done" --> P3_4
    
    P3_4 -- "Update Status" --> D2
    P3_4 -- "Signal" --> P3_5
    
    P3_5 -- "Deliver Notification" --> Client
```

---

## 🎓 5. Understanding the Bridge: ER to DFD
The connection between the static data (ER) and moving data (DFD):

1.  **ER Diagram (Attributes)**: Defines exact fields like `bid_amount`.
2.  **DFD (Flows)**: Shows how `bid_amount` moves from a **Client** into the **Bidding Engine** and finally sits in the **Bids DB**.

---

## 🔧 6. ER Logic Flow Diagram (Conditions & Logic)
### Title: System Logical Flow with ER Integration & Decision Points
This diagram illustrates the **Conditional Logic** within the system, showing how entities interact based on specific business rules and database states.

```mermaid
graph TD
    %% External Entities (Rectangles)
    U[User / Subscriber]
    A[Admin Manager]
    S[Seller / Company]
    B[Buyer / Client]

    %% Start / Terminal
    Start([Registration Flow]) --> Role{Role Type?}

    %% Roles
    Role -- "Company" --> GST{GST Valid?}
    GST -- "No" --> Reject([Registration Denied])
    GST -- "Yes" --> Pending([Status: Pending])
    
    Pending --> AdminCheck{Admin Approve?}
    AdminCheck -- "No" --> Reject
    AdminCheck -- "Yes" --> ActiveS([Status: Verified Seller])

    Role -- "Client" --> ActiveC([Status: Active Buyer])

    %% Product Flow
    ActiveS --> List[Place Product Listing]
    List --> ProdStore[(Products Table)]

    %% Bidding Logic (Conditional)
    ActiveC --> Browse[Browse Catalog]
    Browse --> BidAction[Submit Bid Amount]
    
    BidAction --> TimeCheck{Auction Open?}
    TimeCheck -- "No" --> Closed([Bid Rejected: Time Over])
    TimeCheck -- "Yes" --> ValBid{Bid > Current Max?}
    
    ValBid -- "No" --> LowBid([Bid Rejected: Increase Amount])
    ValBid -- "Yes" --> LogBid[Process & Log Bid]
    
    LogBid --> BidDB[(Bids Table)]
    LogBid --> Notify[Notify Seller & Bidders]

    %% Completion
    ProdStore -- "Time Trigger" --> EndAuction{Auction Ends?}
    EndAuction -- "Yes" --> Winner[Declare Highest Bidder]
    Winner --> MarkSold[Status: Sold]
```

### Logical Conditions & Controls:
1.  **Verification Condition**: Companies must provide a valid GST; otherwise, they are trapped in the `Rejected` state.
2.  **Temporal Constraint**: The `TimeCheck` logic reads the `bid_end` attribute from the **Products** entity.
3.  **Financial Constraint**: The `ValBid` logic compares the input against the `MAX(bid_amount)` in the **Bids** entity.
4.  **Relational Constraint**: Bids can only be processed if the `user_id` is linked to an `active` status in the **Users** entity.

---
**Document Status**: Final Version (Updated Shapes & Logic) | **Project**: Bid For Used Product
