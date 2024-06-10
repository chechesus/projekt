-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1:3307
-- Čas generovania: Po 10.Jún 2024, 18:08
-- Verzia serveru: 10.4.32-MariaDB
-- Verzia PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `data`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `articles`
--

CREATE TABLE `articles` (
  `article_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `articles`
--

INSERT INTO `articles` (`article_id`, `title`, `content`) VALUES
(1, 'Lorem IPsos sokeres', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Augue interdum velit euismod in pellentesque massa placerat duis ultricies. Auctor neque vitae tempus quam pellentesque nec nam. Imperdiet proin fermentum leo vel. Fames ac turpis egestas sed tempus urna et. Integer enim neque volutpat ac tincidunt vitae semper quis lectus. Id eu nisl nunc mi ipsum. Erat imperdiet sed euismod nisi porta lorem mollis aliquam. Senectus et netus et malesuada fames ac turpis egestas. Convallis tellus id interdum velit laoreet. Nunc pulvinar sapien et ligula ullamcorper malesuada proin libero. Pulvinar mattis nunc sed blandit libero. Sagittis eu volutpat odio facilisis mauris sit amet. Arcu odio ut sem nulla pharetra diam. Eu volutpat odio facilisis mauris sit amet massa. Non consectetur a erat nam at lectus urna. Sit amet est placerat in egestas erat.\r\n\r\nQuis hendrerit dolor magna eget est lorem. Nunc lobortis mattis aliquam faucibus purus in. Netus et malesuada fames ac turpis egestas maecenas pharetra convallis. Non tellus orci ac auctor augue mauris augue. Etiam erat velit scelerisque in dictum non consectetur. Sapien pellentesque habitant morbi tristique senectus et netus. Egestas congue quisque egestas diam in arcu. Tincidunt lobortis feugiat vivamus at augue eget arcu dictum. At in tellus integer feugiat scelerisque varius morbi enim. Velit scelerisque in dictum non consectetur. Ultrices dui sapien eget mi proin sed libero enim sed.\r\n\r\nNisi porta lorem mollis aliquam. Tempus egestas sed sed risus pretium quam vulputate. Mattis aliquam faucibus purus in massa. Venenatis tellus in metus vulputate eu. Id porta nibh venenatis cras sed felis eget. Eget magna fermentum iaculis eu non. Pretium nibh ipsum consequat nisl vel pretium lectus. Ultrices tincidunt arcu non sodales. Volutpat lacus laoreet non curabitur gravida. Est sit amet facilisis magna etiam. Odio facilisis mauris sit amet massa vitae tortor condimentum lacinia. Quis auctor elit sed vulputate mi sit amet mauris. Lectus nulla at volutpat diam.\r\n\r\nAugue mauris augue neque gravida in. Blandit volutpat maecenas volutpat blandit aliquam etiam erat velit scelerisque. Gravida quis blandit turpis cursus in. Aliquam vestibulum morbi blandit cursus risus at ultrices mi tempus. Morbi leo urna molestie at elementum eu facilisis. Leo a diam sollicitudin tempor id eu nisl. Ut lectus arcu bibendum at varius vel. Vestibulum rhoncus est pellentesque elit ullamcorper dignissim. Egestas pretium aenean pharetra magna ac placerat vestibulum. Ornare quam viverra orci sagittis. Et egestas quis ipsum suspendisse. Sed vulputate mi sit amet mauris commodo quis. Duis at tellus at urna. In hac habitasse platea dictumst vestibulum rhoncus est pellentesque elit.\r\n\r\nAenean vel elit scelerisque mauris pellentesque pulvinar pellentesque habitant. Leo a diam sollicitudin tempor id eu nisl. Feugiat scelerisque varius morbi enim nunc. Cum sociis natoque penatibus et magnis dis. Pharetra pharetra massa massa ultricies mi. Nullam vehicula ipsum a arcu cursus vitae. In iaculis nunc sed augue lacus viverra. Tincidunt lobortis feugiat vivamus at augue eget arcu dictum. Pellentesque adipiscing commodo elit at. Libero nunc consequat interdum varius. At elementum eu facilisis sed. Nec ullamcorper sit amet risus nullam eget felis eget nunc. At erat pellentesque adipiscing commodo elit at imperdiet dui accumsan. Amet risus nullam eget felis. Amet porttitor eget dolor morbi non arcu. Eget mi proin sed libero enim sed faucibus turpis. Amet massa vitae tortor condimentum lacinia quis vel. Maecenas accumsan lacus vel facilisis volutpat est.\r\n\r\nCras tincidunt lobortis feugiat vivamus at augue eget. Praesent elementum facilisis leo vel fringilla est ullamcorper eget. Semper auctor neque vitae tempus quam pellentesque nec nam. At in tellus integer feugiat scelerisque varius. Egestas tellus rutrum tellus pellentesque eu tincidunt. Neque vitae tempus quam pellentesque nec. Etiam non quam lacus suspendisse. Ornare suspendisse sed nisi lacus sed viverra tellus. Sodales ut etiam sit amet nisl purus. Pretium aenean pharetra magna ac placerat. Diam in arcu cursus euismod quis. Felis eget nunc lobortis mattis aliquam. Vel fringilla est ullamcorper eget nulla facilisi. Vitae sapien pellentesque habitant morbi tristique senectus et. Ornare aenean euismod elementum nisi. Nunc consequat interdum varius sit amet mattis vulputate enim. Feugiat nisl pretium fusce id velit ut tortor.\r\n\r\nDiam in arcu cursus euismod quis viverra. Mattis enim ut tellus elementum sagittis vitae et. Non quam lacus suspendisse faucibus. Auctor elit sed vulputate mi sit amet mauris commodo. Nec feugiat nisl pretium fusce id velit. Tortor pretium viverra suspendisse potenti nullam ac tortor vitae. At tellus at urna condimentum mattis pellentesque id. Neque gravida in fermentum et sollicitudin ac. In arcu cursus euismod quis. Lorem ipsum dolor sit amet consectetur adipiscing elit. Fringilla urna porttitor rhoncus dolor. Ultricies tristique nulla aliquet enim tortor at auctor. Diam ut venenatis tellus in metus vulputate eu scelerisque. Pharetra massa massa ultricies mi quis hendrerit dolor magna eget. Enim lobortis scelerisque fermentum dui faucibus.\r\n\r\nSollicitudin tempor id eu nisl nunc mi ipsum faucibus vitae. Integer feugiat scelerisque varius morbi enim. Erat velit scelerisque in dictum non consectetur a erat nam. At in tellus integer feugiat scelerisque. Quam id leo in vitae turpis massa sed elementum. Tempus egestas sed sed risus pretium quam vulputate dignissim. Lacus vestibulum sed arcu non odio. Integer vitae justo eget magna fermentum iaculis eu. Nibh venenatis cras sed felis eget velit aliquet sagittis id. Natoque penatibus et magnis dis parturient montes nascetur ridiculus.\r\n\r\nVolutpat ac tincidunt vitae semper quis. Nisi vitae suscipit tellus mauris. Sed felis eget velit aliquet sagittis id consectetur purus ut. Sed viverra tellus in hac habitasse platea dictumst. Commodo nulla facilisi nullam vehicula ipsum. Sed egestas egestas fringilla phasellus faucibus. Fusce id velit ut tortor pretium viverra suspendisse. Aliquam purus sit amet luctus venenatis lectus. Mollis nunc sed id semper risus. Quis eleifend quam adipiscing vitae proin sagittis. Commodo quis imperdiet massa tincidunt.\r\n\r\nGravida cum sociis natoque penatibus et. Sodales ut etiam sit amet. Dignissim suspendisse in est ante. Aliquet enim tortor at auctor urna nunc. Parturient montes nascetur ridiculus mus mauris vitae ultricies leo integer. Egestas dui id ornare arcu odio. Sem viverra aliquet eget sit amet tellus cras. Aenean pharetra magna ac placerat vestibulum lectus. Lorem mollis aliquam ut porttitor. Lobortis mattis aliquam faucibus purus in massa tempor nec. Egestas pretium aenean pharetra magna ac placerat vestibulum lectus. Bibendum arcu vitae elementum curabitur. Enim praesent elementum facilisis leo vel fringilla est ullamcorper eget. Proin sagittis nisl rhoncus mattis rhoncus. Eros donec ac odio tempor orci dapibus ultrices in iaculis. At risus viverra adipiscing at in tellus integer feugiat. Convallis a cras semper auctor neque. Fermentum leo vel orci porta non pulvinar neque laoreet.'),
(2, 'Lorem IPsos sokeres', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Augue interdum velit euismod in pellentesque massa placerat duis ultricies. Auctor neque vitae tempus quam pellentesque nec nam. Imperdiet proin fermentum leo vel. Fames ac turpis egestas sed tempus urna et. Integer enim neque volutpat ac tincidunt vitae semper quis lectus. Id eu nisl nunc mi ipsum. Erat imperdiet sed euismod nisi porta lorem mollis aliquam. Senectus et netus et malesuada fames ac turpis egestas. Convallis tellus id interdum velit laoreet. Nunc pulvinar sapien et ligula ullamcorper malesuada proin libero. Pulvinar mattis nunc sed blandit libero. Sagittis eu volutpat odio facilisis mauris sit amet. Arcu odio ut sem nulla pharetra diam. Eu volutpat odio facilisis mauris sit amet massa. Non consectetur a erat nam at lectus urna. Sit amet est placerat in egestas erat.\r\n\r\nQuis hendrerit dolor magna eget est lorem. Nunc lobortis mattis aliquam faucibus purus in. Netus et malesuada fames ac turpis egestas maecenas pharetra convallis. Non tellus orci ac auctor augue mauris augue. Etiam erat velit scelerisque in dictum non consectetur. Sapien pellentesque habitant morbi tristique senectus et netus. Egestas congue quisque egestas diam in arcu. Tincidunt lobortis feugiat vivamus at augue eget arcu dictum. At in tellus integer feugiat scelerisque varius morbi enim. Velit scelerisque in dictum non consectetur. Ultrices dui sapien eget mi proin sed libero enim sed.\r\n\r\nNisi porta lorem mollis aliquam. Tempus egestas sed sed risus pretium quam vulputate. Mattis aliquam faucibus purus in massa. Venenatis tellus in metus vulputate eu. Id porta nibh venenatis cras sed felis eget. Eget magna fermentum iaculis eu non. Pretium nibh ipsum consequat nisl vel pretium lectus. Ultrices tincidunt arcu non sodales. Volutpat lacus laoreet non curabitur gravida. Est sit amet facilisis magna etiam. Odio facilisis mauris sit amet massa vitae tortor condimentum lacinia. Quis auctor elit sed vulputate mi sit amet mauris. Lectus nulla at volutpat diam.\r\n\r\nAugue mauris augue neque gravida in. Blandit volutpat maecenas volutpat blandit aliquam etiam erat velit scelerisque. Gravida quis blandit turpis cursus in. Aliquam vestibulum morbi blandit cursus risus at ultrices mi tempus. Morbi leo urna molestie at elementum eu facilisis. Leo a diam sollicitudin tempor id eu nisl. Ut lectus arcu bibendum at varius vel. Vestibulum rhoncus est pellentesque elit ullamcorper dignissim. Egestas pretium aenean pharetra magna ac placerat vestibulum. Ornare quam viverra orci sagittis. Et egestas quis ipsum suspendisse. Sed vulputate mi sit amet mauris commodo quis. Duis at tellus at urna. In hac habitasse platea dictumst vestibulum rhoncus est pellentesque elit.\r\n\r\nAenean vel elit scelerisque mauris pellentesque pulvinar pellentesque habitant. Leo a diam sollicitudin tempor id eu nisl. Feugiat scelerisque varius morbi enim nunc. Cum sociis natoque penatibus et magnis dis. Pharetra pharetra massa massa ultricies mi. Nullam vehicula ipsum a arcu cursus vitae. In iaculis nunc sed augue lacus viverra. Tincidunt lobortis feugiat vivamus at augue eget arcu dictum. Pellentesque adipiscing commodo elit at. Libero nunc consequat interdum varius. At elementum eu facilisis sed. Nec ullamcorper sit amet risus nullam eget felis eget nunc. At erat pellentesque adipiscing commodo elit at imperdiet dui accumsan. Amet risus nullam eget felis. Amet porttitor eget dolor morbi non arcu. Eget mi proin sed libero enim sed faucibus turpis. Amet massa vitae tortor condimentum lacinia quis vel. Maecenas accumsan lacus vel facilisis volutpat est.\r\n\r\nCras tincidunt lobortis feugiat vivamus at augue eget. Praesent elementum facilisis leo vel fringilla est ullamcorper eget. Semper auctor neque vitae tempus quam pellentesque nec nam. At in tellus integer feugiat scelerisque varius. Egestas tellus rutrum tellus pellentesque eu tincidunt. Neque vitae tempus quam pellentesque nec. Etiam non quam lacus suspendisse. Ornare suspendisse sed nisi lacus sed viverra tellus. Sodales ut etiam sit amet nisl purus. Pretium aenean pharetra magna ac placerat. Diam in arcu cursus euismod quis. Felis eget nunc lobortis mattis aliquam. Vel fringilla est ullamcorper eget nulla facilisi. Vitae sapien pellentesque habitant morbi tristique senectus et. Ornare aenean euismod elementum nisi. Nunc consequat interdum varius sit amet mattis vulputate enim. Feugiat nisl pretium fusce id velit ut tortor.\r\n\r\nDiam in arcu cursus euismod quis viverra. Mattis enim ut tellus elementum sagittis vitae et. Non quam lacus suspendisse faucibus. Auctor elit sed vulputate mi sit amet mauris commodo. Nec feugiat nisl pretium fusce id velit. Tortor pretium viverra suspendisse potenti nullam ac tortor vitae. At tellus at urna condimentum mattis pellentesque id. Neque gravida in fermentum et sollicitudin ac. In arcu cursus euismod quis. Lorem ipsum dolor sit amet consectetur adipiscing elit. Fringilla urna porttitor rhoncus dolor. Ultricies tristique nulla aliquet enim tortor at auctor. Diam ut venenatis tellus in metus vulputate eu scelerisque. Pharetra massa massa ultricies mi quis hendrerit dolor magna eget. Enim lobortis scelerisque fermentum dui faucibus.\r\n\r\nSollicitudin tempor id eu nisl nunc mi ipsum faucibus vitae. Integer feugiat scelerisque varius morbi enim. Erat velit scelerisque in dictum non consectetur a erat nam. At in tellus integer feugiat scelerisque. Quam id leo in vitae turpis massa sed elementum. Tempus egestas sed sed risus pretium quam vulputate dignissim. Lacus vestibulum sed arcu non odio. Integer vitae justo eget magna fermentum iaculis eu. Nibh venenatis cras sed felis eget velit aliquet sagittis id. Natoque penatibus et magnis dis parturient montes nascetur ridiculus.\r\n\r\nVolutpat ac tincidunt vitae semper quis. Nisi vitae suscipit tellus mauris. Sed felis eget velit aliquet sagittis id consectetur purus ut. Sed viverra tellus in hac habitasse platea dictumst. Commodo nulla facilisi nullam vehicula ipsum. Sed egestas egestas fringilla phasellus faucibus. Fusce id velit ut tortor pretium viverra suspendisse. Aliquam purus sit amet luctus venenatis lectus. Mollis nunc sed id semper risus. Quis eleifend quam adipiscing vitae proin sagittis. Commodo quis imperdiet massa tincidunt.\r\n\r\nGravida cum sociis natoque penatibus et. Sodales ut etiam sit amet. Dignissim suspendisse in est ante. Aliquet enim tortor at auctor urna nunc. Parturient montes nascetur ridiculus mus mauris vitae ultricies leo integer. Egestas dui id ornare arcu odio. Sem viverra aliquet eget sit amet tellus cras. Aenean pharetra magna ac placerat vestibulum lectus. Lorem mollis aliquam ut porttitor. Lobortis mattis aliquam faucibus purus in massa tempor nec. Egestas pretium aenean pharetra magna ac placerat vestibulum lectus. Bibendum arcu vitae elementum curabitur. Enim praesent elementum facilisis leo vel fringilla est ullamcorper eget. Proin sagittis nisl rhoncus mattis rhoncus. Eros donec ac odio tempor orci dapibus ultrices in iaculis. At risus viverra adipiscing at in tellus integer feugiat. Convallis a cras semper auctor neque. Fermentum leo vel orci porta non pulvinar neque laoreet.'),
(3, '', '<h1>Toto je 1. paragraf</h1>\r\n    <p>\r\n        Contrary to popular belief, Lorem Ipsum is not simply random text. \r\n        It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. \r\n        Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,\r\n        consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.\r\n        Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC.\r\n        This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\",\r\n        comes from a line in section 1.10.32.\r\n        The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested.\r\n        Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form,\r\n        accompanied by English versions from the 1914 translation by H. Rackham.    \r\n    </p>\r\n    <br>\r\n    <h2>Toto je 2. paragraf</h2>'),
(4, '', '<h1>Toto je 1. paragraf</h1>\r\n    <p>\r\n        Contrary to popular belief, Lorem Ipsum is not simply random text. \r\n        It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. \r\n        Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,\r\n        consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.\r\n        Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC.\r\n        This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\",\r\n        comes from a line in section 1.10.32.\r\n        The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested.\r\n        Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form,\r\n        accompanied by English versions from the 1914 translation by H. Rackham.    \r\n    </p>\r\n    <br>\r\n    <h2>Toto je 2. paragraf</h2>');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(255) NOT NULL,
  `article_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `comment_text` varchar(2048) NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `comments`
--

INSERT INTO `comments` (`comment_id`, `article_id`, `user_id`, `comment_text`, `timestamp`) VALUES
(1, 1, 1, 'lorem ipsun', '2024-06-08 21:16:19');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE `users` (
  `ID` int(255) NOT NULL,
  `nick` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tel` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `users`
--

INSERT INTO `users` (`ID`, `nick`, `name`, `mail`, `password`, `tel`) VALUES
(1, 'janik1', 'janik', 'janik1@gmail.com', '$2y$10$/OHFUOpkdYN69qqHotOiTO3yGeYiOG2YSe/ozt4QthsEqktuVdFvu', '1651651984'),
(3, 'kokot', 'penis', 'jbmnt@kokot.sk', '$2y$10$9gt23aOmwv3JxAV.C/PU6uJywy0i12ZJgog86CIBGwAol180SxR6K', '0');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`article_id`);

--
-- Indexy pre tabuľku `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexy pre tabuľku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `nick` (`nick`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `articles`
--
ALTER TABLE `articles`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pre tabuľku `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pre tabuľku `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
