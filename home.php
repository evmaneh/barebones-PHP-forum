<?php
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
    header('Location: home.php');
    exit();
}

if (isset($_GET['unpin']) && isset($_GET['topic'])) {
    $postTitleToUnpin = htmlspecialchars($_GET['unpin']);
    $topicToUnpin = htmlspecialchars($_GET['topic']);
    unpinPost($postTitleToUnpin, $topicToUnpin);
    header('Location: home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
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
        background-color: #5e3a3e;
    }

    #posts-container {
        margin: 20px;
        padding: 10px;
        border-radius: 5px;
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
    }

    #categories-container {
    }

    .category {
        border-radius: 5px;
    }

    .category a {
        text-decoration: none;
    }

    .category a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
<div class="container">
    <h1>Title</h1>
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
    <h2>Latest Posts:</h2>
    <div id="posts-container">
        <?php
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

        $postCounter = 0;
        foreach ($postsWithTime as $postTime => $postInfo) {
            if ($postCounter >= 5) {
                break;
            }
            $filename = $postInfo['file'];
            $topic = $postInfo['topic'];
            $postTitle = basename($filename, ".php");
            $displayTitle = str_replace('_', ' ', $postTitle);
            $entry = "$topic/$postTitle";
            if (!isset($pinnedPosts[$entry])) {
                echo "<div class='post'><a href='$filename'>" . htmlspecialchars($displayTitle) . "</a> <a href='home.php?pin=" . urlencode($postTitle)
                . "&topic=" . urlencode($topic) . "'><button>Pin</button></a></div>";
                $postCounter++;
            }
        }
        ?>
    </div>

    <h3>Categories</h3>
    <div id="categories-container">
        <?php
        // Customize category display names
        $categoryNames = [
            'murd' => 'Murder',
            'tort' => 'Torture',
            'acci' => 'Accidents',
            'sh' => 'SelfHarm',
            'suic' => 'Suicide',
            'misc' => 'Miscellaneous'
        ];

        foreach ($topics as $topic) {
            if (isset($categoryNames[$topic])) {
                echo "<div class='category'><a href='category.php?topic=" . urlencode($topic) . "'>" . htmlspecialchars($categoryNames[$topic]) . "</a></div>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
