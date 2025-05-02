<?php
/**
 * Samlar innehåll från flera RSS 1.0 (RDF) feeds och visar dem som HTML.
 * Användaren kan lägga till eller ta bort feeds dynamiskt. Lagring sker i feeds.json.
*/

//Skapa feeds.json om den inte finns
$feedFile = "feeds.json";
if (!file_exists($feedFile)) {
    file_put_contents($feedFile, json_encode([
        "https://rss.slashdot.org/Slashdot/slashdot"
    ]));
}

//Ladda befintliga feeds
$feeds = json_decode(file_get_contents($feedFile), true);
if (!is_array($feeds)) {
    $feeds = [];
}


//Lägg till ny feed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newfeed'])) {
    $newFeed = trim($_POST['newfeed']);
    if (filter_var($newFeed, FILTER_VALIDATE_URL)) {
        if (!in_array($newFeed, $feeds)) {
            $feeds[] = $newFeed;
            file_put_contents($feedFile, json_encode($feeds));
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);

    exit;
}

//Ta bort feed
if (isset($_GET['remove'])) {
    $remove = $_GET['remove'];
    $feeds = array_filter($feeds, fn($url) => $url !== $remove);
    file_put_contents($feedFile, json_encode(array_values($feeds)));
    header("Location: " . $_SERVER['PHP_SELF']);

    exit;
}

$self = htmlspecialchars($_SERVER['PHP_SELF']);
//HTML börjar här
echo <<<EOD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RSS 1.0 Channel</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 2rem; background: #fefefe; color: #333; }
        h1 { color: #d6336c; }
        .feed { border: 1px solid #ccc; padding: 1rem; margin-bottom: 2rem; background: #fff; }
        .feed h2 { margin: 0; font-size: 1.2rem; color: #555; }
        .feed-item { margin-bottom: 1.5rem; }
        .remove { color: red; font-size: 0.9rem; margin-left: 1rem; }
    </style>
</head>
<body>
<h1>RSS 1.0 Channel</h1>

<form method="post" action="$self">
    <input type="url" name="newfeed" placeholder="Add RSS 1.0 feed URL" required size="50">
    <button type="submit">Add Feed</button>
</form>

EOD;

//Visa varje feed
foreach ($feeds as $feedUrl) {
    echo "<div class='feed'><h2>📡 $feedUrl <a class='remove' href='?remove=" . urlencode($feedUrl) . "'>(Remove)</a></h2>";

    $xml = @simplexml_load_file($feedUrl);
    if (!$xml) {
        echo "<p style='color: red;'>Failed to load feed.</p></div>";
        continue;
    }

    //RSS 1.0 RDF parsing
    foreach ($xml->item as $item) {
        $title = htmlspecialchars((string)$item->title);
        $link = htmlspecialchars((string)$item->link);
        $desc = htmlspecialchars((string)$item->description);

        echo <<<ITEM
        <div class="feed-item">
            <strong><a href="$link" target="_blank">$title</a></strong><br>
            <p>$desc</p>
        </div>
ITEM;
    }

    echo "</div>";
}

echo <<<EOD
</body>
</html>
EOD;
?>
