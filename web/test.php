<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;

$html = <<<HTML
<html>
<div class="content">
    <h2 class="gamma">Excerpt</h2>
    <p>...content html...</p>
</div>
<div class="content">
    <h2 class="gamma">Excerpt</h2>
    <p>...more content html...</p>
</div>
</html>
HTML;

$crawler = new Crawler($html, 'http://localhost');

// remove all h2 nodes inside .content
$crawler->filter('html .content h2')->each(function (Crawler $crawler) {
    foreach ($crawler as $node) {
        $node->parentNode->removeChild($node);
    }
});

// output .content nodes with h2 removed
$crawler->filter('html .content')->each(function (Crawler $crawler) {
    echo $crawler->html();
});
