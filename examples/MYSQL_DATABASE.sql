-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Erstellungszeit: 19. Jun 2022 um 16:48
-- Server-Version: 8.0.28
-- PHP-Version: 8.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `MYSQL_DATABASE`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dropdown_lookup_table`
--

CREATE TABLE `dropdown_lookup_table` (
  `ID` mediumint NOT NULL,
  `DEL` bit(1) NOT NULL DEFAULT b'0',
  `TEXT` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `dropdown_lookup_table`
--

INSERT INTO `dropdown_lookup_table` (`ID`, `DEL`, `TEXT`) VALUES
(1, b'0', 'ONE'),
(2, b'0', 'TWO'),
(3, b'0', 'THREE'),
(4, b'0', 'FOUR'),
(5, b'0', 'FIVE'),
(6, b'0', 'SIX'),
(7, b'0', 'SEVEN'),
(8, b'0', 'EIGHT'),
(9, b'0', 'NINE');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dropdown_multi_lookup_table`
--

CREATE TABLE `dropdown_multi_lookup_table` (
  `ID` mediumint NOT NULL,
  `DEL` bit(1) NOT NULL DEFAULT b'0',
  `TEXT` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `dropdown_multi_lookup_table`
--

INSERT INTO `dropdown_multi_lookup_table` (`ID`, `DEL`, `TEXT`) VALUES
(1, b'0', 'zéro'),
(2, b'0', 'un'),
(3, b'0', 'deux'),
(4, b'0', 'trois'),
(5, b'0', 'quatre'),
(6, b'0', 'cinq'),
(7, b'0', 'six'),
(8, b'0', 'sept'),
(9, b'0', 'huit'),
(10, b'0', 'neuf'),
(11, b'0', 'dix'),
(12, b'0', 'onze'),
(13, b'0', 'douze'),
(14, b'0', 'treize'),
(15, b'0', 'quatorze'),
(16, b'0', 'quinze'),
(17, b'0', 'seize'),
(18, b'0', 'dix-sept'),
(19, b'0', 'dix-huit'),
(20, b'0', 'dix-neuf'),
(21, b'0', 'vingt');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ref_root_ref_dropdown_multi_table`
--

CREATE TABLE `ref_root_ref_dropdown_multi_table` (
  `ID` mediumint NOT NULL,
  `DEL` bit(1) NOT NULL DEFAULT b'0',
  `REF_ROOT` mediumint NOT NULL,
  `REF_DROPDOWN_MULTI` mediumint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `ref_root_ref_dropdown_multi_table`
--

INSERT INTO `ref_root_ref_dropdown_multi_table` (`ID`, `DEL`, `REF_ROOT`, `REF_DROPDOWN_MULTI`) VALUES
(1, b'0', 1, 1),
(2, b'0', 1, 2),
(3, b'0', 1, 3),
(4, b'0', 1, 4),
(5, b'0', 1, 5),
(6, b'0', 2, 6),
(7, b'0', 2, 7),
(8, b'0', 2, 8),
(9, b'0', 3, 9),
(10, b'0', 3, 10),
(11, b'0', 4, 11),
(12, b'0', 5, 12),
(13, b'0', 5, 13),
(14, b'0', 6, 14),
(15, b'0', 6, 15),
(16, b'0', 6, 16),
(17, b'0', 7, 17),
(18, b'0', 7, 18),
(19, b'0', 7, 19),
(20, b'0', 7, 20);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `root_table`
--

CREATE TABLE `root_table` (
  `ID` mediumint NOT NULL,
  `DEL` bit(1) NOT NULL DEFAULT b'0',
  `TEXT` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `CHECKBOX` bit(1) NOT NULL DEFAULT b'0',
  `REF_DROPDOWN` mediumint DEFAULT NULL,
  `LINK` varchar(2083) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `LINK_BUTTON` varchar(2083) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `DATE` date DEFAULT NULL,
  `DATETIME` datetime DEFAULT NULL,
  `COLOR` varchar(7) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `EMAIL` varchar(70) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Daten für Tabelle `root_table`
--

INSERT INTO `root_table` (`ID`, `DEL`, `TEXT`, `CHECKBOX`, `REF_DROPDOWN`, `LINK`, `LINK_BUTTON`, `DATE`, `DATETIME`, `COLOR`, `EMAIL`) VALUES
(1, b'0', 'ALPHA', b'0', 1, 'https://stackoverflow.com/questions/219569/best-database-field-type-for-a-url', 'https://stackoverflow.com/questions/219569/best-database-field-type-for-a-url', '2022-06-17', '2022-06-17 00:00:00', '#ff0000', 'info@dwuty.de '),
(2, b'0', 'BRAVO', b'0', 2, 'https://packagist.org/packages/datatableswebutility/dwuty', 'https://packagist.org/packages/datatableswebutility/dwuty', '2022-06-23', '2022-06-23 12:57:36', '#00ff1e', 'abuse@dwuty.de'),
(3, b'0', 'CHARLIE', b'0', 3, 'http://datatableswebutility.com/', 'http://datatableswebutility.com/', '2022-06-29', '2022-06-30 01:55:12', '#4f6392', 'postmaster@dwuty.de'),
(4, b'0', 'DELTA', b'0', 4, 'http://datatableswebutility.de', 'http://datatableswebutility.de', '2022-07-05', '2022-07-06 14:52:48', NULL, 'security@dwuty.de'),
(5, b'0', 'ECHO', b'1', 5, 'http://datatableswebutility.net', 'http://datatableswebutility.net', '2022-07-11', '2022-07-13 03:50:24', NULL, 'info@datatableswebutility.de'),
(6, b'0', 'FOXTROT', b'0', 6, 'http://dwuty.com', 'http://dwuty.com', '2022-07-17', '2022-07-19 16:48:00', NULL, 'abuse@datatableswebutility.de'),
(7, b'0', 'GOLF', b'0', 7, 'http://dwuty.de', 'http://dwuty.de', '2022-07-23', '2022-07-26 05:45:36', NULL, 'postmaster@datatableswebutility.de'),
(8, b'0', 'HOTEL', b'0', 8, 'http://dwuty.net', 'http://dwuty.net', '2022-07-29', '2022-08-01 18:43:12', NULL, 'security@datatableswebutility.de'),
(9, b'0', 'INDIA', b'0', 9, NULL, NULL, '2022-08-04', '2022-08-08 07:40:48', NULL, NULL),
(10, b'0', 'JULIETT', b'1', 8, NULL, NULL, '2022-08-10', '2022-08-14 20:38:24', NULL, NULL),
(11, b'0', 'KILO', b'1', 7, NULL, NULL, '2022-08-16', '2022-08-21 09:36:00', NULL, NULL),
(12, b'0', 'LIMA', b'0', 6, NULL, NULL, '2022-08-22', '2022-08-27 22:33:36', NULL, NULL),
(13, b'0', 'MIKE', b'1', 5, NULL, NULL, '2022-08-28', '2022-09-03 11:31:12', NULL, NULL),
(14, b'0', 'NOVEMBER', b'0', 4, NULL, NULL, '2022-09-03', '2022-09-10 00:28:48', NULL, NULL),
(15, b'0', 'OSCAR', b'0', 3, NULL, NULL, '2022-09-09', '2022-09-16 13:26:24', NULL, NULL),
(16, b'0', 'PAPA', b'0', 2, NULL, NULL, '2022-09-15', '2022-09-23 02:24:00', NULL, NULL),
(17, b'0', 'QUEBEC', b'0', 1, NULL, NULL, '2022-09-23', '2022-09-29 15:21:36', NULL, NULL),
(18, b'0', 'ROMEO', b'0', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, b'0', 'SIERRA', b'0', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, b'0', 'TANGO', b'0', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, b'0', 'UNIFORM', b'0', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, b'0', 'VICTOR', b'0', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, b'0', 'WHISKEY', b'0', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, b'0', 'XRAY', b'0', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, b'0', 'YANKEE', b'0', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, b'0', 'ZULU', b'0', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `dropdown_lookup_table`
--
ALTER TABLE `dropdown_lookup_table`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `dropdown_multi_lookup_table`
--
ALTER TABLE `dropdown_multi_lookup_table`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `ref_root_ref_dropdown_multi_table`
--
ALTER TABLE `ref_root_ref_dropdown_multi_table`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `root_table`
--
ALTER TABLE `root_table`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `dropdown_lookup_table`
--
ALTER TABLE `dropdown_lookup_table`
  MODIFY `ID` mediumint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT für Tabelle `dropdown_multi_lookup_table`
--
ALTER TABLE `dropdown_multi_lookup_table`
  MODIFY `ID` mediumint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT für Tabelle `ref_root_ref_dropdown_multi_table`
--
ALTER TABLE `ref_root_ref_dropdown_multi_table`
  MODIFY `ID` mediumint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT für Tabelle `root_table`
--
ALTER TABLE `root_table`
  MODIFY `ID` mediumint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
