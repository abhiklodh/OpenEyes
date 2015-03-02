<?php

	$db = "openeyes";
	$username = "openeyes";
	$password = "oe_test";

	
        $files = `ls csv`;

        $files = explode("\n", trim($files));

	echo "Disabling foreign keys.\n";
	system("mysql --host=localhost --user=root -e \"SET GLOBAL foreign_key_checks=0\"");

	
	foreach ($files as $file)
	{
		$filename = dirname(__FILE__)."/csv/".$file;

		echo "Running MySQL import for $file\n";

		$cmd = "mysqlimport --ignore-lines=1  --fields-terminated-by=\";\" --local -u $username --password=$password -f $db $filename";
		echo $cmd."\n";
		system($cmd); 
		echo "\n\n";
	}

	echo "Enabling foreign keys \n";
	system("mysql --host=localhost --user=root -e \"SET GLOBAL foreign_key_checks=0\"");
?>
