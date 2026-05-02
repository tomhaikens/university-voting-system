<?php
class Vote extends Model {
    protected static $table = 'votes';
    
    public static function create($userId, $candidateId, $postId) {
        $db = Database::getInstance()->getConnection();
        $sql = "INSERT INTO votes (user_id, candidate_id, post_id) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$userId, $candidateId, $postId]);
    }
    
    public static function hasVoted($userId, $postId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM votes WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$userId, $postId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    
    public static function countByCandidate($candidateId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM votes WHERE candidate_id = ?");
        $stmt->execute([$candidateId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    
    public static function getResultsByPost($postId) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT c.id, c.name, c.image_url, COUNT(v.id) as votes 
                FROM candidates c 
                LEFT JOIN votes v ON c.id = v.candidate_id 
                WHERE c.post_id = ? 
                GROUP BY c.id 
                ORDER BY votes DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getUniqueVoters() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT COUNT(DISTINCT user_id) as count FROM votes");
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    
    public static function count() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT COUNT(*) as count FROM votes");
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    
    public static function getUserVotes($userId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT post_id, candidate_id FROM votes WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>