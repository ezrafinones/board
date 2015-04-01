<?php
class Comment extends AppModel
{
    const MIN_LENGTH = 1;

    const MAX_USERNAME_LENGTH = 30;
    const MAX_BODY_LENGTH = 254;

    public $validation = array(
        'body' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, self::MAX_BODY_LENGTH,
            ),
        ),
    );

    public static function getComments($offset, $limit, $id)
    {
        $comments = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM comment 
                        WHERE thread_id = ? ORDER BY created ASC LIMIT {$offset}, {$limit}", array($id));
        
        foreach ($rows as $row) {
            $favorites = array();
            $user_favorites = $db->rows("SELECT * FROM favorites WHERE comment_id = ?", array($row['id']));     
            $total_favorites = Comment::countFavorites($row['id']);     

            foreach ($user_favorites as $v) {
                $favorites[] = $v['user_id'];  
            }
            $row['favorites'] = $favorites; 
            $row['total_favorites'] = $total_favorites;
            $comments[] = new Comment($row);
        }
        return $comments;
    }

    public static function countComments($thread_id)
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM comment
                            WHERE thread_id = ?", array($thread_id));
    }

    public function editComment($comment_id)
    {
        $db = DB::conn();
        $params = array();
        $temp = array('body' => $this->body,

        );

        foreach ($temp as $k => $v) {
            if (!empty($v)) {
                $params[$k] = $v;
            }
        }
        if (!empty($params)) {
            try {
                $db = DB::conn();
                $db->begin();
                $db->query('UPDATE comment SET body = ?, created = NOW() 
                        WHERE id = ?', array($this->body, $comment_id));
                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }
        }
    }

    public static function getComment($comment_id)
    {
        $user = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM comment WHERE id = ?", array($comment_id));

        foreach($rows as $row) {
            $user[] = new self($row);
        }
        return $user;
    }

    public static function deleteComment($comment_id)
    {
        $db = DB::conn();
        try {
            $db->begin();
            $db->query('DELETE FROM comment WHERE id = ?', array($comment_id));
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function favorites($user_id, $comment_id, $action)
    {
        $db = DB::conn();
        try {
            $db->begin();
            if ($action === 'favorite') {
                $db->insert('favorites', array('user_id' => $user_id, 'comment_id' => $comment_id));
            } else {
                $db->query('DELETE FROM favorites WHERE user_id = ? AND comment_id = ?', array($user_id, $comment_id));
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function countFavorites($comment_id)
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM favorites WHERE comment_id = ?", array($comment_id));
    }
}
