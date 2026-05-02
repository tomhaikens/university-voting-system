<?php
// create_admin.php - Run this file once to create admin account

$host = 'localhost';
$dbname = 'university_voting_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Hash the password '1234'
    $hashedPassword = password_hash('1234', PASSWORD_DEFAULT);
    
    echo "Generated hash for password '1234': " . $hashedPassword . "<br>";
    
    // Delete existing admin
    $stmt = $pdo->prepare("DELETE FROM users WHERE email = '2024bcs195@std.must.ac.ug'");
    $stmt->execute();
    
    // Insert new admin
    $sql = "INSERT INTO users (name, email, password, faculty, course, address_type, role) 
            VALUES ('System Administrator', '2024bcs195@std.must.ac.ug', :password, 'Computing and Informatics', 'BCS', 'Halls', 'admin')";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':password' => $hashedPassword]);
    
    echo "Admin user created successfully!<br>";
    echo "Email: 2024bcs195@std.must.ac.ug<br>";
    echo "Password: 1234<br>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>