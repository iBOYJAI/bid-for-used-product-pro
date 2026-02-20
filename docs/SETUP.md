# SETUP INSTRUCTIONS - BID FOR USED PRODUCT

## Complete Setup Guide for Windows + XAMPP

### STEP 1: Install XAMPP

1. Download XAMPP for Windows from: https://www.apachefriends.org/download.html
2. Run the installer as Administrator
3. Select components (make sure Apache and MySQL are selected)
4. Install to default location: C:\xampp
5. Complete installation

### STEP 2: Start XAMPP Services

1. Open XAMPP Control Panel from Start Menu
2. Click "Start" button next to Apache - wait for green "Running" status
3. Click "Start" button next to MySQL - wait for green "Running" status
4. Keep XAMPP Control Panel open while using the application

### STEP 3: Copy Project Files

1. Locate your project folder: `bid_for_used_product`
2. Copy the entire `bid_for_used_product` folder
3. Paste it into: `C:\xampp\htdocs\`
4. Final path should be: `C:\xampp\htdocs\bid_for_used_product\`

### STEP 4: Import Database

1. Open your web browser (Chrome/Firefox/Edge)
2. Navigate to: `http://localhost/phpmyadmin`
3. In phpMyAdmin interface:
   - Click "New" in the left sidebar
   - Database name: `bid_for_used_product`
   - Collation: `utf8mb4_general_ci`
   - Click "Create"
4. Select the newly created database from left sidebar
5. Click on "Import" tab at the top
6. Click "Choose File" button
7. Navigate to: `C:\xampp\htdocs\bid_for_used_product\database\database.sql`
8. Select the file and click "Open"
9. Scroll down and click "Go" button
10. Wait for "Import has been successfully finished" message
11. You should now see 5 tables created: users, companies, products, bids, subscriptions

### STEP 5: Verify Installation

1. In phpMyAdmin, click on `users` table
2. You should see 3 sample users (admin, company, client)
3. Click on `products` table
4. You should see 3 sample products

### STEP 6: Access the Application

1. Open your web browser
2. Navigate to: `http://localhost/bid_for_used_product`
3. You should see the landing page with welcome message
4. Three buttons should be visible: Login, Register as Company, Register as Client

### STEP 7: Test Login

**Test Admin Login:**
1. Click "Login" button
2. Email: `admin@localhost.com`
3. Password: `admin123`
4. Click "Login"
5. You should be redirected to Admin Dashboard with statistics

**Test Company Login:**
1. Logout if logged in
2. Click "Login"
3. Email: `company1@localhost.com`
4. Password: `company123`
5. Click "Login"
6. You should be redirected to Company Dashboard

**Test Client Login:**
1. Logout if logged in
2. Click "Login"
3. Email: `client1@localhost.com`
4. Password: `client123`
5. Click "Login"
6. You should be redirected to Client Dashboard

### STEP 8: Test Registration

**Register New Company:**
1. Go to home page
2. Click "Register as Company"
3. Fill all required fields:
   - Company Name: Test Company Ltd
   - Owner Name: John Doe
   - Email: testcompany@test.com (unique email)
   - Contact: 9876543210
   - Address: 123 Test Street
   - GST Number: (optional)
   - Upload any PDF/image as identity proof
   - Password: test12345
   - Confirm Password: test12345
4. Click "Register Company"
5. Should redirect to login page with success message
6. Login with the new credentials

**Register New Client:**
1. Go to home page
2. Click "Register as Client"
3. Fill required fields:
   - Full Name: Test Client
   - Contact: 9998887770
   - Email: testclient@test.com (unique email)
   - Address: 456 Test Avenue
   - Dealership Details: (optional)
   - Password: test12345
   - Confirm Password: test12345
4. Click "Register as Client"
5. Should redirect to login page with success message
6. Login with the new credentials

### STEP 9: Test Company Features

1. Login as company (company1@localhost.com / company123)
2. Click "Post New Product"
3. Fill product details:
   - Product Name: Test Vehicle
   - Category: 2-wheeler or 4-wheeler or machinery
   - Model: Test Model
   - Year: 2020
   - Chassis Number: (optional)
   - Owner Details: First Owner
   - Running Duration: 10000 km
   - Base Price: 50000
   - Bid Start: Select current date/time
   - Bid End: Select a future date/time
   - Upload product image (optional)
4. Click "Post Product"
5. Should redirect to "My Products" page
6. Product should appear in the list

### STEP 10: Test Client Features

1. Login as client (client1@localhost.com / client123)
2. Click "Browse Products"
3. View available products (including the test product posted above)
4. Click "View Details" on any product
5. Click "Place Bid"
6. Enter bid amount (must be greater than base price)
7. Add comments (optional)
8. Click "Place Bid"
9. Should redirect to "My Bids" page
10. Bid should appear with "pending" status

### STEP 11: Test Bid Approval

1. Logout and login as company again
2. Go to "View My Products"
3. Click "Bids" button on the product
4. You should see the bid placed by client
5. Click "Approve" button
6. Confirm approval
7. Product should change to "closed" status
8. All other bids should be rejected automatically

### STEP 12: Test Subscription

1. Login as client
2. In dashboard, subscription status should show "Inactive"
3. Click "Subscribe to Updates"
4. Subscription status should change to "Active"
5. Click "Unsubscribe" to test unsubscribe
6. Status should change back to "Inactive"

### STEP 13: Test Admin Features

1. Login as admin (admin@localhost.com / admin123)
2. Dashboard should show system statistics
3. Click "Manage Users"
4. List of all users should appear
5. Try activating/deactivating a user (except yourself)
6. Click "View All Products"
7. All products from all companies should appear
8. Click "View All Bids"
9. All bids from all clients should appear
10. Click "Verify Companies"
11. List of companies should appear
12. Click "Verify" on a pending company

## Common Issues and Solutions

### Issue: "Database Connection Failed"
**Solution:**
- Make sure MySQL is running in XAMPP Control Panel
- Check database name in `config/config.php` is `bid_for_used_product`
- Verify database was imported successfully

### Issue: "Cannot upload file"
**Solution:**
- Check that folders exist: `uploads/identity_proofs/` and `uploads/products/`
- Right-click folder > Properties > Security > Make sure full control is allowed
- Check PHP settings: upload_max_filesize in php.ini should be at least 5M

### Issue: "Page not found / 404 error"
**Solution:**
- Verify project is in: `C:\xampp\htdocs\bid_for_used_product\`
- Apache must be running in XAMPP
- Clear browser cache
- Try: `http://localhost/bid_for_used_product/index.php`

### Issue: "Blank white screen"
**Solution:**
- Apache or MySQL might not be running
- Check for PHP errors in: `C:\xampp\apache\logs\error.log`
- Enable error display in `config/config.php` (already enabled)

### Issue: "Password not working"
**Solution:**
- Use exact default credentials as provided
- Passwords are case-sensitive
- Try creating a new user via registration

## File Permissions Check

Make sure these folders have write permissions:
```
C:\xampp\htdocs\bid_for_used_product\uploads\
C:\xampp\htdocs\bid_for_used_product\uploads\identity_proofs\
C:\xampp\htdocs\bid_for_used_product\uploads\products\
```

## Port Configuration

Default ports used:
- Apache: Port 80
- MySQL: Port 3306

If these ports are in use:
- Close Skype, IIS, or other services using port 80
- Change Apache port in XAMPP config if needed

## Testing Checklist

- [x] XAMPP Apache started
- [x] XAMPP MySQL started
- [x] Database imported successfully
- [x] Landing page loads
- [x] Admin login works
- [x] Company login works
- [x] Client login works
- [x] Company registration works
- [x] Client registration works
- [x] Product posting works
- [x] Product image upload works
- [x] Browse products works
- [x] Place bid works
- [x] View bids works
- [x] Approve bid works
- [x] Subscription works
- [x] Admin user management works
- [x] Admin verification works

## Support

If you encounter any issues not covered here:

1. Check XAMPP error logs: `C:\xampp\apache\logs\error.log`
2. Check MySQL is running in XAMPP Control Panel
3. Verify database exists in phpMyAdmin
4. Ensure all files copied correctly to htdocs
5. Clear browser cache and try again
6. Restart Apache and MySQL services

## Success Indicators

When everything is working correctly:

✅ Landing page displays with gradient background  
✅ Login redirects to appropriate dashboard based on role  
✅ Company can post products with images  
✅ Client can browse and filter products  
✅ Client can place bids  
✅ Company can approve/reject bids  
✅ Admin can see all system data  
✅ File uploads work (identity proof, product images)  
✅ Statistics show correct numbers  
✅ Subscription toggle works  

---

**Setup Complete!** 🎉

Your offline auction system is now ready to use.
