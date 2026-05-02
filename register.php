<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MUST Online Voting System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h2 style="text-align: center; color: #2e7d32;">🎓 MUST Online Voting System</h2>
        <h3 style="text-align: center; margin-bottom: 30px;">Student Registration</h3>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="index.php?page=register&action=register" onsubmit="return validateRegistration()">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="email" placeholder="2024bcs195@std.must.ac.ug" required>
                <small>Must end with @std.must.ac.ug</small>
            </div>
            
            <div class="form-group">
                <label>Faculty</label>
                <select name="faculty" id="faculty" required onchange="loadCourses()">
                    <option value="">Select Faculty</option>
                    <option value="Computing and Informatics">Computing and Informatics</option>
                    <option value="Engineering">Engineering</option>
                    <option value="Science">Science</option>
                    <option value="Business and Management Sciences">Business and Management Sciences</option>
                    <option value="Medicine">Medicine</option>
                    <option value="Agriculture">Agriculture</option>
                    <option value="Interdisciplinary Studies">Interdisciplinary Studies</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Course/Program</label>
                <select name="course" id="course" required>
                    <option value="">Select Course</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Residential Address</label>
                <select name="address" required>
                    <option value="">Select Address</option>
                    <option value="Mile 3">Mile 3</option>
                    <option value="Mile 4">Mile 4</option>
                    <option value="Town">Town</option>
                    <option value="Halls">Halls of Residence</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" required>
                    <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
                </div>
            </div>
            
            <div class="form-group">
                <label>Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" name="confirm_password" id="confirm_password" required>
                    <span class="toggle-password" onclick="togglePassword('confirm_password')">👁️</span>
                </div>
            </div>
            
            <button type="submit" class="btn">Register</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            Already have an account? <a href="index.php?page=login">Login here</a>
        </p>
    </div>
    
    <script>
        // Course data mapping
        const coursesByFaculty = {
            'Computing and Informatics': ['BCS', 'BSE', 'BIT', 'BIS', 'BCSIT', 'BSc. Data Science'],
            'Engineering': ['BSE (Civil)', 'BSE (Electrical)', 'BSE (Mechanical)', 'BSE (Telecom)', 'BSE (Mechatronics)', 'BSE (Petroleum)'],
            'Science': ['BSc. Biology', 'BSc. Chemistry', 'BSc. Physics', 'BSc. Mathematics', 'BSc. Statistics', 'BSc. Industrial Chemistry'],
            'Business and Management Sciences': ['BBA', 'BCOM', 'BPA', 'BHRM', 'BITAM', 'BSc. Economics'],
            'Medicine': ['MBChB', 'BDS', 'BPharm', 'BNS', 'BMLS', 'BRT', 'BPT', 'BOT'],
            'Agriculture': ['BSc. Agriculture', 'BSc. Agribusiness', 'BSc. Food Science', 'BSc. Nutrition', 'BSc. Horticulture'],
            'Interdisciplinary Studies': ['BDE', 'BES', 'BGS', 'BSWASA', 'BCD']
        };
        
        function loadCourses() {
            const faculty = document.getElementById('faculty').value;
            const courseSelect = document.getElementById('course');
            
            courseSelect.innerHTML = '<option value="">Select Course</option>';
            
            if (faculty && coursesByFaculty[faculty]) {
                coursesByFaculty[faculty].forEach(course => {
                    const option = document.createElement('option');
                    option.value = course;
                    option.textContent = course;
                    courseSelect.appendChild(option);
                });
            }
        }
        
        function validateRegistration() {
            const email = document.getElementById('email').value;
            const emailPattern = /^.+@std\.must\.ac\.ug$/;
            
            if (!emailPattern.test(email)) {
                alert('Email must be in format: *@std.must.ac.ug');
                return false;
            }
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password.length < 4) {
                alert('Password must be at least 4 characters');
                return false;
            }
            
            if (password !== confirmPassword) {
                alert('Passwords do not match');
                return false;
            }
            
            const faculty = document.getElementById('faculty').value;
            const course = document.getElementById('course').value;
            
            if (!faculty || !course) {
                alert('Please select both faculty and course');
                return false;
            }
            
            return true;
        }
        
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            field.type = field.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>