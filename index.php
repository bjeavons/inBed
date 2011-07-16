<?php

/**
 * InBedify
 *
 * Copyright (C) 2011 Ezra Barnett Gildesgame & Benjamin Jeavons
 */

function getURL() {
  if (isset($_GET['q']) && strpos($_GET['q'], 'http') !== false) {
    list($scheme, $url) = explode(':/', $_GET['q']);
    // A forward slash can be stripped by the browser so plan for it.
    return $scheme . "://" . ltrim($url, '/');
  }
  elseif (isset($_POST['domain']) && strpos($_POST['domain'], 'http') !== false) {
    return $_POST['domain'];
  }
  return false;
}

function validURL($url) {
  return true; // @todo
}

function inBedify($uri) {
  require 'QueryPath/QueryPath.php';
  $page = htmlqp($uri);
  $uri_parts = parse_url($uri);
  // Check response code @todo
  $base = $uri_parts['scheme'] . '://' . $uri_parts['host'];

  // Convert relative CSS and JS to absolute.
  foreach (qp($page, 'link') as $link) {
    if (strpos($link->attr('href'), 'http') === FALSE) {
      $link->attr('href', $base . $link->attr('href'));
    }
  }
  foreach (qp($page, 'script') as $script) {
    if (strpos($script->attr('src'), 'http') === FALSE) {
      $script->attr('src', $base . $script->attr('src'));
    }
  }

  // Rewrite same-domain URLs to run through InBedify.
  foreach (qp($page, 'a') as $a) {
    if (strpos($a->attr('href'), 'http') !== FALSE) {
      // Only rewrite same-domain URLs.
      $host = parse_url($a->attr('href'), PHP_URL_HOST);
      //if (ltrim($host, '.') == ltrim($uri_parts['host'], '.')) { //@todo
        $a->attr('href', 'http://inbedify.com/' . $a->attr('href'));
      //}
    }
    else {
      // Relative URL.
      $a->attr('href', 'http://inbedify.com/' . $base . $a->attr('href'));
    }
  }

  // InBedify!
  // Speed this up @todo
  foreach (qp($page, 'h1') as $header) {
    inBedElement($header);
  }
  foreach (qp($page, 'h2') as $header) {
    inBedElement($header);
  }
  foreach (qp($page, 'h3') as $header) {
    inBedElement($header);
  }

  print $page->html();
  exit;
}

function inBedElement($qp_element) {
  // Check if text is in a child element.
  if ($qp_element->is('a')) {
    $qp_element->find('a');
  }
  //$text = $qp_element->text();
  //$text = preg_replace('/(.*)([\!\?\.])$/', '$1 in Bed$2', $text);
  //$qp_element->text($text);
  $qp_element->append(' in Bed');
}

function frontPage() {
  print <<<eof
<!doctype html> 
<html lang="en" dir="ltr"><!-- in bed -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
  <title>In Bedify</title> 
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<meta name="keywords" content="Meta keywords in bed" /> 
<meta name="description" content="Meta descriptions in bed" />
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>-->
<script type="text/javascript">
  function clearDomainInput(e) {
    if (e.cleared) { return; }
    e.cleared = true;
    e.value = '';
  }
  function formSubmit() {
    domain = document.getElementById('domain_input').value;
    window.location = '/' + domain;
    return false;
  }
</script>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-24581773-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</head><!-- in bed -->
<body>
<h1>In Bedify</h1>
<div>
<form method="GET" name="inbedify" action="/">
  Read
  <input type="text" name="q" id="domain_input" size="29" value="http://talkingpointsmemo.com" onclick="clearDomainInput(this);">
  <a href="#" onclick="formSubmit();">in bed</a>
  <input type="submit" style="display: none;">
</form>
</div>
<div>
Made by <a href="https://twitter.com/benswords">@benswords</a> and <a href="https://twitter.com/ezrabg">@ezrabg</a> &hellip; not in bed.
</div> 
</body><!-- in bed -->
</html>
eof;
  exit;
}

$url = getURL();
if ($url) {
  // Validate URL.
  if (validURL($url)) {
    inBedify($url);
  }
}

frontPage();
?>