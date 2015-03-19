<?php
class Thread extends AppModel
{
    const MIN_TITLE_LENGTH = 1;
    const MAX_TITLE_LENGTH = 30;

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

        foreach($rows as $row) {
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

    public static function getComments($offset, $limit, $id)
    {
        $comments = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM comment 
                        WHERE thread_id = ? ORDER BY created ASC LIMIT {$offset}, {$limit}", array($id));
       
        foreach ($rows as $row) {
           $comments[] = new Comment($row);
        }        
        return $comments;
    }

    public function write(Comment $comment)
    {
        if (!$comment->validate()) {
           throw new ValidationException('invalid comment');
        }

        $db = DB::conn();
        $db->query('INSERT INTO comment 
                SET thread_id = ?, username = ?, body = ?, created = NOW()',
                array($this->id, $_SESSION['username'], $comment->body));
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

    public static function countComments($thread_id)
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM comment
                            WHERE thread_id = ?", array($thread_id));
    }
}
