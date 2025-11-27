<?php
require_once __DIR__ . '/db.php';

echo "Seeding sample applications and requests...\n";

// Create tables if they don't exist
$conn->query('CREATE TABLE IF NOT EXISTS mentor_applications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    title TEXT,
    bio TEXT,
    specialties TEXT,
    years_experience INTEGER DEFAULT 0,
    avatar_url TEXT,
    portfolio_url TEXT,
    status TEXT DEFAULT "pending",
    admin_notes TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    reviewed_at TEXT
)');

$conn->query('CREATE TABLE IF NOT EXISTS mentorship_requests (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_name TEXT NOT NULL,
    student_email TEXT NOT NULL,
    mentor_id INTEGER,
    mentor_name TEXT,
    goals TEXT,
    experience_level TEXT,
    availability TEXT,
    status TEXT DEFAULT "pending",
    admin_notes TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    reviewed_at TEXT
)');

$conn->query('CREATE TABLE IF NOT EXISTS opportunity_applications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    applicant_name TEXT NOT NULL,
    applicant_email TEXT NOT NULL,
    opportunity_id INTEGER,
    opportunity_title TEXT,
    cover_letter TEXT,
    portfolio_url TEXT,
    experience TEXT,
    status TEXT DEFAULT "pending",
    admin_notes TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    reviewed_at TEXT
)');

$conn->query('CREATE TABLE IF NOT EXISTS messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    sender_id TEXT NOT NULL,
    receiver_id TEXT NOT NULL,
    message TEXT NOT NULL,
    is_read INTEGER DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
)');

// Clear existing data
$conn->query('DELETE FROM mentor_applications');
$conn->query('DELETE FROM mentorship_requests');
$conn->query('DELETE FROM opportunity_applications');
$conn->query('DELETE FROM messages');

// Seed mentor applications
$mentorApps = [
    ['John Peterson', 'john.peterson@email.com', 'Cinematography Director', 'Experienced cinematographer with 8 years of film production', 'Cinematography, Lighting, Camera Operation', 8, 'https://i.pravatar.cc/300?img=12', 'https://portfolio.com/john'],
    ['Lisa Wang', 'lisa.wang@email.com', 'Film Editor', 'Award-winning editor specializing in narrative films', 'Film Editing, Color Grading, Post-Production', 6, 'https://i.pravatar.cc/300?img=45', 'https://portfolio.com/lisa'],
    ['Marcus Brown', 'marcus.brown@email.com', 'Sound Designer', 'Professional sound designer and audio engineer', 'Sound Design, Audio Engineering, Foley', 5, 'https://i.pravatar.cc/300?img=33', 'https://portfolio.com/marcus']
];

foreach ($mentorApps as $app) {
    $stmt = $conn->prepare('INSERT INTO mentor_applications (name, email, title, bio, specialties, years_experience, avatar_url, portfolio_url, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, "pending")');
    $stmt->bind_param('ssssssss', $app[0], $app[1], $app[2], $app[3], $app[4], $app[5], $app[6], $app[7]);
    $stmt->execute();
}

// Seed mentorship requests
$mentorshipReqs = [
    ['Emily Johnson', 'emily.j@email.com', 1, 'Sarah Mitchell', 'Learn cinematography fundamentals and camera techniques', 'Beginner', 'Weekends, 5-10 hours/week'],
    ['David Kim', 'david.kim@email.com', 2, 'James Chen', 'Improve editing skills for documentary filmmaking', 'Intermediate', 'Evenings, 3-5 hours/week'],
    ['Sofia Martinez', 'sofia.m@email.com', 1, 'Sarah Mitchell', 'Master advanced lighting techniques for narrative films', 'Advanced', 'Flexible schedule']
];

foreach ($mentorshipReqs as $req) {
    $stmt = $conn->prepare('INSERT INTO mentorship_requests (student_name, student_email, mentor_id, mentor_name, goals, experience_level, availability, status) VALUES (?, ?, ?, ?, ?, ?, ?, "pending")');
    $stmt->bind_param('ssissss', $req[0], $req[1], $req[2], $req[3], $req[4], $req[5], $req[6]);
    $stmt->execute();
}

// Seed opportunity applications
$oppApps = [
    ['Alex Thompson', 'alex.t@email.com', 1, 'Assistant Director Position', 'I am passionate about film direction and have assisted on 5 independent films...', 'https://portfolio.com/alex', '3 years as production assistant, 2 years as 2nd AD'],
    ['Rachel Green', 'rachel.g@email.com', 2, 'Cinematography Internship', 'Looking to gain hands-on experience in professional cinematography...', 'https://portfolio.com/rachel', 'Film school graduate, worked on 10+ student films'],
    ['Michael Chen', 'michael.c@email.com', 3, 'Sound Recording Assistant', 'Experienced with field recording and studio work...', 'https://portfolio.com/michael', '2 years freelance sound recording']
];

foreach ($oppApps as $app) {
    $stmt = $conn->prepare('INSERT INTO opportunity_applications (applicant_name, applicant_email, opportunity_id, opportunity_title, cover_letter, portfolio_url, experience, status) VALUES (?, ?, ?, ?, ?, ?, ?, "pending")');
    $stmt->bind_param('ssissss', $app[0], $app[1], $app[2], $app[3], $app[4], $app[5], $app[6]);
    $stmt->execute();
}

echo "Successfully seeded:\n";
echo "- 3 mentor applications\n";
echo "- 3 mentorship requests\n";
echo "- 3 opportunity applications\n";
echo "Done!\n";
?>
