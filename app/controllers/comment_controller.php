<?php
class CommentController extends AppController
{
    public function edit_comment()
    {
        $comment = new Comment;
        $page = Param::get('page_next', 'edit_comment');
        $comment_id = Param::get('id');
        $comments = Comment::getComment($comment_id);

        switch ($page) {
            case 'edit_comment':
                break;
            case 'write_comment':
                $comment->body = Param::get('body');
                try {
                    $comment->editComment($comment_id);
                } catch (ValidationException $e) {
                        $page = 'settings';
                }
                break;
            default:
                throw new NotFoundException("{$page} is not found");
                break;
        }
        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function delete_comment()
    {
        $comment_id = Param::get('id');
        $comments = Comment::getComment($comment_id);
        Comment::deleteComment($comment_id);
        $this->set(get_defined_vars());
    }

    public function favorites()
    {
        $comment = new Comment;
        $action = Param::get('action');
        $comment_id = Param::get('comment_id');
        $thread_id = Param::get('thread_id');
        $user_id = Session::get('id');

        $comment->favorites($user_id, $comment_id, $action);
        redirect(url('thread/view', array('thread_id' => $thread_id)));
    }

    public function top_comments()
    {
        $favorites = Comment::getMostFavorites();
        $this->set(get_defined_vars());
    }
}
