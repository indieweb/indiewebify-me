/* <!--
   cassis.js Copyright 2008-2013 Tantek Çelik http://tantek.com 
   http://cassisproject.com conceived:2008-254; created:2009-299;
   license:http://creativecommons.org/licenses/by-sa/3.0/         -->
if you see this in the browser, you need to wrap your PHP include of cassis.js and use thereof with calls to ob_start and ob_end_clean, e.g.:
ob_start(); 
include 'cassis.js'; 
// your code that calls CASSIS functions goes here
ob_end_clean(); 
/* <!-- <?php // CASSIS v0.1 start -->
// ===================================================================
// PHP-only block. Processed only by PHP. Use only // comments here.
// -------------------------------------------------------------------
function js() {
  return false;
}

// global configuration

if (php_min_version("5.1.0")) {
  date_default_timezone_set("UTC");
}

function php_min_version($s) {
  $s = explode(".", $s);
  $phpv = explode(".", phpversion());
  for ($i=0; $i < count($s); $i+=1) {
    if ($s[$i] > $phpv[$i]) {
      return false; 
    }
  }
  return true;
}

// -------------------------------------------------------------------
// date time functions

function date_get_full_year($d = "") {
  if ($d == "") {
    $d = new DateTime();
  }
  return $d->format('Y');
} 

function date_get_timestamp($d) { 
  return $d->format('U'); // $d->getTimestamp(); // in PHP 5.3+
}

// mixed-case function names are bad for PHP vs JS. Don't do it.
//function Number($n) {
//  return $n-0;
//}


// -------------------------------------------------------------------
// old wrappers. transition code away from these
// ** do not use these in new code. **

function getFullYear($d = "") {  
  // 2010-020 obsoleted. Use date_get_full_year instead
  return date_get_full_year($d);
}

// ===================================================================
/*/ // This comment inverter switches from PHP only to JS only.
// JS-only block. Processed only by JS. Use only // comments here.
// -------------------------------------------------------------------
function js() {
  return true;
}

// array functions

function array() { // makes an array from arbitrary parameter list.
  return Array.prototype.slice.call(arguments);
}

function count(a) {
  return a.length;
}

// -------------------------------------------------------------------
// math and numerical functions

function floor(n) {
  return Math.floor(n);
}

function intval(n) {
  return parseInt(n, 10);
}

function is_array(a) {
  return (typeof(a) === "object") && (a instanceof Array);
}

Array.min = function(a) { 
// from http://ejohn.org/blog/fast-javascript-maxmin/
  return Math.min.apply(Math, a);
};

function min() {
  var m = arguments;
  if (m.length < 1) {
    return false;
  } 
  if (m.length === 1) {
    m = m[0];
    if (!is_array(m)) {
      return m;
    }
  }
  return Array.min(m);
}

function ctype_digit(s) {
  return (/^[0-9]+$/).test(s);
}

function ctype_space(s) {
 return /\s/.test(s);
}

// -------------------------------------------------------------------
// date time functions

function date_create(s) {
  var d = new Date();
  d.parse(s);
  return d;
}

function date_get_full_year(d) {
  if (arguments.length < 1) {
    d = new Date();
  }
  return d.getFullYear();
}

function date_get_timestamp(d) {
  return floor(d.getTime() / 1000);
}


// -------------------------------------------------------------------
// character and string functions 

function ord(s) {
  return s.charCodeAt(0);
}

function strlen(s) {
  return s.length;
} 

function substr(s, o, n) {
  var m = strlen(s);
  if ((o < 0 ? -1-o : o) >= m) { return false; }
  if (o < 0) { o = m + o; }
  if (n < 0) { n = m - o + n; }
  if (n === undefined) { n = m - o; }
  return s.substring(o, o + n);
}

function substr_count(s, n) {
 return s.split(n).length - 1;
}

function strpos(h, n, o) {
  // clients must triple-equal test return for === false for no match!
  // or use offset(n, h) instead (0 = not found, else 1-based index)
  if (arguments.length === 2) {
    o = 0;
  }
  o = h.indexOf(n, o);
  if (o === -1) { return false; }
  else { return o; }
}

function strncmp(s1, s2, n) {
  s1 = substr(String(s1), 0, n);
  s2 = substr(String(s2), 0, n);
  return (s1 === s2) ? 0 :
         ((s1 < s2) ? -1 : 1);
}

function explode(d, s, n) {
  if (arguments.length === 2) {
    return s.split(d);
  }
  return s.split(d, n);
}

function implode(d, a) {
  return a.join(d);
}

function rawurlencode(s) {
  return encodeURIComponent(s);
}

function htmlspecialchars(s) {
  var c, i;
  c = [["&","&amp;"],  ["<","&lt;"], [">","&gt;"], 
       ["'","&#039;"], ['"',"&quot;"]];
  for (i = 0; i < c.length; i+=1) {
    s = s.replace(new RegExp(c[i][0], "g"), c[i][1]);
  }
  return s;
}

function str_ireplace(a, b, s) {
  return s.replace(new RegExp(a, "gi"), b);
}

function trim() {
  var c, i, j, m, s;
  m = arguments;
  s = m[0];
  c = count(m) > 1 ? m[1] : " \t\n\r\f\x00\x0b\xa0";
  i = 0;
  j = strlen(s);
  
  while (strpos(c,s[i])!==false && i<j) {
    i+=1;
  }
  j-=1;
  while (j>i && strpos(c,s[j])!==false) {
    j-=1;
  }
  j+=1;
  if (j>i) {
    return substr(s,i,j-i);
  }
  else {
    return '';
  }
}

function rtrim() {
  var c,j,m,s;
  m = arguments;
  s = m[0];
  c = count(m)>1 ? m[1] : " \t\n\r\f\x00\x0b\xa0";
  j = strlen(s)-1;
  while (j>=0 && strpos(c,s[j])!==false) {
    j-=1;
  }
  if (j>=0) {
    return substr(s,0,j+1);
  }
  else {
    return '';
  }
}

function strtolower(s) {
  return s.toLowerCase();
}

// -------------------------------------------------------------------
// more javascript-only php-equivalent functions here 


// -------------------------------------------------------------------
// pacify jslint/jshint
// -- define functions and variables only used in PHP flow.
function func_get_args() { }
var FALSE = false;
var PREG_PATTERN_ORDER;
var STR_PAD_LEFT;
// -- may eventually define these for JS.
function date_format() { }
function preg_match_all() { }
function str_pad() { }
function DateTime() { }

// ===================================================================
/**/ // unconditional comment closer exits PHP comment block.
// JS+PHP block. Processed by both JS and  PHP. /*...*/ comments ok.
// -------------------------------------------------------------------
/* original js/php test - doesn't pass jslint/jshint.
function js() {
  return "00"==false;
}
*/

/*global document: false, window: false */
/// ?> <!--   ///
// -------------------------------------------------------------------
// javascript-only framework functions

function doevent(el, evt) {
  if (evt==="click" && el.tagName==='A') {
  // dispatch/fireEvent fails FF3.5+/IE8+ on [a href] w "click" event
    window.location = el.href; // workaround
    return true;
  }
  if (document.createEvent) {
    var eo = document.createEvent("HTMLEvents");
    eo.initEvent(evt, true, true);
    return !el.dispatchEvent(eo);
  } 
  else if (document.createEventObject) {
    return el.fireEvent("on"+evt);
  }
}

function targetelement(e) {
  var t;
  e = e ? e : window.event;
  t = e.target ? e.target : e.srcElement;
  t = (t.nodeType == 3) ? t.parentNode : t; // Safari workaround
  return t;
}

/// --> <?php ///


// -------------------------------------------------------------------
// character and string functions 

// strcat: takes as many strings as you want to give it.
function strcat() {         /// ?> <!--   ///
  var $args, $i, $isjs, $r; /// --> <?php ///
  $r = "";
  $isjs = js();
  $args = $isjs ? arguments : func_get_args();
  for ($i=count($args)-1; $i>=0; $i-=1) {
    $r = $isjs ? $args[$i] + $r : $args[$i] . $r;
  }
  return $r;
}

function number($s) {
 return $s - 0;
}

function string($n) {
  if (js()) { 
    if (typeof($n)==="number") {
      return Number($n).toString(); 
    } else if (typeof($n)==="undefined") {
      return "";
    } else {
      return $n.toString();
    }
  }
  else { 
    return "" . $n; 
  }
}

function str_pad_left($s1, $n, $s2) {
  $s1 = string($s1);
  $s2 = string($s2);
  if (js()) {
    $n -= strlen($s1);
    while ($n >= strlen($s2)) { 
      $s1 = strcat($s2, $s1); 
      $n -= strlen($s2);
    }
    if ($n > 0) {
      $s1 = strcat(substr($s2, 0, $n), $s1);
    }
    return $s1;
  } else { 
    return str_pad($s1, $n, $s2, STR_PAD_LEFT); 
  }
}

function trim_slashes($s) {
  if ($s[0]==="/") { // strip unnecessary / delim PHP regex funcs want
    return substr($s, 1, strlen($s)-2);
  }
  return $s;
}

// define a few JS functions that PHP already has, using CASSIS funcs
/// ?> <!--   ///
function preg_match(p, s) {
  return (s.match(trim_slashes(p)) ? 1 : 0);
}

function preg_split(p, s) {
  return s.split(new RegExp(trim_slashes(p), "gi"));
}
/// --> <?php ///

function preg_match_1($p, $s) { /// ?> <!--   ///
  var $m;                       /// --> <?php ///
  if (js()) {
    return $s.match(new RegExp(trim_slashes($p), "i"));
  } else {
    $m = array();
    if (preg_match($p, $s, $m) !== FALSE) {
      return $m[0];
    } else {
      return null; //array();
    }
  }
}

function preg_replace_1($p, $r, $s) {
  if (js()) {
    return $s.replace(new RegExp(trim_slashes($p), "i"), $r);
  }
  else {
    $r = preg_replace($p, $r, $s, 1);
    if ($r !== null) { return $r; }
    else             { return $s; }
  }
}

function preg_matches($p, $s) { /// ?> <!--   ///
  var $m;                       /// --> <?php ///
  if (js()) {
    return $s.match(new RegExp(trim_slashes($p), "gi"));
  } else {
    $m = array();
    if (preg_match_all($p, $s, $m, PREG_PATTERN_ORDER) !== FALSE) {
      return $m[0];
    } else {
      return null; //array();
    }
  }
}

function ctype_email_local($s) {
 // close enough. no '.' because this is used for last char of.
 return (preg_match("/^[a-zA-Z0-9_%+-]+$/", $s));
}

// -------------------------------------------------------------------
// newbase60

function num_to_sxg($n) { /// ?> <!--   ///
  var $d, $m, $p, $s;     /// --> <?php ///
  $m = "0123456789ABCDEFGHJKLMNPQRSTUVWXYZ_abcdefghijkmnopqrstuvwxyz";
  $p = "";
  $s = "";
  if ($n==="" || $n===0) { return "0"; }
  if ($n<0) {
    $n = -$n;
    $p = "-";
  }
  while ($n>0) {
    $d = $n % 60;
    $s = strcat($m[$d], $s);
    $n = ($n-$d)/60;
  }
  return strcat($p, $s);
}

function num_to_sxgf($n, $f) {
  if (!$f) { $f=1; }
  return str_pad_left(num_to_sxg($n), $f, "0");
}

function sxg_to_num($s) { /// ?> <!--   ///
  var $c, $i, $j, $m, $n; /// --> <?php ///
  $j = strlen($s);
  $m = 1;
  $n = 0;
  if ($s[0]==="-") {
    $m = -1;
    $j-=1;
    $s = substr($s, 1, $j);
  }
  for ($i=0; $i<$j; $i+=1) { // iterate from first to last char of $s
    $c = ord($s[$i]); //  put current ASCII of char into $c  
    if ($c>=48 && $c<=57) { $c=$c-48; }
    else if ($c>=65 && $c<=72) { $c-=55; }
    else if ($c===73 || $c===108) { $c=1; } // typo cap I lower l to 1
    else if ($c>=74 && $c<=78) { $c-=56; }
    else if ($c===79) { $c=0; } // error correct typo capital O to 0
    else if ($c>=80 && $c<=90) { $c-=57; }
    else if ($c===95 || $c===45) { $c=34; } // _ and dash - to _
    else if ($c>=97 && $c<=107) { $c-=62; }
    else if ($c>=109 && $c<=122) { $c-=63; }
    else { break; } // treat all other noise as end of number
    $n = 60*$n + $c;
  }
  return $n*$m;
}

function sxg_to_numf($s, $f) {
  if ($f===undefined) { $f=1; }
  return str_pad_left(sxg_to_num($s), $f, "0");
}

// -------------------------------------------------------------------
// == newbase60 compat functions only == (before 2011-149)
function numtosxg($n) {
  return num_to_sxg($n);
}

function numtosxgf($n, $f) {
  return num_to_sxgf($n, $f);
}

function sxgtonum($s) {
  return sxg_to_num($s);
}

function sxgtonumf($s, $f) {
  return sxg_to_numf($s, $f);
}

// -------------------------------------------------------------------
// date and time

function date_create_ymd($s) { /// ?> <!--   ///
  var $d;                      /// --> <?php ///
  if ($s === 0) {
    return (js() ? new Date() : new DateTime());
  }
  if (js()) { 
    if (substr($s,4,1)==='-') {
      $s=strcat(substr($s,0,4), substr($s,5,2), substr($s,8,2));
    }
    $d = new Date(substr($s,0,4),substr($s,4,2)-1,substr($s,6,2));
    $d.setHours(0); // was setUTCHours, avoiding bc JS no default tz
    return $d;
  } else { 
    return date_create(strcat($s, " 00:00:00"));
  }
}

function date_create_timestamp($s) {
  if (js()) {
    return new Date(1000*$s);
  } else {
    return new DateTime(strcat("@", string($s)));
  }
}

// function date_get_timestamp($d) // in PHP/JS specific code above.

function date_get_rfc3339($d) {
  if (js()) {
    return strcat($d.getFullYear(), '-',
                  str_pad_left(1+$d.getUTCMonth(), 2, "0"), '-',
                  str_pad_left($d.getDate(), 2, "0"), 'T',
                  str_pad_left($d.getUTCHours(), 2, "0"), ':',
                  str_pad_left($d.getUTCMinutes(), 2, "0"), ':',
                  str_pad_left($d.getUTCSeconds(), 2, "0"), 'Z');
  } else {
    return date_format($d, 'c');
  }
}


// -------------------------------------------------------------------
// newcal

function isleap($y) {
  return ($y % 4 === 0 && ($y % 100 !== 0 || $y % 400 === 0));
}

function ymdp_to_d($y, $m, $d) { /// ?> <!--   ///
  var $md;                       /// --> <?php ///
  $md = array(
         array(0,31,59,90,120,151,181,212,243,273,304,334),
         array(0,31,60,91,121,152,182,213,244,274,305,335));
  return $md[number(isleap($y))][$m-1] + number($d);
}

function ymdp_to_yd($y, $m, $d) {
  return strcat(str_pad_left($y, 4, "0"), '-',
                str_pad_left(ymdp_to_d($y, $m, $d), 3, "0"));
}

function ymd_to_yd($d) {
  if (substr($d, 4, 1)==='-') {
    return ymdp_to_yd(substr($d,0,4),substr($d,5,2),substr($d,8,2));
  } else {
    return ymdp_to_yd(substr($d,0,4),substr($d,4,2),substr($d,6,2));
  }
}

function date_get_ordinal_days($d) {
  if (js()) {
    return ymdp_to_d($d.getFullYear(), 1+$d.getMonth(), $d.getDate());
  } else {
    return 1+date_format($d, 'z');
  }
}

function bim_from_od($d) {
  return 1+floor(($d-1)/61);
}

function date_get_bim() { /// ?> <!--   ///
  var $args;              /// --> <?php ///
  $args = js() ? arguments : func_get_args();
  return bim_from_od(
          date_get_ordinal_days(
           date_create_ymd((count($args) > 0) ? $args[0] : 0)));
}

function get_nm_str($m) { /// ?> <!--   ///
  var $a;                 /// --> <?php ///
  $a = array("New January", "New February", "New March", "New April", "New May", "New June", "New July", "New August", "New September", "New October", "New November", "New December");
  return $a[($m-1)];
}

function nm_from_od($d) {
  return ((($d-1) % 61) > 29) ? 2+2*(bim_from_od($d)-1) : 1+2*(bim_from_od($d)-1);
}

// date_get_ordinal_date: optional date argument
function date_get_ordinal_date() { /// ?> <!--   ///
  var $args, $d;                   /// --> <?php ///
  $args = js() ? arguments : func_get_args();
  $d = date_create_ymd((count($args) > 0) ? $args[0] : 0);
  return strcat(date_get_full_year($d), '-',
                str_pad_left(date_get_ordinal_days($d), 3, "0"));
}

// -------------------------------------------------------------------
// begin epochdays

function y_to_days($y) {
  // convert y-01-01 to epoch days
  return floor(
   (date_get_timestamp(date_create_ymd(strcat($y, "-01-01"))) -
    date_get_timestamp(date_create_ymd("1970-01-01")))/86400);
}

// convert ymd to epoch days and sexagesimal epoch days (sd)

function ymd_to_days($d) {
  return yd_to_days(ymd_to_yd($d));
}

/* old:
function ymd_to_days($d) {
  // fails in JS, "2013-03-10" and "2013-03-11" both return 15774 
  return floor(
   (date_get_timestamp(date_create_ymd($d)) -
    date_get_timestamp(date_create_ymd("1970-01-01")))/86400);
}
*/

function ymd_to_sd($d) {
  return num_to_sxg(ymd_to_days($d));
}

function ymd_to_sdf($d, $f) {
  return num_to_sxgf(ymd_to_days($d), $f);
}

// ordinal date (YYYY-DDD) to ymd, epoch days, sexagesimal epoch days

function ydp_to_ymd($y, $d) { /// ?> <!--   ///
  var $md, $m;                /// --> <?php ///
  $md = array(
         array(0,31,59,90,120,151,181,212,243,273,304,334),
         array(0,31,60,91,121,152,182,213,244,274,305,335));
  $d -= 1;
  $m = trunc($d / 29);
  if ($md[isleap($y) - 0][$m] > $d) $m -= 1;
  $d = $d - $md[isleap($y)-0][$m] + 1;
  $m += 1;
  return strcat($y, '-', str_pad_left($m, 2, '0'), 
                    '-', str_pad_left($d, 2, '0'));
}

function yd_to_ymd($d) {
  return ydp_to_ymd(substr($d, 0, 4), substr($d, 5, 3));
}

function yd_to_days($d) {
  return y_to_days(substr($d, 0, 4)) - 1 + number(substr($d, 5, 3));
}

function yd_to_sd($d) {
  return num_to_sxg(yd_to_days($d));
}

function yd_to_sdf($d, $f) {
  return num_to_sxgf(yd_to_days($d), $f);
}

// convert epoch days or sexagesimal epoch days (sd) to ordinal date
function days_to_yd($d) { /// ?> <!--   ///
  var $a, $y;             /// --> <?php ///
  $d = date_create_timestamp(
         date_get_timestamp(
           date_create_ymd("1970-01-01")) + $d*86400);
  $y = date_get_full_year($d);
  $a = date_create_ymd(strcat($y, "-01-01"));
  return strcat($y, "-",
           str_pad_left(
             1 + floor((
                   date_get_timestamp($d) - date_get_timestamp($a))/86400), 3, "0"));
}

function sd_to_yd($d) {
  return days_to_yd(sxg_to_num($d));
}

// -------------------------------------------------------------------
// compat as of 2011-143
function ymdptod($y,$m,$d) { return ymdp_to_d($y,$m,$d); }
function ymdptoyd($y,$m,$d) { return ymdp_to_yd($y,$m,$d); }
function ymdtoyd($d) { return ymd_to_yd($d); }
function bimfromod($d) { return bim_from_od($d); }
function getnmstr($m) { return get_nm_str($m); }
function nmfromod($d) { return nm_from_od($d); }
function ymdtodays($d) { return ymd_to_days($d); }
function ymdtosd($d) { return ymd_to_sd($d); }
function ymdtosdf($d,$f) { return ymd_to_sdf($d, $f); }
function ydtodays($d) { return yd_to_days($d); }
function ydtosd($d) { return yd_to_sd($d); }
function ydtosdf($d,$f) { return yd_to_sdf($d, $f); }
function daystoyd($d) { return days_to_yd($d); }
function sdtoyd($d) { return sd_to_yd($d); }

// -------------------------------------------------------------------
// HTTP

function is_http_header($s) {
  return (preg_match_1('/^[a-zA-Z][-\\w]*:/',$s)!==null);
}

// -------------------------------------------------------------------
// webaddress

function web_address_to_uri($wa, $addhttp) {
  if (!$wa || 
      (substr($wa, 0, 7) === 'http://') || 
      (substr($wa, 0, 8) === 'https://') || 
      (substr($wa, 0, 6) === 'irc://')) {
    return $wa;
  }
  if ((substr($wa, 0, 7) === 'Http://') || 
      (substr($wa, 0, 8) === 'Https://')) { 
      // handle iOS4 overcapitalization of input entries
    return strcat('h', substr($wa, 1, strlen($wa)));
  }

  // TBI: may want to handle typos as well like:
  // missing/extra : or / http:/ http///
  // missing letter in protocol: ttps htps htts, ttp htp htt, ir ic rc
  // use strtolower(substr($wa, 0, 6)); // handle capitals in URLs

  if (substr($wa,0,1) === '@') {
    return strcat('https://twitter.com/', substr($wa, 1, strlen($wa)));
  }

  if ($addhttp) {
    $wa = strcat('http://', $wa);
  }
  return $wa;
}

function uri_clean($uri) {
  $uri = web_address_to_uri($uri, false);
  // prune the optional http:// for a neater param
  if (substr($uri, 0, 7) === "http://") {
    $uri = explode("://", $uri, 2);
    $uri = $uri[1];
  }
  // URL encode
  return str_ireplace("%3A", ":", 
                      str_ireplace("%2F", "/", rawurlencode($uri)));
}

// -------------------------------------------------------------------
// compat as of 2011-149
function webaddresstouri($wa, $addhttp) { 
  return web_address_to_uri($wa, $addhttp);
}
function uriclean($uri) { return uri_clean($uri); }


// -------------------------------------------------------------------
// hexatridecimal

function num_to_hxt($n) { /// ?> <!--   ///
  var $d, $m, $s;         /// --> <?php ///
  $m = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $s = "";
  if ($n === undefined || $n === 0) { return "0"; }
  while ($n>0) {
    $d = $n % 36;
    $s = strcat($m[$d], $s);
    $n = ($n-$d)/36;
  }
  return $s;
}

function num_to_hxtf($n, $f) {
  if ($f === undefined) { $f=1; }
  return str_pad_left(num_to_hxt($n), $f, "0");
}

function hxt_to_num($h) { /// ?> <!--   ///
  var $c, $i, $j, $n;     /// --> <?php ///
  $j = strlen($h);
  $n = 0;
  for ($i=0; $i<$j; $i+=1) { // iterate from first to last char of $h
    $c = ord($h[$i]); //  put current ASCII of char into $c  
    if ($c>=48 && $c<=57) { $c=$c-48; } // 0-9
    else if ($c>=65 && $c<=90) { $c-=55; } // A-Z
    else if ($c>=97 && $c<=122) { $c-=87; } // a-z treat as A-Z
    else { $c = 0; } // treat all other noise as 0
    $n = 36*$n + $c;
  }
  return $n;
}

// -------------------------------------------------------------------
// compat as of 2011-149
function numtohxt($n) { return num_to_hxt($n); }
function numtohxtf($n,$f) { return num_to_hxtf($n, $f); }
function hxttonum($h) { return hxt_to_num($h); }


// -------------------------------------------------------------------
// ISBN-10

function num_to_isbn10($n) { /// ?> <!--   ///
  var $d, $f, $i;            /// --> <?php ///
  $n = string($n);
  $d = 0;
  $f = 2;
  for ($i=strlen($n)-1; $i>=0; $i-=1) {
    $d += $n[$i]*$f;
    $f += 1;  
  }
  $d = 11-($d % 11);
  if ($d===10) {$d="X";}
  else if ($d===11) {$d="0";}
  else {$d=string($d);}
  return strcat(str_pad_left($n, 9, "0"), $d);
}

// -------------------------------------------------------------------
// compat as of 2011-149
function numtoisbn10($n) { return num_to_isbn10($n); }


// -------------------------------------------------------------------
// HyperTalk

function trunc($n) { // just an alias from BASIC days
  return floor($n);
}

function offset($n, $h) {
  $n = strpos($h, $n);
  if ($n === false) { 
    return 0; 
  } else { 
    return $n+1; 
  }
}

function contains($h, $n) {
// HyperTalk syntax haystack contains needle: if ("abc" contains "b")
  return strpos($h, $n)!==false;
}

function last_character_of($s) {
  return (strlen($s) > 0) ? $s[strlen($s)-1] : '';
}

function line_1_of($s) { /// ?> <!--   ///
  var $r;                /// --> <?php ///
  $r = preg_match_1('/^[\\w\\W]*?[\\n\\r]+?/', $s);
  if ($r === null) { return $s; }
  return substr($r, 0, strlen($r)-1);
}

function delete_line_1_of($s) {
 if (preg_match_1('/^[\\w\\W]*?[\\n\\r]+?/', $s) === null) {
   return '';
 }
 return preg_replace_1('/^[\\w\\W]*?[\\n\\r]+?/', '', $s); 
}

// -------------------------------------------------------------------
// microformats

// xpath expressions to extract microformats
function xp_has_class($s) {
  return strcat("//*[contains(concat(' ',@class,' '),' ",$s," ')]");
}

function xpr_has_class($s) {
  return strcat(".//*[contains(concat(' ',@class,' '),' ",$s," ')]");
}

function xp_has_id($s) {
  return strcat("//*[@id='", $s, "']");
}

function xp_attr_starts_with($a, $s) {
  return strcat("//*[starts-with(@", $a, ",'", $s, "')]");
}

function xp_has_rel($s) {
  return strcat("//*[contains(concat(' ',@rel,' '),' ", $s, " ')]");
}

function xpr_has_rel($s) {
  return strcat(".//*[contains(concat(' ',@rel,' '),' ", $s, " ')]");
}

function xpr_attr_starts_with_has_rel($a, $s, $r) {
  return strcat(".//*[contains(concat(' ',@rel,' '),' ", $r, 
                " ') and starts-with(@", $a, ",'", $s, "')]");
}

// value class pattern readable date time from ISO8601 datetime
function vcp_dt_readable($d) { /// ?> <!--   ///
  var $r;                      /// --> <?php ///
  $d = explode("T", $d);
  $r = "";
  if (count($d)>1) { 
    $r = strcat('<time class="value">', $d[1], '</time> on ');
  }
  return strcat($r, '<time class="value">', $d[0], '</time>');
}


// -------------------------------------------------------------------
// compat as of 2011-149
function xphasclass($s) { return xp_has_class($s); }
function xprhasclass($s) { return xpr_has_class($s); }
function xphasid($s) { return xp_has_id($s); }
function xpattrstartswith($a, $s) { 
  return xp_attr_starts_with($a, $s); 
}
function xphasrel($s) { return xp_has_rel($s); }
function xprhasrel($s) { return xpr_has_rel($s); }
function xprattrstartswithhasrel($a, $s, $r) {
  return xpr_attr_starts_with_has_rel($a, $s, $r);
}
function vcpdtreadable($d) { return vcp_dt_readable($d); }


// -------------------------------------------------------------------
// whistle
// algorithmic URL shortener core
// YYYY/DDD/tnnn to tdddss 
// ordinal date, type, decimal #, to sexagesimal epoch days, sexagesimal #
function whistle_short_path($p) {
  return strcat(substr($p, 9, 1),
                ((substr($p, 9, 1)!=='t') ? "/" : ""),
                yd_to_sdf(substr($p, 0, 8), 3),
                num_to_sxg(substr($p, 10, 3)));
}

// -------------------------------------------------------------------
// falcon

function html_unesc_amp_only($s) {
  return str_ireplace('&amp;', '&', $s);
}

function html_esc_amper_once($s) {
  return str_ireplace('&', '&amp;', html_unesc_amp_only($s));
}

function html_esc_amp_ang($s) {
  return str_ireplace('<', '&lt;',
         str_ireplace('>', '&gt;', html_esc_amper_once($s)));
}

function ellipsize_to_word($s, $max, $e, $min) { /// ?> <!--   ///
  var $elen, $i, $slen;                          /// --> <?php ///
  if (strlen($s)<=$max) {
    return $s; // no need to ellipsize
  }

  $elen = strlen($e);
  $slen = $max-$elen;

  // if last characters before $max+1 are ': ', truncate w/o ellipsis.
  // no need to take length of ellipsis into account
  if ($e==='...') {
    for ($i=1; $i<=$elen+1; $i+=1) {
      if (substr($s, $max-$i, 2)===': ') {
        return substr($s, 0, $max-$i+1);
      }
    }
  }

  if ($min) {
    // if a non-zero minimum is provided, then
    // find previous space or word punctuation to break at.
    // do not break at %`'"&.!?^ - reasons why to be documented.
    while ($slen>$min && 
           !contains('@$ -~*()_+[]{}|;,<>', $s[$slen-1])) {
      $slen-=1;
    }
  }
  // at this point we've got a min length string, 
  // only do minimum trimming necessary to avoid a punctuation error.
  
  // trim slash after colon or slash
  if ($s[$slen-1]==='/' && $slen > 2) {
    if ($s[$slen-2]===':') {
      $slen-=1;    
    }
    if ($s[$slen-2]==='/') {
      $slen-=2;
    }
  }

  //if trimmed at a ":" in a URL, trim the whole thing
    //or trimmed at "http", trim the whole URL
  if ($s[$slen-1]===':' && $slen > 5 && 
      substr($s, $slen-5, 5)==='http:') {
    $slen -= 5;
  } else if ($s[$slen-1]==='p' && $slen > 4 &&
             substr($s, $slen-4, 4)==='http') {
    $slen -= 4;
  } else if ($s[$slen-1]==='t' && $slen > 4 &&
             (substr($s, $slen-3, 4)==='http' || 
              substr($s, $slen-3, 4)===' htt')) {
    $slen -= 3;
  } else if ($s[$slen-1]==='h' && $slen > 4 &&
             substr($s, $slen-1, 4)==='http') {
    $slen -= 1;
  }
  
  //if char immediately before ellipsis would be @$ then trim it
  if ($slen > 0 && contains('@$', $s[$slen-1])) {
    $slen-=1;
  }
 
  //if char before ellipsis would be sentence terminator, trim 2 more
  while ($slen > 1 && contains('.!?', $s[$slen-1])) {
    $slen-=2;
  }

  if ($slen < 1) { // somehow shortened too much
    return $e; // or ellipsis by itself exceeded max, return ellipsis.
  }

  // if last two chars are ': ', omit ellipsis. 
  if ($e==='...' && substr($s, $slen-2, 2)===': ') {
    return substr($s, 0, $slen);
  }

  return strcat(substr($s, 0, $slen), $e);
}

function auto_link_re() {
  return '/(?:\\@[_a-zA-Z0-9]{1,17})|(?:(?:(?:(?:http|https|irc)?:\\/\\/(?:(?:[!$&-.0-9;=?A-Z_a-z]|(?:\\%[a-fA-F0-9]{2}))+(?:\\:(?:[!$&-.0-9;=?A-Z_a-z]|(?:\\%[a-fA-F0-9]{2}))+)?\\@)?)?(?:(?:(?:[a-zA-Z0-9][-a-zA-Z0-9]*\\.)+(?:(?:aero|arpa|asia|a[cdefgilmnoqrstuwxz])|(?:biz|b[abdefghijmnorstvwyz])|(?:cat|com|coop|c[acdfghiklmnoruvxyz])|d[ejkmoz]|(?:edu|e[cegrstu])|f[ijkmor]|(?:gov|g[abdefghilmnpqrstuwy])|h[kmnrtu]|(?:info|int|i[delmnoqrst])|j[emop]|k[eghimnrwyz]|l[abcikrstuvy]|(?:mil|museum|m[acdeghklmnopqrstuvwxyz])|(?:name|net|n[acefgilopruz])|(?:org|om)|(?:pro|p[aefghklmnrstwy])|qa|r[eouw]|s[abcdeghijklmnortuvyz]|(?:tel|travel|t[cdfghjklmnoprtvwz])|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw]))|(?:(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[1-9])\\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[0-9])\\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[0-9])\\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[0-9])))(?:\\:\\d{1,5})?)(?:\\/(?:(?:[!#&-;=?-Z_a-z~])|(?:\\%[a-fA-F0-9]{2}))*)?)(?=\\b|\\s|$)/';
  // ccTLD compressed regular expression clauses (re)created.
  // .mobi .jobs deliberately excluded to discourage layer violations.
  // see http://flic.kr/p/2kmuSL for more on the problematic new gTLDs
  // part of $re derived from Android Open Source Project, Apache 2.0
  // with a bunch of subsequent fixes/improvements (e.g. ttk.me/t44H2)
  // thus auto_link_re is also Apache 2.0 licensed
  //  http://www.apache.org/licenses/LICENSE-2.0
  // - Tantek 2010-046 (moved to auto_link_re 2012-062)
}

// auto_link: param 1: text; param 2: do embeds or not
// auto_link is idempotent, works on plain text or typical markup.
function auto_link() { 
  /// ?> <!--   ///
  var $args, $afterchar, $afterlink, $do_embed, $fe, $i, $isjs, 
      $mi, $mlen, $ms, $re, 
      $sp, $spe, $spliti, $t, $wmi, $yvid; 
  /// --> <?php ///

  $isjs = js();
  $args = $isjs ? arguments : func_get_args();
  if (count($args) === 0) {
    return '';
  }
  $t = $args[0];
  $do_embed = (count($args) > 1) && ($args[1]!==false);

  $re = auto_link_re();
  $ms = preg_matches($re, $t);
  if (!$ms) {
    return $t;
  }

  $mlen = count($ms);
  $sp = preg_split($re, $t);
  $t = "";
  $sp[0] = string($sp[0]); // force undefined to ""
  for ($i=0; $i<$mlen; $i+=1) {
    $mi = $ms[$i];
    $spliti = $sp[$i];
    $t = strcat($t, $spliti);
    $sp[$i+1] = string($sp[$i+1]); // force undefined to ""
    if (substr($sp[$i+1], 0, 1)==='/') { //regex omits end/ before </a
      $sp[$i+1] = substr($sp[$i+1], 1, strlen($sp[$i+1])-1);
      $mi = strcat($mi, '/'); // include / in the match
    }
    $spe = substr($spliti, -2, 2);
    // avoid 2x-linking, don't link CSS @-rules, attr values, asciibet
    if ((!$spe || !preg_match('/(?:\\=[\\"\\\']?|t;)/', $spe)) &&
        substr(trim($sp[$i+1]), 0, 3)!=='</a' && 
        (!contains(
'@charset@font@font-face@import@media@namespace@page@ABCDEFGHIJKLMNOPQ@',
            strcat($mi, '@')))) {
      $afterlink = '';
      $afterchar = substr($mi, -1, 1);
      while (contains('.!?,;"\')]}', $afterchar) && // trim punc from
          ($afterchar!==')' || !contains($mi,'('))) { // 1 () pair
        $afterlink = strcat($afterchar, $afterlink);
        $mi = substr($mi, 0, -1);
        $afterchar = substr($mi, -1, 1);
      }
      
      $fe = 0;
      if ($do_embed) {
        $fe = (substr($mi,-4,1)==='.') ? 
               substr($mi,-4,4) :
               substr($mi,-5,5);
      }
      $wmi = web_address_to_uri($mi, true);
      if ($fe && 
          ($fe === '.jpeg' || $fe === '.jpg' || $fe === '.png' || 
           $fe === '.gif')) {
        $t = strcat($t, '<a class="auto-link figure" href="',      
                    $wmi, '"><img src="', 
                    $wmi, '"/></a>', 
                    $afterlink);
      } else if ($fe && 
                 ($fe === '.mp4' || $fe === '.mov' || $fe === '.ogv'))
      {
        $t = strcat($t, '<a class="auto-link figure" href="',      
                    $wmi, '"><video controls="controls" src="', 
                    $wmi, '"></video></a>', 
                    $afterlink);
      } else if (!strncmp($wmi, 'http://vimeo.com/' ,17) && 
                 ctype_digit(substr($wmi, 17))) {
        $t = strcat($t, '<a class="auto-link" href="',
                    $wmi, '">', $mi, '</a> <iframe class="vimeo-player auto-link figure" width="480" height="385" style="border:0"  src="http://player.vimeo.com/video/', 
                    substr($wmi, 17), '"></iframe>', 
                    $afterlink);
      } else if (!strncmp($wmi, 'http://youtu.be/', 16) ||
                 ((!strncmp($wmi, 'http://youtube.com/', 19) ||
                   !strncmp($wmi, 'http://www.youtube.com/', 23)) &&
                  ($yvid = offset('watch?v=', $mi))!==0)) {
        if (!strncmp($wmi,'http://youtu.be/',16)) {
          $yvid = substr($wmi, 16);
        } else {
          $yvid = explode('&', substr($mi, $yvid+7));
          $yvid = $yvid[0];
        }
        $t = strcat($t, '<a class="auto-link" href="',
                    $wmi, '">', $mi, '</a> <iframe class="youtube-player auto-link figure" width="480" height="385" style="border:0"  src="http://www.youtube.com/embed/', 
                    $yvid, '"></iframe>', 
                    $afterlink);
      } else if ($mi[0]==='@') {
        if ($sp[$i+1][0] == '.' && 
            $spliti != '' &&
            ctype_email_local(substr($spliti, -1, 1))) {
          // if email address, simply append info, no linking
          $t = strcat($t, $mi, $afterlink);
        }
        else {
          // treat it as a Twitter @-username reference and link it
          $t = strcat($t, '<a class="auto-link h-x-username" href="',
                      $wmi, '">', $mi, '</a>', 
                      $afterlink);
        }
      } else {
        $t = strcat($t, '<a class="auto-link" href="',
                    $wmi, '">', $mi, '</a>', 
                    $afterlink);
      }
    } else {
      $t = strcat($t, $mi);
    }
  }
  return strcat($t, $sp[$mlen]);
}


// replace URLs with http://j.mp/0011235813 to mimic Twitter's t.co
function tw_text_proxy() {
  /// ?> <!--   ///
  var $args, $afterchar, $afterlink, $i, $isjs,
      $mlen, $ms, $re, 
      $sp, $spe, $spliti, $prot, $proxy_url; 
  /// --> <?php ///
  
  $isjs = js();
  $args = $isjs ? arguments : func_get_args();
  if (count($args) === 0) {
    return '';
  }
  $t = $args[0];

  $re = auto_link_re();
  $ms = preg_matches($re, $t);
  if (!$ms) {
    return $t;
  }

  $mlen = count($ms);
  $sp = preg_split($re, $t);
  $t = "";
  $sp[0] = string($sp[0]); // force undefined to ""
  for ($i=0; $i<$mlen; $i++) {
    $matchi = $ms[$i];
    $spliti = $sp[$i];
    $t = strcat($t, $spliti);
    $sp[$i+1] = string($sp[$i+1]); // force undefined to ""
    if (substr($sp[$i+1], 0, 1)=='/') { // regex omits '/' before </a
      $sp[$i+1] = substr($sp[$i+1], 1, strlen($sp[$i+1])-1);
      $matchi = strcat($matchi, '/'); // explicitly include in match
    }
    $spe = substr($spliti, -2, 2);
    // avoid double-linking or attr values (*** will twitter do that?)
    // and don't proxy @-names
    if ((!$spe || !preg_match('/(?:\\=[\\"\\\']?|t;)/', $spe)) &&
        substr(trim($sp[$i+1]),0,3)!='</a' && $matchi[0]!='@' &&
        (substr($matchi,-3,1)!='.' || substr_count($matchi, '.')>1)) {
      $afterlink = '';
      $afterchar = substr($matchi, -1, 1);
      while (contains('.!?,;"\')]}', $afterchar) && // trim punc @ end
          ($afterchar!=')' || !contains($matchi,'('))) { 
          // allow one paren pair
          // *** not sure twitter is this smart
          $afterlink = strcat($afterchar, $afterlink);
          $matchi = substr($matchi, 0, -1);
          $afterchar = substr($matchi, -1, 1);
      }
      
      $prot = substr($matchi, 0, 6); // irc:// http:/ https:
      $proxy_url = '';
      if ($prot === 'https:') { 
        $proxy_url = 'https://j.mp/0011235813';
      } else if ($prot === 'irc://') {
        $proxy_url = $matchi; // Twitter doesn't tco irc: URLs
      } else { /* 'http:/' or presumed for schemeless URLs */ 
        $proxy_url = 'http://j.mp/0011235813';
      }
      $t = strcat($t, $proxy_url, $afterlink);
    }
    else {
      $t = strcat($t, $matchi);
    }
  }
  return strcat($t, $sp[$mlen]);
}


// note_length_check:
// checks if $note fits in $maxlen characters.
// if $username is non-empty, checks if RT'd $note fits in $maxlen
// 0 - bad params or other precondition failure error
// 200 - exactly fits max characters with RT if username provided
// 206 - less than max chars with RT if username provided
// 207 - more than RT safe length, but less than tweet max
// 208 - tweet max length but with RT would be over
// 413 - (entity too large) over max tweet length
// strlen('RT @: ') === 6.
function note_length_check($note, $maxlen, $username) {
  /// ?> <!--   ///
  var $note_size_chk_u, $note_size_chk_n;
  /// --> <?php ///

  if ($maxlen < 1) { return 0; }
  
  $note_size_chk_u = $username ? 6 + strlen(string($username)) : 0;
  $note_size_chk_n = strlen(string($note)) + $note_size_chk_u;
  
  if ($note_size_chk_n === $maxlen)                    { return 200; }
  if ($note_size_chk_n < $maxlen)                      { return 206; }
  if ($note_size_chk_n - $note_size_chk_u < $maxlen)   { return 207; }
  if ($note_size_chk_n - $note_size_chk_u === $maxlen) { return 208; }
  return 413;
}

function tw_length_check($t, $maxlen, $username) {
  return note_length_check(tw_text_proxy($t), 
                           $maxlen, $username);
}

function tw_url_to_status_id($u) {
// $u - tweet permalink url
// returns tweet status id string; 0 if not a tweet permalink.
  if (!$u) return 0;
  $u = explode("/", string($u)); // https:,,twitter.com,t,status,nnn
  if ($u[2]!="twitter.com" || 
      $u[4]!="status"      ||
      !ctype_digit($u[5])) {
    return 0;
  }
  return $u[5];
}


// ===================================================================
// end CASSIS v0.1, cassis.js
// ?> -->
