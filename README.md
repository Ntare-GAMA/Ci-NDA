# Ci-NDA Platform

**A Digital Platform for Cinematography Skill Development & Global Exposure**

Empowering Rwandan filmmakers with accessible resources, mentorship, and global opportunities through a comprehensive digital platform.

---

## 📋 Table of Contents
- [Mission](#mission)
- [Problem Statement](#problem-statement)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation Guide](#installation-guide)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)
- [Deployment](#deployment)
- [Contributing](#contributing)

---

## 🎯 Mission

My mission at ALU is to empower Rwandans with the skills and opportunities to thrive in cinematography. Through Ci-NDA, I aim to provide accessible digital resources, mentorship, and exposure pathways that elevate filmmaking standards. This mission seeks to bridge the gap between local talent and international platforms, ensuring that Rwandan stories are told authentically and competitively.

**Relevance in Africa:**
Africa's creative economy is booming, but Rwandan filmmakers lack easy access to structured training and global opportunities. Ci-NDA bridges that gap by making resources and opportunities accessible from anywhere in Rwanda, especially for youth who can't afford expensive film schools.

---

## 🔥 Problem Statement

**"Rwandan filmmakers lack skills, resources, and exposure."**

Rwandan filmmakers struggle to access the skills, resources, and opportunities needed to compete in today's global cinema industry. The creative sector contributes less than 2% to Rwanda's GDP compared to Nigeria's Nollywood at 5%, highlighting a major gap in growth potential. Key challenges include:

- Lack of advanced training programs and mentorship
- Limited access to professional-grade equipment
- Scarce opportunities for international exposure
- No centralized platform connecting talent with resources

---

## ✨ Features

- **📚 Course Library** - Browse and enroll in 12+ professional filmmaking courses
- **👥 Mentorship Program** - Connect with 6 industry mentors for personalized guidance
- **💼 Opportunities Board** - Access film grants, competitions, and job opportunities
- **🎨 Portfolio Showcase** - Display your work and build your professional presence
- **📝 Request Mentorship** - Submit customized mentorship requests to experts
- **🔐 User Authentication** - Secure login and profile management

---

## 🛠️ Tech Stack

### Frontend
- **HTML5/CSS3** - Modern, responsive UI
- **Vanilla JavaScript** - Dynamic content loading
- **Fetch API** - RESTful API communication

### Backend
- **PHP 8.2+** - Server-side logic
- **MySQL** - Relational database
- **RESTful API** - Clean endpoint architecture

### Development Tools
- **XAMPP** - Local development environment
- **Git** - Version control
- **VS Code** - Code editor

---

## 📦 Installation Guide

### Prerequisites

Before you begin, ensure you have the following installed:

1. **XAMPP** (includes PHP, MySQL, Apache)
   - Download from: https://www.apachefriends.org/
   - Version: 8.2 or higher recommended

2. **Git** (for cloning the repository)
   - Download from: https://git-scm.com/

3. **Web Browser** (Chrome, Firefox, or Edge recommended)

---

### Step 1: Clone the Repository

```bash
# Navigate to your desired directory
cd C:\Users\YourUsername\Downloads

# Clone the repository
git clone https://github.com/Ntare-GAMA/Ci-NDA.git

# Navigate into the project directory
cd Ci-NDA
```

---

### Step 2: Install XAMPP

1. Download and install XAMPP from https://www.apachefriends.org/
2. Install to default location: `C:\xampp`
3. During installation, select:
   - Apache
   - MySQL
   - PHP
   - phpMyAdmin

---

## 🗄️ Database Setup

### Step 1: Start XAMPP Services

1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache**
3. Click **Start** next to **MySQL**
4. Both should show green "Running" status

### Step 2: Create Database

1. Open your browser and go to: **http://localhost/phpmyadmin**
2. Click **"New"** in the left sidebar
3. Enter database name: **`cinda`**
4. Select **utf8mb4_unicode_ci** collation
5. Click **"Create"**

### Step 3: Import Database Schema

1. Click on the **`cinda`** database you just created
2. Click the **"Import"** tab at the top
3. Click **"Choose File"**
4. Navigate to: `C:\Users\YourUsername\Downloads\Ci-NDA\api\schema.sql`
5. Click **"Go"** at the bottom
6. Wait for success message: "Import has been successfully finished"

---

## 🚀 Running the Application

### Method 1: Using Setup Page (Recommended for First Run)

1. Open **PowerShell** or **Command Prompt**
2. Navigate to project directory:
   ```powershell
   cd C:\Users\YourUsername\Downloads\Ci-NDA
   ```

3. Start the PHP development server:
   ```powershell
   C:\xampp\php\php.exe -S 127.0.0.1:8000
   ```

4. Open your browser and visit:
   ```
   http://127.0.0.1:8000/setup-database.html
   ```

5. Click the **"Setup Database"** button
6. Wait for success message showing:
   - ✓ Courses: 12 records inserted
   - ✓ Mentors: 6 records inserted

7. Click **"Go to Home"** or navigate to:
   ```
   http://127.0.0.1:8000/index.html
   ```

### Method 2: Manual Seed (Alternative)

1. Start the PHP server (step 3 above)
2. Visit these URLs in your browser:
   ```
   http://127.0.0.1:8000/api/seed_courses.php
   http://127.0.0.1:8000/api/seed_mentors.php
   ```

3. You should see JSON responses confirming data insertion

---

## 📁 Project Structure

```
Ci-NDA/
├── api/                          # Backend API endpoints
│   ├── db.php                    # Database connection (MySQL)
│   ├── db-production.php         # Production DB config backup
│   ├── schema.sql                # Database schema (13 tables)
│   ├── courses.php               # Courses API
│   ├── mentors.php               # Mentors API
│   ├── opportunities.php         # Opportunities API
│   ├── portfolios.php            # Portfolios API
│   ├── seed_courses.php          # Seed 12 courses
│   ├── seed_mentors.php          # Seed 6 mentors
│   └── seed_users.php            # Seed sample users
│
├── public/                       # Static assets
│   └── js/
│       └── api.js                # Frontend API helper functions
│
├── index.html                    # Homepage
├── courses.html                  # Browse courses
├── mentorship.html               # Browse mentors
├── public-profile.html           # Individual mentor profiles
├── request-mentorship.html       # Mentorship request form
├── opportunities.html            # Opportunities board
├── portfolios.html               # Portfolio showcase
├── signup.html                   # User registration
├── authentication.html           # User login
├── profile.html                  # User profile management
├── setup-database.html           # Database setup utility
│
└── README.md                     # This file
```

---

## 🌐 Available Pages

Once the server is running, visit these pages:

| Page | URL | Description |
|------|-----|-------------|
| Home | http://127.0.0.1:8000/index.html | Platform homepage |
| Courses | http://127.0.0.1:8000/courses.html | Browse 12 courses |
| Mentorship | http://127.0.0.1:8000/mentorship.html | Browse 6 mentors |
| Opportunities | http://127.0.0.1:8000/opportunities.html | Film opportunities |
| Portfolios | http://127.0.0.1:8000/portfolios.html | Showcase portfolios |
| Login | http://127.0.0.1:8000/authentication.html | User login |
| Sign Up | http://127.0.0.1:8000/signup.html | User registration |

---

## 🐛 Troubleshooting

### Issue: "Class 'mysqli' not found"
**Solution:** Make sure you're using XAMPP's PHP, not system PHP:
```powershell
C:\xampp\php\php.exe -S 127.0.0.1:8000
```

### Issue: "Connection refused" or blank pages
**Solution:** 
1. Ensure XAMPP MySQL is running (green in XAMPP Control Panel)
2. Check database `cinda` exists in phpMyAdmin
3. Run the setup page: http://127.0.0.1:8000/setup-database.html

### Issue: "No courses/mentors displaying"
**Solution:**
1. Visit: http://127.0.0.1:8000/api/mentors.php
2. Should see JSON array with 6 mentors
3. If empty, re-run: http://127.0.0.1:8000/setup-database.html

### Issue: Mentor profiles show "Unnamed"
**Solution:**
1. Database not seeded properly
2. Run setup-database.html again
3. Refresh the profile page

---

## 📊 Database Schema

The platform uses **13 MySQL tables**:

| Table | Description |
|-------|-------------|
| `users` | User accounts and profiles |
| `courses` | Film courses and training |
| `enrollments` | Course enrollment tracking |
| `mentors` | Mentor profiles and expertise |
| `opportunities` | Grants, jobs, competitions |
| `portfolios` | User portfolio projects |
| `sessions` | User session management |
| `mentor_applications` | Mentor signup requests |
| `mentorship_requests` | Mentorship request submissions |
| `opportunity_applications` | Opportunity applications |
| `messages` | User messaging system |

---

## 🚢 Deployment

### Deploying to InfinityFree (or similar hosting)

1. **Upload Files via FTP:**
   - Upload all files to `htdocs/` directory
   - Maintain folder structure

2. **Database Setup:**
   - Create MySQL database via hosting cPanel
   - Import `api/schema.sql` via phpMyAdmin
   - Note database credentials

3. **Update Database Config:**
   - Rename `api/db-production.php` to `api/db.php`
   - Update credentials in `db.php`:
     ```php
     $DB_HOST = 'localhost';  // Not sql211.infinityfree.com
     $DB_USER = 'your_db_user';
     $DB_PASS = 'your_db_password';
     $DB_NAME = 'your_db_name';
     ```

4. **Seed Database:**
   - Visit: `https://yourdomain.com/api/seed_courses.php`
   - Visit: `https://yourdomain.com/api/seed_mentors.php`

5. **Test:**
   - Visit: `https://yourdomain.com/index.html`

---

## 🤝 Contributing

Contributions are welcome! To contribute:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Commit changes: `git commit -m 'Add feature'`
4. Push to branch: `git push origin feature-name`
5. Submit a Pull Request

---

## 📝 Development Model

**Incremental Prototyping Model** guides this project:

1. **Phase 1 (MVP):** Mentorship and structured training resources ✅
2. **Phase 2:** Portfolio uploads and resource library
3. **Phase 3:** Festival submission tools and international collaboration
4. **Phase 4:** Investor and sponsorship analytics dashboard

---

## 📞 Contact & Support

**Developer:** NTARE GAMA Allan  
**Institution:** African Leadership University  
**GitHub:** https://github.com/Ntare-GAMA/Ci-NDA

For issues or questions, please open a GitHub issue or contact via the repository.

---

## 📚 References

- UNESCO. (2022). *The African Creative Economy Report.*
- Rwanda Development Board. (2023). *Creative Industries Policy Brief.*
- African Union. (2021). *Agenda 2063: The Africa We Want.*

---

## 📄 License

This project is developed as part of academic research at African Leadership University aimed at empowering Rwanda's creative economy.

---
