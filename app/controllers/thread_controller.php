<?php
class ThreadController extends AppController
{
    public function index()
    {
        $page = Param::get('page', 1);
        $per_page = Thread::MAX_PAGE_SIZE;
        $pagination = new SimplePagination($page, $per_page);

        $threads = Thread::getAll($pagination->start_index - 1, $pagination->count + 1);
        $pagination->checkLastPage($threads);
        $total = Thread::countAll();
        $pages = ceil($total / $per_page);

        $this->set(get_defined_vars());
        if (!Session::get('username')) {
            redirect(url('user/login'));
        }
    }

    public function view()
    {
        $thread = Thread::get(Param::get('thread_id'));
        $thread_id = Param::get('thread_id');

        $per_page = Thread::MAX_PAGE_SIZE;
        $page = Param::get('page', 1);
        $pagination = new SimplePagination($page, $per_page);
        $comments = Comment::getComments($pagination->start_index - 1, $pagination->count + 1, $thread_id);
        $pagination->checkLastPage($comments);
        $total = Comment::countComments($thread_id);
        $pages = ceil($total / $per_page);
        $this->set(get_defined_vars());
    }   
    public function write()
    {
        $thread = Thread::get(Param::get('thread_id'));
        $comment = new Comment;
        $page = Param::get('page_next');
            
        switch ($page) {
            case 'write':
                break;
            case 'write_end':
                $comment->body = Param::get('body');
                try{
                    $thread->write($comment);
                } catch(ValidationException $e) {
                    $page = 'write';
                }
                break;
            default:
                throw new NotFoundException("{$page} is not found");
                break;
        }
        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function create()
    {
        $thread = new Thread;
        $comment = new Comment;
        $page = Param::get('page_next', 'create');
        $user_id = Session::get('id');

        switch ($page) {
            case 'create':
                break;
            case 'create_end':
                $thread->title = Param::get('title');
                $thread->user_id = $user_id;
                $comment->username = Param::get('username');
                $comment->body = Param::get('body');
                try {
                    $thread->create($comment);
                } catch (ValidationException $e) {
                    $page = 'create';
                }
                break;
            default:
                throw new NotFoundException("{$page} is not found");
                break;
        }
        $this->set(get_defined_vars());
        $this->render($page);
    }
    public function edit_thread()
    {
        $thread = new Thread;
        $page = Param::get('page_next', 'edit_thread');
        $thread_id = Param::get('id');
        $threads = Thread::getThread($thread_id);

        switch ($page) {
            case 'edit_thread':
                break;
            case 'write_thread':
                $thread->title = Param::get('title');
                try {
                    $thread->editThread($thread_id);
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

    public function delete_thread()
    {
        $thread_id = Param::get('id');
        $threads = Thread::getThread($thread_id);
        Thread::deleteThread($thread_id);
        $this->set(get_defined_vars());
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
}
