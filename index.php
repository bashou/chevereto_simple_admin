<?php

# Set URL of your site
$site_url = "static.ananass.fr/uploadfr";

# Set path to your Chevereto installation
$site_root = "/space/www/uploadfr.com/current";

include_once("functions.php");
include_once($site_root."/includes/config.php");


$hote_mysql = $config['db_host'];
$base_mysql = $config['db_name'];
$user_mysql = $config['db_user'];
$pass_mysql = $config['db_pass'];

try
{
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $bdd = new PDO('mysql:host='.$hote_mysql.';dbname='.$base_mysql, $user_mysql, $pass_mysql);
}
catch (Exception $e)
{
        die('Error : ' . $e->getMessage());
}


echo "<html>";

echo "<head>";
echo "<style>

TABLE { 
float : left; 
margin-left : 5px; 
} 

</style>";

echo "</head>";


echo "<body>";

if($_GET['ip'])
{
	echo "<center><b>Tracking ip : ".$_GET['ip']."</b><br/>";
	echo "<a href='javascript:history.back()'>return</a>";
	echo "</center><br/>";
	echo chv_latest($bdd,$_GET['ip']);
	echo chv_gallery($bdd,$_GET['ip'],$site_url);
} else {
	echo chv_stats($bdd);
	echo chv_ip($bdd);
	echo chv_gallery($bdd,$_GET['ip'],$site_url);
}

echo "</body>";
echo "</html>";
?>

