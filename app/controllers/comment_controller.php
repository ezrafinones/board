<?php
class CommentController extends AppController
{
    public function edit()
    {
        $comment = new Comment();
        $page = Param::get(Comment::PAGE_NEXT, Comment::PAGE_EDIT);
        $comment_id = Param::get('id');
        $comments = Comment::getCommentsByCommentId($comment_id);
        $error = false;

        switch ($page) {
            case Comment::PAGE_EDIT:
                break;
            case Comment::PAGE_EDIT_END:
                $comment->body = Param::get('body');
                try {
                    $comment->edit($comment_id);
                } catch (RecordNotFoundException $e) {
                        $page = Comment::PAGE_EDIT;
                        $error = true;
                }
                break;
            default:
                throw new NotFoundException("{$page} is not found");
                break;
        }
        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function delete()
    {
        $comment_id = Param::get('id');
        $comments = Comment::getCommentsByCommentId($comment_id);

        $this->set(get_defined_vars());
    }

    public function confirm_delete()
    {
        $comment_id = Param::get('id');
        $comments = Comment::getCommentsByCommentId($comment_id);

        try {
            Comment::delete($comment_id);
        } catch (ValidationException $e) {
            redirect(url('thread/view', array('thread_id' => $comments->thread_id)));
        }
        $this->set(get_defined_vars());
    }

    public function favorites()
    {
        $action = Param::get('action');
        $comment_id = Param::get('comment_id');
        $thread_id = Param::get('thread_id');
        $user_id = Session::get('id');

        if ($action == true) {
            Favorites::insertFavorites($user_id, $comment_id, $action);
        } else {
            Favorites::deleteFavorites($user_id, $comment_id, $action);
        }
        redirect(url('thread/view', array('thread_id' => $thread_id)));
    }

    public function top()
    {
        $favorites = Comment::getMostFavorites();
        $this->set(get_defined_vars());
    }
}
