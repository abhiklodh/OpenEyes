<?php
	$base_dir = dirname(__FILE__);	

	$lines = explode("\n", file_get_contents("projects.txt"));
	foreach ($lines as $projectName)
	{
		$out = array();
		$s_out = "";
		//checkout project
		if (!is_dir(dirname(__FILE__)."/projects/$projectName"))
		{
			$cmd = "cd {$base_dir}/projects && git clone https://github.com/openeyes/$projectName.git";
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
			
			$cmd = "cd {$base_dir}/projects/$projectName && git log $branch";
			$log = explode("\n", `$cmd`);
			$desc = trim($log[4]);
			$desc = str_replace("\"", "'", $desc);
			$col = array("project" => $projectName, "branch" => $branch, "mergedMaster" => $mergedMaster, "description" => $desc);
			$out[] = $col;

			$s_out = $s_out."\"".implode("\",\"", $col)."\"\n";
		}	

		file_put_contents(dirname(__FILE__)."/".$projectName, $s_out);
		echo "Processed $projectName\n\n\n";
	}


	function getProcessedBranchList($projectName, $params)
	{
		$base_dir = dirname(__FILE__);
		$cmd = "cd {$base_dir}/projects/$projectName && git branch $params";

		$branches = `$cmd`;
                $branchArr = explode("\n", trim($branches));
                unset($branchArr[0]);
                unset($branchArr[1]);
                foreach ($branchArr as $k => $branchName)
                        $branchArr[$k] = trim($branchName);

		return $branchArr;
	}

?>
