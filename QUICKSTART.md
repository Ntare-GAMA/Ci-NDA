# ��� Quick Start Guide - CI-NDA Platform

Get your CI-NDA platform up and running in 5 minutes!

## ��� Prerequisites

Make sure you have these installed:
- ✅ Node.js (v14+) - [Download here](https://nodejs.org/)
- ✅ MongoDB (v4.4+) - [Download here](https://www.mongodb.com/try/download/community)

## ��� Setup Steps

### Step 1: Install Dependencies

```bash
npm install
```

This will install all required packages:
- express
- mongoose
- bcryptjs
- jsonwebtoken
- cors
- dotenv
- express-session
- connect-mongo
- express-validator

### Step 2: Create Environment File

Create a `.env` file in your project root:

```env
PORT=5000
NODE_ENV=development
MONGODB_URI=mongodb://localhost:27017/cinda
JWT_SECRET=my-super-secret-jwt-key-2024
SESSION_SECRET=my-super-secret-session-key-2024
```

### Step 3: Start MongoDB

**Windows:**
```bash
# Open Command Prompt as Administrator
mongod
```

**macOS:**
```bash
brew services start mongodb-community
```

**Linux:**
```bash
sudo systemctl start mongod
```

### Step 4: Seed Database (First Time Only)

```bash
npm run seed
```

You should see:
```
✅ Database seeded successfully!

��� Demo Credentials:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Filmmaker:
  Email: filmmaker@cinda.com
  Password: filmmaker123

Mentor:
  Email: mentor@cinda.com
  Password: mentor123

Sponsor:
  Email: sponsor@cinda.com
  Password: sponsor123
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

### Step 5: Start the Server

```bash
# For development (with auto-reload)
npm run dev

# OR for production
npm start
```

You should see:
```
✅ MongoDB Connected Successfully
��� CI-NDA Server running on port 5000
��� Environment: development
��� Frontend: http://localhost:5000
��� API: http://localhost:5000/api
```

### Step 6: Open Your Browser

Go to: **http://localhost:5000**

## ��� Testing the Application

### 1. Sign In

- Navigate to **http://localhost:5000/authentication.html**
- Use demo credentials:
  - **Filmmaker:** filmmaker@cinda.com / filmmaker123
  - **Mentor:** mentor@cinda.com / mentor123
  - **Sponsor:** sponsor@cinda.com / sponsor123

### 2. Explore Features

After signing in, you can:

✅ **Profile Page** (http://localhost:5000/profile.html)
- View and edit your profile
- See your stats and activity
- Manage your portfolio

✅ **Courses** (http://localhost:5000/courses.html)
- Browse 9 different courses
- Filter by category and level
- Enroll in courses (requires sign-in)

✅ **Opportunities** (http://localhost:5000/opportunities.html)
- Browse grants, jobs, competitions
- Filter by type
- Apply to opportunities (requires sign-in)

✅ **Mentorship** (http://localhost:5000/mentorship.html)
- Browse mentor profiles
- Request mentorship
- View mentor specialties

✅ **Portfolios** (http://localhost:5000/portfolios.html)
- View filmmaker portfolios
- Filter by category
- Like and comment on projects

## ��� API Testing

Test the API using curl or Postman:

### Health Check
```bash
curl http://localhost:5000/api/health
```

### Register New User
```bash
curl -X POST http://localhost:5000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "userType": "filmmaker"
  }'
```

### Login
```bash
curl -X POST http://localhost:5000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "filmmaker@cinda.com",
    "password": "filmmaker123",
    "userType": "filmmaker"
  }'
```

### Get All Courses
```bash
curl http://localhost:5000/api/courses
```

### Get All Opportunities
```bash
curl http://localhost:5000/api/opportunities
```

## ��� Verify Setup

### Check if MongoDB is Running
```bash
# Windows/macOS/Linux
mongosh

# Then in MongoDB shell:
show dbs
use cinda
show collections
```

You should see: `users`, `courses`, `opportunities`

### Check Server Logs

Look for these in your terminal:
- ✅ MongoDB Connected Successfully
- ��� CI-NDA Server running on port 5000
- No error messages

### Check Frontend

Open browser console (F12) and verify:
- No CORS errors
- `window.api` is available
- No 404 errors for api.js

## ⚠️ Common Issues & Solutions

### Issue: "Port 5000 already in use"

**Solution:**
```bash
# Windows
netstat -ano | findstr :5000
taskkill /PID <PID> /F

# macOS/Linux
lsof -ti:5000 | xargs kill -9
```

Or change the port in `.env`:
```env
PORT=3000
```

### Issue: "MongoDB connection failed"

**Solution:**
1. Make sure MongoDB is running:
   ```bash
   mongod --version
   ```

2. Check if MongoDB service is active:
   ```bash
   # Windows
   sc query MongoDB

   # macOS
   brew services list

   # Linux
   sudo systemctl status mongod
   ```

3. Try connecting manually:
   ```bash
   mongosh
   ```

### Issue: "Cannot find module 'xyz'"

**Solution:**
```bash
# Delete node_modules and reinstall
rm -rf node_modules package-lock.json
npm install
```

### Issue: "JWT token errors"

**Solution:**
1. Clear browser localStorage:
   ```javascript
   // In browser console (F12)
   localStorage.clear();
   sessionStorage.clear();
   ```

2. Sign in again

### Issue: "CORS errors in browser"

**Solution:**
Make sure you're accessing via `http://localhost:5000` not `file://`

## ��� Project Structure

```
ci-nda-platform/
├── models/                 # Database models
│   ├── User.js
│   ├── Course.js
│   ├── Opportunity.js
│   ├── Mentorship.js
│   └── Portfolio.js
├── routes/                 # API endpoints
│   ├── auth.js
│   ├── users.js
│   ├── courses.js
│   ├── opportunities.js
│   ├── mentorship.js
│   └── portfolios.js
├── middleware/
│   └── auth.js            # JWT authentication
├── public/
│   └── js/
│       └── api.js         # Frontend API client
├── *.html                 # Frontend pages
├── server.js              # Main server file
├── seed.js               # Database seeding
├── package.json
└── .env                  # Environment config
```

## �� Next Steps

1. **Explore the Code**
   - Check `server.js` for API setup
   - Review `routes/` for endpoint logic
   - Examine `models/` for data structure

2. **Customize**
   - Add your own courses
   - Create new opportunities
   - Design custom features

3. **Deploy**
   - Use MongoDB Atlas for cloud database
   - Deploy to Heroku, DigitalOcean, or AWS
   - Configure production environment variables

## ��� Need Help?

- Check the main README.md for detailed documentation
- Review API endpoints in the setup guide
- Check server logs for errors
- Inspect browser console for frontend issues

## ✨ Features Working

After setup, you should have:
- ✅ User authentication (register/login)
- ✅ Social login (Google/Facebook mock)
- ✅ Protected routes with JWT
- ✅ Course enrollment system
- ✅ Opportunity applications
- ✅ Mentorship requests
- ✅ Portfolio management
- ✅ Profile management

Happy coding! ���
