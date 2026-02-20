# TEST CREDENTIALS - BID FOR USED PRODUCT

## Default User Accounts

All default passwords are already hashed in the database using `password_hash()` with bcrypt algorithm.

### 🔴 ADMIN ACCOUNT

**Purpose**: System administration and oversight

```
Email: admin@localhost.com
Password: admin123
Role: admin
```

**Capabilities**:
- View system-wide statistics
- Manage all users (activate/deactivate)
- View all products from all companies
- View all bids from all clients
- Verify company registrations
- Monitor platform activity
- Cannot be deactivated by itself

---

### 🟢 COMPANY ACCOUNT #1

**Purpose**: Post products and manage bids

```
Email: company1@localhost.com
Password: company123
Company Name: ABC Motors Pvt Ltd
Owner: John Doe
GST: GST123456789
Role: company
Status: Active, Verified
```

**Capabilities**:
- Post new used products
- Edit/delete own products
- View all bids on their products
- Approve or reject bids
- Product automatically closes on bid approval

**Sample Products Posted**:
1. Honda Activa 125 (2-wheeler) - ₹45,000
2. Maruti Swift Dzire (4-wheeler) - ₹4,50,000
3. JCB Excavator (machinery) - ₹12,00,000

---

### 🔵 CLIENT ACCOUNT #1

**Purpose**: Browse products and place bids

```
Email: client1@localhost.com
Password: client123
Name: Ramesh Kumar
Contact: 9998887770
Role: client
Status: Active
```

**Capabilities**:
- Browse all available products
- Search and filter products by category
- View detailed product information
- Place bids on products
- Update pending bids
- View bid history and status
- Subscribe/unsubscribe to product updates
- View company contact details

**Sample Bids Placed**:
1. Bid on Honda Activa 125 - ₹46,000 (Pending)
2. Bid on Maruti Swift Dzire - ₹4,60,000 (Pending)

---

## Creating New Test Accounts

### Register New Company

Use the registration form or manually insert into database:

**Registration Form**: `http://localhost/bid_for_used_product/pages/register_company.php`

**Sample Data**:
- Company Name: XYZ Industries Ltd
- Owner Name: Jane Smith
- Email: company2@localhost.com
- Contact: 9876543210
- Address: 456 Business Park, City Name
- GST Number: GST987654321 (optional)
- Identity Proof: Upload any PDF or image
- Password: company456
- Confirm Password: company456

**Note**: After registration, admin verification is recommended but not required to function.

---

### Register New Client

**Registration Form**: `http://localhost/bid_for_used_product/pages/register_client.php`

**Sample Data**:
- Full Name: Suresh Patel
- Contact: 9123456780
- Email: client2@localhost.com
- Address: 789 Buyer Street, City Name
- Dealership Details: (Optional) ABC Auto Dealers
- Password: client456
- Confirm Password: client456

---

## Password Security

All passwords are stored using PHP's `password_hash()` function with the following features:
- Algorithm: PASSWORD_DEFAULT (currently bcrypt)
- Cost: Default (currently 10)
- Automatic salt generation
- Secure password verification using `password_verify()`

**Example hashed password in database**:
```
$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
```

---

## Testing Scenarios

### Scenario 1: Complete Bid Cycle

1. **Login as Company** (company1@localhost.com)
2. Post a new product (e.g., Motorcycle for ₹30,000)
3. Logout

4. **Login as Client** (client1@localhost.com)
5. Browse products and find the new motorcycle
6. Place a bid of ₹32,000
7. Logout

8. **Login as Company** again
9. Go to "View My Products"
10. Click "Bids" on the motorcycle
11. See the bid from client
12. Click "Approve"
13. Product status changes to "closed"
14. Logout

15. **Login as Client** again
16. Go to "My Bids"
17. See bid status changed to "approved"

---

### Scenario 2: Multiple Competing Bids

1. Register 3 different clients (client2, client3, client4)
2. All three place bids on the same product with different amounts
3. Company views all bids ranked by amount (highest first)
4. Company approves the highest bid
5. Other two bids automatically get "rejected" status

---

### Scenario 3: Admin Management

1. **Login as Admin** (admin@localhost.com)
2. Go to "Manage Users"
3. See all registered users
4. Deactivate a client account
5. Logout

6. **Try to login as deactivated client**
7. Should see "Account inactive" error
8. Cannot login

9. **Login as Admin** again
10. Reactivate the client
11. Client can now login successfully

---

### Scenario 4: Subscription System

1. **Login as Client**
2. Dashboard shows "Subscription: Inactive"
3. Click "Subscribe to Updates"
4. Status changes to "Active"
5. Now subscribed to get new product notifications
6. Click "Unsubscribe"
7. Status changes back to "Inactive"

---

## Database Direct Access

If you need to reset passwords or modify data directly:

**Access phpMyAdmin**: `http://localhost/phpmyadmin`

**Select database**: `bid_for_used_product`

**Common Queries**:

Reset admin password to "admin123":
```sql
UPDATE users 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE email = 'admin@localhost.com';
```

View all users:
```sql
SELECT user_id, name, email, role, status FROM users;
```

View all active products:
```sql
SELECT product_id, product_name, category, base_price, status FROM products;
```

View all bids:
```sql
SELECT b.bid_id, p.product_name, u.name as client_name, b.bid_amount, b.bid_status 
FROM bids b 
JOIN products p ON b.product_id = p.product_id 
JOIN users u ON b.client_id = u.user_id;
```

---

## Security Notes

- All passwords must be at least 8 characters
- Passwords are hashed before storage
- Session timeout: 30 minutes of inactivity
- File uploads limited to 5MB
- Only specific file types allowed (JPG, PNG, PDF)
- SQL injection prevented through PDO prepared statements
- XSS prevention through output sanitization

---

## Quick Reference

| Role | Email | Password | Purpose |
|------|-------|----------|---------|
| Admin | admin@localhost.com | admin123 | System management |
| Company | company1@localhost.com | company123 | Post products |
| Client | client1@localhost.com | client123 | Place bids |

---

**Note**: For production use, change all default passwords and use stronger credentials!
