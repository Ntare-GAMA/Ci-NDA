# Ì¥ó Frontend-Backend Integration Checklist

Use this checklist to verify that your frontend is properly connected to the backend.

## ‚úÖ Pre-Flight Checks

- [ ] Node.js installed (run `node --version`)
- [ ] MongoDB installed and running (run `mongosh`)
- [ ] All dependencies installed (run `npm install`)
- [ ] `.env` file created with correct values
- [ ] Database seeded (run `npm run seed`)
- [ ] Server running (run `npm run dev`)

## Ì¥ê Authentication Flow

### Registration
- [ ] Navigate to `/authentication.html`
- [ ] Fill in new user details
- [ ] Submit form
- [ ] Verify: Token stored in localStorage
- [ ] Verify: User redirected to profile page
- [ ] Verify: No console errors

**Test API Call:**
```javascript
// Open browser console (F12) on authentication.html
api.register({
  name: "Test User",
  email: "test@test.com",
  password: "test123",
  userType: "filmmaker"
}).then(console.log).catch(console.error);
```

### Login
- [ ] Use demo credentials: filmmaker@cinda.com / filmmaker123
- [ ] Click "Sign In"
- [ ] Verify: Token stored in localStorage
- [ ] Verify: sessionStorage has userLoggedIn = 'true'
- [ ] Verify: Redirected to profile page
- [ ] Verify: No 401 errors

**Test API Call:**
```javascript
// In browser console
api.login({
  email: "filmmaker@cinda.com",
  password: "filmmaker123",
  userType: "filmmaker"
}).then(console.log).catch(console.error);
```

### Social Login
- [ ] Click "Continue with Google" or "Continue with Facebook"
- [ ] Mock login completes
- [ ] Token stored in localStorage
- [ ] Redirected to profile
- [ ] No console errors

### Logout
- [ ] Navigate to profile page
- [ ] Open user dropdown
- [ ] Click "Sign Out"
- [ ] Verify: Token removed from localStorage
- [ ] Verify: sessionStorage cleared
- [ ] Verify: Redirected to home/auth page

**Test API Call:**
```javascript
api.logout().then(() => console.log("Logged out")).catch(console.error);
```

## Ì±§ Profile Management

### View Profile
- [ ] Navigate to `/profile.html` (while logged in)
- [ ] User data loads automatically
- [ ] Name displays correctly
- [ ] Email displays correctly
- [ ] User type displays correctly
- [ ] No console errors

**Test API Call:**
```javascript
// On profile page
api.getProfile().then(console.log).catch(console.error);
```

### Update Profile
- [ ] Click "Settings" tab
- [ ] Modify profile fields (name, bio, location, etc.)
- [ ] Click "Save Changes"
- [ ] Verify: Success message appears
- [ ] Verify: Changes reflected immediately
- [ ] Verify: No errors in console

**Test API Call:**
```javascript
api.updateProfile({
  name: "Updated Name",
  bio: "New bio",
  location: "New Location"
}).then(console.log).catch(console.error);
```

## Ì≥ö Courses Integration

### Browse Courses
- [ ] Navigate to `/courses.html`
- [ ] Courses load automatically
- [ ] All 9 courses visible
- [ ] Course images display
- [ ] No console errors
- [ ] No 404 errors

**Test API Call:**
```javascript
// On courses page
api.getCourses().then(data => {
  console.log(`Loaded ${data.length} courses`);
}).catch(console.error);
```

### Filter Courses
- [ ] Click filter buttons (Beginner, Intermediate, Advanced)
- [ ] Courses filter correctly
- [ ] Active button highlighted
- [ ] Results update dynamically
- [ ] No errors

**Test API Call:**
```javascript
api.getCourses({ level: "Beginner" }).then(console.log);
```

### Enroll in Course
- [ ] Must be logged in
- [ ] Click "Enroll Now" button
- [ ] Verify: Success message appears
- [ ] Verify: Enrollment recorded
- [ ] Verify: 401 error if not logged in

**Test API Call:**
```javascript
// Replace COURSE_ID with actual course ID from database
api.enrollInCourse("COURSE_ID").then(console.log).catch(console.error);
```

## Ì≤º Opportunities Integration

### Browse Opportunities
- [ ] Navigate to `/opportunities.html`
- [ ] Opportunities load automatically
- [ ] All 6 opportunities visible
- [ ] Details display correctly
- [ ] Deadlines show correctly
- [ ] No console errors

**Test API Call:**
```javascript
api.getOpportunities().then(data => {
  console.log(`Loaded ${data.length} opportunities`);
}).catch(console.error);
```

### Filter Opportunities
- [ ] Click type filters (Grants, Jobs, Competitions, etc.)
- [ ] Results filter correctly
- [ ] Active filter highlighted
- [ ] No errors

**Test API Call:**
```javascript
api.getOpportunities({ type: "GRANT" }).then(console.log);
```

### Apply to Opportunity
- [ ] Must be logged in
- [ ] Click "Apply Now"
- [ ] Enter cover letter (optional)
- [ ] Verify: Success message
- [ ] Verify: Application recorded
- [ ] Verify: Can't apply twice to same opportunity

**Test API Call:**
```javascript
// Replace OPPORTUNITY_ID with actual ID
api.applyToOpportunity("OPPORTUNITY_ID", "My cover letter").then(console.log);
```

## ÔøΩÔøΩ Mentorship Integration

### Browse Mentors
- [ ] Navigate to `/mentorship.html`
- [ ] Page loads without errors
- [ ] Hero section displays
- [ ] Mentor cards visible
- [ ] Filter buttons work
- [ ] No console errors

**Test API Call:**
```javascript
api.getMentors().then(console.log).catch(console.error);
```

### Request Mentorship
- [ ] Must be logged in
- [ ] Click "Request Mentorship" button
- [ ] Modal or prompt appears
- [ ] Submit request
- [ ] Verify: Success message
- [ ] Verify: Request recorded

**Test API Call:**
```javascript
// Replace MENTOR_ID with actual user ID of mentor type
api.requestMentorship("MENTOR_ID", "I would like to learn cinematography").then(console.log);
```

## Ìæ¨ Portfolios Integration

### Browse Portfolios
- [ ] Navigate to `/portfolios.html`
- [ ] Portfolio cards load
- [ ] Images display
- [ ] Stats show correctly
- [ ] No console errors

**Test API Call:**
```javascript
api.getPortfolios().then(data => {
  console.log(`Loaded ${data.length} portfolios`);
}).catch(console.error);
```

### Create Portfolio
- [ ] Must be logged in
- [ ] Click "Create Portfolio" button
- [ ] Fill in portfolio details
- [ ] Submit
- [ ] Verify: Portfolio created
- [ ] Verify: Appears in list

**Test API Call:**
```javascript
api.createPortfolio({
  title: "My First Film",
  description: "A short film about...",
  category: "Short Films",
  tags: ["drama", "urban"]
}).then(console.log);
```

### Like Portfolio
- [ ] Must be logged in
- [ ] Click heart/like button
- [ ] Verify: Like count increases
- [ ] Click again to unlike
- [ ] Verify: Like count decreases

**Test API Call:**
```javascript
// Replace PORTFOLIO_ID with actual ID
api.likePortfolio("PORTFOLIO_ID").then(console.log);
```

## Ì¥ç API Client Verification

### Check API Client Loaded
- [ ] Open any HTML page
- [ ] Open browser console (F12)
- [ ] Type `api` and press Enter
- [ ] Verify: API client object appears
- [ ] Verify: Methods visible (login, register, etc.)

### Check Authentication Token
```javascript
// In browser console
console.log("Token:", localStorage.getItem('authToken'));
console.log("Is Authenticated:", api.isAuthenticated());
```

### Check Session Data
```javascript
// In browser console
console.log("User Logged In:", sessionStorage.getItem('userLoggedIn'));
console.log("User Email:", sessionStorage.getItem('userEmail'));
console.log("User Type:", sessionStorage.getItem('userType'));
console.log("User Name:", sessionStorage.getItem('userName'));
```

## Ìºê Network Verification

### Check API Requests
1. Open browser DevTools (F12)
2. Go to "Network" tab
3. Perform actions (login, load courses, etc.)
4. Verify requests:
   - [ ] Requests go to `http://localhost:5000/api`
   - [ ] Status codes are 200 (success) or 201 (created)
   - [ ] No 404 errors
   - [ ] No CORS errors
   - [ ] Authorization headers present (when logged in)

### Check Response Data
- [ ] Click on API request in Network tab
- [ ] Check "Response" tab
- [ ] Verify: JSON data returned
- [ ] Verify: No error messages
- [ ] Verify: Data structure correct

## Ì¥í Protected Routes

### Test Unauthorized Access
1. Clear localStorage: `localStorage.clear()`
2. Try to access protected features:
   - [ ] Enroll in course ‚Üí Should show "Please sign in"
   - [ ] Apply to opportunity ‚Üí Should redirect to auth
   - [ ] View profile ‚Üí Should redirect to auth
   - [ ] Create portfolio ‚Üí Should fail with 401

### Test Authorized Access
1. Sign in with demo credentials
2. Try protected features:
   - [ ] All features should work
   - [ ] No 401 errors
   - [ ] Token sent with requests

## Ì∑™ Error Handling

### Test Invalid Login
- [ ] Enter wrong email/password
- [ ] Verify: Error message displays
- [ ] Verify: User not logged in
- [ ] Verify: No token stored

### Test Network Failure
1. Stop the server (Ctrl+C)
2. Try to load courses
3. Verify: Error message shows
4. Restart server
5. Refresh page
6. Verify: Everything works again

### Test Expired Token
```javascript
// Set invalid token
localStorage.setItem('authToken', 'invalid-token');
// Try to access profile
window.location.href = '/profile.html';
// Should redirect to authentication
```

## Ì≥ä Success Metrics

All checkboxes checked above = **Perfect Integration!** ‚ú®

If any checks fail:
1. Check console for errors
2. Verify server is running
3. Check MongoDB is running
4. Review network tab for failed requests
5. Verify API endpoint URLs are correct
6. Check authentication token is present

## ÌæØ Final Integration Test

Run this complete user flow:

1. **Start Fresh**
   - Clear all browser data
   - Restart server
   
2. **Register New User**
   - Go to authentication page
   - Register with new credentials
   - Should redirect to profile
   
3. **Browse Content**
   - Navigate to courses
   - Navigate to opportunities
   - Navigate to mentorship
   - Navigate to portfolios
   - All should load without errors
   
4. **Interact**
   - Enroll in a course
   - Apply to an opportunity
   - Request mentorship
   - Create a portfolio
   - All should succeed
   
5. **Manage Profile**
   - Update profile information
   - View activity
   - Check stats
   - All should work
   
6. **Logout**
   - Sign out
   - Try to access protected features
   - Should be prompted to sign in

If all steps complete successfully: **Ìæâ Integration Complete!**

## Ì≥ù Troubleshooting Guide

### Issue: api is undefined
**Fix:** Check that `<script src="public/js/api.js"></script>` is in HTML

### Issue: CORS errors
**Fix:** Ensure server allows origin http://localhost:5000

### Issue: 401 Unauthorized
**Fix:** Check token in localStorage, sign in again if missing

### Issue: Courses/Opportunities not loading
**Fix:** Verify MongoDB is running and database is seeded

### Issue: Cannot read property of undefined
**Fix:** Check API response structure matches frontend expectations

---

‚úÖ **Integration Status:** _Complete this checklist to verify_

**Last Updated:** [Current Date]
**Version:** 1.0.0
