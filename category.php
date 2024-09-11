<?php
// This page is for displaying specific posts in a catagory
session_start();

function pinPost($postTitle, $topic) {
    $pinnedPosts = file('data/pinned_posts.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $entry = "$topic/$postTitle";
    if (!in_array($entry, $pinnedPosts)) {
        file_put_contents('data/pinned_posts.txt', $entry . PHP_EOL, FILE_APPEND);
    }
}

function unpinPost($postTitle, $topic) {
    $pinnedPosts = file('data/pinned_posts.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $entry = "$topic/$postTitle";
    $pinnedPosts = array_filter($pinnedPosts, function($title) use ($entry) {
        return $title !== $entry;
    });
    file_put_contents('data/pinned_posts.txt', implode(PHP_EOL, $pinnedPosts) . PHP_EOL);
}

if (isset($_GET['pin']) && isset($_GET['topic'])) {
    $postTitleToPin = htmlspecialchars($_GET['pin']);
    $topicToPin = htmlspecialchars($_GET['topic']);
    pinPost($postTitleToPin, $topicToPin);
    header('Location: category.php?topic=' . urlencode($topicToPin));
    exit();
}

if (isset($_GET['unpin']) && isset($_GET['topic'])) {
    $postTitleToUnpin = htmlspecialchars($_GET['unpin']);
    $topicToUnpin = htmlspecialchars($_GET['topic']);
    unpinPost($postTitleToUnpin, $topicToUnpin);
    header('Location: category.php?topic=' . urlencode($topicToUnpin));
    exit();
}

$topic = isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : 'default';

if (empty($topic)) {
    die('Topic not specified.');
}

$categoryNames = [
    'top1' => 'Topic 1',
    'top2' => 'Topic 2',
    'top3' => 'Topic 3',
    'ann' => 'Announcements',
    'rules' => 'Rules',
    'misc' => 'Miscellaneous'
];

$displayTopic = isset($categoryNames[$topic]) ? $categoryNames[$topic] : ucfirst($topic);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $displayTopic; ?> Posts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
        }

        h2 {
        }

        a {
            text-decoration: none;
        }

        button {
            cursor: pointer;
            border-radius: 5px;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
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
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $displayTopic; ?> Posts</h1>
        <div>
            <?php if (isset($_SESSION['username'])): ?>
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                <br>
                <a href="logout.php"><button>Logout</button></a>
                <a href="post_builder.php"><button>New Post</button></a>
            <?php else: ?>
                Welcome, Guest!
                <br>
                <a href="login.php"><button>Login</button></a>
            <?php endif; ?>
        </div>
        <h2>Latest Posts in <?php echo $displayTopic; ?>:</h2>
        <div id="posts-container">
            <?php
            $postsWithTime = array();

            $posts = glob("posts/$topic/*.php");
            foreach ($posts as $post) {
                $postsWithTime[filemtime($post)] = $post;
            }

            krsort($postsWithTime);

            $pinnedPosts = file('data/pinned_posts.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $pinnedPosts = array_flip($pinnedPosts);

            foreach ($pinnedPosts as $pinnedEntry => $dummy) {
                $parts = explode('/', $pinnedEntry);
                if (count($parts) === 2 && $parts[0] === $topic) {
                    list($topic, $postTitle) = $parts;
                    $filename = "posts/$topic/" . $postTitle . ".php";
                    if (file_exists($filename)) {
                        $displayTitle = str_replace('_', ' ', $postTitle);
                        echo "<div class='post pinned'><a href='$filename'>" . htmlspecialchars($displayTitle) . "</a> <a href='category.php?unpin=" . urlencode($postTitle) . "&topic=" . urlencode($topic) . "'></div>";
                    }
                }
            }

            foreach ($postsWithTime as $postTime => $filename) {
                $postTitle = basename($filename, ".php");
                $displayTitle = str_replace('_', ' ', $postTitle);
                $entry = "$topic/$postTitle";
                if (!isset($pinnedPosts[$entry])) {
                    echo "<div class='post'><a href='$filename'>" . htmlspecialchars($displayTitle) . "</a> <a href='category.php?pin=" . urlencode($postTitle) . "&topic=" . urlencode($topic) . "'></div>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
