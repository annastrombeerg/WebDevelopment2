<?php
/**
 * Genererar ett RSS 1.0-flöde (RDF Site Summary) från min WIKI-blogg.
*/

header("Content-Type: application/rss+xml; charset=UTF-8");

//Funktion för att ta bort WIKI-markeringar
function strip_wiki($text) {
    $text = preg_replace('/== (.*?) ==/', '$1', $text);
    $text = preg_replace('/\*\*(.*?)\*\*/', '$1', $text);
    $text = preg_replace('/__(.*?)__/', '$1', $text);
    $text = preg_replace('/\[\[img:(.*?)\]\]/', '', $text);
    $text = preg_replace('/\[\[link:(.*?)\|(.*?)\]\]/', '$2 ($1)', $text);
    return htmlspecialchars($text);
}

//Grunden för RSS 1.0
echo <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<rdf:RDF
  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns="http://purl.org/rss/1.0/"
>
  <channel rdf:about="http://localhost/wiki.php">
    <title>Min WIKI-blogg</title>
    <link>http://localhost/wiki.php</link>
    <description>Senaste inlägget från min WIKI-blogg</description>
    <items>
      <rdf:Seq>
EOD;

//Lista med inlägg
$files = glob("posts/*.txt");
foreach ($files as $file) {
    $title = basename($file, ".txt");
    echo "        <rdf:li rdf:resource=\"http://localhost/wiki.php?view={$title}\" />\n";
}

echo <<<EOD
      </rdf:Seq>
    </items>
  </channel>

EOD;

//Detaljer för varje item
foreach ($files as $file) {
    $title = basename($file, ".txt");
    $content = file_get_contents($file);
    $desc = strip_wiki(implode("\n", array_slice(explode("\n", $content), 0, 3)));

    echo <<<EOD
  <item rdf:about="http://localhost/wiki.php?view={$title}">
    <title>{$title}</title>
    <link>http://localhost/wiki.php?view={$title}</link>
    <description>{$desc}</description>
  </item>

EOD;
}

echo "</rdf:RDF>";
?>
