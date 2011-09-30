<?php
include('../conf/config.php');

$league = 4;
$lines = file('sectors.txt');

$sector_inserts = array();
$path_inserts = array();
$sectors = array();
$pathsectors = array();

foreach($lines as $sector)
{
	if(trim($sector) == '')
	{
		continue;
	}
	$parts = explode("\t", trim($sector));
	$sector_info = explode(',', trim($parts[0]));
	$sectorname = trim($sector_info[0]);
	$entrynode = trim($sector_info[1]);
	$sectors[] = $sectorname;

	$sector_inserts[] = "INSERT INTO `".SQUADWAR_DB."`.`SWSectors` (`SectorName`,`Entry_Node`,`League_ID`) VALUES ('{$sectorname}',{$entrynode},{$league});\n";
	if(!empty($parts[1]))
	{
		$paths = explode(',', trim($parts[1]));
		$fields = '';
		$path_selects = '';

		foreach($paths as $key => $path)
		{
			$path = trim($path);
			$pathsectors[] = $path;
			$fields .= ", `path_".($key+1)."`";
			$path_selects .= ", (SELECT `SWSectors_ID` FROM `".SQUADWAR_DB."`.`SWSectors` WHERE `League_ID` = {$league} AND `SectorName` = '{$path}')";
		}
		$path_inserts[] = "INSERT INTO `".SQUADWAR_DB."`.`SWSectors_Graph` (`SWSectors_ID`{$fields}) VALUES ((SELECT `SWSectors_ID` FROM `".SQUADWAR_DB."`.`SWSectors` WHERE `League_ID` = {$league} AND `SectorName` = '{$sectorname}'){$path_selects});\n";
	}
}

foreach($pathsectors as $sectorname)
{
	if(!in_array($sectorname, $sectors))
	{
		echo "Warning: found sector {$sectorname} in paths, not in main list of sectors.\n";
	}
}

echo implode('', $sector_inserts);
echo implode('', $path_inserts);
?>
