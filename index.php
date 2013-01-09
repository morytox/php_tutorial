<?php
$nodb = false;
$string = file_get_contents($_ENV['CRED_FILE'], false);
if ($string == false) {
    die('FATAL: Could not read credentials file');
}

# the file contains a JSON string, decode it and return an associative array
$creds = json_decode($string, true);
if(!$creds['MYSQLS']['MYSQLS_HOSTNAME'])
  {
  $nodb = true;
}
else
  {
  $mysql_hostname=$creds['MYSQLS']['MYSQLS_HOSTNAME'];
  $mysql_port=$creds['MYSQLS']['MYSQLS_PORT'];
  $mysql_database=$creds['MYSQLS']['MYSQLS_DATABASE'];
  $mysql_username=$creds['MYSQLS']['MYSQLS_USERNAME'];
  $mysql_password=$creds['MYSQLS']['MYSQLS_PASSWORD'];
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>( cloudControl Pacman-as-a-Service )</title>
<style media="screen">
        	html {font: 62.5%/20pt "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif;}
        	body {
            background: url('img/noise-grey.gif') repeat scroll 0 0 #313233;
            color: #fff;
            font-family: "Helvetica Neue",Arial,Helvetica,Geneva,sans-serif;
            font-size: 12px;
            font-size: 1.2rem;
            height: 100%;
            margin: 0px;
            padding: 0px;
            text-align: center;
            width: 100%;
          }
          a, a:visited, a:active {color: #00aeef}
          #content{
            background: url('img/logo.png') no-repeat center top;
            display: inline-block;
            margin: 1% auto 0px auto;
            padding: 100px 0px 0px 0px;
            
            /*width: 500px;*/
          }
          form {text-align: center; width:100%;}
          input {font-size: 16px; font-size: 1.6rem;}
          h1 {font-size: 40px; font-size: 4rem;}
          p {font-size: 16px; font-size: 1.6rem;}
          a { text-decoration:none; }
       
        </style>

</head>

<body>
  <div id="content">
	<h2>( cloudControl Pacman-as-a-Service )</h2>

<?php if($nodb){?>
  <p>Sorry bot no database has been configured yet.<br />
    Please set up a database and try again.</p>
<?php }
      else{?>

  <div id="pacman"></div>
  <script src="pacman.js"></script>
  <script src="modernizr-1.5.min.js"></script>

  <script>

    var el = document.getElementById("pacman");

    if (Modernizr.canvas && Modernizr.localstorage && 
        Modernizr.audio && (Modernizr.audio.ogg || Modernizr.audio.mp3)) {
      window.setTimeout(function () { PACMAN.init(el, "./"); }, 0);
    } else { 
      el.innerHTML = "Sorry, needs a decent browser<br /><small>" + 
        "(firefox 3.6+, Chrome 4+, Opera 10+ and Safari 4+)</small>";
nn    }

  </script>
  <?php }?>
  <br/>
    HTML5-Pacman developed by <a href="http://arandomurl.com/">Writeup</a> | <a href="http://arandomurl.com/2010/07/25/html5-pacman.html">Project page</a> |
  Code on <a href="https://github.com/daleharvey/pacman">Github</a><br />
  PHP highscore back-end quickly developed by <a href="http://www.cloudcontrol.com">cloudControl</a>.</div>

</body>
</html>
