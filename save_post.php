<?php
session_start();
ob_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit();
    }

    $username = htmlspecialchars($_SESSION['username']);
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $topic = htmlspecialchars($_POST['topic']);
    $images = [];

    if (isset($_FILES['images'])) {
        foreach ($_FILES['images']['error'] as $key => $error) {
            if ($error == 0) {
                $imagePath = 'posts/uploads/' . basename($_FILES['images']['name'][$key]);
                move_uploaded_file($_FILES['images']['tmp_name'][$key], $imagePath);
                $images[] = 'uploads/' . basename($_FILES['images']['name'][$key]);
            }
        }
    }

    $postFileName = 'posts/' . $topic . '/' . preg_replace('/\s+/', '_', strtolower($title)) . '.php';
    $postContent = <<<PHP
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>$title</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
    
            h1 {
                margin: 0;
            }
    
            p {
                margin: 20px;
            }
    
            .username {
                font-size: 0.8em;
                color: #555;
            }
    
            #posts-container {
                margin: 20px;
                padding: 10px;
            }
            
            #images {
                border: 5px solid;
                padding: 10px;
                display: flex;
                gap: 10px;
                overflow-x: auto;
                overflow-y: hidden;
                white-space: nowrap;
            }
    
            #images img {
                max-width: 150px;
                max-height: 150px;
                border-radius: 8px;
                transition: transform 0.3s ease;
                display: inline-block;
            }
        </style>
    </head>
    <body>
        <h1>$title</h1>
        <p class="username">$username's post</p>
        <p>$description</p>
        <div id="images">
    PHP;
    
        foreach ($images as $image) {
            $postContent .= "<img src='$image' alt='$title' />";
        }
    
        $postContent .= <<<PHP
        </div>
    </body>
    </html>
PHP;

    if (!is_dir('posts/' . $topic)) {
        mkdir('posts/' . $topic, 0777, true);
    }

    file_put_contents($postFileName, $postContent);

    header('Location: home.php');
    exit();
}

ob_end_flush();
?>
