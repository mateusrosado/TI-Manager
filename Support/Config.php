<?php 

define("ENVIRONMENT", "development");
date_default_timezone_set('America/Fortaleza');

$config = array();
if(ENVIRONMENT == 'development'){
	define("CONF_DB_NAME", "ti_manager_db");
	define("CONF_DB_HOST", "localhost");
	define("CONF_DB_USER", "root");
	define("CONF_DB_PASS", "");	
	$config['options'] = [
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
		PDO::ATTR_CASE => PDO::CASE_NATURAL
	];

	// define("BASE_URL", "http://192.168.18.9/ti_manager/");
	define("BASE_URL_ADMIN", BASE_URL."Admin");
	
	define("TEMPLATE", "default");
	define("TEMPLATE_ADMIN", "admin");

	define("DATE_BR", "d/m/Y H:i:s");
	define("DATE_APP", "Y-m-d H:i:s");	

	define("CONF_PASSWD_MIN_LEN", 8);
	define("CONF_PASSWD_MAX_LEN", 40);
		
	define("CONF_MESSAGE_CLASS", "alert");
	define("CONF_MESSAGE_INFO", "alert-info");
	define("CONF_MESSAGE_SUCCESS", "alert-success");
	define("CONF_MESSAGE_WARNING", "alert-warning");
	define("CONF_MESSAGE_ERROR", "alert-danger");
	
	define("CONF_MAIL_HOST", "smtp.hostinger.com");
	define("CONF_MAIL_PORT", 587);

	define("CONF_MAIL_OPTION_LANG", "br");
	define("CONF_MAIL_OPTION_HTML", true);
	define("CONF_MAIL_OPTION_AUTH", true);
	define("CONF_MAIL_OPTION_SECURE", "ENCRYPTION_STARTTLS");
	define("CONF_MAIL_OPTION_CHARSET", "utf-8");
	
	define("CONF_SESSION_NAME", "ti_manager");
} else{
	 
}
global $db;
try{
	$db = new PDO(
		"mysql:dbname=".CONF_DB_NAME.";charset=utf8;host=".CONF_DB_HOST, 
		CONF_DB_USER, 
		CONF_DB_PASS
	);
} catch(PDOException $e){
	echo "Erro: ".$e->getMessage();
	exit;
}
