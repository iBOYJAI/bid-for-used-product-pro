# BID FOR USED PRODUCT - PROJECT SCREENS DESCRIPTION

## For Academic/Project Report Documentation

This document describes all the screens/pages available in the BID FOR USED PRODUCT offline auction system. Use this for your project report documentation.

---

## 1. Landing Page (index.php)

**Purpose**: Welcome page and entry point to the system

**Features**:
- Application title and description
- "Login" button - directs to login page
- "Register as Company" button - directs to company registration
- "Register as Client" button - directs to client registration
- Attractive gradient background design
- Fully responsive layout

**Screenshot Description**: Landing page with purple gradient background, centered content displaying app name "BID FOR USED PRODUCT" with subtitle "Offline Online Auction System" and three action buttons.

---

## 2. Login Page (pages/login.php)

**Purpose**: User authentication for all roles

**Features**:
- Email and password input fields
- Single login form for all user types (Admin, Company, Client)
- Automatic role-based redirection after login
- Error messages for invalid credentials or inactive accounts
- Success message after registration
- Links to registration pages
- Form validation (client-side and server-side)

**Flow**:
- User enters email and password
- System verifies credentials
- Redirects to appropriate dashboard based on role

**Screenshot Description**: Clean white card with login form containing email and password fields, blue login button, and registration links at bottom.

---

## 3. Company Registration (pages/register_company.php)

**Purpose**: Allow companies to create accounts

**Fields Required**:
- Company Name
- Owner Name
- Email Address
- Contact Number (10 digits)
- Company Address
- GST Number (optional)
- Identity Proof Upload (PDF/Image, max 5MB)
- Password (minimum 8 characters)
- Confirm Password

**Features**:
- Real-time form validation
- File upload preview
- Password match checking
- Email uniqueness validation
- Automatic password hashing before storage

**Screenshot Description**: Multi-field registration form with file upload option, organized in two-column layout for better UX.

---

## 4. Client Registration (pages/register_client.php)

**Purpose**: Allow clients/buyers to create accounts

**Fields Required**:
- Full Name
- Contact Number
- Email Address
- Address
- Dealership Details (optional)
- Password (minimum 8 characters)
- Confirm Password

**Features**:
- Simpler than company registration
- Optional dealership information
- Password strength indicator
- Form validation

**Screenshot Description**: Registration form with personal details fields, organized neatly with validation indicators.

---

## 5. Admin Dashboard (dashboard/admin_dashboard.php)

**Purpose**: Central control panel for system administrator

**Statistics Displayed**:
- Total Users (all roles)
- Total Companies
- Total Clients
- Pending Company Verifications
- Total Products Posted
- Open Products
- Total Bids Received
- Approved Bids

**Quick Actions**:
- Manage Users
- View All Products
- View All Bids
- Verify Companies

**Additional Sections**:
- Recent user registrations table
- Recent products posted table
- Activity monitoring

**Screenshot Description**: Dashboard with colorful statistic cards at top showing system metrics, followed by quick action buttons and tables showing recent activity.

---

## 6. Company Dashboard (dashboard/company_dashboard.php)

**Purpose**: Main interface for companies to manage their listings

**Statistics Displayed**:
- Total Products Posted
- Open Products (actively bidding)
- Total Bids Received
- Approved Bids

**Quick Actions**:
- Post New Product
- View My Products
- View All Bids

**Additional Sections**:
- Recent products table with status and bid counts
- Direct links to view bids on each product

**Screenshot Description**: Dashboard with four gradient statistic cards, quick action buttons, and a table listing recent products with their details and action buttons.

---

## 7. Client Dashboard (dashboard/client_dashboard.php)

**Purpose**: Main interface for clients to browse and track bids

**Statistics Displayed**:
- Available Products (open for bidding)
- My Total Bids
- Approved Bids
- Subscription Status (Active/Inactive)

**Quick Actions**:
- Browse Products
- My Bids
- Subscribe/Unsubscribe to Updates

**Additional Sections**:
- Recent products grid with product cards
- Product images, details, and action buttons
- "View Details" and "Place Bid" buttons on each product

**Screenshot Description**: Dashboard with statistics, action buttons, and a grid layout displaying product cards with images, pricing, and bid buttons.

---

## 8. Post Product (company/post_product.php)

**Purpose**: Allow companies to list used products for bidding

**Fields**:
- Product Name
- Category (2-wheeler / 4-wheeler / machinery)
- Model Name
- Manufacturing Year
- Chassis Number (optional)
- Owner Details
- Running Duration (km or hours)
- Base Bid Price
- Bid Start Date & Time
- Bid End Date & Time
- Product Image Upload (optional)

**Features**:
- Datetime picker for bid duration
- Image upload with preview
- Validation: End date must be after start date
- Base price must be positive number

**Screenshot Description**: Comprehensive product posting form with datetime pickers, dropdown menus, file upload, and submit button.

---

## 9. My Products (company/my_products.php)

**Purpose**: List all products posted by the logged-in company

**Displayed Information**:
- Product Name
- Category
- Model
- Base Price
- Status (Open/Closed)
- Bid Duration
- Number of Bids Received

**Actions Available**:
- View Bids (shows all bids for that product)
- Edit Product (only if still open)
- Delete Product (only if still open)

**Screenshot Description**: Table layout showing all company's products with color-coded status badges and action buttons for each product.

---

## 10. View Bids (company/view_bids.php)

**Purpose**: Display all bids received for a specific product

**Sections**:

**Product Summary**:
- Product details recap
- Base price
- Current status

**Bids Table**:
- Rank (sorted by bid amount, highest first)
- Client Name
- Contact Information (email, phone)
- Bid Amount
- Comments/Notes
- Bid Date
- Bid Status (Pending/Approved/Rejected)
- Action Buttons (Approve/Reject for pending bids)

**Features**:
- Highest bid highlighted
- Approve button: Approves bid, rejects all others, closes product
- Reject button: Rejects individual bid

**Screenshot Description**: Product details card followed by a table ranking all bids by amount, with approve/reject buttons for each pending bid.

---

## 11. Browse Products (client/browse_products.php)

**Purpose**: Allow clients to discover available products

**Features**:
- Search bar (search by product name or model)
- Category filter (All / 2-wheeler / 4-wheeler / Machinery)
- Product grid layout with cards
- Each card shows:
  - Product image (or category icon if no image)
  - Product name
  - Company name
  - Category, Model, Year
  - Base price
  - Running duration
  - Bid end date
  - Total bids count
  - "View Details" and "Place Bid" buttons

**Filter Options**:
- Filter button to apply search/category
- Clear button to reset filters

**Screenshot Description**: Search and filter bar at top, followed by a responsive grid of product cards showing images, details, and action buttons.

---

## 12. Product Details (client/product_details.php)

**Purpose**: Show comprehensive information about a specific product

**Layout**: Two-column design

**Left Column**:
- Large product image (or category placeholder)
- "Place Bid" or "Update Bid" button
- "Back to Products" button

**Right Column**:
- Product name and company
- Large base price display
- Product specifications:
  - Category, Model, Year
  - Chassis Number
  - Running Duration
  - Owner Details
  - Product Status
- Bidding Information:
  - Bid start and end dates
  - Total bids count
  - Highest bid amount
- User's Bid (if placed):
  - Bid amount
  - Status
  - Bid date
- Company Contact Information:
  - Company name and owner
  - Contact number
  - Email address

**Screenshot Description**: Split-screen layout with product image on left and detailed specifications with pricing on right, styled with distinct sections for easy reading.

---

## 13. Place Bid (client/place_bid.php)

**Purpose**: Submit or update a bid on a product

**Sections**:

**Product Summary** (in highlighted box):
- Product name
- Company name
- Category, Model, Year
- Base price (prominently displayed)
- Bid deadline

**Bid Form**:
- Bid Amount input (must be > base price)
- Minimum bid indicator
- Comments/Notes textarea (optional)
- "Place Bid" or "Update Bid" button
- Cancel button

**Features**:
- Shows existing bid if already placed
- Validation: Bid must exceed base price
- Pre-fills form if updating existing bid

**Screenshot Description**: Product summary in a colored info box at top, followed by bid amount input field with validation, comments section, and action buttons.

---

## 14. My Bids (client/my_bids.php)

**Purpose**: Track all bids placed by the client

**Filter Options**:
- All Status
- Pending Only
- Approved Only
- Rejected Only

**Bids Table**:
- Product Name
- Company Name
- Category
- Base Price
- My Bid Amount (highlighted)
- Bid Status (color-coded badges)
- Bid Date
- "View Product" link

**Summary Statistics** (at bottom):
- Total Bids Count
- Pending Bids Count
- Approved Bids Count
- Rejected Bids Count

**Screenshot Description**: Filter dropdown at top, table showing all bids with color-coded status badges, and summary cards at bottom showing bid statistics.

---

## 15. Manage Users (admin/manage_users.php)

**Purpose**: Admin page to oversee all registered users

**Filter**:
- All Roles / Admin / Company / Client

**Users Table**:
- User ID
- Name
- Email
- Role (color-coded badge)
- Contact Number
- Account Status (Active/Inactive badge)
- Registration Date
- Action: Activate / Deactivate button

**Features**:
- Cannot deactivate own account
- One-click status toggle
- Prevents self-modification

**Screenshot Description**: Filter dropdown, comprehensive user table with role and status badges, action buttons for account management.

---

## 16. View All Products (admin/view_all_products.php)

**Purpose**: Admin overview of all products in the system

**Table Columns**:
- Product ID
- Product Name
- Company Name
- Category
- Model and Year
- Base Price
- Number of Bids
- Product Status
- Posted Date

**Purpose**: System monitoring and analytics

**Screenshot Description**: Detailed table showing all products across all companies with bid counts and status indicators.

---

## 17. View All Bids (admin/view_all_bids.php)

**Purpose**: Admin view of all bidding activity

**Table Columns**:
- Bid ID
- Product Name
- Company Name
- Client Name
- Bid Amount
- Bid Status (Pending/Approved/Rejected)
- Bid Date

**Purpose**: Transaction monitoring and dispute resolution

**Screenshot Description**: Comprehensive table listing all bids systemwide with status indicators and participant information.

---

## 18. Verify Companies (admin/verify_companies.php)

**Purpose**: Review and verify company registrations

**Table Columns**:
- Company ID
- Company Name
- Owner Name
- GST Number
- Contact Details (phone and email)
- Verification Status (Pending/Verified badge)
- Registration Date
- Actions:
  - "View Proof" button (opens identity document)
  - "Verify" button (for pending companies)

**Features**:
- Direct link to view uploaded identity proof
- One-click verification
- Status tracking

**Screenshot Description**: Table showing company details with verification status badges, links to view identity proofs, and verify buttons for pending companies.

---

## Navigation Flow Diagram

```
Landing Page
├── Login → Role-Based Dashboard
│   ├── Admin Dashboard
│   │   ├── Manage Users
│   │   ├── View All Products
│   │   ├── View All Bids
│   │   └── Verify Companies
│   ├── Company Dashboard
│   │   ├── Post Product
│   │   ├── My Products
│   │   │   ├── Edit Product
│   │   │   ├── View Bids
│   │   │   │   ├── Approve Bid
│   │   │   │   └── Reject Bid
│   │   │   └── Delete Product
│   │   └── Logout
│   └── Client Dashboard
│       ├── Browse Products
│       │   ├── Product Details
│       │   └── Place Bid
│       ├── My Bids
│       ├── Subscribe/Unsubscribe
│       └── Logout
├── Register as Company
└── Register as Client
```

---

## Color Scheme & Design Elements

**Primary Colors**:
- Primary Blue: #2563eb
- Success Green: #10b981
- Warning Orange: #f59e0b
- Danger Red: #ef4444

**Design Features**:
- Modern card-based layouts
- Gradient backgrounds for statistics
- Color-coded status badges
- Responsive grid layouts
- Smooth transitions and hover effects
- Clean typography
- Professional color palette

---

## Security Features Visible to Users

1. **Password Requirements**: Minimum 8 characters displayed on registration
2. **File Upload Limits**: "Max 5MB - PDF, JPG, PNG" shown on upload fields
3. **Session Timeout**: Automatic logout after 30 minutes inactivity
4. **Error Messages**: Generic errors to not reveal system information
5. **Confirmation Dialogs**: "Are you sure?" prompts for critical actions

---

Use this document as a reference for creating screenshots and descriptions for your project report. Each section describes what the screen does, what information it displays, and how users interact with it.
