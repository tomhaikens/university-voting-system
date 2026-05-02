-- Create database
CREATE DATABASE IF NOT EXISTS university_voting_system;
USE university_voting_system;

-- ==============================================
-- USERS TABLE
-- ==============================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    faculty VARCHAR(100) NOT NULL,
    course VARCHAR(50) NOT NULL,
    address_type ENUM('Mile 3', 'Mile 4', 'Town', 'Halls') NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    is_blocked BOOLEAN DEFAULT FALSE,
    has_voted_general BOOLEAN DEFAULT FALSE,
    has_voted_faculty BOOLEAN DEFAULT FALSE,
    has_voted_regional BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_faculty_course (faculty, course),
    INDEX idx_address (address_type)
);

-- ==============================================
-- POSTS TABLE
-- ==============================================
CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    category ENUM('general', 'faculty', 'regional') NOT NULL,
    faculty_filter VARCHAR(100) NULL,
    course_filter VARCHAR(50) NULL,
    location_filter VARCHAR(20) NULL,
    description TEXT,
    INDEX idx_category (category),
    INDEX idx_course (course_filter),
    INDEX idx_location (location_filter)
);

-- ==============================================
-- GENERAL POSTS
-- ==============================================
INSERT INTO posts (name, category, description) VALUES 
('Guild President', 'general', 'University Guild President - Leads the entire student body'),
('Guild Vice President', 'general', 'University Guild Vice President - Assists the Guild President'),
('Guild Prime Minister', 'general', 'University Guild Prime Minister - Oversees academic affairs'),
('Guild Minister of Finance', 'general', 'University Guild Minister of Finance - Manages guild funds'),
('Guild Minister of Sports', 'general', 'University Guild Minister of Sports - Organizes sports activities'),
('Guild Minister of Entertainment', 'general', 'University Guild Minister of Entertainment - Organizes social events');

-- ==============================================
-- FACULTY POSTS - Computing and Informatics
-- ==============================================
INSERT INTO posts (name, category, faculty_filter, course_filter, description) VALUES 
('GRC - BCS', 'faculty', 'Computing and Informatics', 'BCS', 'Faculty Representative for Bachelor of Computer Science'),
('Secretary - BCS', 'faculty', 'Computing and Informatics', 'BCS', 'Secretary for Bachelor of Computer Science'),
('GRC - BSE', 'faculty', 'Computing and Informatics', 'BSE', 'Faculty Representative for Bachelor of Software Engineering'),
('Secretary - BSE', 'faculty', 'Computing and Informatics', 'BSE', 'Secretary for Bachelor of Software Engineering'),
('GRC - BIT', 'faculty', 'Computing and Informatics', 'BIT', 'Faculty Representative for Bachelor of Information Technology'),
('Secretary - BIT', 'faculty', 'Computing and Informatics', 'BIT', 'Secretary for Bachelor of Information Technology'),
('GRC - BIS', 'faculty', 'Computing and Informatics', 'BIS', 'Faculty Representative for Bachelor of Information Systems'),
('Secretary - BIS', 'faculty', 'Computing and Informatics', 'BIS', 'Secretary for Bachelor of Information Systems'),
('GRC - BCSIT', 'faculty', 'Computing and Informatics', 'BCSIT', 'Faculty Representative for Bachelor of Computer Science and IT'),
('Secretary - BCSIT', 'faculty', 'Computing and Informatics', 'BCSIT', 'Secretary for Bachelor of Computer Science and IT'),
('GRC - Data Science', 'faculty', 'Computing and Informatics', 'BSc. Data Science', 'Faculty Representative for Bachelor of Data Science'),
('Secretary - Data Science', 'faculty', 'Computing and Informatics', 'BSc. Data Science', 'Secretary for Bachelor of Data Science');

-- ==============================================
-- FACULTY POSTS - Engineering
-- ==============================================
INSERT INTO posts (name, category, faculty_filter, course_filter, description) VALUES 
('GRC - Civil Engineering', 'faculty', 'Engineering', 'BSE (Civil)', 'Faculty Representative for Civil Engineering'),
('Secretary - Civil Engineering', 'faculty', 'Engineering', 'BSE (Civil)', 'Secretary for Civil Engineering'),
('GRC - Electrical Engineering', 'faculty', 'Engineering', 'BSE (Electrical)', 'Faculty Representative for Electrical Engineering'),
('Secretary - Electrical Engineering', 'faculty', 'Engineering', 'BSE (Electrical)', 'Secretary for Electrical Engineering'),
('GRC - Mechanical Engineering', 'faculty', 'Engineering', 'BSE (Mechanical)', 'Faculty Representative for Mechanical Engineering'),
('Secretary - Mechanical Engineering', 'faculty', 'Engineering', 'BSE (Mechanical)', 'Secretary for Mechanical Engineering'),
('GRC - Telecommunications', 'faculty', 'Engineering', 'BSE (Telecom)', 'Faculty Representative for Telecommunications Engineering'),
('Secretary - Telecommunications', 'faculty', 'Engineering', 'BSE (Telecom)', 'Secretary for Telecommunications Engineering'),
('GRC - Mechatronics', 'faculty', 'Engineering', 'BSE (Mechatronics)', 'Faculty Representative for Mechatronics Engineering'),
('Secretary - Mechatronics', 'faculty', 'Engineering', 'BSE (Mechatronics)', 'Secretary for Mechatronics Engineering'),
('GRC - Petroleum Engineering', 'faculty', 'Engineering', 'BSE (Petroleum)', 'Faculty Representative for Petroleum Engineering'),
('Secretary - Petroleum Engineering', 'faculty', 'Engineering', 'BSE (Petroleum)', 'Secretary for Petroleum Engineering');

-- ==============================================
-- FACULTY POSTS - Science
-- ==============================================
INSERT INTO posts (name, category, faculty_filter, course_filter, description) VALUES 
('GRC - Biology', 'faculty', 'Science', 'BSc. Biology', 'Faculty Representative for Bachelor of Biology'),
('Secretary - Biology', 'faculty', 'Science', 'BSc. Biology', 'Secretary for Bachelor of Biology'),
('GRC - Chemistry', 'faculty', 'Science', 'BSc. Chemistry', 'Faculty Representative for Bachelor of Chemistry'),
('Secretary - Chemistry', 'faculty', 'Science', 'BSc. Chemistry', 'Secretary for Bachelor of Chemistry'),
('GRC - Physics', 'faculty', 'Science', 'BSc. Physics', 'Faculty Representative for Bachelor of Physics'),
('Secretary - Physics', 'faculty', 'Science', 'BSc. Physics', 'Secretary for Bachelor of Physics'),
('GRC - Mathematics', 'faculty', 'Science', 'BSc. Mathematics', 'Faculty Representative for Bachelor of Mathematics'),
('Secretary - Mathematics', 'faculty', 'Science', 'BSc. Mathematics', 'Secretary for Bachelor of Mathematics'),
('GRC - Statistics', 'faculty', 'Science', 'BSc. Statistics', 'Faculty Representative for Bachelor of Statistics'),
('Secretary - Statistics', 'faculty', 'Science', 'BSc. Statistics', 'Secretary for Bachelor of Statistics'),
('GRC - Industrial Chemistry', 'faculty', 'Science', 'BSc. Industrial Chemistry', 'Faculty Representative for Industrial Chemistry'),
('Secretary - Industrial Chemistry', 'faculty', 'Science', 'BSc. Industrial Chemistry', 'Secretary for Industrial Chemistry');

-- ==============================================
-- FACULTY POSTS - Business and Management Sciences
-- ==============================================
INSERT INTO posts (name, category, faculty_filter, course_filter, description) VALUES 
('GRC - BBA', 'faculty', 'Business and Management Sciences', 'BBA', 'Faculty Representative for Bachelor of Business Administration'),
('Secretary - BBA', 'faculty', 'Business and Management Sciences', 'BBA', 'Secretary for Bachelor of Business Administration'),
('GRC - BCOM', 'faculty', 'Business and Management Sciences', 'BCOM', 'Faculty Representative for Bachelor of Commerce'),
('Secretary - BCOM', 'faculty', 'Business and Management Sciences', 'BCOM', 'Secretary for Bachelor of Commerce'),
('GRC - BPA', 'faculty', 'Business and Management Sciences', 'BPA', 'Faculty Representative for Bachelor of Public Administration'),
('Secretary - BPA', 'faculty', 'Business and Management Sciences', 'BPA', 'Secretary for Bachelor of Public Administration'),
('GRC - BHRM', 'faculty', 'Business and Management Sciences', 'BHRM', 'Faculty Representative for Bachelor of Human Resource Management'),
('Secretary - BHRM', 'faculty', 'Business and Management Sciences', 'BHRM', 'Secretary for Bachelor of Human Resource Management'),
('GRC - BITAM', 'faculty', 'Business and Management Sciences', 'BITAM', 'Faculty Representative for Business Information Technology and Management'),
('Secretary - BITAM', 'faculty', 'Business and Management Sciences', 'BITAM', 'Secretary for Business Information Technology and Management'),
('GRC - Economics', 'faculty', 'Business and Management Sciences', 'BSc. Economics', 'Faculty Representative for Bachelor of Economics'),
('Secretary - Economics', 'faculty', 'Business and Management Sciences', 'BSc. Economics', 'Secretary for Bachelor of Economics');

-- ==============================================
-- FACULTY POSTS - Medicine
-- ==============================================
INSERT INTO posts (name, category, faculty_filter, course_filter, description) VALUES 
('GRC - MBChB', 'faculty', 'Medicine', 'MBChB', 'Faculty Representative for Bachelor of Medicine and Surgery'),
('Secretary - MBChB', 'faculty', 'Medicine', 'MBChB', 'Secretary for Bachelor of Medicine and Surgery'),
('GRC - BDS', 'faculty', 'Medicine', 'BDS', 'Faculty Representative for Bachelor of Dental Surgery'),
('Secretary - BDS', 'faculty', 'Medicine', 'BDS', 'Secretary for Bachelor of Dental Surgery'),
('GRC - BPharm', 'faculty', 'Medicine', 'BPharm', 'Faculty Representative for Bachelor of Pharmacy'),
('Secretary - BPharm', 'faculty', 'Medicine', 'BPharm', 'Secretary for Bachelor of Pharmacy'),
('GRC - BNS', 'faculty', 'Medicine', 'BNS', 'Faculty Representative for Bachelor of Nursing Science'),
('Secretary - BNS', 'faculty', 'Medicine', 'BNS', 'Secretary for Bachelor of Nursing Science'),
('GRC - BMLS', 'faculty', 'Medicine', 'BMLS', 'Faculty Representative for Medical Laboratory Science'),
('Secretary - BMLS', 'faculty', 'Medicine', 'BMLS', 'Secretary for Medical Laboratory Science'),
('GRC - BRT', 'faculty', 'Medicine', 'BRT', 'Faculty Representative for Bachelor of Radiography Technology'),
('Secretary - BRT', 'faculty', 'Medicine', 'BRT', 'Secretary for Bachelor of Radiography Technology'),
('GRC - BPT', 'faculty', 'Medicine', 'BPT', 'Faculty Representative for Bachelor of Physiotherapy'),
('Secretary - BPT', 'faculty', 'Medicine', 'BPT', 'Secretary for Bachelor of Physiotherapy'),
('GRC - BOT', 'faculty', 'Medicine', 'BOT', 'Faculty Representative for Bachelor of Occupational Therapy'),
('Secretary - BOT', 'faculty', 'Medicine', 'BOT', 'Secretary for Bachelor of Occupational Therapy');

-- ==============================================
-- FACULTY POSTS - Agriculture
-- ==============================================
INSERT INTO posts (name, category, faculty_filter, course_filter, description) VALUES 
('GRC - Agriculture', 'faculty', 'Agriculture', 'BSc. Agriculture', 'Faculty Representative for Bachelor of Agriculture'),
('Secretary - Agriculture', 'faculty', 'Agriculture', 'BSc. Agriculture', 'Secretary for Bachelor of Agriculture'),
('GRC - Agribusiness', 'faculty', 'Agriculture', 'BSc. Agribusiness', 'Faculty Representative for Agribusiness'),
('Secretary - Agribusiness', 'faculty', 'Agriculture', 'BSc. Agribusiness', 'Secretary for Agribusiness'),
('GRC - Food Science', 'faculty', 'Agriculture', 'BSc. Food Science', 'Faculty Representative for Food Science'),
('Secretary - Food Science', 'faculty', 'Agriculture', 'BSc. Food Science', 'Secretary for Food Science'),
('GRC - Nutrition', 'faculty', 'Agriculture', 'BSc. Nutrition', 'Faculty Representative for Nutrition'),
('Secretary - Nutrition', 'faculty', 'Agriculture', 'BSc. Nutrition', 'Secretary for Nutrition'),
('GRC - Horticulture', 'faculty', 'Agriculture', 'BSc. Horticulture', 'Faculty Representative for Horticulture'),
('Secretary - Horticulture', 'faculty', 'Agriculture', 'BSc. Horticulture', 'Secretary for Horticulture');

-- ==============================================
-- FACULTY POSTS - Interdisciplinary Studies
-- ==============================================
INSERT INTO posts (name, category, faculty_filter, course_filter, description) VALUES 
('GRC - BDE', 'faculty', 'Interdisciplinary Studies', 'BDE', 'Faculty Representative for Development Economics'),
('Secretary - BDE', 'faculty', 'Interdisciplinary Studies', 'BDE', 'Secretary for Development Economics'),
('GRC - BES', 'faculty', 'Interdisciplinary Studies', 'BES', 'Faculty Representative for Environmental Science'),
('Secretary - BES', 'faculty', 'Interdisciplinary Studies', 'BES', 'Secretary for Environmental Science'),
('GRC - BGS', 'faculty', 'Interdisciplinary Studies', 'BGS', 'Faculty Representative for Gender Studies'),
('Secretary - BGS', 'faculty', 'Interdisciplinary Studies', 'BGS', 'Secretary for Gender Studies'),
('GRC - BSWASA', 'faculty', 'Interdisciplinary Studies', 'BSWASA', 'Faculty Representative for Social Work'),
('Secretary - BSWASA', 'faculty', 'Interdisciplinary Studies', 'BSWASA', 'Secretary for Social Work'),
('GRC - BCD', 'faculty', 'Interdisciplinary Studies', 'BCD', 'Faculty Representative for Community Development'),
('Secretary - BCD', 'faculty', 'Interdisciplinary Studies', 'BCD', 'Secretary for Community Development');

-- ==============================================
-- REGIONAL POSTS
-- ==============================================
INSERT INTO posts (name, category, location_filter, description) VALUES 
('GRC - Mile 3', 'regional', 'Mile 3', 'Regional Representative for Mile 3'),
('Secretary - Mile 3', 'regional', 'Mile 3', 'Regional Secretary for Mile 3'),
('GRC - Mile 4', 'regional', 'Mile 4', 'Regional Representative for Mile 4'),
('Secretary - Mile 4', 'regional', 'Mile 4', 'Regional Secretary for Mile 4'),
('GRC - Town', 'regional', 'Town', 'Regional Representative for Town'),
('Secretary - Town', 'regional', 'Town', 'Regional Secretary for Town'),
('GRC - Halls', 'regional', 'Halls', 'Regional Representative for Halls of Residence'),
('Secretary - Halls', 'regional', 'Halls', 'Regional Secretary for Halls of Residence');

-- ==============================================
-- CANDIDATES TABLE
-- ==============================================
CREATE TABLE candidates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    post_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    course VARCHAR(50) NOT NULL,
    faculty VARCHAR(100) NOT NULL,
    manifesto TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    INDEX idx_post (post_id),
    INDEX idx_course (course)
);

-- ==============================================
-- VOTES TABLE
-- ==============================================
CREATE TABLE votes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    candidate_id INT NOT NULL,
    post_id INT NOT NULL,
    voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    UNIQUE KEY unique_vote_per_post (user_id, post_id),
    INDEX idx_user (user_id),
    INDEX idx_candidate (candidate_id),
    INDEX idx_post_vote (post_id)
);

-- ==============================================
-- ELECTION SETTINGS TABLE
-- ==============================================
CREATE TABLE election_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default election settings
INSERT INTO election_settings (start_time, end_time) VALUES ('09:00:00', '16:00:00');

-- ==============================================
-- PRIMARY ADMIN ACCOUNT
-- Password: 1234 (hashed)
-- ==============================================
INSERT INTO users (name, email, password, faculty, course, address_type, role) VALUES 
('System Administrator', '2024bcs195@std.must.ac.ug', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Computing and Informatics', 'BCS', 'Halls', 'admin');

-- ==============================================
-- SAMPLE STUDENTS FOR TESTING
-- ==============================================
INSERT INTO users (name, email, password, faculty, course, address_type, role) VALUES 

('Jane Smith', '2024bse002@std.must.ac.ug', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Computing and Informatics', 'BSE', 'Town', 'user'),
('Mike Johnson', '2024eee003@std.must.ac.ug', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Engineering', 'BSE (Electrical)', 'Mile 4', 'user');

-- ==============================================
-- SAMPLE CANDIDATES
-- ==============================================
INSERT INTO candidates (post_id, name, email, course, faculty, manifesto) VALUES 
(1, 'Alice Kagwa', 'alice@must.ac.ug', 'BCS', 'Computing and Informatics', 'I will improve student welfare and academic excellence'),
(1, 'Bob Mwesigye', 'bob@must.ac.ug', 'BSE (Civil)', 'Engineering', 'Together we can transform MUST into a center of excellence');

-- ==============================================
-- FIXED: VOTER TURNOUT STATS VIEW
-- ==============================================
CREATE VIEW voter_turnout_stats AS
SELECT 
    users.faculty,
    COUNT(DISTINCT users.id) as total_students,
    SUM(CASE WHEN users.has_voted_general = TRUE THEN 1 ELSE 0 END) as general_voters,
    SUM(CASE WHEN users.has_voted_faculty = TRUE THEN 1 ELSE 0 END) as faculty_voters,
    SUM(CASE WHEN users.has_voted_regional = TRUE THEN 1 ELSE 0 END) as regional_voters
FROM users
WHERE users.role = 'user'
GROUP BY users.faculty;

-- ==============================================
-- CANDIDATE VOTE COUNTS VIEW
-- ==============================================
CREATE VIEW candidate_vote_counts AS
SELECT 
    c.id as candidate_id,
    c.name as candidate_name,
    p.name as post_name,
    p.category,
    COUNT(v.id) as vote_count
FROM candidates c
LEFT JOIN votes v ON c.id = v.candidate_id
LEFT JOIN posts p ON c.post_id = p.id
GROUP BY c.id, p.name, p.category;

-- ==============================================
-- STORED PROCEDURES
-- ==============================================

-- Procedure to reset election
DELIMITER //
CREATE PROCEDURE ResetElection()
BEGIN
    UPDATE users SET 
        has_voted_general = FALSE,
        has_voted_faculty = FALSE,
        has_voted_regional = FALSE;
    DELETE FROM votes;
END//
DELIMITER ;

-- Procedure to get real-time results
DELIMITER //
CREATE PROCEDURE GetElectionResults()
BEGIN
    SELECT 
        p.category,
        p.name as post_name,
        c.name as candidate_name,
        c.course,
        c.faculty,
        COUNT(v.id) as votes
    FROM posts p
    LEFT JOIN candidates c ON p.id = c.post_id
    LEFT JOIN votes v ON c.id = v.candidate_id
    GROUP BY p.id, c.id
    ORDER BY p.category, p.name, votes DESC;
END//
DELIMITER ;

-- ==============================================
-- INDEXES FOR PERFORMANCE
-- ==============================================
CREATE INDEX idx_users_faculty_course ON users(faculty, course);
CREATE INDEX idx_users_address ON users(address_type);
CREATE INDEX idx_votes_user_post ON votes(user_id, post_id);
CREATE INDEX idx_candidates_post ON candidates(post_id);