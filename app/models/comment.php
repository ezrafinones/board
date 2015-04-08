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

    public static function getCommentsById($offset, $limit, $id)
    {
        $comments = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM comment 
                        WHERE thread_id = ? ORDER BY created ASC LIMIT {$offset}, {$limit}", array($id));

        foreach ($rows as $row) {
            $favorites = array();
            $user_favorites = $db->rows("SELECT * FROM favorites WHERE comment_id = ?", array($row['id']));
            $total_favorites = Favorites::getCountByCommentId($row['id']);

            foreach ($user_favorites as $v) {
                $favorites[] = $v['user_id'];
            }
            $row['favorites'] = $favorites; 
            $row['total_favorites'] = $total_favorites;
            $comments[] = new Comment($row);
        }  
        return $comments;
    }

    public static function count($thread_id)
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM comment WHERE thread_id = ?", array($thread_id));
    }

    public function edit($comment_id)
    {
        if (!$this->body) {
            throw new RecordNotFoundException('Record Not Found');
        }
        
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

    public static function getCommentsByCommentId($comment_id)
    {
        $comments = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM comment WHERE id = ?", array($comment_id));

        foreach($rows as $row) {
            $comments[] = new self($row);
        }

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
        }
        return $comments;
    }

    public static function getCommentsByUsername()
    {
        $comments = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM comment WHERE username = ?", array(Session::get('username')));

        foreach ($rows as $row) {
            $comments[] = new self($row);
        }

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
        }
        return $comments;
    }

    public static function getCommentsByUserId($user_id)
    {
        $comments = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM comment WHERE user_id = ?", array($user_id));

        foreach ($rows as $row) {
            $comments[] = new self($row);
        }

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
        }
        return $comments;
    }

    public static function delete($comment_id)
    {
        $db = DB::conn();
        try {
            $db->query('DELETE FROM comment WHERE id = ?', array($comment_id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function getMostFavorites()
    {
        $comments = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT id, comment_id, user_id, COUNT(comment_id) AS total_favorites
                    FROM favorites GROUP BY comment_id ORDER BY total_favorites DESC");
        foreach ($rows as $row) {
            $comment_info = $db->row('SELECT * FROM comment WHERE id = ?', array($row['comment_id']));
            $comments[] = new self(array_merge($row, $comment_info));
        }
        return $comments;
    }
}
