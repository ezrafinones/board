<?php
class ThreadController extends AppController
{
    public function index()
    {
        $min_page = Thread::MIN_PAGE_SIZE;
        $page = Param::get('page', $min_page);
        $per_page = Thread::MAX_PAGE_SIZE;
        $pagination = new SimplePagination($page, $per_page);

        $threads = Thread::getAll($pagination->start_index - $min_page, $pagination->count + $min_page);
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
        $min_page = Thread::MIN_PAGE_SIZE;
        $thread = Thread::get(Param::get('thread_id'));
        $thread_id = Param::get('thread_id');

        $per_page = Thread::MAX_PAGE_SIZE;
        $page = Param::get('page', $min_page);
        $pagination = new SimplePagination($page, $per_page);
        $comments = Comment::getCommentsById($thread_id, $pagination->count + $min_page, $pagination->start_index - $min_page);
        $pagination->checkLastPage($comments);
        $total = Comment::count($thread_id);
        $pages = ceil($total / $per_page);
        $this->set(get_defined_vars());
    }

    public function write()
    {
        $thread = Thread::get(Param::get('thread_id'));
        $comment = new Comment();
        $page = Param::get(Thread::PAGE_NEXT);
            
        switch ($page) {
            case Thread::PAGE_WRITE:
                break;
            case Thread::PAGE_WRITE_END:
                $comment->body = Param::get('body');
                if (!$comment->validate()) {
                    $page = Thread::PAGE_WRITE;  
                } else {
                    $thread->write($comment, Session::get('username'), Session::get('id'));
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
        $thread = new Thread();
        $comment = new Comment();
        $page = Param::get(Thread::PAGE_NEXT, Thread::PAGE_CREATE);
        $user_id = Session::get('id');

        switch ($page) {
            case Thread::PAGE_CREATE:
                break;
            case Thread::PAGE_CREATE_END:
                $thread->title = Param::get('title');
                $thread->user_id = $user_id;
                $comment->username = Param::get('username');
                $comment->body = Param::get('body');

                $thread->validate();
                $comment->validate();
                if ($thread->hasError() || $comment->hasError()) {
                    $page = Thread::PAGE_CREATE;  
                } else {
                    $thread->create($comment, Session::get('username'), Session::get('id'));
                }
                break;
            default:
                throw new NotFoundException("{$page} is not found");
                break;
        }
        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function edit()
    {
        $thread = new Thread();
        $page = Param::get(Thread::PAGE_NEXT, Thread::PAGE_EDIT);
        $thread_id = Param::get('id');
        $threads = Thread::getThread($thread_id);
        $error = false;

        switch ($page) {
            case Thread::PAGE_EDIT:
                break;
            case Thread::PAGE_WRITE_THREAD:
                $thread->title = Param::get('title');
                try {
                    $thread->edit($thread_id);
                } catch (RecordNotFoundException $e) {
                        $page = Thread::PAGE_EDIT;
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
        $thread_id = Param::get('id');
        $threads = Thread::getThread($thread_id);

        $this->set(get_defined_vars());
    }

    public function confirm_delete()
    {
        $thread_id = Param::get('id');
        $threads = Thread::getThread($thread_id);

        try {
            Thread::delete($thread_id);
        } catch (ValidationException $e) {
            redirect(url('thread/index', array('thread_id' => $thread_id)));
        }
        $this->set(get_defined_vars());
    }

    public function top()
    {
        $favorites = Thread::getMostFavorites();
        $this->set(get_defined_vars());
    }
}
