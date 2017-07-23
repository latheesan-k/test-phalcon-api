SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


DROP TABLE IF EXISTS `upload_files`;
CREATE TABLE `upload_files` (
  `id` int(11) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `new_filename` varchar(255) NOT NULL,
  `filesize_bytes` int(11) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `upload_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);


ALTER TABLE `upload_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


INSERT INTO `upload_files` (`id`, `original_filename`, `new_filename`, `filesize_bytes`, `date_created`) VALUES
  (NULL, 'test.csv', 'test.xls', '8281', '2017-07-22');


COMMIT;
