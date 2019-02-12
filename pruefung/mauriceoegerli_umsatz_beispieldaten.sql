-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 12. Feb 2019 um 15:04
-- Server-Version: 10.1.21-MariaDB
-- PHP-Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `m307_mauriceoegerli`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mauriceoegerli_umsatz`
--

CREATE TABLE `mauriceoegerli_umsatz` (
  `umsatz_id` int(11) NOT NULL,
  `umsatz_kunde_name` varchar(255) NOT NULL,
  `umsatz_filiale` varchar(255) NOT NULL DEFAULT 'St. Gallen',
  `umsatz_umsatz` double NOT NULL,
  `umsatz_kunde_seit` date DEFAULT NULL,
  `umsatz_anzbestellungen` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `mauriceoegerli_umsatz`
--

INSERT INTO `mauriceoegerli_umsatz` (`umsatz_id`, `umsatz_kunde_name`, `umsatz_filiale`, `umsatz_umsatz`, `umsatz_kunde_seit`, `umsatz_anzbestellungen`) VALUES
(8, 'sda1', 'St. Gallen1', 231, '2019-02-01', 2341),
(9, 'asdf', 'St. Gallen', 2345, '2019-02-02', 3),
(10, 'Test Name', 'Irgendwo', 234234, '2019-02-10', 23423),
(11, 'sfdasfdasfda', 'St. Gallen', 2353223, '2019-02-26', 232323);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `mauriceoegerli_umsatz`
--
ALTER TABLE `mauriceoegerli_umsatz`
  ADD PRIMARY KEY (`umsatz_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `mauriceoegerli_umsatz`
--
ALTER TABLE `mauriceoegerli_umsatz`
  MODIFY `umsatz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
