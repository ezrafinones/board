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
        try {
            $db->query('DELETE FROM favorites WHERE user_id = ? AND comment_id = ?', array($user_id, $comment_id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function getCountByCommentId($comment_id)
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM favorites WHERE comment_id = ?", array($comment_id));
    }

    public static function deleteByCommentId($comment_id) 
    {
        $db = DB::conn();
        try {
            $db->query('DELETE FROM favorites WHERE comment_id = ?', array($comment_id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function getFavoritesByCommentId($id)
    {
        $db = DB::conn();
        $user_favorites = $db->rows("SELECT * FROM favorites WHERE comment_id = ?", array($id));
        return $user_favorites;
    }

    public static function getFavoritesOrderByCount()
    {
        $db = DB::conn();
        $rows = $db->rows("SELECT id, comment_id, user_id, COUNT(comment_id) AS total_favorites
                    FROM favorites GROUP BY comment_id ORDER BY total_favorites DESC");
        return $rows;
    }
}