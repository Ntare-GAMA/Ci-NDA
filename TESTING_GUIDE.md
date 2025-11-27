# Quick Testing Guide

## System Status ✅
- PHP Server: Running on http://127.0.0.1:8000
- Database: SQLite at `api/data/cinda.sqlite`
- Sample Data: Seeded successfully

## Test the Application Management System

### Step 1: Admin Login
1. Open browser and go to: `http://127.0.0.1:8000/admin-signin.html`
2. Enter credentials:
   - Email: `admin@cinda.com`
   - Password: `admin123`
3. Click "Sign In"

### Step 2: Test Mentor Applications
1. Click "Mentor Applications" in the sidebar
2. You should see 3 applications:
   - John Peterson (Cinematography Director)
   - Lisa Wang (Film Editor)
   - Marcus Brown (Sound Designer)

**Test Actions:**
- Click "View" button → Opens modal with full details
- Click "Approve" button → Confirms, approves application, creates mentor account
- Click "Reject" button → Confirms, rejects application

### Step 3: Test Opportunity Applications
1. Click "Opportunity Applications" in the sidebar
2. You should see 3 applications:
   - Alex Thompson (Assistant Director)
   - Rachel Green (Cinematography Internship)
   - Michael Chen (Sound Recording)

**Test Actions:**
- Click "View" button → Opens modal with cover letter, experience
- Click "Forward" button → Forwards to sponsor
- Click "Reject" button → Rejects application

### Step 4: Test Mentorship Requests
1. Click "Mentorship Requests" in the sidebar
2. You should see 3 requests:
   - Emily Johnson (wants cinematography mentorship)
   - David Kim (wants editing mentorship)
   - Sofia Martinez (wants lighting mentorship)

**Test Actions:**
- Click "View" button → Opens modal with goals, availability
- Click "Approve" button → Approves mentorship
- Click "Reject" button → Rejects mentorship

### Step 5: Test Messaging System
1. Go to: `http://127.0.0.1:8000/mentorship.html`
2. Click "View Profile" on any mentor
3. Click "Message" button
4. A popup window (or new tab) opens with messaging interface

**Test Messaging:**
- Type a message in the input field
- Click "Send" or press Enter
- Message appears on the right (sent messages are red)
- Messages from mentor would appear on left (gray)
- Messages auto-refresh every 3 seconds

### Step 6: Verify Status Changes
1. After approving/rejecting applications, refresh the page
2. Processed applications should show new status badges:
   - Green: Approved
   - Red: Rejected
   - Blue: Forwarded
   - Orange: Pending

3. Action buttons disappear after processing (only View remains)

## API Testing (Optional)

### Get All Mentor Applications
```bash
curl http://127.0.0.1:8000/api/mentor-applications.php
```

### Get Specific Application
```bash
curl http://127.0.0.1:8000/api/mentor-applications.php?id=1
```

### Approve Application
```bash
curl -X POST http://127.0.0.1:8000/api/mentor-applications.php ^
  -H "Content-Type: application/json" ^
  -d "{\"id\":\"1\",\"status\":\"approved\"}"
```

### Send Message
```bash
curl -X POST http://127.0.0.1:8000/api/messages.php ^
  -H "Content-Type: application/json" ^
  -d "{\"sender_id\":\"user123\",\"receiver_id\":\"mentor1\",\"message\":\"Hello!\"}"
```

### Get Messages
```bash
curl "http://127.0.0.1:8000/api/messages.php?user_id=user123&with=mentor1"
```

## Troubleshooting

### If server is not running:
```powershell
php -S 127.0.0.1:8000
```

### If database is empty:
```powershell
php api/seed_applications.php
```

### If you get "Unauthorized" on admin dashboard:
1. Go to `admin-signin.html`
2. Sign in again with credentials above

### If messaging doesn't work:
1. Check browser console for errors (F12)
2. Verify messages table exists: `php api/seed_applications.php`
3. Ensure user_id is set in localStorage

## Expected Behavior Summary

✅ **Approve Mentor Application:**
- Shows confirmation dialog
- Updates status to "approved"
- Creates new mentor in mentors table
- Shows success message
- Refreshes the table
- Remove Approve/Reject buttons

✅ **Reject Application:**
- Shows confirmation dialog
- Updates status to "rejected"
- Shows success message
- Refreshes the table
- Removes action buttons

✅ **Forward Opportunity:**
- Shows confirmation dialog
- Updates status to "forwarded"
- Shows success message
- Refreshes the table

✅ **Messaging:**
- Opens in popup window
- Shows mentor name in header
- Can type and send messages
- Messages appear instantly
- Auto-refreshes every 3 seconds
- Unique user ID stored in localStorage

## All Features Working

1. ✅ Mentor application approval/rejection
2. ✅ Opportunity application forwarding/rejection
3. ✅ Mentorship request approval/rejection
4. ✅ View detailed information modals
5. ✅ Messaging system between users and mentors
6. ✅ Real-time message updates
7. ✅ Admin authentication
8. ✅ Dynamic data loading
9. ✅ Status badge updates
10. ✅ Responsive action buttons
