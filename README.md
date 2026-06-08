# 🚗 GariDorkar — Car Rental System

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![Apache](https://img.shields.io/badge/Apache-XAMPP-D22128?style=for-the-badge&logo=apache&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

A web-based car rental management system built with **PHP**, **MySQL**, and **Bootstrap 5**. Users can browse available cars, make bookings, and manage their reservations. Admins can manage cars, users, and all bookings from a dedicated dashboard.

---

## ✨ Features

### 👤 User Side
- Register & Login with session-based authentication
- Browse all available cars with images and details
- Book a car by selecting pickup location, destination, and date range
- Automatic total price calculation based on rental days
- View and cancel existing bookings (only Pending bookings can be cancelled)
- Search cars by name or model

### 🛡️ Admin Side
- Secure admin login panel
- Dashboard with statistics (total cars, users, bookings)
- Add, edit, and delete cars with image upload
- Manage all user bookings — Confirm or Cancel
- Manage registered users

---

## 🔄 Booking Flow

```
User Registers / Logs In
        │
        ▼
  Browse Available Cars
        │
        ▼
  Select a Car → View Details
        │
        ▼
  Fill Booking Form
  (Pickup Location, Destination, Start Date, End Date)
        │
        ▼
  Total Price Auto-Calculated
  (price_per_day × number of days)
        │
        ▼
  Booking Created → Status: "Pending"
  Car Status → "Booked"
        │
        ├──► Admin Confirms → Status: "Confirmed"
        │
        ├──► Admin Cancels → Status: "Cancelled"
        │
        └──► User Cancels (only if Pending) → Status: "Cancelled"
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP (procedural + OOP helpers) |
| Database | MySQL |
| Frontend | Bootstrap 5, HTML5, CSS3 |
| Server | Apache (XAMPP / WAMP) |
| Auth | PHP Sessions + `password_hash()` / `password_verify()` |

---

## 📁 Project Structure

```
Car-Rental-System-main/
│
├── admin/
│   ├── index.php            # Admin login
│   ├── dashboard.php        # Admin dashboard (stats)
│   ├── manage_cars.php      # Car management
│   ├── manage_bookings.php  # Booking management
│   ├── manage_users.php     # User management
│   ├── edit_car.php         # Edit car details
│   ├── admin_header.php
│   └── admin_footer.php
│
├── includes/
│   ├── db_connect.php       # Database connection config
│   ├── functions.php        # Auth, car, booking helper functions
│   ├── auth_check.php       # Session guard middleware
│   ├── header.php
│   └── footer.php
│
├── assets/
│   ├── css/                 # Custom stylesheets
│   └── images/              # Static car images
│
├── sql/
│   └── car_rental.sql       # Database schema & setup
│
├── uploads/                 # Admin-uploaded car images
│
├── index.php                # Homepage — car listing
├── cars.php                 # All cars page
├── car_details.php          # Single car details
├── book_car.php             # Booking form
├── cancel_booking.php       # Cancel a booking
├── dashboard.php            # User dashboard
├── login.php                # User login
├── register.php             # User registration
└── logout.php               # Logout
```

---

## ⚙️ Installation & Setup

### Prerequisites
- XAMPP or WAMP installed
- PHP >= 7.4
- MySQL >= 5.7

### Steps

**1. Clone or extract the project**
```bash
git clone https://github.com/your-username/Car-Rental-System.git
```
Or extract the ZIP into your server root:
- XAMPP → `C:/xampp/htdocs/car_rental/`
- WAMP → `C:/wamp64/www/car_rental/`

**2. Import the database**
- Open **phpMyAdmin** → `http://localhost/phpmyadmin`
- Create a new database named `car_rental`
- Click **Import** → select `sql/car_rental.sql` → click **Go**

**3. Configure database connection**

Open `includes/db_connect.php` and update if needed:
```php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';          // your MySQL password
$DB_NAME = 'car_rental';
```

**4. Run the project**
- Start Apache and MySQL from XAMPP/WAMP Control Panel
- Open browser: `http://localhost/car_rental/`
- Admin panel: `http://localhost/car_rental/admin/`

---

## 🗄️ Database Schema

**`users`**
```
id | name | email | password | role (user/admin) | created_at
```

**`cars`**
```
id | name | model | price_per_day | image | status (available/booked) | created_at
```

**`bookings`**
```
id | user_id | car_id | pickup_location | destination |
start_date | end_date | total_price | status (Pending/Confirmed/Cancelled) | created_at
```

---

## 🔐 Admin Access Setup

After importing the SQL, promote a user to admin via phpMyAdmin or MySQL CLI:

```sql
UPDATE users SET role = 'admin' WHERE email = 'your@email.com';
```

Or create a direct admin session by registering normally and then running the query above.

---

## ⚠️ Known Issues

| Issue | Description |
|---|---|
| No date conflict check | A car marked "booked" can't be booked again, but overlapping dates for future bookings are not validated |
| No payment gateway | Booking is confirmed without any actual payment integration |
| Image path hardcoded | Car image paths use `/car_rental/assets/images/` prefix — may break if deployed to a different subdirectory |
| No email notification | Users and admins don't receive any email on booking status changes |
| SQL injection risk | Some older query files use `mysqli_query()` directly with `$_GET` without prepared statements |

---

## 🚀 TODO / Roadmap

- [ ] Add date-range conflict detection before confirming a booking
- [ ] Integrate a payment gateway (SSLCommerz / Stripe)
- [ ] Add email notifications for booking updates
- [ ] Implement car search & filter by price range
- [ ] Add pagination to admin tables
- [ ] Migrate all raw queries to prepared statements
- [ ] Add user profile edit page
- [ ] Mobile responsive improvements

---

## 🤝 Contributing

Contributions are welcome! Here's how to get started:

**1. Fork the repository**

**2. Create a new branch**
```bash
git checkout -b feature/your-feature-name
```

**3. Make your changes and commit**
```bash
git add .
git commit -m "Add: your feature description"
```

**4. Push to your fork**
```bash
git push origin feature/your-feature-name
```

**5. Open a Pull Request**

Please make sure your code:
- Does not break existing functionality
- Uses prepared statements for any new database queries
- Follows the existing file and folder structure

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---

> Built with ❤️ for learning purposes. Contributions and feedback are always welcome!
