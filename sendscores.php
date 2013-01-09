<?php

# read the credentials file
$string = file_get_contents($_ENV['CRED_FILE'], false);
if ($string == false) {
    die('FATAL: Could not read credentials file');
}

# the file contains a JSON string, decode it and return an associative array
$creds = json_decode($string, true);

# use credentials to set the configuration for your Add-on
# replace ADDON_NAME and PARAMETER with Add-on specific values

$mysql_hostname=$creds['MYSQLS']['MYSQLS_HOSTNAME'];
$mysql_port=$creds['MYSQLS']['MYSQLS_PORT'];
$mysql_database=$creds['MYSQLS']['MYSQLS_DATABASE'];
$mysql_username=$creds['MYSQLS']['MYSQLS_USERNAME'];
$mysql_password=$creds['MYSQLS']['MYSQLS_PASSWORD'];


		$mysqlconnection = mysql_connect($mysql_hostname.':'.$mysql_port, $mysql_username, $mysql_password);
    	if (!$mysqlconnection) {
	    	die('MySQL Connection failed: ' . mysql_error());
	    }
    	mysql_select_db($mysql_database,$mysqlconnection);
    	$query="SELECT nick, score,realname, email FROM highscores ORDER BY score DESC";
    	$result=mysql_query($query,$mysqlconnection);
    	if(!$result)
    	{
	    	die("MySQL-Error: ".mysql_error());
    	}
    	mysql_close($mysqlconnection);


?>

<html lang="en">
    	<head>
        	<meta charset="utf-8" />
        	<title>cloudControl Highscores</title>
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
            padding: 110px 0px 0px 0px;
            
            /*width: 500px;*/
          }
          form {text-align: center; width:100%;}
          input {font-size: 16px; font-size: 1.6rem;}
          h1 {font-size: 40px; font-size: 4rem;}
          p {font-size: 16px; font-size: 1.6rem;}
           .smallscript {width: 500px; font-size:10pt; line-height:12px; text-align:center;}
          table { padding: 2px; margin: 2px }
          td { background-color:#666666; padding: 3px; }
          .nick { width:200px;}
        </style>
    	</head>
    	<body>
    	<div id="content" class="box">
    	<h2>( cloudControl Highscores )</h2>
    	<table>
    	<thead>Today's top ten scores</thead>
    	<tr><td>Position</td><td>Score</td><td>Nickname</td><td>Realname</td><td>eMail</td></tr>
<?php	
	$i=0;
	while($row=mysql_fetch_array($result))
	{
		$i++;	
		printf("<tr><td align=center>%s</td><td>%s</td><td class=nick>%s</td><td>%s</td><td>%s</td></tr>",$i,$row[1],$row[0],$row[2],$row[3]);
	}
 ?></table><a href="/">Beat them!</a></div></body></html>