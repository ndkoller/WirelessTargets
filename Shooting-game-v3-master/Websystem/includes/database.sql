-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Värd: localhost:3306
-- Tid vid skapande: 24 maj 2020 kl 09:00
-- Serverversion: 10.3.22-MariaDB-0+deb10u1
-- PHP-version: 7.3.14-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `shooting`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `activegame`
--

CREATE TABLE `activegame` (
  `id` int(11) NOT NULL,
  `gameid` int(11) NOT NULL DEFAULT 0,
  `isdone` tinyint(1) NOT NULL DEFAULT 0,
  `beginplay` tinyint(1) NOT NULL DEFAULT 0,
  `newplayer` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur `activeplayers`
--

CREATE TABLE `activeplayers` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `game` int(11) NOT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT 0,
  `isplayed` tinyint(1) NOT NULL DEFAULT 0,
  `runnow` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur `activequick`
--

CREATE TABLE `activequick` (
  `id` int(11) NOT NULL,
  `garound` int(11) NOT NULL DEFAULT 0,
  `garesult` varchar(50) NOT NULL,
  `isdone` tinyint(1) NOT NULL DEFAULT 0,
  `isadded` tinyint(1) NOT NULL DEFAULT 0,
  `gplayer` int(11) NOT NULL DEFAULT 0,
  `winusr` tinyint(1) NOT NULL DEFAULT 0,
  `winall` tinyint(1) NOT NULL DEFAULT 0,
  `gamedate` date NOT NULL DEFAULT '0000-00-00',
  `gametime` time NOT NULL DEFAULT '00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur `activerapid`
--

CREATE TABLE `activerapid` (
  `id` int(11) NOT NULL,
  `garesults` varchar(50) NOT NULL,
  `isdone` tinyint(1) NOT NULL DEFAULT 0,
  `isadded` tinyint(1) NOT NULL DEFAULT 0,
  `gplayer` int(11) NOT NULL DEFAULT 0,
  `winusr` tinyint(1) NOT NULL DEFAULT 0,
  `winall` tinyint(1) NOT NULL DEFAULT 0,
  `gamedate` date NOT NULL DEFAULT '0000-00-00',
  `gametime` time NOT NULL DEFAULT '00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur `activetimed`
--

CREATE TABLE `activetimed` (
  `id` int(11) NOT NULL,
  `garesults` varchar(50) NOT NULL,
  `isdone` tinyint(1) NOT NULL DEFAULT 0,
  `isadded` tinyint(1) NOT NULL DEFAULT 0,
  `gplayer` int(11) NOT NULL DEFAULT 0,
  `winusr` tinyint(1) NOT NULL DEFAULT 0,
  `winall` tinyint(1) NOT NULL DEFAULT 0,
  `gamedate` date NOT NULL DEFAULT '0000-00-00',
  `gametime` time NOT NULL DEFAULT '00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `gname` varchar(50) NOT NULL,
  `gamount` varchar(50) NOT NULL,
  `gtype` varchar(10) NOT NULL,
  `gdesk` longtext NOT NULL,
  `ghard` tinyint(1) NOT NULL DEFAULT 1,
  `plfrom` int(11) NOT NULL DEFAULT 1,
  `plto` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `games`
--



-- --------------------------------------------------------

--
-- Tabellstruktur `printjob`
--

CREATE TABLE `printjob` (
  `id` int(11) NOT NULL,
  `gametype` int(11) NOT NULL DEFAULT 0,
  `gamecode` varchar(50) NOT NULL,
  `startprint` tinyint(1) NOT NULL DEFAULT 0,
  `activegame` tinyint(1) NOT NULL DEFAULT 0,
  `eanprint` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur `savedquick`
--

CREATE TABLE `savedquick` (
  `id` int(11) NOT NULL,
  `garound` int(11) NOT NULL DEFAULT 0,
  `garesult` varchar(50) NOT NULL,
  `isdone` tinyint(1) NOT NULL DEFAULT 0,
  `isadded` tinyint(1) NOT NULL DEFAULT 0,
  `gplayer` int(11) NOT NULL DEFAULT 0,
  `winusr` tinyint(1) NOT NULL DEFAULT 0,
  `winall` tinyint(1) NOT NULL DEFAULT 0,
  `gamecode` varchar(50) NOT NULL DEFAULT '0',
  `fullname` varchar(50) NOT NULL,
  `gametype` int(11) NOT NULL DEFAULT 0,
  `gamedate` date NOT NULL,
  `gametime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



--
-- Tabellstruktur `savedrapid`
--

CREATE TABLE `savedrapid` (
  `id` int(11) NOT NULL,
  `garesults` varchar(50) NOT NULL,
  `isdone` tinyint(1) NOT NULL DEFAULT 0,
  `isadded` tinyint(1) NOT NULL DEFAULT 0,
  `gplayer` int(11) NOT NULL DEFAULT 0,
  `winusr` tinyint(1) NOT NULL DEFAULT 0,
  `winall` tinyint(1) NOT NULL DEFAULT 0,
  `gamecode` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `gametype` int(11) NOT NULL DEFAULT 0,
  `gamedate` date NOT NULL,
  `gametime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



--
-- Tabellstruktur `savedtimed`
--

CREATE TABLE `savedtimed` (
  `id` int(11) NOT NULL,
  `garesults` varchar(50) NOT NULL,
  `isdone` tinyint(1) NOT NULL DEFAULT 0,
  `isadded` tinyint(1) NOT NULL DEFAULT 0,
  `gplayer` int(11) NOT NULL DEFAULT 0,
  `winusr` tinyint(1) NOT NULL DEFAULT 0,
  `winall` tinyint(1) NOT NULL DEFAULT 0,
  `gamecode` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `gametype` int(11) NOT NULL DEFAULT 0,
  `gamedate` date NOT NULL,
  `gametime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



--
-- Tabellstruktur `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `tprinter` tinyint(1) NOT NULL DEFAULT 0,
  `eancodescan` tinyint(1) NOT NULL DEFAULT 0,
  `eancode` varchar(50) NOT NULL,
  `sendover` tinyint(1) NOT NULL DEFAULT 0,
  `testtargets` tinyint(1) NOT NULL DEFAULT 0,
  `sendbat` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `settings`
--

INSERT INTO `settings` (`id`, `tprinter`, `eancodescan`, `eancode`, `sendover`, `testtargets`, `sendbat`) VALUES
(1, 0, 0, '0', 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellstruktur `targets`
--

CREATE TABLE `targets` (
  `id` int(11) NOT NULL,
  `targid` varchar(50) NOT NULL DEFAULT '0',
  `tarname` varchar(100) NOT NULL,
  `sendid` varchar(50) NOT NULL,
  `testok` tinyint(1) NOT NULL DEFAULT 0,
  `sendok` tinyint(1) NOT NULL DEFAULT 0,
  `batstatus` varchar(50) NOT NULL DEFAULT '0',
  `batok` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



--
-- Index för dumpade tabeller
--

--
-- Index för tabell `activegame`
--
ALTER TABLE `activegame`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `activeplayers`
--
ALTER TABLE `activeplayers`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `activequick`
--
ALTER TABLE `activequick`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `activerapid`
--
ALTER TABLE `activerapid`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `activetimed`
--
ALTER TABLE `activetimed`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `printjob`
--
ALTER TABLE `printjob`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `savedquick`
--
ALTER TABLE `savedquick`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `savedrapid`
--
ALTER TABLE `savedrapid`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `savedtimed`
--
ALTER TABLE `savedtimed`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `targets`
--
ALTER TABLE `targets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `activegame`
--
ALTER TABLE `activegame`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT för tabell `activeplayers`
--
ALTER TABLE `activeplayers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT för tabell `activequick`
--
ALTER TABLE `activequick`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT för tabell `activerapid`
--
ALTER TABLE `activerapid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT för tabell `activetimed`
--
ALTER TABLE `activetimed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT för tabell `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


--
-- AUTO_INCREMENT för tabell `printjob`
--
ALTER TABLE `printjob`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT för tabell `savedquick`
--
ALTER TABLE `savedquick`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT för tabell `savedrapid`
--
ALTER TABLE `savedrapid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT för tabell `savedtimed`
--
ALTER TABLE `savedtimed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT för tabell `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT för tabell `targets`
--
ALTER TABLE `targets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
