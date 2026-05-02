<?php
class Post extends Model {
    protected static $table = 'posts';
    
    public static function getByCategory($category) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM posts WHERE category = ?");
        $stmt->execute([$category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getFacultyPosts($course) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM posts WHERE category = 'faculty' AND course_filter = ?");
        $stmt->execute([$course]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getRegionalPosts($location) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM posts WHERE category = 'regional' AND location_filter = ?");
        $stmt->execute([$location]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getGeneralPosts() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM posts WHERE category = 'general'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>