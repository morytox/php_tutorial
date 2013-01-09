<?php
/*
// Debug credentials
$mysql_database="depzrrkn773";
$mysql_hostname="mysqlsdb.co8hm2var4k9.eu-west-1.rds.amazonaws.com";
$mysql_port="3306";
$mysql_password="c%BIZ#3ddV8V";
$mysql_username="depzrrkn773";
*/
# read the credentials file
$string = file_get_contents($_ENV['CRED_FILE'], false);
if ($string == false) {
    die('FATAL: Could not read credentials file');
}

# the file contains a JSON string, decode it and return an associative array
$creds = json_decode($string, true);

$mysql_hostname=$creds['MYSQLS']['MYSQLS_HOSTNAME'];
$mysql_port=$creds['MYSQLS']['MYSQLS_PORT'];
$mysql_database=$creds['MYSQLS']['MYSQLS_DATABASE'];
$mysql_username=$creds['MYSQLS']['MYSQLS_USERNAME'];
$mysql_password=$creds['MYSQLS']['MYSQLS_PASSWORD'];


$where_clause="";
// Which scores do we display
if ($_GET['disp']=='today')
{
	$display="Today's";
	$where_clause=" WHERE datesubmitted >= '".date('Y-m-d').' 00:00:00'."' AND datesubmitted< '".date('Y-m-d').' 23:59:59'."' ";
}
else if ($_GET['disp']=="week")
{
	$display="This week's";
	/*$time=mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
	$where_clause=" WHERE datesubmitted >= '".date('Y-m-d').' 00:00:00'."' AND datesubmitted< '".date('Y-m-d').' 23:59:59'."' ";*/
}
else if ($_GET['disp']=="month")
{
	$display="This month's";
	$where_clause=" WHERE datesubmitted >= '".date('Y-m').'-01 00:00:00'."' AND datesubmitted< '".date('Y-m-d').' 23:59:59'."' ";
}
else if ($_GET['disp']=='alltime')
{
	$display="All time";
}
else 
{
	$display="This month's";
	$where_clause=" WHERE datesubmitted >= '".date('Y-m').'-01 00:00:00'."' AND datesubmitted< '".date('Y-m-d').' 23:59:59'."' ";

}

$mysqlconnection = mysql_connect($mysql_hostname.':'.$mysql_port, $mysql_username, $mysql_password);
if (!$mysqlconnection) {
   	die('MySQL Connection failed: ' . mysql_error());
}
mysql_select_db($mysql_database,$mysqlconnection);

$query="SELECT  nick, max(score) FROM highscores ".$where_clause." GROUP BY nick ORDER BY max(score) DESC LIMIT 10 ;";
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
        	<title>cloudControl <?php echo $display; ?> Leaderboard</title>
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
    	<h2>( cloudControl Leaderboard )</h2>
    	<p>
    		Display <a href="highscores.php?disp=today">Today</a> | <a href="highscores.php?disp=month">This month</a> | <a href="highscores.php?disp=alltime">All time</a>
    	</p>
    	<table>
    	<thead><?php echo $display; ?> top ten players</thead>
    	<tr><td>Position</td><td>Score</td><td>Nickname</td></tr>
<?php	
	$i=0;
	while($row=mysql_fetch_array($result))
	{
		$i++;	
		printf("<tr><td align=center>%s</td><td align=right>%s</td><td class=nick>%s</td></tr>",$i,$row[1],$row[0]);
	}
 ?></table><a href="/">Beat them!</a></div></body></html>