<?php

/**
 * InBedify
 *
 * Copyright (C) 2011 Ezra Barnett Gildesgame & Benjamin Jeavons
 */

function getURL() {
  if (isset($_GET['q'])) {
    $domain = $_GET['q'];
  }
  elseif (isset($_POST['domain'])) {// && strpos($_POST['domain'], 'http') !== false) {
    $domain = $_POST['domain'];
  }
  if (isset($domain)) {
    if (strpos($domain, 'http') === false) {
      $domain = 'http://' . $domain;
    }
    list($scheme, $url) = explode(':/', $domain);
    // A forward slash can be stripped by the browser so plan for it.
    return $scheme . "://" . ltrim($url, '/');
  }
  else {
    return false;
  }
}

function validURL($url) {
  return (bool) preg_match("
    /^                                                      # Start at the beginning of the text
    (?:https?):\/\/                                         # Look for http, https or schemes
    (?:                                                     # Userinfo (optional) which is typically
      (?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*      # a username or a username and password
      (?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@          # combination
    )?
    (?:
      (?:[a-z0-9\-\.]|%[0-9a-f]{2})+                        # A domain name or a IPv4 address
      |(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\])         # or a well formed IPv6 address
    )
    (?::[0-9]+)?                                            # Server port number (optional)
    (?:[\/|\?]
      (?:[\w#!:\.\?\+=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})   # The path and query (optional)
    *)?
  $/xi", $url);
}

function inBedify($url) {
  require 'QueryPath/QueryPath.php';
  $page = htmlqp($url);
  if (!$page) {
    return;
  }
  $url_parts = parse_url($url);
  // Check response code @todo
  $base = $url_parts['scheme'] . '://' . $url_parts['host'];

  // Convert relative CSS and JS to absolute.
  foreach (qp($page, 'link') as $link) {
    if (strpos($link->attr('href'), 'http') === false) {
      $link->attr('href', $base . $link->attr('href'));
    }
  }
  foreach (qp($page, 'script') as $script) {
    if (strpos($script->attr('src'), 'http') === false) {
      $script->attr('src', $base . $script->attr('src'));
    }
  }

  // Rewrite same-domain URLs to run through InBedify.
  foreach (qp($page, 'a') as $a) {
    if (strpos($a->attr('href'), 'http') !== false) {
      // Only rewrite same-domain URLs.
      $host = parse_url($a->attr('href'), PHP_URL_HOST);
      //if (ltrim($host, '.') == ltrim($url_parts['host'], '.')) { //@todo
        $a->attr('href', 'http://inbedify.com/' . $a->attr('href'));
      //}
    }
    else {
      // Relative URL.
      $a->attr('href', 'http://inbedify.com/' . $base . '/' . ltrim($a->attr('href'), '/'));
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
<style type="text/css">
  body{background-color:#fff;color:#333;font-family:Arial,Verdana,sans-serif;font-size:62.5%;margin:10% 5% 0 5%;text-align:center;}
  .inbedit{font-weight:bold;}
  a,a:visited,a:active{color:#0080ff;text-decoration:underline;font-weight:bold;}
  a:hover{text-decoration:none;font-weight:bold;}
  h1{font-size:5em;text-shadow: #999 5px 5px 8px;}
  input[type=text]{border:1px solid #ccc;font-size:1em;padding:4px 6px 4px 6px;}
  .ify{font-style:italic;font-size:1em;}
  #content{clear:both;font-size:3em;margin:auto;}
  #domain_input{width:380px;}
  .footer{padding-top:3em;}
  .credits{font-size:1.8em;}
  .fineprint{font-size:1.2em;}
</style>
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
<h1>In Bed<span class="ify">ify</span></h1>
<div id="content">
<form method="GET" name="inbedify" action="/">
  <span class="inbedit">Why don't you read</span>
  <input type="text" name="q" class="domain" id="domain_input" value="talkingpointsmemo.com" onclick="clearDomainInput(this);">
  <span class="inbedit"><a href="#" onclick="formSubmit();">in bed?</a></span>
  <input type="submit" style="display: none;">
</form>
</div>
<div class="footer">
<p class="credits">Made by <a href="https://twitter.com/benswords">@benswords</a> and <a href="https://twitter.com/ezrabg">@ezrabg</a> &hellip; <em>not</em> in bed.</p>
<p class="fineprint">This is a novelty service, no ownership over served content is implied &hellip; in bed.</p>
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