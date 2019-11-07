<?php
    class Post{
        //DB stuff
        private $conn;
        private $table = 'posts';

        //Post Properties - Initializing them
        public $id;
        public $category_id;
        public $title;
        public $body;
        public $author;
        public $created_at;

        //Creating a constructopr with the DB to run when a new post is made
        public function __construct($db){
            //Set the connection of this class to that DB
            $this->conn = $db;
        }

        //Get posts (read the posts in the table)
        public function read(){
            //Building the query for this get request
            $query = 'SELECT
                    c.name as category_name,
                    p.id,
                    p.category_id,
                    p.title,
                    p.body,
                    p.author,
                    p.created_at
                FROM
                    ' . $this->table . ' p
                LEFT JOIN
                    categories c ON p.category_id = c.id
                ORDER BY
                    p.created_at DESC';
                    // Categories C - is where we are dfining the c.field - the c is an alias - similar to the posts tabel
                    //which was created above as $table = 'posts'

            //Prepared statement - not executing it yet
            $stmt = $this->conn->prepare($query);

            //Execute the query
            $stmt->execute();

            return $stmt;
        }

        //Get single Blog Post
        public function readSingle() {
            //building the query
            $query = 'SELECT
                c.name as category_name,
                p.id,
                p.category_id,
                p.title,
                p.body,
                p.author,
                p.created_at
            FROM
                ' . $this->table . ' p
            LEFT JOIN
                categories c ON p.category_id = c.id
            WHERE
                p.id = ?
            LIMIT 0,1';

            //Prepare
            $stmt = $this->conn->prepare($query);

            //Bind the query to the ID which has the question mark in the query above
            //Positiona Parameter - below: the first parameter should bind to ID
            $stmt->bindParam(1, $this->id);

            //Execute the statement and query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            //set the properties to what is returned
            $this->title            = $row['title'];
            $this->body             = $row['body'];
            $this->author           = $row['author'];
            $this->category_id      = $row['category_id'];
            $this->category_name    = $row['category_name'];
        }

        public function addPost(){
            $query  = 'INSERT INTO ' . $this->table . '
            SET
                title = :title,
                body = :body,
                author = :author,
                category_id= :category_id';

            $stmt = $this->conn->prepare($query);

            //clean up the data
            //using htmlspecialchars -> dont want any html / html special characters
            //stripping the html tags
            $this->title        = htmlspecialchars(strip_tags($this->title));
            $this->body         = htmlspecialchars(strip_tags($this->body));
            $this->author       = htmlspecialchars(strip_tags($this->author));
            $this->category_id  = htmlspecialchars(strip_tags($this->category_id));  
            
            //bind the data thats set in the query
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':body', $this->body);
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':category_id', $this->category_id);
            
            //execute the query
            if($stmt->execute()) {
                return true;
            }

            //print error if something doesnt work / execute correctly
            printf('Error: %s.\n', $stmt->error);
        }
    }