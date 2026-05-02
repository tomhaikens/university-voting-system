# University Voting Online System (UVOS)

A web-based student election platform built with PHP and MySQL that enables 
universities to conduct secure, organised guild elections online. The system 
supports three categories of elections — general, faculty-level, and regional 
— with role-based access for students and administrators.

## What the System Does

Students register using their university details including their faculty, 
course, and residential address. Once logged in, they can vote in the 
elections they are eligible for based on their profile. The system 
automatically determines which posts each student can vote on, preventing 
them from accessing elections outside their category. Voting is only 
permitted within a time window set by the administrator, and each student 
can only vote once per position.

Administrators have access to a separate dashboard where they can manage 
candidates, oversee registered users, block accounts, configure the voting 
time window, and generate downloadable reports broken down by faculty, 
course, and residential area.

## Features

- Student registration and secure login with password hashing
- Three election categories: General, Faculty, and Regional
- Automatic voter eligibility filtering based on student profile
- Time-restricted voting window controlled by the administrator
- Admin panel for managing candidates, users, and election settings
- Real-time vote monitoring accessible without login
- CSV report generation for voter turnout by faculty, course, and region
- Prevention of double voting enforced at the database level

## Tech Stack

- Backend: PHP (custom MVC architecture)
- Database: MySQL
- Frontend: HTML, CSS, Bootstrap, JavaScript
- Architecture: Model-View-Controller (MVC)

## Project Structure

uvos/
├── config/          # Database connection configuration
├── controllers/     # AuthController, AdminController, VoteController, MonitorController
├── core/            # Base Controller, Model, and Database classes
├── models/          # User, Candidate, Post, Vote, ElectionSettings models
├── views/           # PHP view templates (admin, auth, user)
├── db.sql           # Database schema and seed data
└── index.php        # Main entry point and router

## Getting Started

1. Clone the repository
2. Import db.sql into your MySQL database
3. Update config/database.php with your database credentials
4. Place the project inside your htdocs folder (XAMPP) or equivalent
5. Visit http://localhost/uvos in your browser to get started

## Election Categories

General elections are open to all registered students and cover 
university-wide guild positions such as Guild President, Vice President, 
and ministerial roles. Faculty elections are restricted to students within 
a specific course and cover positions like Guild Representative Councillor 
and Secretary for each programme. Regional elections are based on the 
student's declared residential area, covering Mile 3, Mile 4, Town, and 
Halls of Residence.

## Default Admin Setup

Run the create_admin.php script once after setup to generate the default 
administrator account. Remove or restrict access to this file after use.

## Notes

- Voting is time-restricted and must be enabled by an administrator
- Each student can only vote once per position
- The system uses PHP sessions for authentication
- Passwords are hashed using PHP's built-in password_hash function
