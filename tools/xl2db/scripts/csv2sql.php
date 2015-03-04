<?php
	
	if (!isset($argv[1]) || $argv[1] != "run")
	{
		echo "Usage: ./csv2db run\n";
		echo "csv2db will convert all csv files contained in the ./csv directory into tables from the DB specified in config.ini. Camel case CSVs will be converted into underscore deliminated table names.";
	}
	
	$baseDir = dirname(dirname(__FILE__));
	$ini = parse_ini_file($baseDir."/config.ini");

	$configFile = dirname(dirname($baseDir))."/protected/config/local/common.php";
	require_once($configFile);

	$db = $ini["db"]["db"];
	$username = $config["components"]["db"]["username"];
	$password = $config["components"]["db"]["password"];
	$rootUser = $ini["db"]["rootuser"];
	$rootPassword = $ini["db"]["rootPassword"];

        $files = `ls $baseDir/csv`;

        $files = explode("\n", trim($files));

	if (strlen($rootPassword))
		$rootPasswordLine = "--password=$rootPassword";
	else
		$rootPasswordLine = "";

	echo "Disabling foreign keys.\n";
	system("mysql --host=$host --user=$rootUser $rootPasswordLine -e \"SET GLOBAL foreign_key_checks=0\"");

	
	foreach ($files as $file)
	{
		$filename = $baseDir."/csv/".$file;

		echo "Running MySQL import for $file\n";

		$cmd = "mysqlimport --ignore-lines=1  --fields-terminated-by=\";\" --local -u $username --password=$password -f $db $filename";
		echo $cmd."\n";
		system($cmd); 
		echo "\n\n";
	}

	echo "Enabling foreign keys \n";
	system("mysql --host=$host --user=$rootUser $rootPasswordLine -e \"SET GLOBAL foreign_key_checks=0\"");
?>
