SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `squadwar`
--

-- --------------------------------------------------------

--
-- Table structure for table `form_connection_type`
--

CREATE TABLE IF NOT EXISTS `form_connection_type` (
  `ID` int(11) unsigned NOT NULL,
  `type` varchar(45) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `form_connection_type`
--

INSERT INTO `form_connection_type` (`ID`, `type`) VALUES
(10, '28.8'),
(20, '56K'),
(30, 'ISDN'),
(40, 'Cable'),
(50, 'ADSL'),
(60, 'T1+'),
(0, 'n/a');

-- --------------------------------------------------------

--
-- Table structure for table `form_time_zones`
--

CREATE TABLE IF NOT EXISTS `form_time_zones` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value_hours` int(11) NOT NULL DEFAULT '0',
  `value_minutes` int(11) NOT NULL DEFAULT '0',
  `Description` varchar(144) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

--
-- Dumping data for table `form_time_zones`
--

INSERT INTO `form_time_zones` (`ID`, `value_hours`, `value_minutes`, `Description`) VALUES
(1, -12, 0, 'Eniwetok, Kwajalein'),
(2, -11, 0, 'Midway Island, Samoa'),
(3, -10, 0, 'Hawaii'),
(4, -9, 0, 'Alaska'),
(5, -8, 0, 'Pacific Time (US & Canada); Tijuana'),
(6, -7, 0, 'Arizona'),
(7, -7, 0, 'Mountain Time (US & Canada)'),
(8, -6, 0, 'Central Time (US & Canada)'),
(9, -6, 0, 'Mexico City, Tegucigalpa'),
(10, -6, 0, 'Saskatchewan'),
(11, -5, 0, 'Bogota, Lima Quito'),
(12, -5, 0, 'Eastern Time (US & Canada)'),
(13, -5, 0, 'Indiana (East)'),
(14, -4, 0, 'Atlantic Time (Canada)'),
(15, -4, 0, 'Caracas, La Paz'),
(16, -3, -30, 'Newfoundland'),
(17, -3, 0, 'Brasilia'),
(18, -3, 0, 'Buenos Aires, Georgetown'),
(19, -2, 0, 'Mid-Atlantic'),
(20, -1, 0, 'Azores, Cape Verde Is.'),
(21, 0, 0, 'Casablanca, Monrovia'),
(22, 0, 0, 'GMT: Dublin, Edinburgh, Lisbon, London'),
(23, 1, 0, 'Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'),
(24, 1, 0, 'Belgrade, Bratislava, Budapest, Ljubljana, Prague'),
(25, 1, 0, 'Brussels, Copenhagen, Madrid, Paris, Vilnius'),
(26, 1, 0, 'Sarajevo, Skopje, Sofija, Warsaw, Zagreb'),
(27, 2, 0, 'Athens, Istanbul, Minsk'),
(28, 2, 0, 'Bucharest'),
(29, 2, 0, 'Cairo'),
(30, 2, 0, 'Harare, Pretoria'),
(31, 2, 0, 'Helsinki, Riga, Talinn'),
(32, 2, 0, 'Israel'),
(33, 3, 0, 'Baghdad, Kuwait, Riyadh'),
(34, 3, 0, 'Moscow, St. Petersburg, Volgograd'),
(35, 3, 0, 'Nairobi'),
(36, 3, 30, 'Tehran'),
(37, 4, 0, 'Abu Dhabi, Muscat'),
(38, 4, 0, 'Baku, Tbilisi'),
(39, 4, 0, 'Kabul'),
(40, 5, 0, 'Ekaterinburg'),
(41, 5, 0, 'Islamabad, Karachi, Tashkent'),
(42, 5, 30, 'Bombay, Calcutta, Madras, New Delhi'),
(43, 6, 0, 'Almaty, Dhaka'),
(44, 6, 0, 'Colombo'),
(45, 7, 0, 'Bangkok, Hanoi, Jakarta'),
(46, 8, 0, 'Beijing, Chongqing, Hong Kong, Urumqi'),
(47, 8, 0, 'Perth'),
(48, 8, 0, 'Singapore'),
(49, 8, 0, 'Taipei'),
(50, 9, 0, 'Osaka, Sapporo, Tokyo'),
(51, 9, 0, 'Seoul'),
(52, 9, 0, 'Yakutsk'),
(53, 9, 30, 'Adelaide'),
(54, 9, 30, 'Darwin'),
(55, 10, 0, 'Brisbane'),
(56, 10, 0, 'Canberra, Melbourne, Sydney'),
(57, 10, 0, 'Guam, Port Moresby'),
(58, 10, 0, 'Hobart'),
(59, 10, 0, 'Vladivostok'),
(60, 11, 0, 'Magadan, Soloman Is., New Caledonia'),
(61, 12, 0, 'Auckland, Wellington'),
(62, 12, 0, 'Fiji, Kamchatka, Marshall Is.'),
(64, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `match_history`
--

CREATE TABLE IF NOT EXISTS `match_history` (
  `MatchID` int(10) unsigned NOT NULL,
  `SWCode` varchar(50) NOT NULL,
  `SWSquad1` int(11) NOT NULL,
  `SWSquad2` int(11) NOT NULL,
  `SWSector_ID` varchar(50) NOT NULL,
  `match_victor` int(11) NOT NULL DEFAULT '0',
  `match_loser` int(11) NOT NULL DEFAULT '0',
  `match_time` int(11) NOT NULL DEFAULT '0',
  `League_ID` int(11) NOT NULL DEFAULT '0',
  `special` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`MatchID`),
  UNIQUE KEY `SWCode` (`SWCode`),
  KEY `League_ID` (`League_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Squad_Signup_Survey`
--

CREATE TABLE IF NOT EXISTS `Squad_Signup_Survey` (
  `Squadid` int(10) unsigned NOT NULL,
  `Answer1` varchar(50) NOT NULL,
  `Answer2` varchar(50) NOT NULL,
  `Answer3` varchar(50) NOT NULL,
  `Answer4` varchar(50) NOT NULL,
  `Answer5` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SWLeagues`
--

CREATE TABLE IF NOT EXISTS `SWLeagues` (
  `League_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Title` varchar(75) NOT NULL,
  `Description` text,
  `Active` tinyint(1) NOT NULL DEFAULT '1',
  `Archived` tinyint(1) NOT NULL DEFAULT '0',
  `Closed` tinyint(1) NOT NULL DEFAULT '0',
  `challenged_max` int(11) NOT NULL DEFAULT '0',
  `map_location` varchar(150) NOT NULL,
  `map_graphics` varchar(255) NOT NULL,
  PRIMARY KEY (`League_ID`),
  KEY `Active` (`Active`),
  KEY `Archived` (`Archived`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SWMatches`
--

CREATE TABLE IF NOT EXISTS `SWMatches` (
  `MatchID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SWCode` varchar(50) NOT NULL,
  `SWSquad1` int(11) NOT NULL,
  `SWSquad2` int(11) NOT NULL,
  `SWSector_ID` int(11) NOT NULL,
  `SWDeleteTime` int(11) NOT NULL,
  `League_ID` int(11) NOT NULL,
  PRIMARY KEY (`MatchID`),
  UNIQUE KEY `SWCode` (`SWCode`),
  KEY `League_ID` (`League_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SWMatches_Info`
--

CREATE TABLE IF NOT EXISTS `SWMatches_Info` (
  `MatchID` int(10) unsigned NOT NULL,
  `SWCode` varchar(50) NOT NULL,
  `match_time1` datetime DEFAULT NULL,
  `match_time2` datetime DEFAULT NULL,
  `proposed_final_time` datetime DEFAULT NULL,
  `proposed_alternate_time` datetime DEFAULT NULL,
  `squad_last_proposed` int(11) DEFAULT NULL,
  `final_match_time` datetime DEFAULT NULL,
  `time_created` datetime NOT NULL,
  `Mission` varchar(50) DEFAULT NULL,
  `Pilots` varchar(50) DEFAULT NULL,
  `AI` varchar(50) DEFAULT NULL,
  `dispute` tinyint(1) NOT NULL DEFAULT '0',
  `status_last_changed` datetime DEFAULT NULL,
  `swsquad1_reports_noshow` tinyint(1) DEFAULT NULL,
  `swsquad1_noshow_datetime` datetime DEFAULT NULL,
  `swsquad2_reports_noshow` tinyint(1) DEFAULT NULL,
  `swsquad2_noshow_datetime` datetime DEFAULT NULL,
  `swsquad1_protest` tinyint(1) DEFAULT NULL,
  `swsquad1_protest_datetime` datetime DEFAULT NULL,
  `swsquad2_protest` tinyint(1) DEFAULT NULL,
  `swsquad2_protest_datetime` datetime DEFAULT NULL,
  `mail_sent` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`MatchID`),
  UNIQUE KEY `SWCode` (`SWCode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SWPilots`
--

CREATE TABLE IF NOT EXISTS `SWPilots` (
  `PilotID` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `ICQ` varchar(50) NOT NULL DEFAULT '',
  `connection_type` int(10) unsigned NOT NULL DEFAULT '0',
  `time_zone` int(11) NOT NULL DEFAULT '0',
  `Member_Since` datetime NOT NULL,
  `show_email` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(50) NOT NULL,
  `Pilot_Name` varchar(75) NOT NULL,
  `Recruitme` tinyint(1) NOT NULL DEFAULT '1',
  `Special` int(11) DEFAULT NULL,
  `Active` tinyint(1) NOT NULL,
  PRIMARY KEY (`PilotID`),
  UNIQUE KEY `UserID_Name` (`user_id`,`Pilot_Name`),
  KEY `Active` (`Active`),
  KEY `Recruitme` (`Recruitme`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SWSectors`
--

CREATE TABLE IF NOT EXISTS `SWSectors` (
  `SWSectors_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SectorName` varchar(50) NOT NULL,
  `SectorSquad` int(11) NOT NULL DEFAULT '0' COMMENT 'What quad controls this sector or 0 if none',
  `SectorTime` int(11) NOT NULL DEFAULT '0' COMMENT 'Last time this sector was contested',
  `Entry_Node` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is this node an entry node?',
  `League_ID` int(10) unsigned NOT NULL COMMENT 'What league this belongs to',
  `Value` int(11) NOT NULL DEFAULT '0' COMMENT 'Point Value (if any)',
  `Graph` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Sector belongs to a graph based map',
  `Active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`SWSectors_ID`),
  UNIQUE KEY `SectorName` (`SectorName`,`League_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SWSectors_Graph`
--

CREATE TABLE IF NOT EXISTS `SWSectors_Graph` (
  `SWSectors_ID` int(10) unsigned NOT NULL,
  `path_1` int(11) unsigned NOT NULL DEFAULT '0',
  `path_2` int(11) unsigned NOT NULL DEFAULT '0',
  `path_3` int(11) unsigned NOT NULL DEFAULT '0',
  `path_4` int(11) unsigned NOT NULL DEFAULT '0',
  `path_5` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`SWSectors_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SWSquads`
--

CREATE TABLE IF NOT EXISTS `SWSquads` (
  `SquadID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SquadName` varchar(32) NOT NULL,
  `SquadPassword` varchar(34) NOT NULL,
  `SquadMembers` varchar(255) NOT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`SquadID`),
  KEY `Active` (`Active`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SWSquads_Leagues`
--

CREATE TABLE IF NOT EXISTS `SWSquads_Leagues` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SWSquad_SquadID` int(11) NOT NULL DEFAULT '0',
  `Leagues` int(11) NOT NULL DEFAULT '0',
  `League_PW` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `SWSquad_SquadID` (`SWSquad_SquadID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SWSquad_Info`
--

CREATE TABLE IF NOT EXISTS `SWSquad_Info` (
  `SquadID` int(10) unsigned NOT NULL,
  `Squad_Leader_ID` int(10) unsigned NOT NULL,
  `Squad_Leader_ICQ` varchar(16) DEFAULT NULL,
  `Squad_IRC` varchar(50) DEFAULT NULL,
  `Squad_Email` varchar(50) NOT NULL,
  `Squad_Join_PW` varchar(50) NOT NULL,
  `Squad_Logo` varchar(50) NOT NULL DEFAULT '',
  `Approved` tinyint(1) NOT NULL DEFAULT '0',
  `Rest` tinyint(1) NOT NULL DEFAULT '0',
  `time_registered` datetime NOT NULL,
  `Squad_Red` int(11) DEFAULT NULL,
  `Squad_Green` int(11) DEFAULT NULL,
  `Squad_Blue` int(11) DEFAULT NULL,
  `Squad_Time_Zone` varchar(45) NOT NULL,
  `Squad_Web_Link` varchar(255) DEFAULT NULL,
  `Squad_Statement` text,
  `Abbrv` varchar(5) DEFAULT NULL,
  `ribbon_1` tinyint(1) NOT NULL DEFAULT '0',
  `ribbon_2` tinyint(1) NOT NULL DEFAULT '0',
  `ribbon_3` tinyint(1) NOT NULL DEFAULT '0',
  `ribbon_4` tinyint(1) NOT NULL DEFAULT '0',
  `medal_1` tinyint(1) NOT NULL DEFAULT '0',
  `medal_2` tinyint(1) NOT NULL DEFAULT '0',
  `medal_3` tinyint(1) NOT NULL DEFAULT '0',
  `ribbon_5` tinyint(1) NOT NULL DEFAULT '0',
  `ribbon_6` tinyint(1) NOT NULL DEFAULT '0',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `win_loss` float NOT NULL DEFAULT '0',
  `power_rating` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`SquadID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `missions`
--

CREATE TABLE IF NOT EXISTS `missions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(27) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `specifics` text NOT NULL,
  `respawns` smallint(6) NOT NULL DEFAULT '0',
  `players` smallint(6) NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `missions`
--

INSERT INTO `missions` (`id`, `filename`, `name`, `description`, `specifics`, `respawns`, `players`) VALUES
(1, 'MT-01', 'Aeolus Duel', 'Team vs. team mission in which two terran cruisers and their escorts tangle in a battle to the finish.', 'Each crusier is worth equal points.  The first team to destroy the enemy''s crusier receives a bonus.  The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(2, 'MT-02c', 'Mentu Duel', 'Team vs. team mission in which two vasudan cruisers and their escorts tangle in a battle to the finish.', 'Each crusier is worth equal points. The first team to destroy the enemy''s crusier receives a bonus. The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(3, 'MT-03c', 'Deimos Duel', 'Team vs. team mission in which two terran corvettes and their escorts tangle in a battle to the finish.', 'Each corvette is worth equal points. The first team to destroy the enemy''s corvette receives a bonus. The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(4, 'MT-05a', 'Close Quarters', 'Destroy the other team''s Shivan corvette before they destroy yours.', 'Each corvette is worth equal points. The first team to destroy the enemy''s corvettes receives a bonus. The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(5, 'MT-06', 'Nowhere to Run', 'Team vs. team mission -- no obstacles, no nebula, no excuses.', 'A race to the most kills in six minutes.  After six minutes, the team in the lead receives a bonus.  The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(6, 'MT-07', 'Ganymede Redux', 'Team vs. team furball in and around a Ganymede installation and vasudan destroyer.', 'A race to the most kills in six minutes.  After six minutes, the team in the lead receives a bonus.  The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(7, 'MT-10', 'Ganymede Showdown', 'Team vs. team mission around a Ganymede repair facility and Hades destroyer.', 'A race to the most kills in six minutes.  After six minutes, the team in the lead receives a bonus.  The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(8, 'MT-11', 'Aeolus Duel II', 'Two Aeolus Cruisers and their escorts tangle in a battle to the finish.', 'Cruisers are worth equal points, bonus given to team that gets Cruiser first. Also an AWAC for each side with a bonus for first AWAC killed. Both AWACS worth equal points. Added Stealth fighters and bombers, to compliment AWACS.', 10, 8),
(9, 'MT-12a', 'Fighter Mayhem', 'Team vs. team mission -- fighters only, no obstacles, no nebula, no fighter suppression, no excuses.', 'A race to the most kills in six minutes. After six minutes, the team in the lead receives a bonus. The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(10, 'MT-13', 'Bomber Mayhem', 'Team vs. team mission -- all bombers, no fighters, no obstacles, no nebula, no excuses.', 'A race to the most kills in six minutes. After six minutes, the team in the lead receives a bonus. The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(11, 'MT-14', 'Aspect Lock', 'Team vs. team mission -- no obstacles, no nebula, no fire and forget, no excuses.', 'A race to the most kills in six minutes. After six minutes, the team in the lead receives a bonus. The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(12, 'MT-15', 'Primary Mayhem', 'Team vs. team mission --  fighters and primaries only, no obstacles, no nebula, no excuses.', 'A race to the most kills in six minutes. After six minutes, the team in the lead receives a bonus. The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(13, 'MAL-SDd', 'Sobek Duel', 'Team vs. team mission in which two vasudan corvettes and their escorts tangle in a battle to the finish.', 'Each corvette is worth equal points. The first team to destroy the enemy''s corvette receives a bonus. The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(14, 'Tep_MT-25', 'Fighter Mayhem II', 'Team vs. team mission -- Hide and seek around the debris of a destroyed shivan starbase in a dense asteroid field. All fighters, no bombers, no fighter suppression.', 'A race to the most kills in six minutes. After six minutes, the team in the lead receives a bonus. The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(15, 'Tep_MT-26', 'Bomber Mayhem II', 'Team vs. team mission -- Hide and seek around the debris of a destroyed shivan starbase in a dense asteroid field. All bombers, no fighters.', 'A race to the most kills in six minutes. After six minutes, the team in the lead receives a bonus. The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(16, 'Tep_MT-27', 'Aspect Lock II', 'Team vs. team mission -- Hide and seek around the debris of a destroyed shivan starbase in a dense asteroid field. No fire and forget.', 'A race to the most kills in six minutes. After six minutes, the team in the lead receives a bonus. The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(17, 'Tep_MT-28', 'Primary Mayhem II', 'Team vs. team mission -- Hide and seek around the debris of a destroyed shivan starbase in a dense asteroid field. Fighters and primaries only.', 'A race to the most kills in six minutes. After six minutes, the team in the lead receives a bonus. The value of the individual fighters is negligible compared to the bonus.', 10, 8),
(18, 'TCADis_MT22', 'Battle of Fallen Timbers', 'Team vs. team in an encirclement of Ganymede construction docks. Seek and destroy the enemy ships while trying to navigate and stay alive.', 'A race to the most kills in six minutes. After six minutes, the team in the lead receives a bonus. The value of the individual fighters is negligible compared to the bonus.', 10, 8);
