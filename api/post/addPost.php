<?php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers:
                                    Access-Control-Allow-Headers,
                                    Content-Type,
                                    Access-Control-Allow-Methods,
                                    Authorization,
                                    X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Post.php';

    $database   = new Database();
    $db         = $database->connect();

    $post       = new Post($db);

    ///Get the raw posted data and put it into json format
    $data       = json_decode(file_get_contents('php://input'));

    //assign the data thats in the $data object to the $post object / Post.php
    $post->title        = $data->title;
    $post->body         = $data->body;
    $post->author       = $data->author;
    $post->category_id  = $data->category_id;

    //create the post here - using hte method in the Post class
    if($post->addPost()) {
        echo json_encode(
            array('message' => 'Post Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Post Was Not Created')
        );
    }