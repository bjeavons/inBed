<?php

function validURL($scheme, $url) {
  return true; // @todo
}

function inBedify($uri) {
  require 'QueryPath/QueryPath.php';
  $page = htmlqp($uri);

  //$page->branch('h2')->append(' in bed');

  foreach (qp($page, 'h2 a') as $header) {
    $header->replaceWith($header->innerHTML() . 'in bed');
    $header->append(' in bed');
  }

  foreach (qp($page, 'h3 a') as $header) {
    //$header->replaceWith($header->innerHTML() . 'in bed');
    $header->append(' in bed');
  }

  print 'bar';//$page->html();
  exit;
}

function frontPage() {
  print 'foo';
  exit;
}

if (isset($_GET['q']) && strpos($_GET['q'], 'http') !== false) {
  // A forward slash was stripped.
  list($scheme, $url) = explode(':/', $_GET['q']);
  // Validate URL.
  if (validURL($scheme, $url)) {
    inBedify($url);
  }
}

frontPage();

?>