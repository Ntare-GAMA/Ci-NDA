-- 1.  create & use database
CREATE DATABASE IF NOT EXISTS cinda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cinda;

-- 2.  users  (basic auth table)
CREATE TABLE users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  email         VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  name          VARCHAR(100),
  user_type     ENUM('filmmaker','mentor','sponsor') DEFAULT 'filmmaker',
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3.  courses
CREATE TABLE courses (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  title         VARCHAR(255) NOT NULL,
  instructor    VARCHAR(255) NOT NULL,
  category      VARCHAR(100) NOT NULL,
  duration      VARCHAR(50)  NOT NULL,
  level         ENUM('Beginner','Intermediate','Advanced') NOT NULL,
  description   TEXT         NOT NULL,
  image_url     VARCHAR(500),
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4.  enrollments
CREATE TABLE enrollments (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT,
  course_id   INT,
  schedule    VARCHAR(50),
  enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
  UNIQUE (user_id, course_id)
);

-- 5.  portfolios
CREATE TABLE portfolios (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  owner         VARCHAR(255) NOT NULL,
  title         VARCHAR(255) NOT NULL,
  description   TEXT,
  category      VARCHAR(100),
  tags          VARCHAR(500),
  views         INT DEFAULT 0,
  likes         INT DEFAULT 0,
  thumbnail_url VARCHAR(500),
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 6.  opportunities
CREATE TABLE opportunities (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(255) NOT NULL,
  org         VARCHAR(255) NOT NULL,
  type        ENUM('grant','job','competition','festival') NOT NULL,
  description TEXT,
  funding     VARCHAR(100),
  location    VARCHAR(100),
  deadline    DATE,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 7.  mentors
CREATE TABLE mentors (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  name            VARCHAR(255) NOT NULL,
  title           VARCHAR(255) NOT NULL,
  bio             TEXT,
  specialties     VARCHAR(500),        -- comma-separated
  years_mentoring INT DEFAULT 0,
  mentees_count   INT DEFAULT 0,
  spots_left      INT DEFAULT 5,
  avatar_url      VARCHAR(500),
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 8.  sample users  (password = plain text for demo only)
INSERT INTO users (email, password_hash, name, user_type) VALUES
('filmmaker@cinda.com', 'filmmaker123', 'John Filmmaker', 'filmmaker'),
('mentor@cinda.com',    'mentor123',    'Sarah Mentor',   'mentor'),
('sponsor@cinda.com',   'sponsor123',   'Big Sponsor',    'sponsor');

-- 9.  sample courses  (matches front-end)
INSERT INTO courses (title, instructor, category, duration, level, description, image_url) VALUES
('Introduction to Cinematography', 'Roger Deakins', 'CINEMATOGRAPHY', '12 weeks', 'Beginner', 'Master the fundamentals of camera work, lighting composition, and visual storytelling techniques used in professional film production.', 'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=400&q=80'),
('Film Editing Masterclass', 'Thelma Schoonmaker', 'EDITING', '8 weeks', 'Intermediate', 'Learn professional editing techniques, pacing, rhythm, and how to craft compelling narratives in post-production.', 'https://images.unsplash.com/photo-1536240478700-b869070f9279?w=400&q=80'),
('Directing Actors', 'Martin Scorsese', 'DIRECTING', '10 weeks', 'Advanced', 'Discover how to communicate with actors, block scenes effectively, and bring out authentic performances on camera.', 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=400&q=80'),
('Sound Design for Film', 'Ben Burtt', 'SOUND DESIGN', '6 weeks', 'Intermediate', 'Create immersive audio experiences through sound effects, foley, dialogue editing, and mixing techniques.', 'https://images.unsplash.com/photo-1579989913662-b9cd2e18dcb3?w=400&q=80'),
('Screenwriting Essentials', 'Aaron Sorkin', 'SCREENWRITING', '14 weeks', 'Beginner', 'Craft compelling narratives, develop memorable characters, and master screenplay structure and dialogue.', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80'),
('Advanced Lighting Techniques', 'Emmanuel Lubezki', 'LIGHTING', '9 weeks', 'Advanced', 'Master natural and artificial lighting setups, color temperature, and creating mood through illumination.', 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=400&q=80'),
('Production Design & Art Direction', 'Hannah Beachler', 'PRODUCTION DESIGN', '11 weeks', 'Intermediate', 'Learn to create visually stunning worlds, design sets, and collaborate with directors to realize their vision.', 'https://images.unsplash.com/photo-1524634126442-357e0eac3c14?w=400&q=80'),
('Color Grading & Finishing', 'Walter Volpatto', 'COLOR GRADING', '7 weeks', 'Advanced', 'Master DaVinci Resolve and industry-standard color grading workflows to achieve cinematic looks.', 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=400&q=80'),
('Documentary Filmmaking', 'Ken Burns', 'DOCUMENTARY', '10 weeks', 'Beginner', 'Explore the art of non-fiction storytelling, interview techniques, and ethical considerations in documentary work.', 'https://images.unsplash.com/photo-1508672019048-805c876b67e2?w=400&q=80');

-- 10.  sample portfolios
INSERT INTO portfolios (owner, title, description, category, tags, views, likes, thumbnail_url) VALUES
('Alex Johnson',  'Urban Stories Collection', 'A series of short films exploring life in modern cities through intimate character portraits and cinematic visuals.', 'Short Films', 'Drama,Documentary,Urban', 12500, 892, 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?w=600&q=80'),
('Maya Patel',    'Climate Voices', 'Award-winning documentary series highlighting communities affected by climate change across three continents.', 'Documentary', 'Environmental,Social Impact', 24800, 1500, 'https://images.unsplash.com/photo-1492619375914-88005aa9e8fb?w=600&q=80'),
('Carlos Mendez', 'Visual Rhythms', 'Creative music videos blending experimental visuals with contemporary music across multiple genres.', 'Music Videos', 'Experimental,Visual Effects', 18200, 1100, 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=600&q=80');

-- 11.  sample opportunities
INSERT INTO opportunities (title, org, type, description, funding, location, deadline) VALUES
('$50K Short Film Production Grant', 'Stowe Story Labs', 'grant', 'Narrative shorts (~10 min). Production funding + mentorship.', '$50 000', 'Worldwide', '2025-11-15'),
('Sundance Institute Documentary Fund', 'Sundance Institute', 'grant', 'Supports international documentary films in production/post.', 'Up to $50 000', 'Worldwide', '2025-12-15'),
('Cinematographer – Natural History', 'National Geographic', 'job', 'Shoot environmental docu-series across 6 continents.', 'Negotiable', 'International', '2025-12-31'),
('Manchester Film Festival 2026', 'FilmFreeway', 'festival', 'Open for features & shorts. Early-bird deadline.', 'Prizes + distribution', 'UK', '2025-11-30');

-- 12.  sample mentors
INSERT INTO mentors (name, title, bio, specialties, years_mentoring, mentees_count, spots_left, avatar_url) VALUES
('Sarah Mitchell', 'Award-Winning Cinematographer', '15+ years of experience in feature films and documentaries. Specializing in natural lighting and atmospheric compositions. Emmy-nominated for documentary work.', 'Cinematography,Lighting,Documentary', 15, 48, 5, 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200&q=80'),
('James Chen', 'Emmy-Nominated Editor', 'Specialized in documentary and narrative editing. Worked on multiple award-winning films and series. Passionate about teaching storytelling through editing.', 'Editing,Post-Production,Storytelling', 12, 35, 3, 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80'),
('Maria Rodriguez', 'Acclaimed Independent Director', 'Focused on developing new voices in independent cinema. Multiple festival awards and recognition for empowering emerging filmmakers globally.', 'Directing,Independent Film,Creative Vision', 10, 28, 2, 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=200&q=80');
