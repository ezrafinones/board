<?php
class Thread extends AppModel
{
    const MIN_TITLE_LENGTH = 1;
    const MAX_TITLE_LENGTH = 30;
    const MAX_PAGE_SIZE = 5;
    const MIN_PAGE_SIZE = 1;

    const PAGE_WRITE = 'write';
    const PAGE_WRITE_END= 'write_end';
    const PAGE_CREATE = 'create';
    const PAGE_CREATE_END = 'create_end';
    const PAGE_EDIT = 'edit';
    const PAGE_WRITE_THREAD = 'write_thread';
    const PAGE_NEXT = 'page_next';

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
        $offset = (int)$offset;
        $limit = (int)$limit;

        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM thread LIMIT {$offset}, {$limit}");

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
        }

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

    public function create($comment, $username, $id)
    {
        $params = array(
            'title' => $this->title,
            'user_id' => $this->user_id
        );

        $this->validate();
        $comment->validate();
        if ($this->hasError() || $comment->hasError()) {
            throw new ValidationException("Invalid thread or comment");
        }

        try {
            $db = DB::conn();
            $db->begin();
            $db->insert('thread', $params);

            $this->id = $db->lastInsertId(); 
            Comment::write($this->id, $comment, $username, $id);
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function countAll()
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM thread");
    }

    public function edit($thread_id)
    {
        if (!$thread_id) {
            throw new RecordNotFoundException('Record Not Found');
        }

        if (!$this->validate()) {
            throw new ValidationException("Invalid thread");
        }

        try {
            $db = DB::conn();
            $db->query('UPDATE thread SET title = ?, updated = NOW() 
                WHERE id = ?', array($this->title, $thread_id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function getById($thread_id)
    {
        $user = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM thread WHERE id = ?", array($thread_id));

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
        }

        foreach ($rows as $row) {
            $user[] = new self($row);
        }
        return $user;
    }

    public static function deleteById($thread_id)
    {
        try {
            $db = DB::conn();
            $db->query('DELETE FROM thread WHERE id = ?', array($thread_id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function getMostFavorites()
    {
        $threads = array();
        $rows = Comment::getCount();
        $db = DB::conn();

        if (!$rows) {
            throw new RecordNotFoundException('No Record found');
        }

        foreach ($rows as $row) {
            $thread_info = $db->row('SELECT * FROM thread WHERE id = ?', array($row['thread_id']));
            $username = Comment::getUsernameByThreadId($row['thread_id']);
            $threads[] = new self(array_merge($row, $thread_info, $username));
        }
        return $threads;
    }
}
