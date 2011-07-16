<?php

require 'QueryPath/QueryPath.php';

$uri = $_GET['q'];
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

/*$heading = $bodyqp->branch('h1')->remove()->text();
      $body = $bodyqp->innerHTML();*/
print_r($page->html());


?>