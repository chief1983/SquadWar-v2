<?php

require_once 'Image/GraphViz.php';

class nodemap
{
	private $leagueid = null;

	public function __construct($leagueid)
	{
		$this->leagueid = $leagueid;

		$dbh = dbm::get(SITE_CODE, 'pdo');

		$sth = $dbh->prepare(
			'SELECT
				`s`.`SWSectors_ID`,
				`s`.`SectorName`,
				`s`.`Entry_Node`,
				`s`.`League_ID`,
				`sg`.`path_1`,
				`sg`.`path_2`,
				`sg`.`path_3`,
				`sg`.`path_4`,
				`sg`.`path_5`,
				`sq`.`SquadID`,
				`sq`.`SquadName`,
				`sqi`.`Squad_Red`,
				`sqi`.`Squad_Blue`,
				`sqi`.`Squad_Green`,
				`m`.`SWSquad1` AS `challengerid`,
				`m`.`SWSquad2` AS `challengedid`,
				`challenger`.`SquadName` AS `challengername`,
				`challenged`.`SquadName` AS `challengedname`
			FROM `'.SQUADWAR_DB.'`.`'.TABLE_PREFIX.'SWSectors` AS `s`
			LEFT JOIN `'.SQUADWAR_DB.'`.`'.TABLE_PREFIX.'SWSectors_Graph` AS `sg`
			ON `s`.`SWSectors_ID` = `sg`.`SWSectors_ID`
			LEFT JOIN `'.SQUADWAR_DB.'`.`'.TABLE_PREFIX.'SWSquads` AS `sq`
			ON `s`.`SectorSquad` = `sq`.`SquadID`
			LEFT JOIN `'.SQUADWAR_DB.'`.`'.TABLE_PREFIX.'SWSquad_Info` AS `sqi`
			ON `sq`.`SquadID` = `sqi`.`SquadID`
			LEFT JOIN `'.SQUADWAR_DB.'`.`'.TABLE_PREFIX.'SWMatches` AS `m`
			ON `s`.`SWSectors_ID` = `m`.`SWSector_ID`
			LEFT JOIN `'.SQUADWAR_DB.'`.`'.TABLE_PREFIX.'SWSquads` AS `challenger`
			ON `m`.`SWSquad1` = `challenger`.`SquadID`
			LEFT JOIN `'.SQUADWAR_DB.'`.`'.TABLE_PREFIX.'SWSquads` AS `challenged`
			ON `m`.`SWSquad2` = `challenged`.`SquadID`
			WHERE `s`.`League_ID` = '.$leagueid);

		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		$this->gv = new Image_GraphViz(false, array('overlap'=>'scale','bgcolor'=>'transparent'), 'nodemap');

		foreach($result as $row)
		{
			$tooltip = "<span>"."<h3>{$row['SectorName']}</h3><p>";
			$tooltip .= ($row['Entry_Node'] ? "Entry Node<br />" : null);
			$tooltip .= "Owner: " . ($row['SquadName'] ? $this->squadlink($row['SquadID'], $row['SquadName']) : "Unclaimed") . "<br />";
			$tooltip .= ($row['challengerid'] ? "Under challenge from " . $this->squadlink($row['challengerid'], $row['challengername']) . ($row['SquadID'] ? null : " via ".$this->squadlink($row['challengedname'], $row['challengedname'])) : null);
			$tooltip .= "</p></span>";

			$this->gv->addNode(
				$row['SWSectors_ID'],
				array(
					'label' => $row['SectorName'] . ($row['SquadName'] ? "\n{$row['SquadName']}" : ''),
					'style' => 'filled,bold',
					'color' => 'white',
					'fillcolor'=>($row['SquadID'] ? $this->colorstring($row) : 'darkgrey'),
					'fontcolor'=>'white',
					'tooltip' => $tooltip,
					'shape' => ($row['Entry_Node'] ? 'hexagon' : 'oval'),
					'fontname' => 'Arial',
				)
			);

			for($i = 1; $i <= 5; $i++)
			{
				$path = 'path_'.$i;
				if($row[$path])
				{
					$path_sector = $this->get_sector($row[$path], $result);
					$path_color = 'lightgrey';
					$style = null;
					if($row['challengerid'] && $path_sector['SquadID'] == $row['challengerid'])
					{
						$path_color = $this->colorstring($path_sector);
						$style = 'bold,dashed';
					}
					elseif($row['challengedid'] && $path_sector['SquadID'] == $row['challengedid'])
					{
						$path_color = $this->colorstring($path_sector);
						$style = 'bold,dashed';
					}
					elseif($path_sector['challengerid'] && $row['SquadID'] == $path_sector['challengerid'])
					{
						$path_color = $this->colorstring($row);
						$style = 'bold,dashed';
					}
					elseif($path_sector['challengedid'] && $row['SquadID'] == $path_sector['challengedid'])
					{
						$path_color = $this->colorstring($row);
						$style = 'bold,dashed';
					}
					elseif($row['SquadID'] && $path_sector['SquadID'] == $row['SquadID'])
					{
						$path_color = $this->colorstring($row);
						$style = 'bold';
					}
					$this->gv->addEdge(
						array($row['SWSectors_ID'] => $row[$path]),
						array(
							'color'=>$path_color,
							'style'=>$style,
						)
					);
				}
			}
		}
	}

	public function output_image($type)
	{
		$this->gv->image($type, 'neato');
	}

	public function colorstring($row)
	{
		return '#'.substr('00'.dechex((int)$row['Squad_Red']), -2).substr('00'.dechex((int)$row['Squad_Green']), -2).substr('00'.dechex((int)$row['Squad_Blue']), -2);
	}

	public function fetch_graph($type)
	{
		return $this->gv->fetch($type, 'neato');
	}

	private function squadlink($squadid, $squadname)
	{
		return '<a href="'.RELATIVEPATH.'squads/squadinfo.php?id='.$squadid.'&amp;leagueid='.$this->leagueid.'">'.$squadname.'</a>';
	}

	private function get_sector($sector, $rows)
	{
		foreach($rows as $row)
		{
			if($row['SWSectors_ID'] == $sector)
			{
				return $row;
			}
		}
	}
}
