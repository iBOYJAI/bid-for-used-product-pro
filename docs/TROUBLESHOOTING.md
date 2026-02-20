# ⚠️ TROUBLESHOOTING GUIDE

Complete guide for fixing all common issues with BID FOR USED PRODUCT

---

## 🔍 Quick Diagnosis

### Run System Check First!
```
http://localhost/bid_for_used_product/check_system.php
```
This will identify 90% of issues automatically.

---

## ❌ Problem: White Pages (Blank Screen)

### Symptoms
- Page loads but shows nothing
- No error messages
- Just blank white screen

### Solutions (Try in order)

**Solution 1: Check Error Log**
1. Open: `bid_for_used_product/logs/error_log.txt`
2. Look at the last few lines
3. Follow error-specific fixes below

**Solution 2: Verify XAMPP Services**
1. Open XAMPP Control Panel
2. Both Apache and MySQL must be GREEN "Running"
3. If not, click "Start" for each
4. If they won't start, see "Services Won't Start" section

**Solution 3: Run Setup Wizard**
```
http://localhost/bid_for_used_product/setup_wizard.php
```
This recreates the database automatically.

**Solution 4: Check Database Exists**
1. Go to: `http://localhost/phpmyadmin`
2. Look for database: `bid_for_used_product`
3. If missing, run setup wizard
4. If exists, check it has 5 tables: users, companies, products, bids, subscriptions

**Solution 5: Enable Error Display**
1. Open: `config/config.php`
2. Find: `define('DISPLAY_ERRORS', true);`
3. Make sure it's set to `true`
4. Refresh page to see error details

---

## 🚫 Problem: Database Connection Failed

### Error Message
"⚠️ Database Connection Failed" or "Cannot connect to MySQL database"

### Solutions

**Quick Fix:**
```
http://localhost/bid_for_used_product/setup_wizard.php
```

**Manual Fix:**
1. Open XAMPP Control Panel
2. Make sure MySQL shows GREEN "Running"
3. If not green, click "Start"
4. If it won't start:
   - Click "Logs" → Find error
   - Usually port 3306 is in use
   - Stop other MySQL services
   
5. Open phpMyAdmin: `http://localhost/phpmyadmin`
6. Create database: `bid_for_used_product`
7. Import: `database/database.sql`

---

## 🔴 Problem: Apache Won't Start

### Symptoms
- XAMPP shows Apache is not running
- Clicking "Start" does nothing or shows error
- Port 80/443 is in use

### Solutions

**Solution 1: Check Port Conflict**
1. Open Command Prompt as Administrator
2. Type: `netstat -ano | findstr :80`
3. If something is using port 80:
   - Likely IIS or Skype
   - Stop IIS: Services → IIS → Stop
   - Or change Apache port in XAMPP config

**Solution 2: Run as Administrator**
1. Close XAMPP
2. Right-click XAMPP Control Panel
3. "Run as Administrator"
4. Try starting Apache again

**Solution 3: Check Antivirus/Firewall**
- Temporarily disable antivirus
- Allow XAMPP through Windows Firewall
- Try starting Apache

---

## 🟢 Problem: MySQL Won't Start

### Symptoms
- MySQL won't turn green in XAMPP
- Error about port 3306
- Database connection fails

### Solutions

**Solution 1: Port 3306 in Use**
1. Open Command Prompt as Administrator
2. Type: `netstat -ano | findstr :3306`
3. If something is using it:
   - Likely another MySQL installation
   - Stop Windows "MySQL" service
   - Stop "MySQL80" in Services

**Solution 2: Reset MySQL**
1. In XAMPP, click "Config" for MySQL  
2. Select "my.ini"
3. Find line with port: `port=3306`
4. Save and restart XAMPP

**Solution 3: Reinstall MySQL**
1. In XAMPP, uncheck MySQL
2. Reinstall MySQL component
3. Restart XAMPP

---

## 📤 Problem: Cannot Upload Files

### Symptoms
- Product images won't upload
- Identity proof upload fails
- "Upload error" message

### Solutions

**Solution 1: Check Folder Permissions**
1. Go to: `bid_for_used_product/uploads`
2. Right-click → Properties
3. Security tab → Edit
4. Give "Everyone" Full Control
5. Apply to all subfolders

**Solution 2: Check PHP Settings**
1. Find `php.ini` in XAMPP
2. Set: `upload_max_filesize = 10M`
3. Set: `post_max_size = 10M`
4. Restart Apache

**Solution 3: Create Upload Folders**
```
bid_for_used_product/uploads/
bid_for_used_product/uploads/identity_proofs/
bid_for_used_product/uploads/products/
```

---

## 🔐 Problem: Login Not Working

### Symptoms
- Can't login with provided credentials
- "Invalid credentials" error
- Redirects back to login

### Solutions

**Solution 1: Use Correct Credentials**
```
Admin:
Email: admin@localhost.com
Password: admin123

Company:
Email: company1@localhost.com
Password: company123

Client:
Email: client1@localhost.com
Password: client123
```

**Solution 2: Reset Password in Database**
1. Go to phpMyAdmin
2. Select `bid_for_used_product` database
3. Click `users` table
4. Find your user
5. Set password to: `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi`
6. This resets password to: `admin123`

**Solution 3: Re-import Database**
Run setup wizard to recreate users:
```
http://localhost/bid_for_used_product/setup_wizard.php
```

---

## 📋 Problem: No Data Showing

### Symptoms
- Dashboard shows 0 products
- No bids visible
- Empty tables

### Solution

**Re-import Sample Data:**
1. Go to: `http://localhost/phpmyadmin`
2. Select database: `bid_for_used_product`
3. Click "Import" tab
4. Choose: `database/database.sql`
5. Check: "Drop existing tables" (if option available)
6. Click "Go"

Or run setup wizard which imports data automatically.

---

## 🔄 Problem: Project Won't Work on Another PC

### For Portable Installation

**Simple Method:**
1. Copy entire `bid_for_used_product` folder
2. Paste to new PC at: `C:\xampp\htdocs\`
3. Start XAMPP (Apache + MySQL)
4. Visit: `http://localhost/bid_for_used_product/setup_wizard.php`
5. Click through wizard (auto-creates database)
6. Done!

**The setup wizard handles everything!**

---

## 🆘 Other Common Issues

### Issue: "Page not found" (404)

**Check:**
- Project is in: `C:\xampp\htdocs\bid_for_used_product\`
- Apache is running
- URL is: `http://localhost/bid_for_used_product/`
- Not: `http://localhost/bid_for_used_product/index.php/`

### Issue: "Session error" or constant logouts

**Fix:**
- Clear browser cookies
- Close all browser tabs
- Try a different browser
- Check: `includes/session.php` exists

### Issue: CSS not loading / page looks broken

**Fix:**
- Clear browser cache (Ctrl + F5)
- Check: `assets/css/style.css` exists
- Check Apache is running

### Issue: Redirects don't work

**Fix:**
- Check `config/config.php` has correct `APP_URL`
- Should be: `http://localhost/bid_for_used_product`
- No trailing slash

---

## 🛠️ Advanced Troubleshooting

### Enable Full Error Reporting

Edit `config/config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
define('DISPLAY_ERRORS', true);
```

### Check PHP Version
```
http://localhost/bid_for_used_product/check_system.php
```
Requires PHP 7.2+

### View Apache Error Log
```
C:\xampp\apache\logs\error.log
```

### View MySQL Error Log
```
C:\xampp\mysql\data\mysql_error.log
```

### Clear All Errors
1. Delete: `logs/error_log.txt`
2. Recreate folders:
   - `logs/`
   - `uploads/`
   - `uploads/products/`
   - `uploads/identity_proofs/`

---

## ✅ Verification Checklist

After fixing, verify:

- [ ] XAMPP Apache: GREEN
- [ ] XAMPP MySQL: GREEN
- [ ] System check passes: `check_system.php`
- [ ] Can access: `http://localhost/bid_for_used_product`
- [ ] Can see landing page
- [ ] Can login as admin
- [ ] Dashboard shows data
- [ ] No error log entries (or old ones only)

---

## 📞 Still Stuck?

1. **Run System Check:**
   ```
   http://localhost/bid_for_used_product/check_system.php
   ```

2. **Check Error Log:**
   ```
   logs/error_log.txt
   ```

3. **Re-run Setup:**
   ```
   http://localhost/bid_for_used_product/setup_wizard.php
   ```

4. **Fresh Installation:**
   - Delete `bid_for_used_product` folder
   - Extract fresh copy
   - Run `auto_setup.bat`

---

**✨ 99% of issues are solved by running the setup wizard!**

```
http://localhost/bid_for_used_product/setup_wizard.php
```
