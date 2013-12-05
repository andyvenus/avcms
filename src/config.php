<?php
///////////////////////////////////////////
//   Databaseold (MySQL) Connection Info    //
///////////////////////////////////////////

$server = 'localhost';                   // MySql server
$username = 'root';                      // MySql Username
$password = 'root' ;                         // MySql Password
$database = 'avms';                  // MySql Databaseold

$db_prefex = 'avms_';

///////////////////////////////////////////
// The following does not need be edited //
///////////////////////////////////////////

// Create PHP Databaseold Object
try {   
  # MySQL with PDO_MYSQL  
  $PDO = new PDO("mysql:host=$server;dbname=$database", $username, $password);  
  $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
} 

return $PDO;

/*/ Import & Create AVMS PDO Databaseold Wrapper Object
//include 'includes/class/class_database.php';
$DB = new AVCMS\Core\Databaseold\Databaseold($PDO, $db_prefex);

// Get settings
if (!isset($install)) {
	$settings = $DB->query("SELECT * FROM {$DB->prefix}settings");
	while ($get_setting = $settings->fetch()) {
		$setting[$get_setting['name']] = $get_setting['value'];
	}
}

// Set SMTP Server
$setting['smtp_server'] = 'smtp.gmail.com';
$setting['smtp_port'] = 587;
$setting['smtp_username'] = 'knife.pwns@gmail.com';
$setting['smtp_password'] = 'gmailz33monster1990';

$setting['db_prefix'] = $db_prefex;
*/
?>