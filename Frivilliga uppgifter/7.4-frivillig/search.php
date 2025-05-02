<?php
/**
 * En sökmotor som letar efter ett visst ord i HTML-sidor och följer länkar till ett visst djup.
 * Startar från en URL angiven via formulär och går rekursivt (djupet-först).
 * 
 * !!!OBS!!!
 * Provade med samma som ditt exempel men antar att den blockerar hämtningar av file_get_contents,
 * men den fungerar med detta exempel:
 * URL: https://www.w3.org/
 * Sökord: html
 * Djup: 2
 * !!!OBS!!!
 */

function spider($url, $searchTerm, $depth = 0, $maxDepth = 2, &$visited = [], &$counter = []) {
    if ($depth > $maxDepth || isset($visited[$url])) return;
    $visited[$url] = true;

    if (!isset($counter[$depth])) $counter[$depth] = 1;
    $linkNumber = $counter[$depth];
    $level = $depth + 1;
    $totalVisited = count($visited);
    
    $block = <<<EOD
    <pre>
    Level: {$level}, link: {$linkNumber} ({$totalVisited})
      Y: {$url}
    </pre>
    EOD;
    echo $block;

    $html = @file_get_contents($url);
    if (!$html) {
        $output = <<<EOD
        <p>Kunde inte hämta sidan</p>
        EOD;
        echo $output;
        return;
    }

    //Om det finns en träff
    if (stripos($html, $searchTerm) !== false) {
        $output = <<<EOD
        <p>Träff: '$searchTerm' hittades!</p>
        EOD;
    } else {
        $output = <<<EOD
        <p>Inget resultat.</p>
        EOD;
    }
    echo $output;

    //Hitta länkar
    preg_match_all('/<a\s+[^>]*href=["\'](.*?)["\']/i', $html, $matches);
    $links = array_unique($matches[1]);
    $absoluteLinks = array_filter($links, fn($link) => preg_match('/^https?:\/\//', $link));
    $numLinks = count($absoluteLinks);

    $linkInfo = <<<EOD
    <pre>  Links on this page: {$numLinks}, links on next level: {$numLinks}</pre>
    EOD;
    echo $linkInfo;

    if (!isset($counter[$depth + 1])) $counter[$depth + 1] = 1;

    foreach ($absoluteLinks as $link) {
        $counter[$depth + 1]++;
        spider($link, $searchTerm, $depth + 1, $maxDepth, $visited, $counter);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['url']);
    $term = trim($_POST['term']);
    $depth = isset($_POST['depth']) ? (int)$_POST['depth'] : 2;

    $head = <<<EOD
        <h1>Sökresultat för '{$term}'</h1>
    EOD;
    echo $head;

    spider($url, $term, 0, $depth);

    $footer = <<<EOD
        <hr>
        <p><a href='search.html'>Tillbaka till formuläret</a></p>
    EOD;
    echo $footer;
}
?>
