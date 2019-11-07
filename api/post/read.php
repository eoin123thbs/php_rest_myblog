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

    //Call the read function thats inside the Post.php class
    $result = $post->read();
    //get row count
    $num = $result->rowCount();

    //Check if any posts are in the db
    if($num > 0){
        //post the array of available posts
        $posts_arr = array();

        //when request is made, we're not just displaying a jason array of the db data - 
        //to display more if we wanna display pagination information or patch version things
        $posts_arr['data'] = array();//this is where the data is going to go

        //loop through the result
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            //using extract instead of using $row['title] to get the field/values
            extract($row);

            //because of extract we can use $title explicitly 
            $post_item  = array(
                'id'            => $id,
                'title'         => $title,
                'body'          => html_entity_decode($body),
                'author'        => $author,
                'category_id'   => $category_id,
                'category_name' => $category_name
            );

            //pushing this item to the data array created above
            array_push($posts_arr['data'], $post_item);
        }

        //Turn it to JSON & OUTPUT
        echo json_encode($posts_arr);

    } else {
        echo json_encode(
            array('message' => 'No posts found')
        );
    }