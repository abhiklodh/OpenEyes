<?php

	if (!isset($argv[1]) || $argv[1] != "run")
	{
		echo "Usage: ./scan run\n\n";
		echo "Projects.txt should contain a per-line list of the OpenEyes projects (name only) to checkout and generate reports for. \n";
		echo "Please make sure a user-writable directory 'projects' exists in the same dir as ./scan for project clones to be created in.";
		die;
	}

	$baseDir = dirname(dirname(__FILE__));	

	require_once(dirname($baseDir)."/lib/phpexcel/Classes/PHPExcel.php");
	$objPHPExcel = new PHPExcel();

	$lines = explode("\n", trim(file_get_contents("projects.txt")));
	foreach ($lines as $projectName)
	{
		if (strlen($projectName) <= 1)
			continue;

		$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, substr($projectName,0,31));

		$out = array();
		$s_out = "";
		//checkout project
		if (!is_dir($baseDir."/projects/$projectName"))
		{
			$cmd = "cd {$baseDir}/projects && git clone https://github.com/openeyes/$projectName.git";
			echo "Cloning $projectName\n";
			system($cmd);
		}

		echo "Cloned $projectName.\n\n";
		echo "Processing...\n";
		//list branches of project
		$allBranchArr =  getProcessedBranchList($projectName, "-a");
		$masterMergedArr = getProcessedBranchList($projectName, "-a --merged  master"); 
		
		//check which branches have been merged
	
		foreach ($allBranchArr as $branch)
		{
			$mergedMaster = in_array($branch, $masterMergedArr);

			if ($mergedMaster)
				$mergedPretty = "yes";
			else
				$mergedPretty = "no";
			
			$cmd = "cd {$baseDir}/projects/$projectName && git log $branch";
			$log = explode("\n", `$cmd`);

			if (count($log) < 4)
				continue;

			$desc = trim($log[4]);
			$desc = str_replace("\"", "'", $desc);
				$col = array("project" => $projectName, "branch" => $branch, "mergedMaster" => $mergedPretty, "description" => $desc);
			$out[] = $col;

			$s_out = $s_out."\"".implode("\",\"", $col)."\"\n";
		}	

		file_put_contents($baseDir."/output/".$projectName, $s_out);

		if (count($out))
		{
			$myWorkSheet->fromArray($out, NULL, "A1");	
			$objPHPExcel->addSheet($myWorkSheet);
		}
		echo "Processed $projectName\n\n\n";
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
		$objWriter->save("git_branches.xlsx");

	}


	function getProcessedBranchList($projectName, $params)
	{
		$baseDir = dirname(dirname(__FILE__));
		$cmd = "cd {$baseDir}/projects/$projectName && git branch $params";

		$branches = `$cmd`;
                $branchArr = explode("\n", trim($branches));
                unset($branchArr[0]);
                unset($branchArr[1]);
                foreach ($branchArr as $k => $branchName)
                        $branchArr[$k] = trim($branchName);

		return $branchArr;
	}

?>
