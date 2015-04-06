<?php
class Thread extends AppModel
{
    const MIN_TITLE_LENGTH = 1;
    const MAX_TITLE_LENGTH = 30;

    const MAX_PAGE_SIZE = 5;

    public $validation = array(
       'title' => array(
            'length' => array(
               'validate_between', self::MIN_TITLE_LENGTH, self::MAX_TITLE_LENGTH,
            ),
        ),
    );

    public static function getAll($offset, $limit)
    {
        $threads = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM thread LIMIT {$offset}, {$limit}");

        foreach ($rows as $row) {
            $threads[] = new self($row);
        }
        return $threads;
    }

    public static function get($id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM thread WHERE id = ?', array($id));

        if (!$row) {
           throw new RecordNotFoundException('no record found');
        }
        return new self($row);
    }

    public function write(Comment $comment)
    {
        if (!$comment->validate()) {
           throw new ValidationException('invalid comment');
        }

        $db = DB::conn();
        $db->query('INSERT INTO comment SET thread_id = ?, username = ?, body = ?, user_id = ?',
                array($this->id, Session::get('username'), $comment->body, Session::get('id')));
    }

    public function create(Comment $comment)
    {
        $this->validate();
        $comment->validate();
        if ($this->hasError() || $comment->hasError()) {
           throw new ValidationException('invalid thread or comment');
        }

        $db = DB::conn();
        $db->begin();

        $params = array(
            'title' => $this->title,
            'user_id' => $this->user_id
        );

        $db->insert('thread', $params);
        $this->id = $db->lastInsertId();
        $this->write($comment);
        $db->commit();
    }

    public static function countAll()
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM thread");
    }

    public function editThread($thread_id)
    {
        $db = DB::conn();
        $params = array();
        $temp = array('title' => $this->title);

        foreach ($temp as $k => $v) {
            if (!empty($v)) {
                $params[$k] = $v;
            }
        }

        if (!empty($params)) {
            try {
                $db = DB::conn();
                $db->begin();
                $db->query('UPDATE thread SET title = ?, created = NOW()
                        WHERE id = ?', array($this->title, $thread_id));
                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }
        }
    }

    public static function getThread($thread_id)
    {
        $user = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM thread WHERE id = ?", array($thread_id));

        foreach ($rows as $row) {
            $user[] = new self($row);
        }
        return $user;
    }

    public static function deleteThread($thread_id)
    {
        $db = DB::conn();
        try {
            $db->begin();
            $db->query('DELETE FROM thread WHERE id = ?', array($thread_id));
            $db->query('DELETE FROM comment WHERE thread_id = ?', array($thread_id));
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function getMostFavorites()
    {
        $threads = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT id, comment_id, user_id, COUNT(comment_id) AS total_favorites 
                    FROM favorites GROUP BY comment_id ORDER BY total_favorites DESC");
        foreach ($rows as $row) {
            $thread_info = $db->row('SELECT * FROM thread WHERE id = ?', array($row['comment_id']));
            $thread_username = $db->row('SELECT * FROM comment WHERE id = ?', array($row['comment_id']));
            $threads[] = new self(array_merge($row, $thread_info, $thread_username));
        }
        return $threads;
    }
}
