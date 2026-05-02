<?php
class ElectionSettings extends Model {
    protected static $table = 'election_settings';
    
    public static function getCurrent() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM election_settings WHERE is_active = TRUE ORDER BY id DESC LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function update($startTime, $endTime) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE election_settings SET start_time = ?, end_time = ? WHERE is_active = TRUE");
        return $stmt->execute([$startTime, $endTime]);
    }
}
?>