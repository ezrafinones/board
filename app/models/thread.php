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

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
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

    public function write($comment, $username, $id)
    {
        if (!$comment->validate()) {
           throw new ValidationException('invalid comment');
        }

        $db = DB::conn();
        $db->query('INSERT INTO comment SET thread_id = ?, username = ?, body = ?, user_id = ?',
                array($this->id, $username, $comment->body, $id));
    }

    public function create($comment, $username, $id)
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
        $this->write($comment, $username, $id);
        $db->commit();
    }

    public static function countAll()
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM thread");
    }

    public function edit($thread_id)
    {
        if (!$this->title) {
            throw new RecordNotFoundException('Record Not Found');
        }

        try {
            $db = DB::conn();
            $db->begin();
            $db->query('UPDATE thread SET title = ?, WHERE id = ?', array($this->title, $thread_id));
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
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

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
        }
        return $user;
    }

    public static function delete($thread_id)
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

        $rows = $db->rows("SELECT thread_id, COUNT(*) as comment_count FROM comment 
                    GROUP BY thread_id ORDER BY comment_count DESC LIMIT 0, 10");
        foreach ($rows as $row) {
            $thread_info = $db->row('SELECT * FROM thread WHERE id = ?', array($row['thread_id']));
            $thread_username = $db->row('SELECT username FROM comment WHERE id = ?', array($row['thread_id']));
            $threads[] = new self(array_merge($row, $thread_info, $thread_username));
        }

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
        }
        return $threads;
    }
}
