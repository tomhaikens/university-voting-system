<?php
class Candidate extends Model {
    protected static $table = 'candidates';
    
    public static function getByPost($postId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM candidates WHERE post_id = ? ORDER BY name");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function create($data) {
        $db = Database::getInstance()->getConnection();
        $sql = "INSERT INTO candidates (post_id, name, email, course, faculty, manifesto, image_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['post_id'], $data['name'], $data['email'], 
            $data['course'], $data['faculty'], $data['manifesto'], 
            isset($data['image_url']) ? $data['image_url'] : null
        ]);
    }
    
    // NEW METHOD: Update candidate
    public static function update($id, $data) {
        $db = Database::getInstance()->getConnection();
        
        // Build dynamic update query
        $fields = [];
        $params = [];
        
        if (isset($data['name'])) {
            $fields[] = "name = ?";
            $params[] = $data['name'];
        }
        if (isset($data['email'])) {
            $fields[] = "email = ?";
            $params[] = $data['email'];
        }
        if (isset($data['course'])) {
            $fields[] = "course = ?";
            $params[] = $data['course'];
        }
        if (isset($data['faculty'])) {
            $fields[] = "faculty = ?";
            $params[] = $data['faculty'];
        }
        if (isset($data['manifesto'])) {
            $fields[] = "manifesto = ?";
            $params[] = $data['manifesto'];
        }
        if (isset($data['image_url'])) {
            $fields[] = "image_url = ?";
            $params[] = $data['image_url'];
        }
        
        $params[] = $id;
        $sql = "UPDATE candidates SET " . implode(", ", $fields) . " WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }
    
    // NEW METHOD: Get single candidate with details
    public static function getWithDetails($id) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT c.*, p.name as post_name, p.category 
                FROM candidates c 
                LEFT JOIN posts p ON c.post_id = p.id 
                WHERE c.id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // NEW METHOD: Delete candidate image
    public static function deleteImage($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT image_url FROM candidates WHERE id = ?");
        $stmt->execute([$id]);
        $candidate = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($candidate && $candidate['image_url'] && file_exists($candidate['image_url'])) {
            unlink($candidate['image_url']);
        }
        
        $stmt = $db->prepare("UPDATE candidates SET image_url = NULL WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>