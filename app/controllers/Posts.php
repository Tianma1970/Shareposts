<?php

class Posts extends Controller {

    public function __construct(){
        if (!isLoggedIn()) { //if we are not logged in
            redirect('users/login');
        }

        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');

    }
    
    public function index(){
        //get Posts
        $posts = $this->postModel->getPosts();

        $data = [
            'posts' => $posts
        ];


        $this->view('posts/index', $data);
    }

    public function add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => ''
        ];

        //Validate data
        if(empty($data['title'])){
            $data['title_err'] = 'Please enter title';
        }
        if(empty($data['body'])){
            $data['body_err'] = 'Please enter body text';
        }

        //Make sure no errors
        if(empty($data['title_err']) && empty($data['body_err'])){
            //Validated
            if($this->postModel->addPost($data)){
                flash('post_message', 'Post added');
                redirect('posts');

            } else {
                die('Something went wrong');

            }

        } else {
        //load view with errors
        $this->view('posts/add', $data);

        }
            } else {
                $data = [
                'title' => '',
                'body' => ''
        ];
        
        $this->view('posts/add', $data);


            }
        }

        public function edit($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => ''
        ];

        //Validate data
        if(empty($data['title'])){
            $data['title_err'] = 'Please enter title';
        }
        if(empty($data['body'])){
            $data['body_err'] = 'Please enter body text';
        }

        //Make sure no errors
        if(empty($data['title_err']) && empty($data['body_err'])){
            //Validated
            if($this->postModel->updatePost($data)){
                flash('post_message', 'Post Updated');
                redirect('posts');

            } else {
                die('Something went wrong');

            }

        } else {
        //load view with errors
        $this->view('posts/edit', $data);

        }
            } else {
        //get existing post from model
                $post = $this->postModel->getPostById($id);
        //check for owner

        if($post->user_id != $_SESSION['user_id]']){
            redirect('posts');

        }
                $data = [
                'id' => $id,
                'title' => $post->title,
                'body' => $post->body
        ];
        
        $this->view('posts/edit', $data);


            }
        }
        
        public function show($id){
            $post = $this->postModel->getPostById($id);
            $user = $this->userModel->getUserById($post->user_id);
            $data = [
                    'post' => $post,
                    'user' => $user
            ];

            $this->view('posts/show', $data);
        }
} 