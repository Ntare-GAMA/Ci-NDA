Ci-NDA: A Digital Platform for Cinematography Skill Development & Global Exposure
Prepared by: NTARE GAMA Allan
Institution: African Leadership University
Date: 12/10/2025


1. The Mission
My mission at ALU is to empower Rwandans with the skills and opportunities to thrive in cinematography. Through Ci-NDA, I aim to provide accessible digital resources, mentorship, and exposure pathways that elevate filmmaking standards. This mission seeks to bridge the gap between local talent and international platforms, ensuring that Rwandan stories are told authentically and competitively. By doing so, we strengthen youth employment, cultural identity, and Rwanda’s position in the global creative economy.

         Relevance in Africa:

 Africa’s creative economy is booming, but Rwandan filmmakers lack easy access to structured training and global opportunities. Ci-NDA bridges that gap by making resources and opportunities accessible from anywhere in Rwanda, especially for youth who can’t afford expensive film schools.

2. Problem Statement
      “Rwandan filmmakers lack skills, resources, and exposure.”
Rwandan filmmakers struggle to access the skills, resources, and opportunities needed to compete in today’s global cinema industry. The creative sector contributes less than 2% to Rwanda’s GDP compared to Nigeria’s Nollywood at 5%, highlighting a major gap in growth potential. Filmmakers face a lack of advanced training programs, mentorship, and structured career pathways. Access to professional-grade equipment and affordable learning resources remains limited. Opportunities for international exposure and festival participation are scarce, leaving many unable to showcase their talent on global platforms. Without a centralized system to connect them with knowledge, resources, and opportunities, Rwanda risks underutilizing its creative talent pool.

3. Software Development Model
To address this challenge, the Incremental Prototyping Model will guide the development of the platform. The first phase will deliver a Minimum Viable Product (MVP) featuring mentorship and structured training resources for filmmakers. The second phase will allow users to upload portfolios and access a resource library to elevate their skills. The third phase will integrate tools for festival submissions and international collaboration. Finally, an investor and sponsorship analytics dashboard will connect creators with financial opportunities. This staged model ensures that each release delivers tangible value, reduces upfront risk, and allows feedback-driven improvements. Investors can track growth and fund development gradually, making progress measurable at every step.

4. Hypothesis of the Solution
If aspiring filmmakers are given a digital platform that provides structured learning, mentorship, and access to global opportunities, then Rwanda’s cinema industry can be transformed into a thriving regional hub. With curated resources, creators will sharpen their technical and creative skills while gaining visibility for their work. Portfolio showcases and submission tools will expand their reach to festivals, investors, and distributors worldwide. This will drive youth employment, stimulate innovation, and increase Rwanda’s cultural exports. Within 5–10 years, the country can build a self-sustaining creative ecosystem recognized on global platforms. The solution will bridge the gap between talent, training, and opportunity.



5. References (APA Style)
UNESCO. (2022). The African Creative Economy Report.


Rwanda Development Board. (2023). Creative Industries Policy Brief.


African Union. (2021). Agenda 2063: The Africa We Want.





# CI-NDA Platform - Setup Guide

## Prerequisites

Before you begin, ensure you have the following installed:
- Node.js (v14 or higher)
- npm (v6 or higher)
- MongoDB (v4.4 or higher)

## Installation Steps

### 1. Install Dependencies

```bash
npm install
```

### 2. Configure Environment Variables

Create a `.env` file in the root directory:

```bash
cp .env.example .env
```

Update the `.env` file with your configuration:

```env
PORT=5000
NODE_ENV=development
MONGODB_URI=mongodb://localhost:27017/cinda
JWT_SECRET=your-secure-jwt-secret
SESSION_SECRET=your-secure-session-secret
```

### 3. Start MongoDB

Make sure MongoDB is running on your system:

```bash
# Windows
mongod

# macOS/Linux
sudo systemctl start mongod
```

### 4. Seed the Database (Optional)

Populate the database with sample data:

```bash
npm run seed
```

### 5. Start the Server

```bash
# Development mode (with auto-reload)
npm run dev

# Production mode
npm start
```

The server will start on `http://localhost:5000`

## Project Structure

```
ci-nda-platform/
├── models/                 # MongoDB models
│   ├── User.js
│   ├── Course.js
│   ├── Opportunity.js
│   ├── Mentorship.js
│   └── Portfolio.js
├── routes/                 # API routes
│   ├── auth.js
│   ├── users.js
│   ├── courses.js
│   ├── opportunities.js
│   ├── mentorship.js
│   └── portfolios.js
├── middleware/             # Custom middleware
│   └── auth.js            # JWT authentication
├── public/                 # Static files
│   └── js/
│       └── api.js         # Frontend API client
├── HTML files             # Frontend pages
│   ├── index.html
│   ├── authentication.html
│   ├── profile.html
│   ├── courses.html
│   ├── opportunities.html
│   ├── mentorship.html
│   └── portfolios.html
├── server.js              # Express server
├── seed.js               # Database seeding script
├── package.json
└── .env                  # Environment variables
```

## API Endpoints

### Authentication
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login
- `POST /api/auth/social-login` - Social media login
- `POST /api/auth/logout` - Logout

### Users
- `GET /api/users/profile` - Get user profile (protected)
- `PUT /api/users/profile` - Update profile (protected)

### Courses
- `GET /api/courses` - Get all courses
- `GET /api/courses/:id` - Get single course
- `POST /api/courses/:id/enroll` - Enroll in course (protected)

### Opportunities
- `GET /api/opportunities` - Get all opportunities
- `POST /api/opportunities/:id/apply` - Apply to opportunity (protected)

### Mentorship
- `GET /api/mentorship` - Get all mentors
- `POST /api/mentorship/request` - Request mentorship (protected)
- `GET /api/mentorship/my-mentorships` - Get user's mentorships (protected)

### Portfolios
- `GET /api/portfolios` - Get all portfolios
- `POST /api/portfolios` - Create portfolio (protected)
- `POST /api/portfolios/:id/like` - Like portfolio (protected)
- `POST /api/portfolios/:id/comment` - Comment on portfolio (protected)

## Frontend Features

### Authentication System
- Email/password registration and login
- Social media login (Google, Facebook)
- JWT token-based authentication
- User type selection (Filmmaker, Mentor, Sponsor)

### User Dashboard
- Profile management
- Course enrollment tracking
- Application management
- Mentorship sessions

### Courses
- Browse and filter courses
- Course enrollment
- Progress tracking

### Opportunities
- Browse grants, jobs, competitions
- Filter by type
- Apply to opportunities

### Mentorship
- Find and connect with mentors
- Request mentorship
- Schedule sessions

### Portfolios
- Showcase work
- Like and comment on projects
- Filter by category

## Testing the Application

### Demo Credentials

After seeding, you can use these credentials:

**Filmmaker:**
- Email: `filmmaker@cinda.com`
- Password: `filmmaker123`

**Mentor:**
- Email: `mentor@cinda.com`
- Password: `mentor123`

**Sponsor:**
- Email: `sponsor@cinda.com`
- Password: `sponsor123`

## Troubleshooting

### MongoDB Connection Error
```
Error: Failed to connect to MongoDB
```
**Solution:** Ensure MongoDB is running and the connection string in `.env` is correct.

### Port Already in Use
```
Error: Port 5000 is already in use
```
**Solution:** Change the `PORT` in `.env` or kill the process using port 5000.

### JWT Token Errors
```
Error: Invalid or expired token
```
**Solution:** Clear browser localStorage and sign in again.

### CORS Errors
**Solution:** Ensure the frontend is accessing the correct API URL (http://localhost:5000).

## Development Tips

1. **Watch for changes:** Use `npm run dev` for automatic server restart
2. **Check logs:** Monitor console for errors and API requests
3. **Test API:** Use tools like Postman or Thunder Client
4. **Clear cache:** If issues persist, clear browser cache and localStorage

## Production Deployment

Before deploying to production:

1. Set `NODE_ENV=production` in `.env`
2. Use strong JWT and session secrets
3. Configure proper CORS origins
4. Use a production MongoDB database (MongoDB Atlas recommended)
5. Enable HTTPS
6. Set secure cookie options

## Support

For issues or questions, refer to the documentation or contact the development team.