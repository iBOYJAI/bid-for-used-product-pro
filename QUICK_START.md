# 🚀 BID FOR USED PRODUCT - Quick Start & Setup Guide

This guide provides step-by-step instructions to set up the **Bid For Used Product** application on a local development environment (Windows/XAMPP recommended).

---

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

1.  **XAMPP** (or WAMP/MAMP/LAMP):
    *   **PHP Version**: 8.0 or higher.
    *   **MySQL/MariaDB**: 10.4 or higher.
    *   **Apache Web Server**.
2.  **Web Browser**: Chrome, Firefox, or Edge.

---

## 🛠️ Installation Steps

### 1. Project Placement
*   Navigate to your local server directory (e.g., `C:\xampp\htdocs`).
*   Create a folder named `bid_for_used_product`.
*   Place all project files into this folder.

### 2. Database Configuration
1.  Open **phpMyAdmin** (usually `http://localhost/phpmyadmin`).
2.  **Create a new database**:
    *   Name: `bid_for_used_product`
    *   Collation: `utf8mb4_general_ci`
3.  **Import Schema & Data**:
    *   Select the `bid_for_used_product` database.
    *   Go to the **Import** tab.
    *   Choose file: `database/database.sql`.
    *   Click **Go**.

    *(Note: The database is pre-seeded with 65+ products, 15 companies, and 20 clients specific to the Tamil Nadu region).*

### 3. Verify Configuration
*   Open `config/config.php` in a text editor.
*   Ensure settings match your database credentials:
    ```php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'bid_for_used_product');
    ```

---

## 🔑 User Login Credentials

The system comes pre-populated with users for testing all roles.

### 👨‍💼 Administrator (Super User)
*   **Email**: `admin@example.com`
*   **Password**: `admin123`

---

### 🏢 Companies (Sellers)
*   **Default Password**: `password` (for all companies below)

| # | Company Name | Email | Location | Phone |
|:-:|:---|:---|:---|:---|
| 1 | **Chennai Premium Auto** | `premium@chennai.com` | Chennai | 9840012345 |
| 2 | **Madurai Vehicle Hub** | `vehicles@madurai.com` | Madurai | 9443067890 |
| 3 | **Coimbatore Motors** | `motors@cbe.com` | Coimbatore | 9894023456 |
| 4 | **Salem Tractor World** | `tractors@salem.com` | Salem | 9944056789 |
| 5 | **Trichy Auto Traders** | `auto@trichy.com` | Trichy | 9787034567 |
| 6 | **Erode Machinery Mart** | `machinery@erode.com` | Erode | 9626078901 |
| 7 | **Tiruppur Industrial** | `industry@tiruppur.com` | Tiruppur | 9876545678 |
| 8 | **Vellore Bike Centre** | `bikes@vellore.com` | Vellore | 9865098765 |
| 9 | **Thanjavur Agri** | `agri@thanjavur.com` | Thanjavur | 9865432109 |
| 10 | **Kanchipuram Luxury** | `luxury@kanchi.com` | Kanchipuram | 9841156789 |
| 11 | **Tuticorin Port Vehicles** | `port@tuticorin.com` | Tuticorin | 9443345678 |
| 12 | **Dindigul Farm Equip** | `farm@dindigul.com` | Dindigul | 9865223456 |
| 13 | **Karur Transport** | `transport@karur.com` | Karur | 9843267890 |
| 14 | **Tirunelveli Depot** | `depot@tirunelveli.com` | Tirunelveli | 9443412345 |
| 15 | **Hosur Heavy Equip** | `heavy@hosur.com` | Hosur | 9865556789 |

---

### 👤 Clients (Buyers)
*   **Default Password**: `password` (for all clients below)

| # | Client Name | Email | Location | Phone |
|:-:|:---|:---|:---|:---|
| 1 | **Vijay Kumar** | `vijay.k@gmail.com` | Chennai | 9000112233 |
| 2 | **Priya Lakshmi** | `priya.l@yahoo.com` | Chennai | 9000223344 |
| 3 | **Arun Prasad** | `arun.p@outlook.com` | Trichy | 9000334455 |
| 4 | **Kavitha Ramesh** | `kavitha.r@gmail.com` | Coimbatore | 9000445566 |
| 5 | **Ravi Shankar** | `ravi.s@mail.com` | Salem | 9000556677 |
| 6 | **Meena Sundaram** | `meena.s@gmail.com` | Madurai | 9000667788 |
| 7 | **Suresh Babu** | `suresh.b@gmail.com` | Tiruppur | 9000778899 |
| 8 | **Lakshmi Narayanan** | `lakshmi.n@yahoo.com` | Vellore | 9000889900 |
| 9 | **Karthik Raja** | `karthik.r@outlook.com` | Thanjavur | 9000990011 |
| 10 | **Divya Bharathi** | `divya.b@gmail.com` | Kanchipuram | 9001001122 |
| 11 | **Senthil Kumar** | `senthil.k@mail.com` | Tuticorin | 9001112233 |
| 12 | **Anjali Devi** | `anjali.d@gmail.com` | Dindigul | 9001223344 |
| 13 | **Muthu Vel** | `muthu.v@yahoo.com` | Karur | 9001334455 |
| 14 | **Ramya Krishnan** | `ramya.k@outlook.com` | Tirunelveli | 9001445566 |
| 15 | **Bala Murugan** | `bala.m@gmail.com` | Hosur | 9001556677 |
| 16 | **Geetha Rani** | `geetha.r@mail.com` | Chennai | 9001667788 |
| 17 | **Arjun Vikram** | `arjun.v@gmail.com` | Madurai | 9001778899 |
| 18 | **Nithya Menen** | `nithya.m@yahoo.com` | Coimbatore | 9001889900 |
| 19 | **Prakash Raj** | `prakash.r@outlook.com` | Salem | 9001990011 |
| 20 | **Sowmya Devi** | `sowmya.d@gmail.com` | Erode | 9002001122 |

---

## 🚦 Running the Application

1.  Start **Apache** and **MySQL** in XAMPP Control Panel.
2.  Open your browser and navigate to:
    **[http://localhost/bid_for_used_product](http://localhost/bid_for_used_product)**

---

## ⚠️ Troubleshooting Common Issues

| Issue | Solution |
| :--- | :--- |
| **Database Connection Error** | Check `config/config.php` credentials. Ensure MySQL is running. |
| **Images Not Loading** | Verify `uploads/products/` contains images like `product_1.jpg`. Check file permissions. |
| **404 Not Found** | Ensure the project folder is named exactly `bid_for_used_product` in `htdocs`. |
| **Login Invalid Password** | Use the credentials listed above. Admin password is unique (`admin123`); others are `password`. |
| **CSS/JS Not Loading** | Hard refresh the page (`Ctrl + F5`) to clear browser cache. |

---

## 📁 Critical Folders Overview

*   `admin/` - Backend for administrators.
*   `uploads/` - Stores dynamic content (Product images, Identity proofs).
*   `database/` - Contains `database.sql` (schema + data).
*   `includes/` - Reusable code blocks (`header.php`, `footer.php`, `db_connect.php`).
*   `assets/` - Static files (CSS, Images, JS).

---

**Setup Complete!** You are now ready to explore the Bid For Used Product platform.
