<?php
class User extends Model {
    protected static $table = 'users';
    
    // Valid faculties and courses at MUST
    public static $faculties = [
        'Computing and Informatics' => [
            'BCS', 'BSE', 'BIT', 'BIS', 'BCSIT', 'BSc. Data Science'
        ],
        'Engineering' => [
            'BSE (Civil)', 'BSE (Electrical)', 'BSE (Mechanical)', 
            'BSE (Telecom)', 'BSE (Mechatronics)', 'BSE (Petroleum)'
        ],
        'Science' => [
            'BSc. Biology', 'BSc. Chemistry', 'BSc. Physics', 
            'BSc. Mathematics', 'BSc. Statistics', 'BSc. Industrial Chemistry'
        ],
        'Business and Management Sciences' => [
            'BBA', 'BCOM', 'BPA', 'BHRM', 'BITAM', 'BSc. Economics'
        ],
        'Medicine' => [
            'MBChB', 'BDS', 'BPharm', 'BNS', 'BMLS', 'BRT', 'BPT', 'BOT'
        ],
        'Agriculture' => [
            'BSc. Agriculture', 'BSc. Agribusiness', 'BSc. Food Science', 
            'BSc. Nutrition', 'BSc. Horticulture'
        ],
        'Interdisciplinary Studies' => [
            'BDE', 'BES', 'BGS', 'BSWASA', 'BCD'
        ]
    ];
    
    public static function findByEmail($email) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function create($data) {
        $db = Database::getInstance()->getConnection();
        
        // Validate faculty and course
        if (!self::isValidCourse($data['faculty'], $data['course'])) {
            return false;
        }
        
        $sql = "INSERT INTO users (name, email, password, faculty, course, address_type) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['name'], $data['email'], $data['password'],
            $data['faculty'], $data['course'], $data['address_type']
        ]);
    }
    
    public static function isValidCourse($faculty, $course) {
        if (!isset(self::$faculties[$faculty])) {
            return false;
        }
        return in_array($course, self::$faculties[$faculty]);
    }
    
    public static function getAllFaculties() {
        return array_keys(self::$faculties);
    }
    
    public static function getCoursesByFaculty($faculty) {
        return isset(self::$faculties[$faculty]) ? self::$faculties[$faculty] : [];
    }
    
    public static function getAllUsers() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function updateRole($userId, $role) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE users SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $userId]);
    }
    
    public static function updateBlockStatus($userId, $isBlocked) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE users SET is_blocked = ? WHERE id = ?");
        return $stmt->execute([$isBlocked, $userId]);
    }
    
    public static function updateVotingStatus($userId, $field) {
        $db = Database::getInstance()->getConnection();
        $sql = "UPDATE users SET $field = TRUE WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$userId]);
    }
    
    public static function getVotingStats() {
        $db = Database::getInstance()->getConnection();
        
        $stats = [];
        
        // Total users
        $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
        $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Users who voted in general
        $stmt = $db->query("SELECT COUNT(*) as voted FROM users WHERE has_voted_general = TRUE");
        $stats['general_voters'] = $stmt->fetch(PDO::FETCH_ASSOC)['voted'];
        
        // Faculty wise voting
        $stmt = $db->query("
            SELECT faculty, COUNT(*) as total_students, 
                   SUM(CASE WHEN has_voted_faculty = TRUE THEN 1 ELSE 0 END) as voted 
            FROM users 
            WHERE role = 'user'
            GROUP BY faculty
        ");
        $stats['faculty_voting'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Course wise voting
        $stmt = $db->query("
            SELECT course, faculty, COUNT(*) as total_students,
                   SUM(CASE WHEN has_voted_faculty = TRUE THEN 1 ELSE 0 END) as voted
            FROM users 
            WHERE role = 'user'
            GROUP BY course, faculty
            ORDER BY faculty, course
        ");
        $stats['course_voting'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Regional voting
        $stmt = $db->query("
            SELECT address_type, COUNT(*) as total_students,
                   SUM(CASE WHEN has_voted_regional = TRUE THEN 1 ELSE 0 END) as voted 
            FROM users 
            WHERE role = 'user'
            GROUP BY address_type
        ");
        $stats['regional_voting'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }
    
    public static function count() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'user'");
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    
    public static function findById($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>