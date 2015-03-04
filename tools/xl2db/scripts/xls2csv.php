<?php
	function camelToUnderscore($s)
        {
            $name = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $s));
            return $name;
        }

	$baseDir = dirname(dirname(__FILE__));
	require_once(dirname($baseDir)."/lib/phpexcel/Classes/PHPExcel.php");
	
	// Create new PHPExcel object
	$files = `ls $baseDir/xl`;

	$files = explode("\n", trim($files));

	$skip = 0;
	if (isset($argv[1]) && is_numeric($argv[1]))
		$skip = $argv[1];

	$n = 0;	
	foreach ($files as $file)
	{
		if ($skip)
		{
			if ($n < $skip)
			{
				$n++;
				continue;
			}
		}

		try
		{
			$filename = $baseDir."/xl/$file";
			echo "Opening $filename\n";
			$xl  = PHPExcel_IOFactory::load($filename);
		
			$worksheetCount = $xl ->getSheetCount();
		}
		catch (Exception $e1)
		{
			echo "Error opening $file. Skipping...\n";
		}

		for ($i = 0; $i < $worksheetCount; $i++)
		{
			try
			{
				echo "Opening sheet $i\n";
				$sheet = $xl->getSheet($i);
				$name = $sheet->getTitle();
				$objWriter = new PHPExcel_Writer_CSV($xl);
				$objWriter->setDelimiter(';');
				$objWriter->setEnclosure('"');
				$objWriter->setLineEnding("\n");
				$objWriter->setSheetIndex($i);

				$name2 = camelToUnderscore($name);

				$saveFilename = $baseDir."/csv/".$name2;

				if (file_exists($saveFilename))
					$saveFilename = $saveFilename.time();

				echo "Saving sheet $name from $file\n";
				$objWriter->save($saveFilename);		
				echo "Saved $name from $file\n\n";	
			}
			catch (Exception $e2)
			{
				echo "Error processing sheet $i. Skipping...\n";
			}
		}	
	}
?>
