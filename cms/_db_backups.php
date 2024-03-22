<?php

	$folder = "_db_backups";
	$date = date("Y-m-d");

	$filesList = eFile::explore_folder($folder);
	$nb_files = count($filesList);
		
	$done_backup = false;
	for ($f=0;$f<$nb_files;$f++) {
		//echo substr($filesList[$f], 0, 10)." ".$date;
		if (substr($filesList[$f], 0, strlen($date))==$date) {
			$done_backup = true;
			break;			
		}
	}
			
	if (!isset($force_backup)) { $force_backup = false; }
			
	if (!$done_backup || $force_backup == true) {
		$result = eMain::$sql->backup('prefix');
		if ($result) { echo "Backup de la DB réussi !"; }
	}	

?>