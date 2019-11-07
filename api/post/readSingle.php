<?php

    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Post.php';

    //Instantiate DB & connect
    $database   = new Database();
    $db         = $database->connect();

    //Instantiate the Blog POST Object / Post.php Class
    //Because we created a constructor the POST.php object requires that constructors parameters - i.e $db
    $post       = new Post($db);

    //Get the ID form the URL
    //$_GET superglobal to get a something from the url // turnery operator is like an if statement (?)
    $post->id   = isset($_GET['id']) ? $_GET['id'] : die();
    //so IF there is an ID in the URL THEN or ? (in this case) get the ID. ELSE or : (in this case) die/stop

    //call the read single method
    $post->readSingle();

    //create an array to produce the result as JSON format
    $post_arr   = array(
        'id'            => $post->id,
        'title'         => $post->title,
        'body'          => $post->body,
        'author'        => $post->author,
        'category_id'   => $post->category_id,
        'category_name' => $post->category_name
    );

    //Make the result into JSON format
    //print_r is short for print array
    print_r(json_encode($post_arr));