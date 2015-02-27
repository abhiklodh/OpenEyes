<?php

	$db = "openeyes";
	$username = "openeyes";
	$password = "oe_test";

	
        $files = `ls csv`;

        $files = explode("\n", trim($files));
	
	foreach ($files as $file)
	{
		$filename = dirname(__FILE__)."/csv/".$file;

		echo "Running MySQL import for $file\n";

		$cmd = "mysqlimport --ignore-lines=1  --fields-terminated-by=; --local -u $username -p $database $filename";
		system($cmd); 
	}

?>
