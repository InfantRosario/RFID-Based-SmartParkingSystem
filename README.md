<<<<<<< HEAD
# RFID-Based Smart Parking System with ERP-Based Auto-Fee Integration

A smart bike parking management system that uses **RFID technology, ESP32, PHP, and MySQL** to automate student vehicle entry/exit tracking and parking fee calculation.

The system records every parking transaction, automatically detects entry and exit through RFID scans, calculates the parking fee, and provides separate interfaces for administrators and students.

---

## 🚀 Features

### 🔐 RFID-Based Vehicle Identification
- Uses an **RC522 RFID reader** to identify registered students.
- Each RFID card is linked to a student's vehicle and registration details.
- Prevents unauthorized/unregistered RFID cards from being processed.

### 🅿️ Automatic Entry & Exit Tracking
- First RFID scan records the vehicle as **IN**.
- Second RFID scan records the vehicle as **OUT**.
- Entry and exit timestamps are stored in MySQL.

### 💰 Automatic Parking Fee Calculation
- Parking fee is calculated automatically based on the configured parking rule.
- Current project fee: **₹3 per IN/OUT parking cycle**.
- Payment status is maintained in the database.

### 👨‍💼 Admin Dashboard
Administrators can monitor:
- Registered students
- Parking entries
- Active parking
- Parking activity
- RFID status

### 👨‍🎓 Student Dashboard
Students can:
- View their profile
- View parking history
- Check parking/payment information

### 🗄️ Database Management
MySQL stores:
- Student information
- Login information
- RFID information
- Vehicle information
- Parking transactions
- Fee information
- Payment status

---

## 🛠️ Technology Stack

### Hardware
- ESP32-WROOM-32
- RC522 MFRC522 RFID Reader
- RFID Cards/Tags
- LED
- Resistor
- Breadboard
- Jumper Wires

### Software
- Arduino IDE
- PHP
- MySQL
- HTML
- CSS
- XAMPP
- Git & GitHub

---

## 🏗️ System Architecture

```text
                    ┌─────────────────┐
                    │    RFID Card    │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │   RC522 RFID    │
                    │     Reader      │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │      ESP32      │
                    └────────┬────────┘
                             │
                         HTTP Request
                             │
                             ▼
                    ┌─────────────────┐
                    │   PHP Backend   │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │     MySQL       │
                    │    Database     │
                    └────────┬────────┘
                             │
                  ┌──────────┴──────────┐
                  ▼                     ▼
          ┌───────────────┐     ┌───────────────┐
          │    Student    │     │     Admin     │
          │   Dashboard   │     │   Dashboard   │
          └───────────────┘     └───────────────┘
```

---

## 🔄 System Workflow

```text
RFID Card Scan
      │
      ▼
ESP32 Reads RFID UID
      │
      ▼
Send UID to PHP Backend
      │
      ▼
Check RFID in MySQL
      │
      ├── RFID Not Registered
      │       │
      │       ▼
      │   Reject Request
      │
      └── RFID Registered
              │
              ▼
       Check Parking Status
              │
        ┌─────┴─────┐
        │           │
      OUTSIDE      INSIDE
        │           │
        ▼           ▼
      ENTRY        EXIT
        │           │
        ▼           ▼
   Save Entry    Save Exit
   Timestamp     Timestamp
        │           │
        └─────┬─────┘
              ▼
       Calculate Fee
              │
              ▼
       Update Database
```

---

## 💵 Parking Fee Logic

The current implementation uses a fixed parking fee of **₹3 per completed parking cycle**.

```text
First Scan  → IN
Second Scan → OUT
             ↓
       Parking Cycle
             ↓
          ₹3 Fee
```

The transaction is stored along with its payment status.


---

## 🗃️ Database

The project uses a MySQL database named:

```text
project
```

Important data includes:

### Student Information
- Student name
- Department number
- Department
- Vehicle number
- Email
- RFID UID
- RFID status

### RFID Status

```text
ACTIVE
BLOCKED
NOT_ASSIGNED
```

### Parking Log

The parking transaction records include:

```text
id
Department_number
rfid_uid
entry_time
exit_time
status
fee
payment_status
```

### Payment Status

```text
Pending
```

---

## ⚙️ Hardware Connections

### RC522 → ESP32

| RC522 Pin | ESP32 |
|---|---|
| SDA/SS | GPIO 21 |
| RST | GPIO 22 |
| MOSI | SPI MOSI |
| MISO | SPI MISO |
| SCK | SPI SCK |
| 3.3V | 3.3V |
| GND | GND |

> Verify the SPI pin configuration used in your Arduino code before assembling the hardware.

---

## 🖥️ Local Setup

### 1. Clone the Repository

```bash
git clone <YOUR-GITHUB-REPOSITORY-URL>
cd Smart-Parking-System
```

### 2. Install XAMPP

Install XAMPP with:

- Apache
- MySQL

Place the PHP project inside:

```text
C:\xampp\htdocs\
```

For example:

```text
C:\xampp\htdocs\Smart-Parking-System
```

### 3. Start XAMPP

Start:

```text
Apache
MySQL
```

from the XAMPP Control Panel.

### 4. Create the Database

Open:

```text
http://localhost/phpmyadmin
```

Create:

```text
project
```

Import the project's SQL file if one is included:

```text
database/project.sql
```

### 5. Configure Database Connection

Update the database connection file with your local MySQL credentials.

Example:

```php
$host = "localhost";
$username = "root";
$password = "";
$database = "project";
```
The default local XAMPP MySQL configuration is used. Users should update the database credentials according to their local environment.

### 6. Upload the ESP32 Code

Open the Arduino project in **Arduino IDE**.

Install the required libraries and select:

```text
Board: ESP32
```

Connect the ESP32 and upload the RFID program.

### 7. Configure the Backend URL

Update the ESP32 code with the URL of your PHP backend.

For local network testing, use your computer's local IP address rather than `localhost`.

Example:

```text
http://192.168.x.x/Smart-Parking-System/php/rfidinsert.php
```

---

## 🔌 RFID API Endpoints

### Test RFID

```text
/php/testrfid.php
```

Used for testing RFID communication with the backend.

### RFID Insert

```text
/php/rfidinsert.php
```

Handles RFID data received from the ESP32 and processes the parking transaction.

---

## 🔒 Security Notes

For development purposes, the project uses a local XAMPP environment.

Before deploying this system publicly:

- Use environment variables for credentials.
- Never commit database passwords.
- Validate and sanitize user input.
- Use prepared SQL statements.
- Implement proper authentication and authorization.
- Use HTTPS.
- Protect admin routes.
- Secure API endpoints.
- Store passwords using secure hashing.

---

## 🎯 Project Objective

The main objective of this project is to develop a simple and automated parking management system that reduces manual parking record maintenance by combining **RFID identification, ESP32 hardware, PHP backend processing, and MySQL database management**.

---

## 📌 Project Scope

The current system focuses on:

- RFID-based student identification
- Vehicle registration
- Parking entry tracking
- Parking exit tracking
- Automatic fee recording
- Student parking history
- Admin monitoring

The system does **not** use parking-slot reservation or slot allocation. Students can park in any available physical space.

---

## 👨‍💻 Technologies Used

```text
ESP32
RC522 RFID
Arduino
PHP
MySQL
HTML
CSS
XAMPP
Git
GitHub
```

---

## 📜 License

This project was developed as an academic project.

You may modify this section according to your college's project and team requirements.

---

=======

