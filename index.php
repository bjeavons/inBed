<?php

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

  foreach (qp($page, 'h1 a') as $header) {
    $header->append(' in Bed');
  }
  foreach (qp($page, 'h2 a') as $header) {
    $header->append(' in Bed');
  }
  foreach (qp($page, 'h3 a') as $header) {
    $header->append(' in Bed');
  }

  print $page->html();
  exit;
}

function frontPage() {
  print <<<eof
<!doctype html> 
<html lang="en" dir="ltr"> 
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
</head>
<body>
<h1>In Bedify</h1>
<div>
<form method="GET" name="inbedify" action="/">
  Read
  <input type="text" name="q" id="domain_input" value="http://nytimes.com" onclick="clearDomainInput(this);">
  <a href="#" onclick="formSubmit();">in bed</a>
  <input type="submit" style="display: none;">
</form>
</div>
<div>
Made by <a href="https://twitter.com/benswords">@benswords</a> and <a href="https://twitter.com/ezrabg">@ezrabg</a> &hellip; not in bed.
</div>
</body>
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