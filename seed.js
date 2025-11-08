const mongoose = require('mongoose');
const dotenv = require('dotenv');
const User = require('./models/User');
const Course = require('./models/Course');
const Opportunity = require('./models/Opportunity');

dotenv.config();

const users = [
  {
    name: 'Filmmaker Demo',
    email: 'filmmaker@cinda.com',
    password: 'filmmaker123',
    userType: 'filmmaker',
    bio: 'Passionate filmmaker dedicated to telling authentic African stories',
    location: 'Kigali, Rwanda',
    specialization: ['Cinematography', 'Directing']
  },
  {
    name: 'Sarah Mitchell',
    email: 'mentor@cinda.com',
    password: 'mentor123',
    userType: 'mentor',
    bio: 'Award-winning cinematographer with 15+ years of experience',
    location: 'Los Angeles, CA',
    specialization: ['Cinematography', 'Lighting', 'Documentary']
  },
  {
    name: 'Sponsor Demo',
    email: 'sponsor@cinda.com',
    password: 'sponsor123',
    userType: 'sponsor',
    bio: 'Supporting emerging talent in African film industry',
    location: 'New York, NY',
    specialization: ['Film Financing', 'Production']
  }
];

const courses = [
  {
    title: "Introduction to Cinematography",
    category: "CINEMATOGRAPHY",
    instructor: {
      name: "Roger Deakins",
      bio: "Award-winning cinematographer"
    },
    description: "Master the fundamentals of camera work, lighting composition, and visual storytelling techniques used in professional film production.",
    image: "d.jpeg",
    duration: "12 weeks",
    level: "Beginner",
    price: 0
  },
  {
    title: "Film Editing Masterclass",
    category: "EDITING",
    instructor: {
      name: "Thelma Schoonmaker",
      bio: "Legendary film editor"
    },
    description: "Learn professional editing techniques, pacing, rhythm, and how to craft compelling narratives in post-production.",
    image: "n.jpg",
    duration: "8 weeks",
    level: "Intermediate",
    price: 0
  },
  {
    title: "Directing Actors",
    category: "DIRECTING",
    instructor: {
      name: "Martin Scorsese",
      bio: "Acclaimed director"
    },
    description: "Discover how to communicate with actors, block scenes effectively, and bring out authentic performances on camera.",
    image: "ik.jpeg",
    duration: "10 weeks",
    level: "Advanced",
    price: 0
  },
  {
    title: "Sound Design for Film",
    category: "SOUND DESIGN",
    instructor: {
      name: "Ben Burtt",
      bio: "Sound design pioneer"
    },
    description: "Create immersive audio experiences through sound effects, foley, dialogue editing, and mixing techniques.",
    image: "yh.jpg",
    duration: "6 weeks",
    level: "Intermediate",
    price: 0
  },
  {
    title: "Screenwriting Essentials",
    category: "SCREENWRITING",
    instructor: {
      name: "Aaron Sorkin",
      bio: "Award-winning screenwriter"
    },
    description: "Craft compelling narratives, develop memorable characters, and master screenplay structure and dialogue.",
    image: "c.jpg",
    duration: "14 weeks",
    level: "Beginner",
    price: 0
  },
  {
    title: "Advanced Lighting Techniques",
    category: "LIGHTING",
    instructor: {
      name: "Emmanuel Lubezki",
      bio: "Master cinematographer"
    },
    description: "Master natural and artificial lighting setups, color temperature, and creating mood through illumination.",
    image: "cx.jpeg",
    duration: "9 weeks",
    level: "Advanced",
    price: 0
  },
  {
    title: "Production Design & Art Direction",
    category: "PRODUCTION DESIGN",
    instructor: {
      name: "Hannah Beachler",
      bio: "Production designer"
    },
    description: "Learn to create visually stunning worlds, design sets, and collaborate with directors to realize their vision.",
    image: "t.jpeg",
    duration: "11 weeks",
    level: "Intermediate",
    price: 0
  },
  {
    title: "Color Grading & Finishing",
    category: "COLOR GRADING",
    instructor: {
      name: "Walter Volpatto",
      bio: "Color grading expert"
    },
    description: "Master DaVinci Resolve and industry-standard color grading workflows to achieve cinematic looks.",
    image: "aa.jpeg",
    duration: "7 weeks",
    level: "Advanced",
    price: 0
  },
  {
    title: "Documentary Filmmaking",
    category: "DOCUMENTARY",
    instructor: {
      name: "Ken Burns",
      bio: "Documentary filmmaker"
    },
    description: "Explore the art of non-fiction storytelling, interview techniques, and ethical considerations in documentary work.",
    image: "tr.jpg",
    duration: "10 weeks",
    level: "Beginner",
    price: 0
  }
];

const opportunities = [
  {
    type: "GRANT",
    title: "Independent Film Production Grant",
    company: "Sundance Institute",
    description: "We're seeking original, compelling narratives from emerging filmmakers. This grant provides $50,000 in production funding, mentorship from industry veterans, and distribution support for your independent feature film project.",
    details: {
      funding: "$50,000 Funding",
      location: "Remote/Worldwide",
      category: "Feature Film"
    },
    deadline: new Date(Date.now() + 60 * 24 * 60 * 60 * 1000) // 60 days from now
  },
  {
    type: "JOB",
    title: "Cinematographer for Documentary Series",
    company: "National Geographic",
    description: "Join our team to shoot an environmental documentary series exploring climate change across six continents. We're looking for an experienced DP with strong natural history filmmaking skills and ability to work in challenging conditions.",
    details: {
      duration: "3-month contract",
      location: "International Travel",
      category: "Full-time"
    },
    deadline: new Date(Date.now() + 15 * 24 * 60 * 60 * 1000) // 15 days
  },
  {
    type: "COMPETITION",
    title: "International Short Film Festival",
    company: "Cannes Short Film Corner",
    description: "Submit your short film (under 40 minutes) for a chance to screen at Cannes. Winners receive $25,000 cash prize, distribution deal with major platform, and networking opportunities with industry professionals.",
    details: {
      funding: "$25,000 Prize",
      location: "Cannes, France",
      category: "Short Films"
    },
    deadline: new Date(Date.now() + 45 * 24 * 60 * 60 * 1000) // 45 days
  },
  {
    type: "INTERNSHIP",
    title: "Production Assistant Internship",
    company: "Warner Bros. Studios",
    description: "Gain hands-on experience in major studio production. Work alongside industry professionals on set, learn about all aspects of film production, and build your network in Hollywood.",
    details: {
      duration: "6 months",
      location: "Los Angeles, CA",
      category: "Paid Internship"
    },
    deadline: new Date(Date.now() + 20 * 24 * 60 * 60 * 1000) // 20 days
  },
  {
    type: "COLLABORATION",
    title: "Seeking Director for Music Video",
    company: "Independent Artist",
    description: "Upcoming artist looking for creative director to collaborate on a series of music videos. This is a paid opportunity with creative freedom and potential for long-term collaboration on future projects.",
    details: {
      funding: "$15,000 Budget",
      location: "New York, NY",
      category: "Music Video"
    },
    deadline: new Date(Date.now() + 10 * 24 * 60 * 60 * 1000) // 10 days
  },
  {
    type: "GRANT",
    title: "Women in Film Diversity Grant",
    company: "Film Independent",
    description: "Supporting underrepresented voices in cinema. This grant provides $35,000 funding, production resources, and year-long mentorship for women directors working on their first or second feature film.",
    details: {
      funding: "$35,000 Funding",
      location: "Worldwide",
      category: "Feature Film"
    },
    deadline: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000) // 30 days
  }
];

async function seedDatabase() {
  try {
    console.log('🔄 Connecting to MongoDB...');
    await mongoose.connect(process.env.MONGODB_URI || 'mongodb://localhost:27017/cinda');
    console.log('✅ Connected to MongoDB');

    // Clear existing data
    console.log('🗑️  Clearing existing data...');
    await User.deleteMany({});
    await Course.deleteMany({});
    await Opportunity.deleteMany({});
    console.log('✅ Existing data cleared');

    // Seed users
    console.log('👥 Seeding users...');
    const createdUsers = await User.insertMany(users);
    console.log(`✅ Created ${createdUsers.length} users`);

    // Seed courses
    console.log('📚 Seeding courses...');
    const createdCourses = await Course.insertMany(courses);
    console.log(`✅ Created ${createdCourses.length} courses`);

    // Seed opportunities
    console.log('💼 Seeding opportunities...');
    const createdOpportunities = await Opportunity.insertMany(opportunities);
    console.log(`✅ Created ${createdOpportunities.length} opportunities`);

    console.log('\n🎉 Database seeded successfully!');
    console.log('\n📋 Demo Credentials:');
    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    console.log('Filmmaker:');
    console.log('  Email: filmmaker@cinda.com');
    console.log('  Password: filmmaker123');
    console.log('\nMentor:');
    console.log('  Email: mentor@cinda.com');
    console.log('  Password: mentor123');
    console.log('\nSponsor:');
    console.log('  Email: sponsor@cinda.com');
    console.log('  Password: sponsor123');
    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n');

    process.exit(0);
  } catch (error) {
    console.error('❌ Seeding error:', error);
    process.exit(1);
  }
}

seedDatabase();