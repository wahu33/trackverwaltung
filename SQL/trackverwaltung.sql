SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `datei` (
`id` int(11) NOT NULL,
  `bezeichnung` varchar(120) NOT NULL,
  `beschreibung` text NOT NULL,
  `filename` varchar(120) NOT NULL,
  `quelle` tinyint(4) NOT NULL,
  `kategorie` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `groesse` int(11) NOT NULL,
  `datum` date NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `lat_max` double NOT NULL,
  `lat_min` double NOT NULL,
  `lon_max` double NOT NULL,
  `lon_min` double NOT NULL,
  `trkpt_count` int(11) NOT NULL,
  `trk_date` date NOT NULL,
  `distance` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `kategorie` (
`id` int(11) NOT NULL,
  `bezeichnung` varchar(40) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL,
  `plural` varchar(40) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL,
  `kommentar` varchar(80) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL,
  `sort` int(4) NOT NULL,
  `privat` tinyint(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `kategorie` (`id`, `bezeichnung`, `plural`, `kommentar`, `sort`, `privat`) VALUES
(1, 'Radtouren', 'Radtouren', 'Radtouren rund um meinen Heimatort', 10, 0),
(2, 'Wanderung', 'Wanderungen', 'Wandertouren ', 20, 0);

CREATE TABLE IF NOT EXISTS `quelle` (
`id` int(11) NOT NULL,
  `bezeichnung` varchar(20) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL,
  `kommentar` varchar(80) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL,
  `sort` tinyint(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

INSERT INTO `quelle` (`id`, `bezeichnung`, `kommentar`, `sort`) VALUES
(7, 'Iphone', '', 10),
(8, 'Garmin', '', 30);

CREATE TABLE IF NOT EXISTS `tracks` (
`id` bigint(20) NOT NULL,
  `track_id` bigint(22) NOT NULL,
  `lon` double NOT NULL,
  `lat` double NOT NULL,
  `ele` float NOT NULL,
  `dist` float NOT NULL,
  `date` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `login` varchar(20) NOT NULL,
  `role` varchar(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(60) NOT NULL,
  `bemerkung` varchar(60) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `login`, `role`, `password`, `name`, `bemerkung`) VALUES
(1, 'admin', '', '*4ACFE3202A5FF5CF467898FC58AAB1D615029441', 'Administrator', '');


ALTER TABLE `datei`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `kategorie`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `quelle`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `tracks`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `user`
 ADD PRIMARY KEY (`id`);


ALTER TABLE `datei`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `kategorie`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
ALTER TABLE `quelle`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
ALTER TABLE `tracks`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
