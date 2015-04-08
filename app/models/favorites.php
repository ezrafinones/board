<?php
class Favorites extends AppModel
{
    public static function vote($user_id, $comment_id, $action)
    {
        $db = DB::conn();
        try {
            $db->begin();
            if ($action === 'favorite') {
                self::insertFavorites($user_id, $comment_id);
            } else {
                self::deleteFavorites($user_id, $comment_id);
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function insertFavorites($user_id, $comment_id) 
    {
        $db = DB::conn();
        $db->insert('favorites', array('user_id' => $user_id, 'comment_id' => $comment_id));
    }

    public static function deleteFavorites($user_id, $comment_id) 
    {
        $db = DB::conn();
        $db->query('DELETE FROM favorites WHERE user_id = ? AND comment_id = ?', array($user_id, $comment_id));
    }

    public static function countFavorites($comment_id)
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM favorites WHERE comment_id = ?", array($comment_id));
    }
}