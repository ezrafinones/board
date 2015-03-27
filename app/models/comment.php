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
}
