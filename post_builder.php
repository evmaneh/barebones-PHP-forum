<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        form {
            
        }

        label {
            
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
        }

        textarea {
            resize: vertical;
        }

        button {
            padding: 12px 24px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: ;
        }
    </style>
</head>
<body>
    <h1>Create a New Post</h1>
    <form action="save_post.php" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="images">Images (optional):</label>
        <input type="file" id="images" name="images[]" multiple>

        <label for="topic">Select Topic:</label>
        <select id="topic" name="topic" required>
        <option value="top1">Topic 1</option>
        <option value="top2">Topic 2</option>
        <option value="top3">Topic 3</option>
        <option value="ann">Announcements</option>
        <option value="rules">Rules</option>
            <option value="misc">Misc</option>
        </select>

        <button type="submit">Save Post</button>
    </form>
</body>
</html>
