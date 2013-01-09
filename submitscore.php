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


function __autoload($class_name)
{
    // Customize this to your root Flourish directory
    $flourish_root = $_SERVER['DOCUMENT_ROOT'] . '/flourish/';
    
    $file = $flourish_root . $class_name . '.php';
 
    if (file_exists($file)) {
        include $file;
        return;
    }
    
    throw new Exception('The class ' . $class_name . ' could not be loaded');
}


function renderForm($error='')
{
?>	<!DOCTYPE html>
	<html lang="en">
    	<head>
        	<meta charset="utf-8" />
        	<title>cloudControl Highscore Submitter</title>
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
          .smallscriptcontainer {width: 100%; text-align:center;}
          .smallscript {width: 600px; font-size:10pt; line-height:12px; text-align:left;}
        </style>
    	</head>
    	<body>
    	<div id="content" class="box">
<?php	
	
    if($error!='') { echo "<p>".$error."</p>"; }
	echo "<form method=post action=submitscore.php><h1>( Submit your score: ". $_COOKIE["hsk4shhca"] ." points )</h1><p>";	
	echo "Nickname (public):<br/> <input type=text name=nick value='".$_POST['nick']."'><br/>";
	echo "Real Name (non-public, not required): <br/><input type=text name=realname value='".$_POST['realname']."'><br/>";
	echo "eMail (non-public, not required): <br/><input type=text name=email value='".$_POST['email']."'><br/>";
	echo "<input type=hidden name='trans' value='thisisacow'><br/>";
	echo "<br/> <div class=smallscriptcontainer><div class=smallscript>cloudControl loves developers and therefore we care about your personal info and we will only use the entered data for purpose of this game's hightscore list. If you want cloudControl to contact you, please sign up for the newsletter or follow us on Facebook or Twitter.</div></div><br/>";
	echo "<input type=submit value='Click here to submit your score!'></p>";
 ?></div></body></html><?php
}



// Three steps
// 1.) Get the highscore out of the cookie and save to a variable to embed in the form
// 2.) Check whether all form entries are there 
// 3.) Write form to db, delete cookie and display hightscores.php

// Cookie (kinda lame cookie protection)
if ($_COOKIE["hsk4shhca"]>0) 
{ 
	// Save score
	$score=$_COOKIE["hsk4shhca"]; 
}
else
{
	die("Sorry, pacman didn't tell me how you scored... :-(");
};



// Highscore submitted by POST
//if (!isset($score)&&$_POST["hsk4shhca"]>0) { $score=$_COOKIE["hsk4shhca"]}:


// Check if this is a POST-Request
if($_POST['trans']=='thisisacow')
{
	try {
    	$validator = new fValidation();    
    	$validator->addRequiredFields('nick');
    	//$validator->addEmailFields('email'); 
    	$validator->validate();
    	// Here would be the action of the contact form, such as sending an email
    	$mysqlconnection = mysql_connect($mysql_hostname.':'.$mysql_port, $mysql_username, $mysql_password);
    	if (!$mysqlconnection) {
	    	die('MySQL Connection failed: ' . mysql_error());
	    }
	    mysql_select_db($mysql_database,$mysqlconnection);
    	if(!is_numeric($score)) { die("This is not a score!"); };
    	
    	$esc_nick=mysql_real_escape_string($_POST['nick'],$mysqlconnection);
    	$esc_email=mysql_real_escape_string($_POST['email'],$mysqlconnection);
    	$esc_realname=mysql_real_escape_string($_POST['realname'],$mysqlconnection);
    	
    	$datesubmitted=date("Y-m-d H:i:s");

    	$query=sprintf("INSERT INTO highscores (nick, email, realname, score, datesubmitted) VALUES ('%s','%s','%s',%s,'%s')",$esc_nick,$esc_email,$esc_realname,$score,$datesubmitted);


    	$result=mysql_query($query,$mysqlconnection);
    	if(!$result)
    	{
	    	die("MySQL Error ".mysql_error());
    	}
    	mysql_close($mysqlconnection);
    	
    	// delete cookie
    	setcookie ("hsk4shhca", "", time() - 3600);
    	// Redirect to highscore list
    	header('Location: highscores.php');

    	} catch (fValidationException $e) {
	    	// Render form
	    	renderForm("Please fill in nickname, your real name and your eMail address!");
	 }
}
// Render form
else
{
	//echo "First print form";
	renderForm();
}






?>