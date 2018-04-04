-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
-- Server version: 5.5.59-0+deb8u1
-- PHP Version: 7.0.28-1~dotdeb+8.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `prm`
--
CREATE DATABASE IF NOT EXISTS `prm` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `prm`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `create_user`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `create_user`(IN `p_name` VARCHAR(64), IN `p_email` VARCHAR(64), IN `p_pass` VARCHAR(128), IN `p_apikey` VARCHAR(256))
    NO SQL
BEGIN
  INSERT INTO users(`name`, `email`, `password`, `api_key`, `file_number`, `size_used`, `allowed`)
    VALUES (p_name, p_email, p_pass, p_apikey, 0, 0, 1);
END$$

DROP PROCEDURE IF EXISTS `does_apikey_exist`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `does_apikey_exist`(IN `p_apikey` VARCHAR(256), OUT `response` INT(2))
    NO SQL
BEGIN

  SELECT COUNT(*) FROM users WHERE api_key=p_apikey INTO response;

END$$

DROP PROCEDURE IF EXISTS `does_filename_exist`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `does_filename_exist`(IN `p_filename` VARCHAR(128), OUT `response` INT(11))
    NO SQL
BEGIN

  SELECT COUNT(*) FROM files WHERE file_name=p_filename INTO response;

END$$

DROP PROCEDURE IF EXISTS `does_name_exist`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `does_name_exist`(IN `p_name` VARCHAR(64), OUT `response` INT(2))
    NO SQL
BEGIN

  SELECT COUNT(*) FROM users WHERE name=p_name INTO response;

END$$

DROP PROCEDURE IF EXISTS `do_name_email_exist`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `do_name_email_exist`(IN `p_name` VARCHAR(64), IN `p_email` VARCHAR(64), OUT `response` INT(2))
    NO SQL
BEGIN
  SELECT
      (SELECT COUNT(*) FROM users WHERE name=p_name) +
        (SELECT COUNT(*) FROM users WHERE email=p_email)
  INTO response;
END$$

DROP PROCEDURE IF EXISTS `get_apikey_from_name`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_apikey_from_name`(IN `p_name` VARCHAR(64), OUT `response` VARCHAR(256))
    NO SQL
BEGIN

  SELECT `api_key` FROM `users` WHERE `name` = p_name INTO response;

END$$

DROP PROCEDURE IF EXISTS `get_infos_user`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_infos_user`(IN `p_apikey` VARCHAR(256), OUT `r_size_used` BIGINT(20), OUT `r_file_number` INT(11))
    NO SQL
BEGIN

  SELECT `size_used`, `file_number` FROM `users` WHERE `api_key` = p_apikey INTO r_size_used, r_file_number;

END$$

DROP PROCEDURE IF EXISTS `get_last_upload_from_apikey`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_last_upload_from_apikey`(IN `p_apikey` VARCHAR(256), OUT `response` TIMESTAMP)
    NO SQL
BEGIN

  DECLARE _id_user INT;
    SELECT `id` FROM `users` WHERE `api_key` = p_apikey INTO _id_user;
  SELECT `date` FROM `files` WHERE `id_user` = _id_user ORDER BY `date` DESC LIMIT 1 INTO response;

END$$

DROP PROCEDURE IF EXISTS `get_password_from_name`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_password_from_name`(IN `p_name` VARCHAR(64), OUT `response` VARCHAR(128))
    NO SQL
BEGIN

  SELECT `password` FROM `users` WHERE `name` = p_name INTO response;

END$$

DROP PROCEDURE IF EXISTS `get_size_used_from_apikey`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_size_used_from_apikey`(IN `p_apikey` VARCHAR(256), OUT `response` BIGINT(20))
    NO SQL
BEGIN

  SELECT size_used FROM users WHERE api_key=p_apikey INTO response;

END$$

DROP PROCEDURE IF EXISTS `get_stream_media_date_size_from_filename`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_stream_media_date_size_from_filename`(IN `p_filename` VARCHAR(64), OUT `r_stream` LONGBLOB, OUT `r_media` VARCHAR(64), OUT `r_date` TIMESTAMP, OUT `r_size` BIGINT(11))
    NO SQL
BEGIN

  SELECT `stream` FROM `blobs` WHERE `id` = (SELECT `id_blob` FROM `files` WHERE `file_name` = p_filename) INTO r_stream;
    SELECT `media_type`, `date`, `size` FROM `files` WHERE `file_name` = p_filename INTO r_media, r_date, r_size;

END$$

DROP PROCEDURE IF EXISTS `get_total_size_used`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_total_size_used`(OUT `response` BIGINT(32) UNSIGNED)
    NO SQL
BEGIN

  SELECT SUM(size_used) FROM users INTO response;

END$$

DROP PROCEDURE IF EXISTS `get_uploads_list_from_apikey_offset`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_uploads_list_from_apikey_offset`(IN `p_apikey` VARCHAR(256), IN `p_offset` INT(11), IN `p_limit` INT(11))
    NO SQL
BEGIN

  SELECT `origin_name`, `file_name`, `media_type`, `date` FROM `files` WHERE `id_user` = (SELECT `id` FROM `users` WHERE `api_key` = p_apikey) ORDER BY `date` DESC LIMIT p_limit OFFSET p_offset;

END$$

DROP PROCEDURE IF EXISTS `is_allowed`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `is_allowed`(IN `p_apikey` VARCHAR(256), OUT `response` INT(11))
    NO SQL
BEGIN

  SELECT `allowed` FROM `users` WHERE `api_key` = p_apikey INTO response;

END$$

DROP PROCEDURE IF EXISTS `upload_file`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `upload_file`(IN `p_stream` LONGBLOB, IN `p_hash` VARCHAR(128), IN `p_apikey` VARCHAR(256), IN `p_filename` VARCHAR(64), IN `p_mediatype` VARCHAR(64), IN `p_size` BIGINT(11) UNSIGNED, IN `p_originname` VARCHAR(64))
    NO SQL
BEGIN

    DECLARE file_exists TINYINT;
    DECLARE _tmp_id_blob INT;
    DECLARE _tmp_id_user INT;
    
    SELECT COUNT(*) FROM `blobs` WHERE `hash`=p_hash INTO file_exists;
    
    IF file_exists = 0 THEN
      INSERT INTO `blobs`(`stream`, `hash`) VALUES(p_stream, p_hash);
    END IF;
    
    SELECT `id` FROM `blobs` WHERE `hash`=p_hash INTO _tmp_id_blob;
    SELECT `id` FROM `users` WHERE `api_key`=p_apikey INTO _tmp_id_user;
    
    INSERT INTO `files`(`id_user`, `id_blob`, `origin_name`, `file_name`, `media_type`, `size`, `important`) VALUES(_tmp_id_user, _tmp_id_blob, p_originname, p_filename, p_mediatype, p_size, 0);

    UPDATE `users` SET `file_number` = `file_number`+1, `size_used` = `size_used`+p_size WHERE `id` = _tmp_id_user;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `blobs`
--

DROP TABLE IF EXISTS `blobs`;
CREATE TABLE IF NOT EXISTS `blobs` (
`id` int(11) NOT NULL,
  `stream` longblob NOT NULL,
  `hash` varchar(128) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
`id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_blob` int(11) NOT NULL,
  `origin_name` varchar(64) NOT NULL,
  `file_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `media_type` varchar(64) NOT NULL,
  `size` bigint(11) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `important` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `files`:
--   `id_user`
--       `users` -> `id`
--   `id_blob`
--       `blobs` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `api_key` varchar(256) NOT NULL,
  `file_number` int(11) unsigned NOT NULL,
  `size_used` bigint(20) unsigned NOT NULL,
  `allowed` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blobs`
--
ALTER TABLE `blobs`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `hash` (`hash`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
 ADD PRIMARY KEY (`id`), ADD KEY `id_user` (`id_user`), ADD KEY `id_blob` (`id_blob`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `files`
--
ALTER TABLE `files`
ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
ADD CONSTRAINT `files_ibfk_2` FOREIGN KEY (`id_blob`) REFERENCES `blobs` (`id`);
