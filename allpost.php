<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Posts</title>
<style>
    body {
        font-family: Arial, sans-serif;
    }

    button {
        
    }

    button:hover {
        background-color: ;
    }

    #posts-container {
        margin: 20px;
        padding: 10px;
        background-color: #18191f;
    }

    .post {
        
    }

    .post a {
        text-decoration: none;
    }

    .post a:hover {
        text-decoration: underline;
    }

    .pinned {
        background-color: ;
    }

    .container {
    }
</style>
</head>
<body>
<div class="container">
    <h1>All Posts</h1>
    <div>
            <a href="post_builder.php"><button>New Post</button></a>
            <br>
    </div>
    <div id="posts-container">
        <?php
        // Replace with your catagories
        $topics = ['top1', 'top2', 'top3', 'ann', 'rules', 'misc'];
        $postsWithTime = array();

        foreach ($topics as $topic) {
            $posts = glob("posts/$topic/*.php");
            foreach ($posts as $post) {
                $postsWithTime[filemtime($post)] = ['file' => $post, 'topic' => $topic];
            }
        }

        krsort($postsWithTime);

        $pinnedPosts = file('data/pinned_posts.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $pinnedPosts = array_flip($pinnedPosts);

        foreach ($pinnedPosts as $pinnedEntry => $dummy) {
            $parts = explode('/', $pinnedEntry);
            if (count($parts) === 2) {
                list($topic, $postTitle) = $parts;
                $filename = "posts/$topic/" . $postTitle . ".php";
                if (file_exists($filename)) {
                    $displayTitle = str_replace('_', ' ', $postTitle);
                    echo "<div class='post pinned'><a href='$filename'>" . htmlspecialchars($displayTitle) . "</a> <a href='home.php?unpin=" . urlencode($postTitle) . "&topic=" . urlencode($topic) . "'><button>Unpin</button></a></div>";
                }
            }
        }

        foreach ($postsWithTime as $postTime => $postInfo) {
            $filename = $postInfo['file'];
            $topic = $postInfo['topic'];
            $postTitle = basename($filename, ".php");
            $displayTitle = str_replace('_', ' ', $postTitle);
            $entry = "$topic/$postTitle";
            if (!isset($pinnedPosts[$entry])) {
                echo "<div class='post'><a href='$filename'>" . htmlspecialchars($displayTitle) . "</a> <a href='home.php?pin=" . urlencode($postTitle) . "&topic=" . urlencode($topic) . "'><button>Pin</button></a></div>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
