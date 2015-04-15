<?php
class Comment extends AppModel
{
    const MIN_LENGTH = 1;
    const MAX_BODY_LENGTH = 254;

    const PAGE_EDIT = 'edit';
    const PAGE_NEXT = 'page_next';
    const PAGE_EDIT_END = 'edit_end';

    public $validation = array(
        'body' => array(
            'length' => array(
                'validate_between', self::MIN_LENGTH, self::MAX_BODY_LENGTH,
            ),
        ),
    );

    public static function getFavoriteCommentsById($id, $limit, $offset)
    {
        $comments = array();
        $offset = (int)$offset;
        $limit = (int)$limit;

        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM comment
                        WHERE thread_id = ? ORDER BY created ASC LIMIT {$offset}, {$limit}", array($id));

        foreach ($rows as $row) {
            $favorites = array();
            $user_favorites = Favorites::getFavoritesByCommentId($row['id']);
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
        if (!$comment_id) {
            throw new RecordNotFoundException('Record Not Found');
        }

        if (!$this->validate()) {
            throw new ValidationException("Invalid comment");
        }

        try {
            $db = DB::conn();
            $db->query('UPDATE comment SET body = ?, updated = NOW() WHERE id = ?', array($this->body, $comment_id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function getById($comment_id)
    {
        $comments = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM comment WHERE id = ?", array($comment_id));

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
        }

        foreach($rows as $row) {
            $comments[] = new self($row);
        }
        return $comments;
    }

    public static function getByUsername($username)
    {
        $comments = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM comment WHERE username = ?", array($username));

        foreach ($rows as $row) {
            $comments[] = new self($row);
        }
        return $comments;
    }

    public static function getByUserId($user_id)
    {
        $comments = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM comment WHERE user_id = ?", array($user_id));

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
        }

        foreach ($rows as $row) {
            $comments[] = new self($row);
        }
        return $comments;
    }

    public static function delete($comment_id)
    {
        try {
            $db = DB::conn();
            $db->query('DELETE FROM comment WHERE id = ?', array($comment_id));
            Favorites::deleteByCommentId($comment_id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function deleteByThreadId($thread_id)
    {
        try {
            $db = DB::conn();
            $rows = $db->rows('SELECT * FROM comment WHERE thread_id = ?', array($thread_id));
            foreach ($rows as $comments) {
                self::delete($comments['id']);
            }      
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function getMostFavorites()
    {
        $comments = array();
        $rows = Favorites::getFavoritesOrderByCount();
        $db = DB::conn();

        foreach ($rows as $row) {
            $comment_info = $db->row('SELECT * FROM comment WHERE id = ?', array($row['comment_id']));
            $comments[] = new self(array_merge($row, $comment_info));
        }
        return $comments;
    }

    public static function write($thread_id, $comment, $username, $id)
    {
        if (!$comment->validate()) {
            throw new ValidationException("Invalid comment");
        }

        $db = DB::conn();
        $db->query('INSERT INTO comment SET thread_id = ?, username = ?, body = ?, user_id = ?',
                array($thread_id, $username, $comment->body, $id));
    }

    public static function getUsernameByThreadId($thread_id)
    {
        $db = DB::conn();
        $thread_username = $db->row('SELECT username FROM comment WHERE thread_id = ?', array($thread_id));
        return $thread_username;
    }

    public static function getCount()
    {
        $db = DB::conn();
        $rows = $db->rows("SELECT thread_id, COUNT(*) as comment_count FROM comment
                    GROUP BY thread_id ORDER BY comment_count DESC LIMIT 0, 10");
        return $rows;
    }
}
