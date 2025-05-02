<?php
/**
 * Ett enkelt publiceringssystem (WIKI-blogg) där en användare kan skapa/redigera inlägg i ett minimalt WIKI-språk.
 * Text lagras i .txt-filer i en "posts"-mapp. WIKI-text omvandlas till HTML med parse_wiki().
*/

//Skapa mappen om den inte finns
if (!file_exists("posts")) {
    mkdir("posts", 0777, true);
}

//WIKI-format
function parse_wiki($text) {
    $text = preg_replace('/== (.*?) ==/', '<h2>$1</h2>', $text);
    $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/__(.*?)__/', '<em>$1</em>', $text);
    $text = preg_replace('/\[\[img:(.*?)\]\]/', '<img src="$1">', $text);
    $text = preg_replace('/\[\[link:(.*?)\|(.*?)\]\]/', '<a href="$1">$2</a>', $text);
    return nl2br($text);
}

//Spara inlägg
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    //Filnamnskontroller
    $safeTitle = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $title);

    file_put_contents("posts/$safeTitle.txt", $content);
    header("Location: wiki.php?view=$safeTitle");
    exit;
}

//Redigera formulär
if (isset($_GET['edit'])) {
    $title = $_GET['edit'];
    $safeTitle = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $title);
    $content = file_exists("posts/$safeTitle.txt") ? file_get_contents("posts/$safeTitle.txt") : "";

    $output = <<<EOD
    <h1>Edit '$safeTitle'</h1>
    <form method="post">
        <input type="text" name="title" value="$safeTitle" required><br><br>
        <textarea name="content" rows="15" cols="80">$content</textarea><br><br>
        <button type="submit">Save</button>
    </form>
    <p><a href="wiki.php">← Back to blog</a></p>
    EOD;

    echo $output;
    exit;
}


//Visa ett inlägg
if (isset($_GET['view'])) {
    $title = $_GET['view'];
    $safeTitle = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $title);
    $filepath = "posts/$safeTitle.txt";

    if (file_exists($filepath)) {
        $content = file_get_contents($filepath);
        $parsed = parse_wiki($content);

        $output = <<<EOD
        <h1>$safeTitle</h1>
        <div>$parsed</div>
        <p><a href="?edit=$safeTitle">Edit this post</a></p>
        <p><a href="wiki.php">← Back to blog</a></p>
        EOD;
    } else {
        $output = <<<EOD
        <h1>$safeTitle</h1>
        <p style='color: red;'>Post not found.</p>
        <p><a href="?edit=$safeTitle">Edit this post</a></p>
        <p><a href="wiki.php">← Back to blog</a></p>
        EOD;
    }

    echo $output;
    exit;
}


//Visa alla inlägg
$files = glob("posts/*.txt");
$list = "<h1>My WIKI Blog</h1>";
foreach ($files as $file) {
    $title = basename($file, ".txt");
    $list .= "<p><a href='?view=$title'>$title</a> | <a href='?edit=$title'>Edit</a></p>";
}
$list .= <<<EOD
<p><a href='?edit=NewPost'>Create New Post</a></p>
<p><a href='rss.php'>View RSS Feed</a></p>
EOD;

echo $list;
?>
