-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 28, 2021 at 09:27 AM
-- Server version: 5.7.23-23
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iffpeomy_spicyguitar_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_tbl`
--

CREATE TABLE `admin_tbl` (
  `id` double NOT NULL,
  `email` varchar(100) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) NOT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  `experience` year(4) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_tbl`
--

INSERT INTO `admin_tbl` (`id`, `email`, `firstname`, `lastname`, `avatar`, `telephone`, `twitter`, `experience`, `date_added`) VALUES
(1, 'ebukaodini@gmail.com', 'Ebuka', 'Odini', 'storage/public/avatars/e9d8814.jpg', '09093590559', '@__ebukaodini', 2020, '2020-11-03 03:41:43'),
(4, 'ksamchukwudi@gmail.com', 'Samuel', 'Kalu', NULL, '08173165202', NULL, 2020, '2020-11-03 03:36:27'),
(5, 'spicyjazzy4u@gmail.com', 'OC', 'Omofuma', 'storage/public/avatars/e16488d.jpg', '07061988174', NULL, 2020, '2021-01-29 15:43:00');

-- --------------------------------------------------------

--
-- Table structure for table `assignment_tbl`
--

CREATE TABLE `assignment_tbl` (
  `id` double NOT NULL,
  `course_id` double NOT NULL,
  `tutor_id` double NOT NULL,
  `note` text NOT NULL,
  `video` varchar(100) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `assignment_tbl`
--

INSERT INTO `assignment_tbl` (`id`, `course_id`, `tutor_id`, `note`, `video`, `date_added`) VALUES
(4, 28, 1, '1) Play all the notes of your C major Scale 2) What are the notes of your Key C major Chord\r\n4 trial', 'NULL', '2021-03-10 21:53:50'),
(5, 5, 5, '1) Play all the notes of your C major Scale\r\n2) What are the notes of your Key C major Chord', 'NULL', '2021-05-10 17:38:24'),
(6, 3, 5, '1) Play all the notes of your C major Scale 2) What are the notes of your Key C major Chord\r\n3) This is Beta Testing', 'NULL', '2021-03-10 21:50:27'),
(7, 29, 5, 'We are Testing this Assignment section', 'NULL', '2021-03-10 22:04:32');

-- --------------------------------------------------------

--
-- Table structure for table `auth_tbl`
--

CREATE TABLE `auth_tbl` (
  `id` double NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(10) NOT NULL,
  `token` varchar(10) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'inactive',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `auth_tbl`
--

INSERT INTO `auth_tbl` (`id`, `email`, `password`, `role`, `token`, `status`, `date_added`) VALUES
(1, 'ebukaodini@gmail.com', '$2y$10$qz7rDhadrPLLA03BSwObW.I236n5.l4/mS.7pbo.yRjKOPJIltkdC', 'admin', NULL, 'active', '2020-06-18 05:21:37'),
(4, 'ksamchukwudi@gmail.com', '$2y$10$VdUOcEj08aDesbouMLLFV.SkacHG2vB3EA8a2eEtNmX1Sy5P0hd9S', 'tutor', NULL, 'active', '2020-10-29 15:03:00'),
(9, 'spicyjazzy4u@gmail.com', '$2y$10$tRaPtmJwd0rOtWwvo.1eSOYykIJQjvc5Z7EjLTadsjaVfysSrbnme', 'admin', NULL, 'active', '2020-11-03 03:41:05'),
(10, 'j.hamlet@gmail.com', '$2y$10$YhfKpT../MXm5XEBXoofTuRKHtBd1EH51zsi1PuS6HW05ADhbhUcy', 'student', '343817', 'blocked', '2021-06-23 15:35:29'),
(13, 'odiniaugustine@gmail.com', '$2y$10$MVsJxNSHC/kSWUkUxZNmEexaYotwfpilvv4w5uTMchO7Y75n8D8p.', 'student', NULL, 'inactive', '2021-03-08 18:34:11'),
(14, 'khalidjanell@gmail.com', '$2y$10$qQJ1akWrPIm9lDN/BQC/sOLSmuf7A7AC69SW3GEuWdNrG4P4ury2S', 'student', '343817', 'active', '2021-05-05 18:39:37'),
(16, 'grepublic47@gmail.com', '$2y$10$CQ35EY9cybRVwWt6BSgeSe0CcJQGFibxHWwOJsbVM3a7jYP5yf.Ui', 'student', '343817', 'active', '2021-05-05 11:03:04');

-- --------------------------------------------------------

--
-- Table structure for table `category_tbl`
--

CREATE TABLE `category_tbl` (
  `id` int(4) NOT NULL,
  `category` varchar(15) NOT NULL,
  `thumbnail` varchar(100) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category_tbl`
--

INSERT INTO `category_tbl` (`id`, `category`, `thumbnail`, `date_added`) VALUES
(1, 'Beginners', 'storage/public/thumbnails/5a5ea2a.jpg', '2020-05-25 10:42:26'),
(2, 'Amateur', 'storage/public/thumbnails/cbe51e3.jpg', '2020-05-25 10:52:17'),
(3, 'Intermediate', 'storage/public/thumbnails/3140c73.jpg', '2020-05-25 10:53:03'),
(4, 'Advanced', 'storage/public/thumbnails/28467a9.jpg', '2020-05-25 10:53:23');

-- --------------------------------------------------------

--
-- Table structure for table `course_tbl`
--

CREATE TABLE `course_tbl` (
  `id` double NOT NULL,
  `category` int(4) NOT NULL,
  `course` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL DEFAULT 'No Description',
  `thumbnail` varchar(100) NOT NULL,
  `tutor` varchar(40) NOT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `featuredprice` double NOT NULL DEFAULT '0',
  `ord` int(20) NOT NULL COMMENT 'courses must be taken in this order.',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course_tbl`
--

INSERT INTO `course_tbl` (`id`, `category`, `course`, `description`, `thumbnail`, `tutor`, `featured`, `featuredprice`, `ord`, `date_added`, `active`) VALUES
(1, 1, 'Introduction', 'Welcome to Spicy Guitar Academy', 'storage/public/thumbnails/2affb40.jpg', 'OC Omofuma', 0, 0, 1, '2020-10-30 11:37:28', 0),
(2, 1, 'Introduction', 'An introduction to Guitar', 'storage/public/thumbnails/066bce0.jpg', 'OC Omofuma', 0, 0, 1, '2021-03-09 13:15:40', 0),
(3, 1, 'Introduction', 'Welcome to Spicy Guitar Academy', 'storage/public/thumbnails/e582216.jpg', 'OC Omofuma', 1, 1500, 1, '2021-06-22 12:54:26', 1),
(4, 1, 'Mak sound with d Gui', 'It is time, lets begin to make some sound with the Guitar.\r\nLet&#39;s begin to Strike the Guitar Strings', 'storage/public/thumbnails/dbcbca0.jpg', 'OC Omofuma', 0, 0, 2, '2020-11-16 12:05:39', 0),
(5, 1, 'LET&#39;S PLAY', 'Guys, we start now. Lets play some Guitar', 'storage/public/thumbnails/88cb57b.jpg', 'OC Omofuma', 0, 0, 3, '2021-04-06 04:42:23', 1),
(6, 2, 'Melodies & Arpeg', 'Playing melodies and simple song Arpeggios', 'storage/public/thumbnails/bbedf5e.jpg', 'OC Omofuma', 0, 0, 1, '2021-06-21 10:55:41', 0),
(7, 2, 'STRUMMING', 'Learn different Strumming patterns and be able to play along different songs adding more groove and', 'storage/public/thumbnails/33b6dff.jpg', 'OC Omofuma', 0, 0, 2, '2021-01-27 14:16:19', 0),
(8, 2, 'Simple Strumming', 'These simple strumming patterns will give us a good head start into awesome guitar Strumming', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 3, '2021-01-27 14:17:03', 0),
(9, 2, 'WALTZ TIME SIGNATURE (STRUMMING)', 'Waltz time signature is a unique one because its usually has 3 beats per bar.\r\nLearn different Strumming that works well with Waltz', 'storage/public/thumbnails/af9d775.jpg', 'OC Omofuma', 0, 0, 6, '2021-06-23 09:46:35', 1),
(10, 2, '1ST STRUMMING PATTERN', 'Strum pattern number 1.\r\nD U D DD DU ( down : up : down  :  down down : down up)', 'storage/public/thumbnails/5cf5b0e.jpg', 'OC Omofuma', 0, 0, 7, '2021-06-23 09:46:15', 1),
(11, 2, '2nd STRUM PATTERN (D D U DD U : U D U DD U)', 'Our 2nd Strum patten', 'storage/public/thumbnails/d7dacbf.jpg', 'OC Omofuma', 0, 0, 8, '2021-06-23 09:45:50', 1),
(12, 2, '3RD STRUM PATTERN (D U D U D : U D U : DDU)', 'Our 3rd strumming pattern\r\ndown up down up down  :  up down up :  down down up', 'storage/public/thumbnails/95c0c8d.jpg', 'OC Omofuma', 0, 0, 9, '2021-06-23 09:45:25', 1),
(13, 2, 'DIFFERENT STRUM FOR WORSHIP', 'These Strums will work for different feel of worship.', 'storage/public/thumbnails/5e9d4cb.jpg', 'OC Omofuma', 0, 0, 10, '2021-06-23 09:45:06', 1),
(14, 2, '6/8 TIME STRUMMING', 'Learn this Strum so that you can easy Strum along a 6/8 time signature', 'storage/public/thumbnails/5e55d97.jpg', 'OC Omofuma', 0, 0, 10, '2021-06-23 09:44:36', 0),
(15, 2, 'SONG AND STRUMS', 'Different variety of Strums', 'storage/public/thumbnails/5448475.jpg', 'OC Omofuma', 0, 0, 11, '2021-01-27 11:08:51', 1),
(16, 3, 'ALTERNATIVE PICKING - SPEED BUILDING', 'Alternate picking is a technique that enhances speed.\r\nIt&#39;s basically up/down picking direction.', 'storage/public/thumbnails/cb3d7b0.jpg', 'OC Omofuma', 0, 0, 1, '2021-06-23 13:42:05', 0),
(17, 2, 'TRANSITIONS', 'Let&#39;s learn to play on all keys, transiting from C to all other keys.', 'storage/public/thumbnails/36fa063.jpg', 'OC Omofuma', 0, 0, 14, '2021-04-11 21:18:15', 1),
(18, 3, 'MAJOR SCALE INTERVALS AND SEQUENCE', 'Learn to play different major scale intervals and sequences', 'storage/public/thumbnails/9853f6d.jpg', 'OC Omofuma', 0, 0, 3, '2021-01-28 04:55:18', 0),
(19, 3, 'DUETS - HARMONIES', 'Combining two different notes to make good harmonies and play em simultaneously as duets', 'storage/public/thumbnails/12b363d.jpg', 'OC Omofuma', 0, 0, 4, '2021-03-25 15:57:36', 0),
(20, 3, 'DUET APPLICATION', 'Application is very key.\r\nHere we will learn different ways to apply our Intervals, sequences and duets to songs', 'storage/public/thumbnails/e462556.jpg', 'OC Omofuma', 0, 0, 4, '2021-06-23 14:13:16', 1),
(21, 3, 'MORE STRUM GROOVES', 'More Strum techniques and possibilities.\r\nAdd different percussive grooves  to your\r\nLearn how to drum with the box guitar and strum along', 'storage/public/thumbnails/144346f.jpg', 'OC Omofuma', 0, 0, 6, '2021-06-23 12:59:40', 1),
(22, 3, 'THE PENTATONIC SCALE', 'Pentatonic is a 5 note music scale, one of the commonly used scales in music..', 'storage/public/thumbnails/1bae020.jpg', 'OC Omofuma', 0, 0, 12, '2021-06-23 12:55:52', 1),
(23, 3, 'SLUR / HAMMER-ON & PULL-OFF', 'To Solo and play melodies like a pro, These techniques Slur, Hammer-on and Pull-off is a must learn.\r\nLater on, we will also learn Bend and Vibrato,', 'storage/public/thumbnails/8635857.jpg', 'OC Omofuma', 0, 0, 8, '2021-06-23 12:46:10', 0),
(24, 3, 'IMPROVISING WITH PENTATONIC SCALE', 'A quick jump to improvising with Pentatonic scales.\r\nOnly a few intermediate improvisation concepts we will attempt here, then we will learn 21 pentatonic licks to get use started.', 'storage/public/thumbnails/09b605d.jpg', 'OC Omofuma', 0, 0, 9, '2020-11-08 18:00:37', 1),
(25, 3, 'TRANSITIONS', 'Transitions from one key to the other', 'storage/public/thumbnails/b43dd95.jpg', 'OC Omofuma', 0, 0, 4, '2020-11-08 18:52:32', 0),
(26, 2, 'PLAYING ON OTHER KEYS', 'We have 12 musical keys..\r\nPlaying from one key to another is easy, let&#039;s learn how.', 'storage/public/thumbnails/bab031e.jpg', 'OC Omofuma', 0, 0, 13, '2021-03-29 13:26:20', 1),
(27, 2, 'DOOR WAY TO INTERMEDIATE LEVEL', 'Here we are going to learn different things that will make us start thinking like an intermediate Guitarist\r\nThe fret board will be made more clear and relatable to us so that we can move around with our chords and notes all over the fret Board', 'storage/public/thumbnails/93e5e8f.jpg', 'OC Omofuma', 0, 0, 16, '2021-02-03 14:31:29', 0),
(28, 1, 'MAKING SOUND WITH THE GUITAR', 'It is time! lets begin to make some sound with the Guitar as we strike the Strings.', 'storage/public/thumbnails/ec56519.jpg', 'OC Omofuma', 1, 2000, 2, '2021-04-06 03:58:56', 1),
(29, 1, 'BEGINNER&#39;S FINGER EXERCISE', 'Simple finger exercises to help a Beginner&#39;s finger grip and flexibility.', 'storage/public/thumbnails/5906eea.jpg', 'OC Omofuma', 0, 0, 4, '2021-06-23 09:42:33', 1),
(30, 2, 'Understanding the Guitar Fret Board', 'Find your way around the Guitar Fret Board.\r\nThis helps you to be able to locate and play notes and chord all over the fret board', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 2, '2021-01-26 21:37:02', 0),
(31, 2, 'UNDERSTANDING THE GUITAR FRET BOARD', 'Learn to play different shapes and positions of your Major scale and Chords.', 'storage/public/thumbnails/9326ffa.jpg', 'OC Omofuma', 1, 5000, 3, '2021-06-23 09:48:13', 1),
(32, 2, 'AMATEUR FINGER EXERCISES', 'No Description', 'storage/public/thumbnails/fc7e34b.jpg', 'OC Omofuma', 0, 0, 4, '2021-06-23 09:47:56', 1),
(33, 2, 'STRUM - CHORD CHANGES - SONG PROGRESSIONS', 'Learn different Strumming patterns and be able to play along different songs adding mo', 'storage/public/thumbnails/f115f34.jpg', 'OC Omofuma', 0, 0, 5, '2021-06-23 09:47:35', 1),
(34, 2, 'FINGER PICKING (Amateur)', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 17, '2021-03-29 13:19:56', 1),
(35, 2, 'EAR TRAINING', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 18, '2021-03-29 13:19:14', 1),
(36, 2, 'CREATING SIMPLE GUITAR LINES', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 14, '2021-02-07 07:42:31', 0),
(37, 2, 'PLAYING SIMPLE GUITAR LINES', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 16, '2021-03-29 13:24:24', 1),
(38, 2, 'MELODIES & ARPEGGIOS', 'No Description', 'storage/public/thumbnails/33a80a9.jpg', 'OC Omofuma', 0, 0, 1, '2021-06-21 10:56:03', 1),
(39, 3, 'TRANSITIONS', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 2, '2021-01-28 04:47:28', 0),
(40, 3, 'SIMPLE TECHNIQUES (intermediate)', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 2, '2021-01-28 04:48:22', 1),
(41, 3, 'APPLICATION - SEQUENCE - INTERVALS and DUETS', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 5, '2021-03-25 15:57:15', 0),
(42, 3, 'FINGER PICKING', 'No Description', 'storage/public/thumbnails/8dfac6e.jpg', 'OC Omofuma', 0, 0, 7, '2021-06-23 12:59:13', 1),
(43, 3, 'ARIARIA GUITAR STYLE', 'No Description', 'storage/public/thumbnails/4fbdc7f.jpg', 'OC Omofuma', 0, 0, 8, '2021-06-23 12:58:10', 1),
(44, 3, 'HIGH LIFE GUITAR', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 9, '2021-06-23 12:57:32', 1),
(45, 3, 'MAKOSA GUITAR LINES', 'No Description', 'storage/public/thumbnails/91fb6c1.jpg', 'OC Omofuma', 0, 0, 10, '2021-06-23 12:56:26', 1),
(46, 3, 'STRUMMING - PERCUSSIVE BEAT STYLE', 'No Description', 'storage/public/thumbnails/ba1cf0f.jpg', 'OC Omofuma', 0, 0, 15, '2021-06-23 13:06:11', 0),
(47, 3, 'BEND AND VIBRATO', 'No Description', 'storage/public/thumbnails/c765c03.jpg', 'OC Omofuma', 0, 0, 16, '2021-06-23 12:52:54', 1),
(48, 3, 'GUITAR SEQUENCES (intermediate)', 'No Description', 'storage/public/thumbnails/c7f1622.jpg', 'OC Omofuma', 0, 0, 17, '2021-06-23 14:40:07', 0),
(49, 3, 'MUSIC SCALES - FINGER EXERCISES', 'No Description', 'storage/public/thumbnails/323f0c1.jpg', 'OC Omofuma', 0, 0, 17, '2021-06-23 14:40:30', 1),
(50, 3, 'CHORD FORMATION', 'No Description', 'storage/public/thumbnails/42c1e73.jpg', 'OC Omofuma', 0, 0, 18, '2021-06-23 14:40:51', 1),
(51, 3, 'INTERVALS - SEQUENCES AND DUET HARMONIES', 'Learn to play different major scale intervals and sequences', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 3, '2021-06-23 14:14:46', 0),
(52, 4, 'ADVANCED CHORDS', 'No Description', 'storage/public/thumbnails/f81de11.jpg', 'OC Omofuma', 0, 0, 1, '2021-03-20 10:54:32', 1),
(53, 4, 'PASSING CHORDS AND SUBSTITUTION', 'No Description', 'storage/public/thumbnails/1a4ce42.jpg', 'OC Omofuma', 0, 0, 2, '2021-03-20 10:55:16', 1),
(54, 4, 'CIRCLE OF 4TH', 'No Description', 'storage/public/thumbnails/8bda8cc.jpg', 'OC Omofuma', 0, 0, 3, '2021-06-28 09:53:29', 1),
(55, 4, 'SWEEP PICKING', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 4, '2021-01-28 08:48:50', 1),
(56, 4, 'SEQUENCES (advanced', 'No Description', 'storage/public/thumbnails/65f074a.jpg', 'OC Omofuma', 0, 0, 5, '2021-03-20 10:56:06', 1),
(57, 4, 'DIFFERENT SONG PROGRESSIONS', 'No Description', 'storage/public/thumbnails/19acd69.jpg', 'OC Omofuma', 0, 0, 6, '2021-03-20 10:57:17', 1),
(58, 4, 'JAZZ STANDARDS', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 7, '2021-01-28 14:23:21', 1),
(59, 4, 'JAZZ GUITAR SOLO (Chord and melody)', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 10, '2021-01-28 16:58:52', 1),
(60, 4, 'IMPROVISATION', 'No Description', 'storage/public/thumbnails/3214166.jpg', 'OC Omofuma', 0, 0, 8, '2021-03-20 10:58:28', 1),
(61, 4, 'MODE', 'No Description', 'storage/public/thumbnails/b2359d0.jpg', 'OC Omofuma', 0, 0, 9, '2021-03-20 10:59:04', 1),
(62, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 8, '2021-03-09 13:15:40', 0),
(63, 1, 'Test Course', 'Just a test course', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 8, '2021-03-09 13:15:40', 0),
(64, 1, 'Test Course', 'Just a test course', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 8, '2021-03-09 13:15:40', 0),
(65, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 5, '2021-03-09 13:15:40', 0),
(66, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 6, '2021-03-09 13:15:40', 0),
(67, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 7, '2021-03-09 13:15:40', 0),
(68, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 8, '2021-03-09 13:15:40', 0),
(69, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 5, '2021-03-09 13:15:40', 0),
(70, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 5, '2021-03-09 13:15:40', 0),
(71, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 5, '2021-03-09 13:15:40', 0),
(72, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 5, '2021-03-09 13:15:40', 0),
(73, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 5, '2021-03-09 13:15:40', 0),
(74, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 5, '2021-03-09 13:15:40', 0),
(75, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 5, '2021-03-09 13:15:40', 0),
(76, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 5, '2021-03-09 13:15:40', 0),
(77, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 5, '2021-03-09 13:15:40', 0),
(78, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 6, '2021-03-09 13:15:40', 0),
(79, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 7, '2021-03-09 13:15:40', 0),
(80, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 8, '2021-03-09 13:15:40', 0),
(81, 1, 'Test Course', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 9, '2021-03-09 13:15:40', 0),
(82, 3, 'WORSHIP GUITAR', 'No Description', 'storage/public/thumbnails/5627eec.jpg', 'OC Omofuma', 0, 0, 19, '2021-06-23 14:41:04', 1),
(89, 1, 'Test Course', 'None', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 1, 5000, 5, '2021-03-09 13:15:40', 0),
(90, 3, 'Percussive Guitar (Guitar Drumming)', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 14, '2021-06-23 13:40:53', 0),
(91, 3, 'PERCUSSIVE GUITAR (GUITAR DRUMMING)', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 6, '2021-06-23 12:45:52', 0),
(92, 2, 'Left and Right hand mute lines', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 15, '2021-03-29 13:24:57', 1),
(93, 2, 'Approach to song Progressions ( Amateur Basics )', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 12, '2021-03-29 13:28:34', 1),
(94, 1, 'Test Course &amp; More', 'No Description', 'storage/public/thumbnails/default.jpg', 'Ebuka Odini', 0, 0, 7, '2021-05-04 21:27:58', 0),
(95, 1, 'UNARRANGED', 'This just to transport everything here, we will have to rearrange them all', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 1, '2021-06-28 08:50:30', 0),
(96, 3, 'SOLOS AND LICKS', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 11, '2021-06-22 05:47:41', 1),
(97, 3, 'INTERMEDIATE SEQUENCE AND APPLICATION', 'No Description', 'storage/public/thumbnails/4a74255.jpg', 'OC Omofuma', 0, 0, 13, '2021-06-23 13:41:47', 1),
(98, 3, 'DOUBLE STOP', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 5, '2021-06-22 11:02:29', 1),
(99, 4, 'QUARTAL CONCEPT', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 6, '2021-06-22 12:20:02', 1),
(100, 2, 'CHORDS AND SONG PROGRESSIONS', 'In this Course, we will learn more Chords, and hold chords Fuller  and also more song progressions', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 2, '2021-06-23 09:20:03', 1),
(101, 3, 'PERCUSSIVE ACOUSTIC - GUITAR BEAT DRUMMING', 'No Description', 'storage/public/thumbnails/93ad27e.jpg', 'OC Omofuma', 0, 0, 14, '2021-06-23 13:12:44', 1),
(102, 3, 'ALTERNATE PICKING - SPEED BUILDING', 'Alternate picking is a technique that enhances speed. It&#39;s basically up/down picking direction.', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 1, '2021-06-23 13:11:57', 1),
(103, 3, 'INTERVALS - DUETS - APPLICATION', 'No Description', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 4, '2021-06-23 14:16:42', 0),
(104, 3, 'SEQUENCES - DUET HARMONIES', 'Learn to play different major scale intervals and sequences', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 3, '2021-06-23 14:01:11', 1),
(105, 3, 'DUETS - HARMONIES APPLICATION', 'Application is very key. Here we will learn different ways to apply our Intervals, sequences and due', 'storage/public/thumbnails/default.jpg', 'OC Omofuma', 0, 0, 4, '2021-06-23 14:13:52', 0);

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE `forums` (
  `id` double NOT NULL,
  `category_id` double NOT NULL,
  `sender` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `reply_id` double NOT NULL DEFAULT '0',
  `comment` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `forums`
--

INSERT INTO `forums` (`id`, `category_id`, `sender`, `is_admin`, `reply_id`, `comment`, `date_added`) VALUES
(1, 1, 'j.hamlet@gmail.com', 0, 0, 'Hi', '2021-05-04 13:03:20'),
(2, 1, 'j.hamlet@gmail.com', 0, 0, 'Holz', '2021-05-04 13:04:36'),
(3, 1, 'j.hamlet@gmail.com', 0, 0, 'Hi Today', '2021-05-04 16:28:26'),
(4, 1, 'j.hamlet@gmail.com', 0, 0, 'spicyguitaracademy is the best  spicyguitaracademy is the best spicyguitaracademy is the best spicyguitaracademy is the best ', '2021-05-04 16:32:33'),
(5, 1, 'j.hamlet@gmail.com', 0, 0, 'hello hello hello hello hello hello hello', '2021-05-04 16:34:27'),
(7, 2, 'khalidjanell@gmail.com', 0, 0, 'Hola', '2021-05-04 17:25:03'),
(8, 2, 'khalidjanell@gmail.com', 0, 0, 'Hi again', '2021-05-04 17:25:26'),
(9, 2, 'khalidjanell@gmail.com', 0, 0, 'Hi', '2021-05-04 17:25:55'),
(10, 2, 'khalidjanell@gmail.com', 0, 0, 'Hey', '2021-05-04 17:26:22'),
(11, 2, 'khalidjanell@gmail.com', 0, 0, 'Hi ', '2021-05-04 17:27:03'),
(12, 2, 'khalidjanell@gmail.com', 0, 0, 'Great', '2021-05-04 17:56:12'),
(13, 2, 'khalidjanell@gmail.com', 0, 0, 'Hi', '2021-05-04 17:56:42'),
(14, 2, 'khalidjanell@gmail.com', 0, 0, 'Everyone', '2021-05-04 17:56:56'),
(15, 2, 'khalidjanell@gmail.com', 0, 0, 'welcome', '2021-05-04 17:57:16'),
(16, 1, 'j.hamlet@gmail.com', 0, 0, 'Hi', '2021-05-04 21:34:32'),
(17, 1, 'j.hamlet@gmail.com', 0, 0, 'Hola', '2021-05-04 21:34:46'),
(18, 1, 'j.hamlet@gmail.com', 0, 0, 'ty', '2021-05-04 21:34:55'),
(19, 1, 'j.hamlet@gmail.com', 0, 0, 'Hola', '2021-05-05 14:19:31'),
(20, 1, 'j.hamlet@gmail.com', 0, 0, 'Hola', '2021-05-05 15:47:10'),
(21, 1, 'j.hamlet@gmail.com', 0, 0, 'Ebuka', '2021-05-05 15:47:33'),
(22, 1, 'j.hamlet@gmail.com', 0, 0, 'Hi Ebuka Odini', '2021-05-05 16:18:23'),
(23, 1, 'j.hamlet@gmail.com', 0, 0, 'Hello There', '2021-05-05 16:19:22'),
(24, 1, 'j.hamlet@gmail.com', 0, 0, 'Hi Again', '2021-05-05 16:19:53'),
(25, 1, 'j.hamlet@gmail.com', 0, 0, 'Hi Again Again', '2021-05-05 16:21:43'),
(26, 1, 'j.hamlet@gmail.com', 0, 0, 'Hello', '2021-05-05 16:32:42'),
(27, 1, 'j.hamlet@gmail.com', 0, 0, 'Hi', '2021-05-05 16:33:15'),
(28, 1, 'j.hamlet@gmail.com', 0, 0, 'Hullo', '2021-05-05 16:33:57'),
(29, 1, 'j.hamlet@gmail.com', 0, 0, 'Jan', '2021-05-05 16:35:40'),
(30, 1, 'khalidjanell@gmail.com', 0, 0, 'Feb', '2021-05-05 16:35:59'),
(31, 1, 'j.hamlet@gmail.com', 0, 0, 'Mar', '2021-05-05 16:36:28'),
(32, 1, 'j.hamlet@gmail.com', 0, 0, 'Apr', '2021-05-05 16:37:02'),
(33, 1, 'j.hamlet@gmail.com', 0, 0, 'May', '2021-05-05 16:38:04'),
(34, 1, 'j.hamlet@gmail.com', 0, 0, 'hi adain', '2021-05-05 16:41:29'),
(35, 1, 'j.hamlet@gmail.com', 0, 0, 'hullo', '2021-05-05 16:45:06'),
(36, 1, 'j.hamlet@gmail.com', 0, 0, 'Hi', '2021-05-05 16:48:14'),
(37, 1, 'j.hamlet@gmail.com', 0, 0, 'Jun', '2021-05-05 16:49:32'),
(38, 1, 'j.hamlet@gmail.com', 0, 0, 'Jul', '2021-05-05 17:03:18'),
(39, 1, 'j.hamlet@gmail.com', 0, 0, 'Aug', '2021-05-05 17:03:32'),
(40, 1, 'j.hamlet@gmail.com', 0, 0, 'Sep', '2021-05-05 17:05:55'),
(41, 1, 'j.hamlet@gmail.com', 0, 30, 'Oct', '2021-05-05 17:06:06'),
(42, 1, 'j.hamlet@gmail.com', 0, 0, 'Hi', '2021-05-05 17:11:57'),
(43, 1, 'j.hamlet@gmail.com', 0, 0, 'gui', '2021-05-05 17:28:17'),
(44, 1, 'j.hamlet@gmail.com', 0, 0, 'hu', '2021-05-05 17:28:38'),
(45, 1, 'j.hamlet@gmail.com', 0, 0, 'fr', '2021-05-05 17:28:57'),
(46, 1, 'j.hamlet@gmail.com', 0, 0, 'r', '2021-05-05 17:29:18'),
(47, 1, 'j.hamlet@gmail.com', 0, 0, 'k', '2021-05-05 17:30:17'),
(48, 1, 'j.hamlet@gmail.com', 0, 0, 'Huri', '2021-05-05 17:32:23'),
(49, 2, 'khalidjanell@gmail.com', 0, 0, 'Welcome', '2021-05-05 18:40:39'),
(50, 2, 'khalidjanell@gmail.com', 0, 0, 'Replying myself', '2021-05-05 18:50:57'),
(51, 2, 'khalidjanell@gmail.com', 0, 0, 'Hi', '2021-05-05 18:52:47'),
(52, 2, 'khalidjanell@gmail.com', 0, 0, 'Hi', '2021-05-05 18:53:55'),
(53, 2, 'khalidjanell@gmail.com', 0, 52, 'Hi Me', '2021-05-05 18:55:51'),
(54, 2, 'khalidjanell@gmail.com', 0, 0, 'Gttt', '2021-05-05 18:57:00'),
(55, 2, 'khalidjanell@gmail.com', 0, 53, 'Hi You', '2021-05-05 20:18:51'),
(56, 2, 'khalidjanell@gmail.com', 0, 15, 'Hi Junior, welcome to amateurs group', '2021-05-10 15:44:15'),
(57, 1, 'j.hamlet@gmail.com', 0, 41, 'Nov', '2021-05-10 17:55:33'),
(58, 1, 'j.hamlet@gmail.com', 0, 34, 'Hello admin', '2021-05-16 15:31:44'),
(59, 1, 'j.hamlet@gmail.com', 0, 58, 'Whats happening', '2021-05-16 15:41:43'),
(60, 1, 'j.hamlet@gmail.com', 0, 5, 'Hello Hello Hello Hello ', '2021-05-16 20:14:42'),
(61, 1, 'j.hamlet@gmail.com', 0, 0, '', '2021-05-16 20:16:00'),
(62, 1, 'j.hamlet@gmail.com', 0, 4, 'yes', '2021-05-17 09:22:36'),
(63, 1, 'j.hamlet@gmail.com', 0, 30, 'hi janell', '2021-05-17 11:50:14');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_tbl`
--

CREATE TABLE `lesson_tbl` (
  `id` double NOT NULL,
  `course` double NOT NULL,
  `lesson` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL DEFAULT 'No Description',
  `ord` int(11) NOT NULL,
  `thumbnail` varchar(100) NOT NULL,
  `tutor` varchar(40) NOT NULL,
  `low_video` varchar(100) DEFAULT NULL,
  `high_video` varchar(100) DEFAULT NULL,
  `audio` varchar(100) DEFAULT NULL,
  `practice_audio` varchar(100) DEFAULT NULL,
  `tablature` varchar(100) DEFAULT NULL,
  `note` text,
  `free` tinyint(1) NOT NULL DEFAULT '0',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lesson_tbl`
--

INSERT INTO `lesson_tbl` (`id`, `course`, `lesson`, `description`, `ord`, `thumbnail`, `tutor`, `low_video`, `high_video`, `audio`, `practice_audio`, `tablature`, `note`, `free`, `date_added`, `active`) VALUES
(1, 2, 'Introduction', 'Welcome to Spicy Guitar Academy', 1, 'storage/public/thumbnails/7b3169c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/ccfb1e8.mp4', NULL, NULL, NULL, 'Welcome to Spicy Guitar Academy', 0, '2020-11-16 12:06:21', 0),
(2, 3, 'Intro', 'Fundamentals', 0, 'storage/public/thumbnails/c7332fc.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/289b480.mp4', 'storage/public/tutorials/audios/77de6d0.mpeg', NULL, NULL, NULL, 0, '2020-11-06 15:50:47', 0),
(3, 2, 'Guitar', 'The right way to hold and pose with the Guitar', 1, 'storage/public/thumbnails/6e77f7f.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/15d938c.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-15 22:30:55', 0),
(4, 2, 'Guitar Anatomy', 'Know the different parts of the Guitar', 3, 'storage/public/thumbnails/ab52ad7.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/4170731.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-16 12:06:21', 0),
(5, 2, '4) The Wanna be gita', 'Few tips to get you started on your Guitar journey', 0, 'storage/public/thumbnails/8ec807c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/c2c8ba1.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-15 11:09:03', 0),
(6, 4, 'Finger and String nu', 'The numbers of your fingers and Strings', 0, 'storage/public/thumbnails/dcef398.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2020-11-16 12:04:49', 0),
(7, 3, 'Holding the Pick', 'In this lesson, we will learn a very efficient way to hold the Guitar pick.\r\nPlease take this seriously, holding the pick the wrong way will eventually lead to different unforseen limitations.', 5, 'storage/public/thumbnails/f19d619.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/3434981.mp4', NULL, NULL, NULL, 'Different Guitarist hold picks differently, but this one we are learning here is very efficient, it will thrive in different guitar playing styles.\r\nThe Wrong pick grip could make a thousand things go wrong along the way. \r\nSome guitarist face frustration after years of unfruitful guitar playing only to discover at last that the wrong pick grip had been the cause all along..', 0, '2021-06-22 12:59:41', 0),
(8, 28, 'Tuning the Guitar', 'How to tune your Guitar to a standard tune.', 1, 'storage/public/thumbnails/d1a3de0.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/cca0951.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-08 17:28:33', 1),
(9, 2, '4) Strike and hold n', 'Learn how to Strike and hold notes', 0, 'storage/public/thumbnails/fe74d11.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/4d91a6b.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-15 22:30:49', 0),
(10, 4, '5)Maj Scale 1 string', 'Key C major Scale one String', 5, 'storage/public/thumbnails/6b849a3.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a204a99.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/d339c7b.pdf', NULL, 0, '2020-11-16 12:04:58', 0),
(11, 28, 'Music Keys', 'Here we will learn to read all 12 different music keys and also understand Modulations and how it applies to the formation of the major scale with the reading of Tone and Semi-Tone.', 3, 'storage/public/thumbnails/b95d092.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/92fe459.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 08:18:11', 1),
(12, 28, 'Key C Maj scale Neck', 'Learn how to play key C major scale on the Guitar neck.', 5, 'storage/public/thumbnails/ba02534.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/315d1e4.mp4', NULL, 'storage/public/tutorials/practice/7b616f6.mpeg', 'storage/public/tutorials/tablatures/a6c11b7.pdf', NULL, 0, '2021-04-08 17:54:04', 1),
(13, 5, 'Two notes Chords', 'Learn to hold two notes simple Chords.\r\nC : F : G', 1, 'storage/public/thumbnails/831f2ab.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/4c85fe7.mp4', NULL, 'storage/public/tutorials/practice/e686c07.mpeg', NULL, 'Make sure each note of the Chord sound clear enough.\r\nAdjust your fingers where necessary so that one finger doesn&#039;t obstruct the sound of the other finger ', 0, '2021-04-03 18:35:04', 1),
(14, 5, 'Extend C maj Scale', 'Extending the C major Scale one the Guitar neck', 5, 'storage/public/thumbnails/1d6b88a.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/0862b6c.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/3f6a52c.pdf', NULL, 0, '2020-11-17 10:11:32', 0),
(15, 5, 'Staccato techniques', 'Staccato; a technique to control the sound vibration of the notes we play.', 5, 'storage/public/thumbnails/32c9952.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/9e8264a.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-08 18:16:15', 1),
(16, 5, 'Sofa Notation A.', 'Sofa Notation for different songs Part A', 5, 'storage/public/thumbnails/f16bf66.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2020-11-06 16:52:31', 0),
(17, 5, '6) Sofa Notation B', 'Sofa Notation for different song&#39;s Part B', 6, 'storage/public/thumbnails/e13a122.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2020-11-17 10:12:18', 0),
(18, 29, 'Bar &  Notations', 'Counting Bar and time Notations.', 1, 'storage/public/thumbnails/5d77d5d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/89b6a26.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-08 18:25:16', 1),
(19, 38, 'C F G simple chords', 'C F G simple chords', 1, 'storage/public/thumbnails/12e0714.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/33ebb4f.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/3ac811a.pdf', NULL, 0, '2021-01-27 10:59:22', 1),
(20, 6, '3) Arpeggios & m', 'Chord Arpeggios and Melodies', 3, 'storage/public/thumbnails/18b5be6.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-21 10:55:41', 0),
(21, 100, 'CFG Complete chord A', 'C F G complete chords part A', 3, 'storage/public/thumbnails/4214d8b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b6d2f69.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/42b78e5.pdf', NULL, 0, '2021-06-23 09:25:25', 1),
(22, 100, 'CFG Complete Chord B', 'C F G complete chords part B', 4, 'storage/public/thumbnails/e05246e.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/cabffc1.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/de58827.pdf', NULL, 0, '2021-06-23 09:25:48', 1),
(23, 38, 'C : Am : Dm : G prog', 'C major, A minor, D minor and G major chord Progression', 11, 'storage/public/thumbnails/81605f0.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/8569fec.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 13:28:59', 0),
(24, 38, 'Minor Chord changes', 'Minor Chord changes', 12, 'storage/public/thumbnails/65560fb.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/3f64900.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 13:33:43', 0),
(25, 6, 'Melodies & Simple Chord Arpeggios', 'Playing different melodies and simple chord Arpeggios', 1, 'storage/public/thumbnails/5d1619a.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/aee5d42.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-21 10:55:41', 0),
(26, 100, 'Key C all Neck Chords', 'Learn all the neck chords on Key C', 5, 'storage/public/thumbnails/dafa4c2.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/9839dfd.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/efacace.pdf', NULL, 0, '2021-06-23 09:26:25', 1),
(27, 38, 'Arpeggio & Melodies', 'Learn some Chord Arpeggios and sweet melodies', 2, 'storage/public/thumbnails/1065571.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/4a56db5.mp4', NULL, 'storage/public/tutorials/practice/5602e88.mpeg', 'storage/public/tutorials/tablatures/8d11b70.pdf', NULL, 0, '2021-06-22 13:21:24', 0),
(28, 5, 'Sofa Notation for different songs Part B', 'Learn how to the sofa notation of different song melodies (Part B)', 7, 'storage/public/thumbnails/be3415a.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/949af0e.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/9453d87.pdf', NULL, 0, '2021-04-08 18:20:29', 1),
(29, 5, 'Sofa Notation for different songs Part A', 'Learn how to play the sofa notation of different song melodies. (Part A)', 6, 'storage/public/thumbnails/d446f64.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/239e408.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/9b81bd6.pdf', NULL, 0, '2021-04-08 18:19:27', 1),
(30, 33, 'Strumming Tips', 'Let&#39;s get started by listening to these Strumming Tips', 1, 'storage/public/thumbnails/04ff514.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/5001e7b.mp4', NULL, NULL, NULL, NULL, 0, '2021-01-26 22:48:46', 1),
(31, 33, 'Simple Strumming', 'This is a good place to start our strumming journey.', 3, 'storage/public/thumbnails/46b7386.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/caf2af8.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-12 09:29:45', 1),
(32, 33, 'Boom Chick and Boom Chicka Strumming', 'A simple way to go for starter guitar strumming.', 4, 'storage/public/thumbnails/8494942.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/de8ad75.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-12 09:36:07', 1),
(33, 33, 'Mary had a little lamb ( Boom chick boom Chicka Strum)', 'Applying Boom chick and boom Chicka Strumming over a simple song, MARY HAD A LITTLE LAMB', 6, 'storage/public/thumbnails/e46f9ac.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/e701915.mp4', NULL, 'storage/public/tutorials/practice/37ae839.mpeg', NULL, NULL, 0, '2021-04-09 22:13:25', 1),
(34, 33, 'Happy Birthday (Boom chick boom Chicka Strum)', 'Applying Boom chick and boom Chicka Strum over a simple song HAPPY BIRTHDAY', 7, 'storage/public/thumbnails/cae2977.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b947cc5.mp4', NULL, NULL, NULL, NULL, 0, '2021-02-03 15:42:47', 1),
(35, 33, 'Boom Chick and Boom Chicka Strum (Different Chords)', 'Learn how to Strum the Boom Chick Boom Chicka Strumming pattern with different Chords.', 5, 'storage/public/thumbnails/2915631.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/da00647.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-12 09:44:14', 1),
(36, 9, 'Waltz time Strumming INTRO', 'This is an introduction to waltz time Strumming', 1, 'storage/public/thumbnails/4059f13.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/52f604b.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-08 10:31:31', 1),
(37, 9, '2nd waltz Strum patten', 'Learn this other Strum patten for waltz time signature', 2, 'storage/public/thumbnails/a1bd327.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/00292ad.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-08 10:44:45', 1),
(38, 9, 'Because he lives (2nd waltz Strum pattern)', 'Apply the 2nd waltz Strum pattern on this song (BECAUSE HE LIVES)', 4, 'storage/public/thumbnails/e4840e1.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/9744813.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-08 10:55:46', 1),
(39, 9, 'Waltz Strumming on different Chords', 'Learn to play the waltz Strumming over different Chords', 3, 'storage/public/thumbnails/bcbf46d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/04258b9.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-08 10:54:09', 1),
(40, 9, 'Happy Birthday ( Waltz Strum pattern )', 'Play over HAPPY BIRTHDAY song with Waltz time Strumming', 5, 'storage/public/thumbnails/fa34f29.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/4eb92cd.mp4', NULL, 'storage/public/tutorials/practice/63b6d1d.mpeg', NULL, NULL, 0, '2021-04-08 09:59:05', 1),
(41, 33, 'Applying different Strum patterns on (I LOVE JESUS)', 'Apply different Strum patterns on i love JESUS song', 8, 'storage/public/thumbnails/06eec29.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/34b376a.mp4', NULL, NULL, NULL, NULL, 0, '2021-02-03 15:43:17', 1),
(42, 10, '1st Strum pattern ( C F G & Amin Emin Progression)', 'Learn this 1st Strum Pattern and also learn to apply it over different chords\r\nC F G and Am Em Progressions', 1, 'storage/public/thumbnails/5a6fbb8.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/2407ae1.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-08 11:03:37', 1),
(43, 10, '1st Strum pattern ( AMEN AMEN )', 'Use our 1st Strum pattern over AMEN AMEN song', 2, 'storage/public/thumbnails/0062181.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/ac9628d.mp4', NULL, 'storage/public/tutorials/practice/c609454.mpeg', NULL, NULL, 0, '2021-04-09 22:33:28', 1),
(44, 10, '1st Strum pattern (NARA)', 'Strum over song NARA by Tim Godfrey with our 1st Strum pattern', 3, 'storage/public/thumbnails/467a118.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b61b5f7.mp4', NULL, 'storage/public/tutorials/practice/c8e331b.mpeg', NULL, NULL, 0, '2021-04-09 22:39:24', 1),
(45, 10, '1st Strum pattern (ONISE IYANU)', 'Strum over song ONISE IYANU by Nathaniel Bassey with our 1st Strum pattern', 4, 'storage/public/thumbnails/8d4e932.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/23b5060.mp4', NULL, 'storage/public/tutorials/practice/481763d.mpeg', NULL, NULL, 0, '2021-04-09 22:42:38', 1),
(46, 11, '2nd Strum pattern ( C : Em : F : G )', 'Learn our 2nd Strum pattern here and apply it over our Cmaj : Emin : Fmaj : Gmaj Chords', 1, 'storage/public/thumbnails/c6dfb06.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/77d9dd9.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-08 11:13:34', 1),
(47, 11, 'The only God ( 2nd Strum pattern)', 'Learn how to apply our 2nd Strum pattern over Song THE ONLY GOD by Nathaniel Bassey', 2, 'storage/public/thumbnails/41e2844.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6aa29eb.mp4', NULL, 'storage/public/tutorials/practice/0871f4e.mpeg', NULL, NULL, 0, '2021-04-10 11:03:30', 1),
(48, 11, 'Onise Iyanu ( 2nd Strum Pattern)', 'Learn how to apply our 2nd Strum pattern over Song ONISE IYANU by Nathaniel Bassey', 3, 'storage/public/thumbnails/c688069.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/ab9f0ae.mp4', NULL, 'storage/public/tutorials/practice/6556bb4.mpeg', NULL, NULL, 0, '2021-04-10 11:04:29', 1),
(49, 12, 'Em : Am : CMaj : DMaj (3rd Strum Pattern)', 'Master the 3rd Strum pattern with these Chord changes\r\nE minor : Aminor : CMajor : DMajor', 1, 'storage/public/thumbnails/d600644.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/61d112d.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-08 11:27:31', 1),
(50, 12, '3rd Strum Pattern on 3 different song', 'Learn tp walk this our very 3rd Strum pattern over 3 different song', 2, 'storage/public/thumbnails/27dc442.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/dea8291.mp4', NULL, 'storage/public/tutorials/practice/26881c5.mpeg', NULL, NULL, 0, '2021-04-25 22:15:18', 1),
(51, 13, 'Worship Strum - Your Presence is heaven to me', 'Learn to Play this worship Strum over this worship song, Your Presence is Heaven to me', 1, 'storage/public/thumbnails/ae70a3b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/feb9213.mp4', NULL, 'storage/public/tutorials/practice/e410b9c.mpeg', NULL, NULL, 0, '2021-04-10 11:12:50', 1),
(52, 14, '6/8 time Strum', '6/8 Beat Strum', 1, 'storage/public/thumbnails/7fdb6a6.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/52c048f.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 09:44:36', 0),
(53, 15, 'Strum - Melody - African Queen', 'Strum pattern, Melody and Progression for AFRICAN QUEEN by 2 Face Idibia', 7, 'storage/public/thumbnails/dc6b828.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/73b04e6.mp4', NULL, 'storage/public/tutorials/practice/1913706.mpeg', NULL, NULL, 0, '2021-06-23 10:00:46', 1),
(54, 15, 'Strum - Melody - No Woman Cry', 'Learn this Strum pattern, also the sofa notation and chord progression of No Woman no Cry by Bob Marley', 8, 'storage/public/thumbnails/da0997f.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/21e38ab.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 10:02:06', 1),
(55, 102, 'Major Scale Alternate picking F#', 'We will learn to play our Major scale on Key F# strictly using alternate picking', 1, 'storage/public/thumbnails/2d46851.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/5964be5.mp4', NULL, NULL, NULL, 'Alternate picking is speed picking, our speed will be limited if all we do is only a down direction picking, so let&#039;s be sure to learn this alternate picking Technique diligently ', 0, '2021-06-23 13:16:54', 1),
(56, 29, 'One String Chromatic scale (Alternate Picking)', 'Chromatic scale on one string using alternate picking. Alternate picking is simply to Strike down and up.', 3, 'storage/public/thumbnails/ecedbbe.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a9ec66b.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-08 18:41:18', 1),
(57, 102, 'Right hand build up ( Alternate Picking)', 'This exercise will build up dexterity on your right hand.\r\nIt&#39;s played using Major scale, The idea is to strick each note of your Key C major Scale at mid fret 8 strokes per note', 3, 'storage/public/thumbnails/efdca2d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/66f6954.mp4', NULL, 'storage/public/tutorials/practice/240f560.mpeg', NULL, NULL, 0, '2021-06-23 13:17:37', 1),
(58, 17, 'Key C to C# transition', 'Learn to play major scale on C# also', 1, 'storage/public/thumbnails/c9b4491.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/2f213fa.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/596c341.pdf', NULL, 0, '2021-06-22 13:44:21', 0),
(59, 17, 'Transition part A', 'Move from one key to another on the Guitar', 2, 'storage/public/thumbnails/826faad.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/2823b7d.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 13:46:58', 0),
(60, 17, 'Transition B', 'Learn to move from one key to another on the Guitar part B', 3, 'storage/public/thumbnails/333c1dd.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d00b29d.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 14:11:10', 0),
(61, 17, 'Transition to key F', 'Moving to key F from other keys', 4, 'storage/public/thumbnails/4aca38c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/76b7079.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 14:13:45', 0),
(62, 104, 'Sequence of 3rd Alternate picking (mid fret)', 'Major scale sequenced in 3rds. We learn to play this strictly with alternate picking.\r\nThis is a good finger exercise to help straighten our alternate picking', 1, 'storage/public/thumbnails/91044bb.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a2303d7.mp4', NULL, 'storage/public/tutorials/practice/5b316b4.mpeg', 'storage/public/tutorials/tablatures/5587959.pdf', NULL, 0, '2021-06-23 14:02:01', 1),
(63, 51, 'Major Scale Intervals of 3rd', 'Playing the C major scale in 3rd intervals', 2, 'storage/public/thumbnails/652a167.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/c088608.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/f5195cd.pdf', NULL, 0, '2021-01-27 20:17:40', 0),
(64, 37, '3rd duet harmony', 'The 1st and the 3rd notes of the major scale played simultaneously as 3rd duets', 1, 'storage/public/thumbnails/3c90686.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6fe01c6.mp4', NULL, NULL, NULL, NULL, 0, '2021-01-27 09:50:20', 1),
(65, 104, '3rd - 4th - 5th - 6th - 10th duet harmonies', 'This lesson is a Collection of Different duets harmonies\r\n3rd, 4th, 5th, 6th and 10th\r\nIt&#39;s very important to Learn all These duets harmonies because we will be playing different guitar lines with them', 4, 'storage/public/thumbnails/7e5ff5d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6e0fda3.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/5202156.pdf', NULL, 0, '2021-06-23 14:03:31', 1),
(66, 104, '3rd duet harmony Line 1', 'Learn this beautiful sequence using 3rd duet harmonies', 5, 'storage/public/thumbnails/fe9564a.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/8f4135c.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:04:07', 1),
(67, 104, '3rd duet harmony Line 1', 'Line 2\r\nIn this lesson, we will learn different guitar lines with 3rd duet harmonies', 8, 'storage/public/thumbnails/6b16210.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/994e4ac.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/2e60e1c.pdf', NULL, 0, '2021-06-23 14:06:28', 1),
(68, 20, '3rd duet harmony application', 'Apply 3rd duet harmony on two different songs\r\nNara and Your presence', 1, 'storage/public/thumbnails/5fe6420.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/c0a7f8f.mp4', NULL, 'storage/public/tutorials/practice/81ef290.mpeg', 'storage/public/tutorials/tablatures/d8bf6d8.pdf', NULL, 0, '2021-04-12 08:50:18', 1),
(69, 19, '5th Duet harmony App', 'Apply the 5th duet harmony on Nara and Your Presence', 3, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-30 11:27:10', 0),
(70, 20, '4th duet harmony application', 'Apply the 4th duet harmony on Nara and Your Presence', 2, 'storage/public/thumbnails/d33f62d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/5f91f60.mp4', NULL, 'storage/public/tutorials/practice/85d7648.mpeg', 'storage/public/tutorials/tablatures/db446db.pdf', NULL, 0, '2021-04-12 08:51:29', 1),
(71, 20, '5th Duet harmony Application', 'Use the 5th duet harmony on Nara and Your presence is heaven to me', 3, 'storage/public/thumbnails/ebbd7ef.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a9d4dfa.mp4', NULL, 'storage/public/tutorials/practice/0226aa6.mpeg', 'storage/public/tutorials/tablatures/f52eade.pdf', NULL, 0, '2021-04-12 08:52:51', 1),
(72, 20, '6th duet harmony application', 'Applying the 6th duet harmony on Nara', 4, 'storage/public/thumbnails/a94f95c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/96f47e2.mp4', NULL, 'storage/public/tutorials/practice/ca805b1.mpeg', 'storage/public/tutorials/tablatures/b2ade30.pdf', NULL, 0, '2021-04-12 09:11:42', 1),
(73, 20, '10th duet harmony Application', 'Applying the 10th duet harmony over Nara', 5, 'storage/public/thumbnails/0bdf5c0.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6c8dbd9.mp4', NULL, 'storage/public/tutorials/practice/ae8acaf.mpeg', NULL, NULL, 0, '2021-04-12 09:14:01', 1),
(74, 20, 'Applying 3rd and 4th harmony on Darling Jesus', 'Use your 3rd and 4th harmony to play guitar lines over the sing\r\nDARLING JESUS', 6, 'storage/public/thumbnails/55ba081.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/2b64445.mp4', NULL, 'storage/public/tutorials/practice/3c35d44.mpeg', NULL, NULL, 0, '2021-04-12 09:14:55', 1),
(75, 20, 'Apply 5th, 6th and 10th duet harmony', 'In this lesson, we will learn how Apply our 5th, 6th and 10th duet harmonies on Darling Jesus song', 7, 'storage/public/thumbnails/006f364.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/2090bd7.mp4', NULL, 'storage/public/tutorials/practice/075f07f.mpeg', NULL, NULL, 0, '2021-04-12 09:16:01', 1),
(76, 20, 'Spice up melodies  with sequence and Intervals of 3rd', 'In this lesson, we will learn how to Spice up the melody of Nara using sequences and Intervals of 3rd here and there', 8, 'storage/public/thumbnails/5c7ab4e.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/63f6536.mp4', NULL, 'storage/public/tutorials/practice/00b33be.mpeg', 'storage/public/tutorials/tablatures/283a25e.pdf', NULL, 0, '2021-04-12 09:17:19', 1),
(77, 20, 'Spice up Darling Jesus melody  with sequence and Intervals of 3rd', 'Spicing up the melody Darling Jesus with the sequence and Intervals of 3rd here and there.', 9, 'storage/public/thumbnails/c96bf7e.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/fd8eb26.mp4', NULL, 'storage/public/tutorials/practice/a1901dc.mpeg', NULL, NULL, 0, '2021-04-12 09:18:06', 1),
(78, 20, 'Apply 4th sequence on Darling Jesus melody', 'Apply 4th sequence on song melody', 10, 'storage/public/thumbnails/ab3c830.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/77c9bc7.mp4', NULL, 'storage/public/tutorials/practice/f95955f.mpeg', 'storage/public/tutorials/tablatures/470801d.pdf', NULL, 0, '2021-04-12 09:19:14', 1),
(79, 10, 'Right hand Muting', 'Right Hand Muting', 5, 'storage/public/thumbnails/6ae7b53.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/1948d9d.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-25 10:03:57', 1),
(80, 15, 'Right hand Mute on Boom Chicka and Boom Chicka Strum', 'Use the right hand mute on Boom Chicka and Boom Chicka Strum', 1, 'storage/public/thumbnails/2869b7b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/c8fb899.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 09:56:39', 1),
(81, 15, 'Add Tap and mute to strum', 'Strum with tap and mute', 2, 'storage/public/thumbnails/64193b1.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a8554fd.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 09:57:35', 1),
(82, 21, 'Add different grooves to simple strum 2', 'Take a simple strum and make it more interesting by adding different grooves', 2, 'storage/public/thumbnails/cda35e5.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/4176beb.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 12:37:19', 1),
(83, 21, 'This Strum on Angel of my life song', 'This Strum works with the song Angel of my Life by Paul ik Dairo', 3, 'storage/public/thumbnails/d9d3fbf.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/0752ab9.mp4', NULL, 'storage/public/tutorials/practice/278670e.mpeg', NULL, NULL, 0, '2021-06-23 12:36:14', 1),
(84, 21, 'Osondi owendi Strum', 'This Strum can work for Osondi Owendi song by Osita Osadebe', 4, 'storage/public/thumbnails/86b7d40.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6ec891b.mp4', NULL, 'storage/public/tutorials/practice/62a3d32.mpeg', NULL, NULL, 0, '2021-06-23 12:35:29', 1),
(85, 22, 'Pentatonic Scale', 'The pentatonic scale, a 5 note scale.\r\nC D E G A', 1, 'storage/public/thumbnails/f40a8e9.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/3627602.mp4', NULL, 'storage/public/tutorials/practice/28b28da.mpeg', 'storage/public/tutorials/tablatures/db3a311.pdf', NULL, 0, '2021-04-11 16:31:18', 1),
(86, 22, 'Pentatonic Scale Positions A', 'We will learn the different Pentatonic scale patterns on different position of the Guitar Fret Board', 2, 'storage/public/thumbnails/587c1c2.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/576a5e6.mp4', NULL, 'storage/public/tutorials/practice/1c49d8f.mpeg', 'storage/public/tutorials/tablatures/e367f06.pdf', NULL, 0, '2021-04-11 16:33:03', 1),
(87, 22, 'Pentatonic Scale Positions B', 'We learn the rest Pentatonic Scale patterns', 3, 'storage/public/thumbnails/8852d52.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/ce7338d.mp4', NULL, 'storage/public/tutorials/practice/672d974.mpeg', 'storage/public/tutorials/tablatures/153fd6b.pdf', NULL, 0, '2021-04-11 16:35:20', 1),
(88, 22, 'Pentatonic Scale intervals of 3rd', 'We will sequence our Pentatonic Scale in 3rds, learning this Good to starting playing some runs on the Guitar with our Pentatonic scale', 4, 'storage/public/thumbnails/7b2a58e.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/cad11ed.mp4', NULL, 'storage/public/tutorials/practice/3196497.mpeg', 'storage/public/tutorials/tablatures/343e0dc.pdf', NULL, 0, '2021-04-11 16:41:05', 1),
(89, 22, 'Pentatonic Scale intervals of 4th', 'We sequence our Pentatonic Scale in 4th, this is another good door way to playing some cool runs', 5, 'storage/public/thumbnails/6cdceda.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/5e2277e.mp4', NULL, 'storage/public/tutorials/practice/d158785.mpeg', 'storage/public/tutorials/tablatures/d3d270a.pdf', NULL, 0, '2021-04-11 16:42:00', 1),
(90, 22, 'Pentatonic application on song melodies', 'Use the pentatonic scale to spice up the song melody\r\nYour presence is heaven to me', 6, 'storage/public/thumbnails/2d6c261.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/039c5f6.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/27ca074.pdf', NULL, 0, '2020-11-11 21:37:30', 1),
(91, 22, 'Solo over the fret board with pentatonic scale', 'Since we have covered our different Pentatonic scale patterns, now the fret board should be easy to walk around on using pentatonic scale.\r\nSo this lesson, we will initiate that process of Moving all over the fret board with pentatonic scale fills', 7, 'storage/public/thumbnails/b015c3a.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/c2e154d.mp4', NULL, 'storage/public/tutorials/practice/eddeb92.mpeg', 'storage/public/tutorials/tablatures/d632a0a.pdf', NULL, 0, '2021-04-12 12:11:59', 1),
(92, 31, 'Slur | Hammer-on | Pull-off', 'Learn these techniques\r\nSlur,\r\nHammer-on and\r\nPull-off', 12, 'storage/public/thumbnails/68bc186.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/e444624.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-12 09:25:31', 1),
(93, 23, 'Major Scale with Slur, Hammer-on and Pull-off', 'No Description', 2, 'storage/public/thumbnails/fc9209c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/020521a.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-21 15:30:47', 0),
(94, 31, 'Play song melody with Slur, Hammer-on and Pull-off', 'We will learn to apply the slur, Hammer-on and Pull-off techniques we have learnt on different song sofa notations..\r\nTo sound like a professional, you must learn to make good use of slur, Hammer-on and Pull-off.', 13, 'storage/public/thumbnails/45e24a1.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/c923df4.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 20:12:07', 1),
(95, 22, '7 Pentatonic licks Beat 1', 'Yes.. Its lick time.\r\nIn this Lesson, we will learn 7 different licks with our Pentatonic scale', 8, 'storage/public/thumbnails/6a53149.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/3600e2d.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/530e9e1.pdf', NULL, 0, '2021-04-12 12:54:04', 1),
(96, 22, '7 Pentatonic licks Beat 2', 'Here we have the second set of 7 Pentatonic licks.', 9, 'storage/public/thumbnails/e036039.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6e864c9.mp4', NULL, 'storage/public/tutorials/practice/a4315fe.mpeg', 'storage/public/tutorials/tablatures/c6aa50f.pdf', NULL, 0, '2021-04-12 12:14:58', 1),
(97, 22, '7 Pentatonic licks Beat 3', 'This is the last batch of the Pentatonic lick series.\r\nHere we have another 7 Pentatonic licks', 10, 'storage/public/thumbnails/de9fe10.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/3323d7c.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/46597f4.pdf', NULL, 0, '2021-04-12 13:00:58', 1),
(98, 22, 'Improvising with pentatonic scale', 'Here we will learn more licks improvising with our Pentatonic scale', 11, 'storage/public/thumbnails/9417f4d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a50c3e5.mp4', NULL, 'storage/public/tutorials/practice/febe72f.mpeg', 'storage/public/tutorials/tablatures/9f03a70.pdf', NULL, 0, '2021-04-12 12:17:26', 1),
(99, 26, 'Chords G - D - A - F# - Bm & C#m', 'To be able to play in other keys, we have to be used to these Chord shapes\r\nG Major - D Major -  A Major - F#Major - B minor and C#minor', 1, 'storage/public/thumbnails/9f2041f.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d51d3ce.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/a2b9b73.pdf', NULL, 0, '2021-02-02 23:39:14', 1),
(100, 26, 'Key F full Chord', 'Hold key F chord full on all 6 strings', 2, 'storage/public/thumbnails/1f0e82d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/9d37e66.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-12 16:06:28', 1),
(101, 26, 'Play on Key G', 'Now we begin to get familiarise with our Key G as a Root key, so we can play all the things we could play on key C now on key G', 3, 'storage/public/thumbnails/173145c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/dbd0c33.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-12 16:17:22', 1),
(102, 26, 'Key G major Scale', 'Our Major scale, but this time we learn it on Key G', 4, 'storage/public/thumbnails/3043b9e.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2020-11-12 16:17:54', 0),
(103, 26, 'Playing on key D', 'We begin to play all the things we know on the key of D.', 5, 'storage/public/thumbnails/8ab2f7e.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/f3799e2.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-12 09:55:08', 1),
(104, 26, 'Major Scale on Key D', 'Learn how to play your major scale on key D.', 6, 'storage/public/thumbnails/08eb6c2.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/1a04080.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-12 09:56:46', 1),
(105, 26, 'Playing on key A', 'We now learn to get used to Key A, then Everything thing we know to play on key C, let&#39;s bring them all here on key A', 8, 'storage/public/thumbnails/ba27c92.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/dbb9af4.mp4', NULL, NULL, NULL, NULL, 0, '2021-02-02 23:30:03', 1),
(106, 26, 'Major Scale on Key A', 'Learn to play our major scale here on key A', 9, 'storage/public/thumbnails/e86cc1d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/797d308.mp4', NULL, NULL, NULL, NULL, 0, '2021-02-02 23:29:29', 1),
(107, 26, 'Melodies and Chords on key A', 'We play different melodies and Chords on key A.\r\nIts easy, let&#39;s try it..', 10, 'storage/public/thumbnails/eabd456.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/3f239fc.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/09a4bc4.pdf', NULL, 0, '2021-02-02 23:28:58', 1),
(108, 26, 'Sofa and chord changes on key G', 'We will learn how to play different song melodies and Chord changes on key G', 4, 'storage/public/thumbnails/d35377b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/072eba2.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-12 16:21:05', 1),
(109, 5, 'Happy Birthday ( 2 notes chord)', 'Sing the happy birthday song along with our 2 notes chord accompaniment.', 3, 'storage/public/thumbnails/b2fb539.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/91e6074.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-08 18:11:29', 1),
(110, 5, 'O Lord my God song ( 2notes chord)', 'Oh lord↗️ my God➡️\r\nHow excellent is your name...\r\n\r\nSing this simple song and accompany it with our 2 notes chord.', 2, 'storage/public/thumbnails/00a3237.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/22f938d.mp4', NULL, 'storage/public/tutorials/practice/1ef460d.mpeg', NULL, NULL, 0, '2021-04-08 18:10:22', 1),
(111, 27, 'Key C Mid fret major Scale', 'Playing your key c major at the middle of the fret board on two octaves', 1, 'storage/public/thumbnails/6098b41.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-02-03 14:31:29', 0),
(112, 27, '3 different patterns of C Major Scale', 'Play major scale on key C at 3 different patterns (positions)', 2, 'storage/public/thumbnails/2a63160.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/f990c3c.mp4', NULL, NULL, NULL, NULL, 0, '2021-02-03 14:31:29', 0),
(113, 27, 'Key C mid fret Chords', 'Learn our chords on key C but around the middle of our Frets', 3, 'storage/public/thumbnails/742460c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/fe3c971.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/6307cd7.pdf', NULL, 0, '2021-02-03 14:31:29', 0),
(114, 27, 'Understand the Relation between Keys and Notes', 'Music is communicated in different ways\r\nNotes can be called either by keys, sofas or numbers. \r\nThis lesson helps to understand these relations', 4, 'storage/public/thumbnails/265f312.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/ee3b17d.mp4', NULL, NULL, NULL, NULL, 0, '2021-02-03 14:31:29', 0),
(115, 2, 'Guitar Grip and Posture', 'Learn how to hold your Guitar and the right  posture', 2, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/f592678.mp4', NULL, NULL, NULL, NULL, 0, '2020-11-16 12:06:21', 0),
(116, 2, 'The Wanna be Guitarist', 'Few tips to get you started on your Guitar journey', 4, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2020-11-16 12:06:21', 0),
(117, 28, 'Strike and hold notes', 'It is time, lets begin to hold some notes and strike the very notes we hold.', 2, 'storage/public/thumbnails/ba10cde.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/643f330.mp4', NULL, NULL, NULL, 'Since your fingers are new to striking guitar strings, there is a chance that you could feel some pains on your fingers.\r\nPlease make sure you give your fingers some rest when it hurts ', 0, '2021-06-22 13:03:20', 0),
(118, 3, 'Finger and String numbers', 'Learn the numbers assigned to your Guitar strings and fingers.', 4, 'storage/public/thumbnails/310779b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/e82a65e.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-08 17:23:09', 1),
(119, 28, 'Key C major Scale ( One string )', 'Learn to play the major scale on just one string.', 4, 'storage/public/thumbnails/38285fb.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/11e4bda.mp4', NULL, 'storage/public/tutorials/practice/3d03176.mpeg', NULL, 'The Modulation arrangement for the formation of the major scale is Tone : Tone : Semitone : Tone : Tone : Tone : Semitone. \r\n', 0, '2021-04-25 16:43:51', 1),
(120, 3, 'Welcoming new students', 'You are welcome to Spicy Guitar Academy', 1, 'storage/public/thumbnails/7002aec.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/06d3bdc.mp4', 'storage/public/tutorials/audios/f3add8c.mpeg', 'storage/public/tutorials/practice/dc76616.mpeg', 'storage/public/tutorials/tablatures/0ac1a92.pdf', 'This is a sample note on the introduction to guitars. This is a sample note on the introduction to guitars. This is a sample note on the introduction to guitars. This is a sample note on the introduction to guitars. This is a sample note on the introduction to guitars. This is a sample note on the introduction to guitars. ', 0, '2021-02-02 23:08:15', 0),
(121, 3, 'The Guitar Grip and Posture', 'It&#39;s important to Learn the right and healthy pose with the guitar and the right way to Grip the guitar \r\nThe wrong grip and posture over time could affect your back bones, shoulder or wrist.', 2, 'storage/public/thumbnails/183906e.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/298ce06.mp4', 'storage/public/tutorials/audios/e0cb201.mpeg', NULL, NULL, NULL, 1, '2021-04-08 17:20:47', 1),
(122, 3, 'The Guitar Anatomy', 'Know the names of the different parts of the Guitar.', 3, 'storage/public/thumbnails/e8b5d39.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/c7d4592.mp4', NULL, 'storage/public/tutorials/practice/d3a8904.mpeg', NULL, NULL, 0, '2021-04-08 17:21:47', 1),
(123, 3, 'The Wanna be Guitarist', 'We are getting set to become that Guitarist we so longed to be.\r\n Get ready to input work from your end, be diligent with the lessons, get all the details and take your time to learn them right.. \r\nCreate time for practice!', 6, 'storage/public/thumbnails/a9c619b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a23de81.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-08 17:27:00', 1),
(124, 5, 'Extending Key C major Scale', 'Extending the C major scale on the Guitar neck.', 4, 'storage/public/thumbnails/c7d73db.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/984379a.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-08 18:13:53', 1),
(125, 32, 'Intervals of 3rd (Finger Exercise)', 'Simple 3rd Intervals finger exercise', 1, 'storage/public/thumbnails/bedcccd.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/add05e3.mp4', NULL, 'storage/public/tutorials/practice/9e33fda.mpeg', NULL, NULL, 0, '2021-04-09 22:15:08', 1),
(126, 29, 'One String Chromatic scale (Alternate Picking)', 'Chromatic scale on one string using alternate picking. \r\nAlternate picking is simply to Strike down, then strike up continuously.\r\nThis exercise is meant to improve your right picking hand', 3, 'storage/public/thumbnails/a5103fe.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-28 04:41:56', 0),
(127, 29, 'Incomplete Chromatic scale (Two strings)', 'Chromatic-like movement within two strings.\r\nA finger exercise to help flexibility and independence within your left fingers.', 5, 'storage/public/thumbnails/fdeb52f.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/aafe150.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 13:07:51', 0),
(128, 29, 'Major Scale (8 stroke per note)', 'This finger exercise is aimed at building your grip and endurance on your right hand.\r\nIt&#39;s the normal major scale but this time you strike each note eight times before moving to the next note.', 4, 'storage/public/thumbnails/f2878ae.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d17f113.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-08 18:43:57', 1),
(129, 38, 'Simple melodies', 'Learn these simple melody lines.\r\nDifferent melodies compiled here will help both right and left hand. \r\nLearn them well', 5, 'storage/public/thumbnails/a0f0206.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/949d7af.mp4', NULL, 'storage/public/tutorials/practice/934c337.mpeg', NULL, 'These different melodies will help our left hand and right hand.. It will help us to be able to switch between notes easily on our left hand and articulation with our picking hand', 0, '2021-04-12 09:00:39', 1),
(130, 29, 'Major Scale Neck (Alternate Picking)', 'Major Scale on key C neck position with alternate picking.\r\nAlternate picking is simply to Strike up down steadily', 6, 'storage/public/thumbnails/9ea117b.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-02-01 16:36:58', 0),
(131, 38, 'Simple song progressions 1', 'Easy to play progressions of different songs. \r\nNow we begin to make use of the chords you have learnt on different songs. \r\nEnjoy...', 8, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-22 13:22:29', 0),
(132, 100, 'Striking Chords neatly', 'Watch to Learn how to glide your pick through the strings to strike your chords Neatly and make it sound more professional', 8, 'storage/public/thumbnails/6041265.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b39dcc5.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 09:29:41', 1),
(133, 100, 'Using the CAPO', 'How to transpose From one Key to the other on the Guitar using a device called the CAPO', 9, 'storage/public/thumbnails/16808d2.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b1369e3.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 09:30:42', 1),
(134, 30, 'Different shapes and positions of C major Scale', 'Learn how to play the major scale on different positions', 1, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-26 21:37:02', 0),
(135, 31, 'Different shapes and positions of C major Scale', 'Play your major scale on different shapes and positions.\r\nThis helps broaden your scope on the Guitar board.', 1, 'storage/public/thumbnails/d046115.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/57441c4.mp4', NULL, 'storage/public/tutorials/practice/61db6c6.mpeg', NULL, NULL, 0, '2021-04-12 09:18:48', 1),
(136, 31, 'Two Octaves of major scale', 'Major Scale that covers two octaves, played with all 6 strings', 2, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-03-25 07:33:18', 0),
(137, 31, 'Song melodies on different positions', 'Play song melodies, same so-fa notation, same song but on different positions...', 2, 'storage/public/thumbnails/7167f8b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/9b30d7c.mp4', NULL, 'storage/public/tutorials/practice/803625e.mpeg', NULL, NULL, 0, '2021-04-12 09:20:22', 1),
(138, 31, 'Major Scale on Other keys', 'Now we move to other keys; relate and play our major scale.', 3, 'storage/public/thumbnails/a93f874.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/725a4a1.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-12 09:21:50', 1),
(139, 31, 'Chord Family and inversions', 'Let&#39;s learn to understand the terms for different chord inversions.', 4, 'storage/public/thumbnails/6acceaa.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/119054f.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-12 09:24:05', 1),
(140, 31, 'Major Chord Shape ( G )', 'We will learn different G Major shapes of Chords on different positions. \r\nThis lesson will help you to see the fret board better \r\nThis lessons will open your eyes to the Guitar board.', 7, 'storage/public/thumbnails/2b8bbea.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/1b0b83c.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 20:04:38', 1),
(141, 31, 'Song progressions on different shapes', 'We will play same song progressions that we had learnt before, but now we will be holding the chords on different shapes', 10, 'storage/public/thumbnails/fcda29f.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/8bb9ce0.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 20:06:02', 1),
(142, 31, 'Angel of my Life ( Hook line)', 'No Description', 11, 'storage/public/thumbnails/4ace8f6.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/404b4c6.mp4', NULL, 'storage/public/tutorials/practice/b3b31e3.mpeg', 'storage/public/tutorials/tablatures/627d4cb.pdf', NULL, 0, '2021-04-08 14:28:50', 1),
(143, 29, 'Intervals of 3rd', '3rd Intervals\r\nC E - D F - E G - F A - G B - A C - B D. \r\nWe will learn to play these 3rd intervals in an arranged shape', 2, 'storage/public/thumbnails/183fb8c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/3337045.mp4', NULL, 'storage/public/tutorials/practice/9e3a865.mpeg', NULL, NULL, 0, '2021-06-22 19:07:11', 1),
(144, 32, 'Sequence of 3rd', 'No Description', 2, 'storage/public/thumbnails/6d8b743.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/120b6ab.mp4', NULL, 'storage/public/tutorials/practice/2c5f7a0.mpeg', NULL, NULL, 0, '2021-04-09 22:16:24', 1),
(145, 32, 'Chromatic scale ( one Octave)', 'Chromatic is consist of all 12 music keys.\r\nC C# D Eb E F F# G Ab A Bb B c ( keys) \r\n\r\nDoh De Re Maw Mi Fa Fe Soh Ze La Taw Ti do. (Sofas)', 5, 'storage/public/thumbnails/bf08618.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/f718e73.mp4', NULL, 'storage/public/tutorials/practice/c8ee3ed.mpeg', NULL, NULL, 0, '2021-06-22 20:14:29', 1),
(146, 33, 'Strumming with the thumb', 'Apart from using the Pick, Strumming with Our thumb is another way to Strum.\r\nWatch and learn', 2, 'storage/public/thumbnails/cded5ca.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/466b503.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 20:16:56', 1),
(147, 33, 'Right hand Muting', 'No Description', 3, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-02-03 15:40:04', 0),
(148, 10, 'Different Strum patterns with thumb', 'No Description', 6, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-22 13:41:16', 0),
(149, 13, 'More Worship Strums', 'This Strum pattern will work for some worship song.', 2, 'storage/public/thumbnails/07b9098.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a53e41a.mp4', NULL, 'storage/public/tutorials/practice/e01c586.mpeg', NULL, NULL, 0, '2021-06-22 20:18:36', 1),
(150, 15, 'Right hand mute all Down strum', 'No Description', 1, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-28 07:14:10', 0),
(151, 15, 'Right hand Mute on Boom Chicka and Boom Chicka Strum', 'No Description', 2, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-28 07:13:55', 0),
(152, 15, 'Add Tap and mute to strum', 'No Description', 3, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-28 07:16:07', 0),
(153, 15, 'No Woman no Cry ( Song Progression )', 'Chord progression of NO WOMAN NO CRY, original song by Bob Marley', 3, 'storage/public/thumbnails/4078f56.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/78a5129.mp4', NULL, 'storage/public/tutorials/practice/a8358ea.mpeg', NULL, NULL, 0, '2021-06-23 09:57:56', 1),
(154, 15, 'African Queen ( Song Progression)', 'Chord progression for the song African Queen by 2Face Idibia.\r\nC - Am - F - G', 4, 'storage/public/thumbnails/d2ddafe.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6137e26.mp4', NULL, 'storage/public/tutorials/practice/65d19de.mpeg', NULL, NULL, 0, '2021-06-23 09:58:26', 1),
(155, 15, 'Eya adaba song progression', 'Chord progression for song Eya Adaba by ASA. \r\nPlayed on Key C', 5, 'storage/public/thumbnails/1aa49ed.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/fc9310e.mp4', NULL, 'storage/public/tutorials/practice/bb509af.mpeg', NULL, NULL, 0, '2021-06-23 09:59:48', 1),
(156, 15, 'ElShadai (Song Progression)', 'Chord progression for ElShadai song.', 6, 'storage/public/thumbnails/98f51a5.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/fa9880e.mp4', NULL, 'storage/public/tutorials/practice/f76702e.mpeg', NULL, NULL, 0, '2021-06-23 10:00:16', 1),
(157, 34, 'Finger picking', 'No Description', 1, 'storage/public/thumbnails/3e6b259.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/7a182d8.mp4', NULL, NULL, NULL, NULL, 0, '2021-01-31 23:39:29', 1),
(158, 34, 'Simple finger picking lines', 'No Description', 2, 'storage/public/thumbnails/16bbad2.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/ab645ed.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-09 00:51:24', 1),
(159, 34, 'Simple finger picking lines 2', 'No Description', 3, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-22 14:34:17', 1);
INSERT INTO `lesson_tbl` (`id`, `course`, `lesson`, `description`, `ord`, `thumbnail`, `tutor`, `low_video`, `high_video`, `audio`, `practice_audio`, `tablature`, `note`, `free`, `date_added`, `active`) VALUES
(160, 35, 'Ear training intro', 'No Description', 1, 'storage/public/thumbnails/f26ab3c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/0cad717.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-09 00:50:21', 1),
(161, 35, 'Understanding Notes and Frequencies', 'No Description', 3, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-22 20:58:10', 1),
(162, 35, 'Identifying notes and Melodies', 'No Description', 3, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-27 09:36:38', 1),
(163, 35, 'Scoring Song Melodies', 'No Description', 4, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-27 09:39:55', 1),
(164, 35, 'Identify key of a Song (Major and Minor)', 'No Description', 5, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-27 09:41:02', 1),
(165, 35, 'Scoring Song Progressions', 'Hello()', 6, 'storage/public/thumbnails/default.jpg', 'Ebuka Odini', NULL, NULL, NULL, NULL, NULL, 'Hello', 0, '2021-03-31 16:50:14', 1),
(166, 37, 'Simple 3rd duet harmony line', '3rd duet is used in African rhythms.\r\nIn this lesson, we will learn few guitar lines with 3rd duet and hopefully you get inspired and are able to create yours', 2, 'storage/public/thumbnails/377e6d0.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/11cc497.mp4', NULL, 'storage/public/tutorials/practice/fcc7ae9.mpeg', NULL, NULL, 0, '2021-06-22 20:41:18', 1),
(167, 37, 'Sweet Mother guitar line', 'We will learn the Guitar intro of the song SWEET MOTHER.\r\nThe line was played with 3rd harmonies', 3, 'storage/public/thumbnails/355a7bd.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/19d8b3c.mp4', NULL, 'storage/public/tutorials/practice/c40a41b.mpeg', NULL, NULL, 0, '2021-06-22 20:43:14', 1),
(168, 37, 'Ayo muted guitar', 'The muted Guitar hook line of the song AYO by Simi\r\nSimple but sweet', 4, 'storage/public/thumbnails/09c8758.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/54e03ee.mp4', NULL, 'storage/public/tutorials/practice/7797b60.mpeg', NULL, NULL, 0, '2021-06-22 20:44:32', 1),
(169, 37, 'Joromi rhythm Guitar', 'In this lesson, we will learn just the Rhythm guitar played in the Song JOROMI by Victor Uwaifo', 5, 'storage/public/thumbnails/0493816.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/52c490c.mp4', NULL, 'storage/public/tutorials/practice/a702dad.mpeg', NULL, NULL, 0, '2021-06-22 20:45:58', 1),
(170, 37, 'Guitar lines on 3 different song', 'No Description', 7, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-22 14:17:04', 0),
(171, 15, 'Eya adaba song progression', 'No Description', 7, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-27 15:50:53', 0),
(172, 15, 'ElShadai (Song Progression)', 'No Description', 8, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-27 15:51:40', 0),
(173, 26, 'Song progressions on different keys', 'We will learn the chord progression of different songs on other keys', 13, 'storage/public/thumbnails/bb24d98.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b1a7b02.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 20:30:52', 1),
(174, 93, 'Basic Approach to song Progression', 'Basic and simple approach to song progression.\r\nHere you will learn how to craft the chord progressions for songs by your self', 1, 'storage/public/thumbnails/f4829fb.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/e17536a.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 20:24:11', 1),
(175, 40, '3 Techniques on major and Pentatonic Scale', 'Here we will be combining 3 different techniques, Slur, Hammer-on and Pull-off on our major and Pentatonic Scale.\r\nIt&#039;s a cool thing to learn and incorporate into our playing', 1, 'storage/public/thumbnails/d41f5e3.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/5962e66.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 07:39:56', 1),
(176, 40, 'Guitar Trills', 'Trill on the Guitar is the technique that helps you to rapidly alternate between two notes on a single string.\r\nIt&#039;s literally a rapid switch between Hammer-on and Pull-off between two different notes.', 2, 'storage/public/thumbnails/fcc6c4d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d044f5c.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 07:43:48', 1),
(177, 40, 'Guitar Trill line', 'These guitar trill lines will get you well started trilling.\r\nAdding Trills to your notes as you play will make your playing more interesting', 3, 'storage/public/thumbnails/d521b17.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/8727fbb.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 07:45:26', 1),
(178, 104, 'Sequence of 4th', 'Our major Scale sequenced in 4th.', 2, 'storage/public/thumbnails/541890b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/2d794f0.mp4', NULL, 'storage/public/tutorials/practice/3fc7dfc.mpeg', NULL, NULL, 0, '2021-06-23 14:02:25', 1),
(179, 104, '3rd duet harmony', '3rd Duet is a combination of our 1st and 3rd notes played at once as duet.\r\nThis is important to Learn because, playing African music without 3rd duet would be incomplete', 3, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 14:02:52', 1),
(180, 51, '3rd - 4th - 5th - 6th - 10th duet harmonies', 'No Description', 4, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-27 20:20:13', 0),
(181, 51, '3rd duet harmony sequence', 'No Description', 6, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-03-25 15:07:18', 0),
(182, 104, '3rd duet harmony lines 4', 'We learn these other cool lines using our 3rd duet harmonies', 11, 'storage/public/thumbnails/046d50b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/942b785.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:04:57', 1),
(183, 51, 'Intervals of 3rd, 4th and 5th', 'In this lesson, we will learn to play different intervals .\r\n3rd, 4th and 5th', 1, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, 'storage/public/tutorials/practice/6a70065.mpeg', NULL, NULL, 0, '2021-06-22 15:04:03', 0),
(184, 104, 'Sweet Mother guitar line', 'Sweet Mother is a Classic song that most Nigerians are familiar with.\r\nThe song has a hook guitar intro line.. \r\nThe line is played with 3rd duet harmony.\r\nSo this lesson is a good one for us to begin to put our 3rd duets to use', 7, 'storage/public/thumbnails/b263fc7.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/cf8f8a2.mp4', NULL, 'storage/public/tutorials/practice/c57dbb8.mpeg', NULL, NULL, 0, '2021-06-23 14:06:04', 1),
(185, 104, '3rd duet harmony lines 2', 'We will learn more guitar lines with our 3rd duet harmonies', 9, 'storage/public/thumbnails/0c18b55.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/2712f84.mp4', NULL, 'storage/public/tutorials/practice/6aacacd.mpeg', NULL, NULL, 0, '2021-06-23 14:06:54', 1),
(186, 104, '3rd duet harmony lines 3', 'Yet, even more 3rd duet harmony lines we will  learn in this lesson', 10, 'storage/public/thumbnails/a614fa0.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/26a7586.mp4', NULL, 'storage/public/tutorials/practice/2544a73.mpeg', NULL, NULL, 0, '2021-06-23 14:07:14', 1),
(187, 42, 'Finger picking intro', 'Finger picking is simply the style of picking notes with your right fingers. Finger picking can also be referred to as FINGER STYLE \r\nThis lessons gets us started right to it explaining the nitty gritty of the style', 1, 'storage/public/thumbnails/0d1a4ac.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/f625196.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 09:15:18', 1),
(188, 42, 'Finger picking lines 1', 'Getting us started, we learn these beautiful set of finger picking lines on Key C', 3, 'storage/public/thumbnails/319a849.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/2d4d365.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:18:43', 1),
(189, 42, 'Finger picking lines 2', 'This lesson contains different collection of finger picking lines.\r\nPlease learn them diligently', 4, 'storage/public/thumbnails/60f4be3.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a2ab11e.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:21:21', 1),
(190, 42, 'Finger picking lines 3', 'We will learn more interesting finger picking lines here.\r\nPlease try to learn all because they are uniquely created to address and inspire you differently', 5, 'storage/public/thumbnails/a3d0ef5.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/0563a15.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:25:46', 1),
(191, 42, 'Finger style lines on key E', 'This finger Style line is on key E..\r\nIt&#39;s an interesting one, let&#39;s get over to learning it', 7, 'storage/public/thumbnails/334fe6c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/ffd9457.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:24:37', 1),
(192, 42, 'Ire and Fame Guitar lines ( Finger Style)', 'Here we will learn how to play the the finger picking lines of Two songs by Adekunle Gold. IRE and FAME', 10, 'storage/public/thumbnails/5b752e1.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/5d69046.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:23:09', 1),
(193, 42, 'By you and Marry me ( Finger style)', 'This lesson contains to different finger style lines of the Guitar lines of two different songs, BY YOU by Simi and MARRY ME by Teni', 9, 'storage/public/thumbnails/cc56e8d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b3845bb.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:23:43', 1),
(194, 43, 'Ariaria Guitar Style intro', 'Now jump into Ariaria Music.\r\nThis style of music originated from the Eastern part of Nigeria in a place called ARIARIA in ABA. \r\nAriaria literally connotes danceable music. Music with more Vibes to get you dancing. \r\n\r\nSo we start to learn different Ariaria gospel guitar lines for church praise', 1, 'storage/public/thumbnails/1174a8a.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/f770d9d.mp4', NULL, 'storage/public/tutorials/practice/096ebcc.mpeg', NULL, NULL, 0, '2021-04-12 09:24:28', 1),
(195, 43, 'Ariaria passing Chord and Substitution', 'African music has its own unique character.\r\nIn this lesson we will learn how we can use some basic African chord Substitute on our African Ariaria Guitar lines', 2, 'storage/public/thumbnails/0f8cf15.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/5c884fd.mp4', NULL, 'storage/public/tutorials/practice/1717b5c.mpeg', NULL, NULL, 0, '2021-04-10 17:38:35', 1),
(196, 43, 'Si Mba Chord and Strum', 'In this lesson we will learn different Strum lines that we will apply over an Ibo song Si Mba', 4, 'storage/public/thumbnails/dec35b0.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a140c69.mp4', NULL, 'storage/public/tutorials/practice/feef798.mpeg', NULL, NULL, 0, '2021-04-25 22:28:27', 1),
(197, 43, 'Si Mba Muted Guitar and Strum', 'We will learn these simple mute lines on our Ariaria Si Mba Song', 5, 'storage/public/thumbnails/4ef39f1.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/1635e4c.mp4', NULL, 'storage/public/tutorials/practice/e59705a.mpeg', NULL, NULL, 0, '2021-04-25 22:27:38', 1),
(198, 43, 'Si Mba Guitar Arpeggio lines', 'Some arpeggio lines on Si Mba song', 6, 'storage/public/thumbnails/38db4d0.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/1e1572e.mp4', NULL, 'storage/public/tutorials/practice/3a06898.mpeg', 'storage/public/tutorials/tablatures/e388adf.pdf', NULL, 0, '2021-04-25 22:27:05', 1),
(199, 43, 'Si Mba 3rd Harmony Guitar Lines', 'We will put our 3rd duet harmony to use here in Ariaria Music.\r\nSo we will learn and apply different 3rd Harmony lines on Si Mba', 7, 'storage/public/thumbnails/edc42da.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a55e33c.mp4', NULL, 'storage/public/tutorials/practice/b2841cd.mpeg', 'storage/public/tutorials/tablatures/b17a49b.pdf', NULL, 0, '2021-04-25 22:26:09', 1),
(200, 43, 'Si Mba Guitar Lines', 'We will learn different Ariaria guitar lines on this Si Mba song', 8, 'storage/public/thumbnails/57152e0.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/391adba.mp4', NULL, 'storage/public/tutorials/practice/3ed53a6.mpeg', NULL, NULL, 0, '2021-04-25 22:25:37', 1),
(201, 43, 'O Lord, we are very very grateful', 'This new song O Lord, We are Very Very Grateful.\r\nThis song also has the Ariaria beat vibe so we will learn different Ariaria Guitar lines on this song', 9, 'storage/public/thumbnails/19d88ea.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d11cc12.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-25 22:25:08', 1),
(202, 43, 'Higher Higher', 'This is an Ibo Church fast praise song. A typical one to play some Ariaria Guitar lines on.\r\nThis very lesson contains different guitar lines that we can use to Serenade this song and its likes', 11, 'storage/public/thumbnails/a617ff3.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/70c46de.mp4', NULL, 'storage/public/tutorials/practice/baa7995.mpeg', NULL, NULL, 0, '2021-04-25 22:24:09', 1),
(203, 43, 'Akamarama', 'This is another Ibo Church Fast Praise song.\r\nWe learn different guitar lines here. Lines that will give us a good handle on Ariaria music', 12, 'storage/public/thumbnails/e6437d3.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/7f61e6d.mp4', NULL, 'storage/public/tutorials/practice/1363967.mpeg', NULL, NULL, 0, '2021-04-25 22:23:44', 1),
(204, 44, 'High life Guitar intro', 'High Life Music is a West African Music that originated from Ghana and it has a whole lot of influence on the Nigerian Music.\r\nGuitar play a key role in high life music, and in this High Guitar Course, we are going to Learn different guitar lines and some tips to get you started playing highife guitar', 1, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-22 15:49:04', 0),
(205, 44, 'Love Adure', 'Love Adure, a classic high song by Cardinal Rex Lawson\r\nIn this lesson, we will learn the famous intro line and some more guitar lines', 2, 'storage/public/thumbnails/0373b1e.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d5e7608.mp4', NULL, 'storage/public/tutorials/practice/0d3ab80.mpeg', NULL, NULL, 0, '2021-04-10 21:19:13', 1),
(206, 44, '1 6 2 5 Highlife Guitar lines', '1 6 2 5, a typical chord progression for high life music\r\nIn this lesson, we will learn different high life guitar lines on 1 6 2 5 chord progression', 3, 'storage/public/thumbnails/3dfca9a.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/62440f1.mp4', NULL, 'storage/public/tutorials/practice/980a96b.mpeg', 'storage/public/tutorials/tablatures/ea13a38.pdf', NULL, 0, '2021-04-25 22:34:58', 1),
(207, 44, 'Edikwansa', 'Edikwansa another Classic high song by Dan Orji.\r\nWe will learn the famous Edikwansa guitar intro in this lesson.', 4, 'storage/public/thumbnails/c574cc4.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b913c4a.mp4', NULL, 'storage/public/tutorials/practice/f18f5a4.mpeg', NULL, NULL, 0, '2021-04-25 22:34:25', 1),
(208, 44, 'Osondi Owendi Complete Guitar', 'Osondi Owendi, a popular Ariaria high life song by Osita Osadebe\r\nThis is grouped as high life here because they say high life is the Music for Men, and sure this one is for em Real Men.. Smiles..\r\nWe will learn the complete guitar lines in this song. Intro and solo included', 5, 'storage/public/thumbnails/2e09d13.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/64d4260.mp4', NULL, 'storage/public/tutorials/practice/26bfdd6.mpeg', NULL, NULL, 0, '2021-04-25 22:33:59', 1),
(209, 37, 'Osondi owendi', 'We will learn the rhythm guitar of the song Osondi Owendi on key F.\r\nSong by Osita Osadebe', 6, 'storage/public/thumbnails/a5b6e17.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/346e32d.mp4', NULL, 'storage/public/tutorials/practice/d8f6771.mpeg', NULL, NULL, 0, '2021-06-22 20:48:48', 1),
(210, 44, 'Osondi owendi solo', 'No Description', 6, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-03-27 06:30:55', 0),
(211, 44, 'Joromi guitar (Intro line)', 'Joromi is another Classic Song by the Sir Victor Uwaifo\r\nIn this lesson, we will learn the Renown Guitar Intro', 6, 'storage/public/thumbnails/d154a0a.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/68b66c3.mp4', NULL, 'storage/public/tutorials/practice/65a1c29.mpeg', NULL, NULL, 0, '2021-04-25 22:33:08', 1),
(212, 44, 'Different highlife guitar lines', 'We will create different guitar lines in this lesson.\r\nLearn them and be inspired to do more', 11, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-22 16:02:00', 1),
(213, 44, 'You and i will live as one', 'In this lesson, we will create different guitar lines on this song by Onyeka Onwenu.', 8, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-22 15:58:16', 0),
(214, 45, 'Makossa Guitar Style ( 1 4 5 4)', 'Now, we move over to Makossa Guitar. \r\nMakossa music Originated from Cameroon and guitar is at the center of Makossa. \r\nWe will get started by learning different guitar lines on 1 4 5 4 progression', 1, 'storage/public/thumbnails/aafd0f3.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/db02842.mp4', NULL, 'storage/public/tutorials/practice/580ecbe.mpeg', 'storage/public/tutorials/tablatures/931d97d.pdf', NULL, 0, '2021-04-12 11:07:07', 1),
(215, 45, '6th Melody Makosa Guitar lines', 'We create different Makossa Guitar lines highlighting our 6th melody', 2, 'storage/public/thumbnails/78e0537.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/ce80c3e.mp4', 'storage/public/tutorials/audios/dc93aac.mpeg', 'storage/public/tutorials/practice/5a54191.mpeg', NULL, NULL, 0, '2021-04-12 11:10:43', 1),
(216, 45, '9th melody Makosa Guitar lines', 'We will create different Makossa Guitar lines highlighting the 9th melody', 3, 'storage/public/thumbnails/724fcc4.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/371da0a.mp4', NULL, 'storage/public/tutorials/practice/22f2d3f.mpeg', NULL, NULL, 0, '2021-04-12 11:58:05', 1),
(217, 45, '3rd and 4th Makosa style', 'With our 3rd and 4th duet harmony, we will craft our different Makossa Guitar lines', 4, 'storage/public/thumbnails/d88ed31.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6e7b478.mp4', NULL, 'storage/public/tutorials/practice/8f3accd.mpeg', NULL, NULL, 0, '2021-04-12 11:56:02', 1),
(218, 45, '4th duet harmony Makosa Guitar lines', 'We will Delve into using 4th duet Harmony to create different Makossa Guitar lines', 6, 'storage/public/thumbnails/9d2c1b3.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/bc25319.mp4', NULL, 'storage/public/tutorials/practice/b5177c8.mpeg', NULL, NULL, 0, '2021-04-12 11:59:49', 1),
(219, 45, '3rd duet harmony Makosa Guitar lines', 'Here, we will learn different 3rd duet harmony Makossa Guitar lines', 5, 'storage/public/thumbnails/ade9158.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/efe2c30.mp4', NULL, 'storage/public/tutorials/practice/d9eb0c7.mpeg', NULL, NULL, 0, '2021-04-12 11:53:52', 1),
(220, 45, '1 : 5 : 4 : 5 Makosa Guitar lines', 'We will create different Makossa Guitar lines on this progression  1  5  4  5', 7, 'storage/public/thumbnails/d4fb4b4.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/9ef8f2c.mp4', NULL, 'storage/public/tutorials/practice/ba43785.mpeg', 'storage/public/tutorials/tablatures/8cfdf19.pdf', NULL, 0, '2021-04-12 12:00:39', 1),
(221, 45, '1 : 6 : 4 : 5 Makosa Guitar lines', 'We will learn different Makossa Guitar lines on this progression 1  6  4  5', 8, 'storage/public/thumbnails/b07fadb.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/7f69771.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/715811f.pdf', NULL, 0, '2021-03-31 12:46:19', 1),
(222, 45, '6 : 2 : 5 : 2 Makosa Guitar lines', 'We will learn various Makossa Guitar lines on this progression 6  2  5  2', 9, 'storage/public/thumbnails/f83f9bb.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/3c13e2e.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 12:48:09', 1),
(223, 45, 'Makosa Solo Melody Guitar lines', 'This solo melody concept is playing Makosa Guitar lines with single notes, weaving it notes as though you are playing song melodies.\r\n1 4 5 4 Progression', 10, 'storage/public/thumbnails/0d6c789.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/dc351e5.mp4', NULL, 'storage/public/tutorials/practice/f3bde49.mpeg', 'storage/public/tutorials/tablatures/53e84f3.pdf', NULL, 0, '2021-04-12 12:05:58', 1),
(224, 45, 'Yaweh Yaweh ( Makosa Guitar lines)', 'We will learn different Makossa guitar lines on this song, Yahweh Yahweh,', 11, 'storage/public/thumbnails/7b6e08b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/0610876.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 12:51:50', 1),
(225, 46, 'Strum Angel of my life', 'No Description', 1, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 13:06:11', 0),
(226, 46, 'More Percussive Strum', 'No Description', 2, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 13:06:11', 0),
(227, 46, 'Funky strums', 'No Description', 3, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 13:06:11', 0),
(228, 46, 'Add Snare and Kick to Strum', 'No Description', 4, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 13:06:11', 0),
(229, 46, 'Strum Drumming 1', 'No Description', 5, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 13:06:11', 0),
(230, 46, 'Strum Drumming 2', 'No Description', 6, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 13:06:11', 0),
(231, 46, 'Left hand muted Guitar lines', 'We learn to play muted notes with our left hand.', 7, 'storage/public/thumbnails/31bca99.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/e97ff8a.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/8f1b650.pdf', NULL, 0, '2021-06-23 13:06:11', 0),
(232, 47, 'Bend and Vibrato', 'Guitar string Bending is a basic technique, you achieve this but pushing a string up taunting it thus achieving a higher pitch.\r\nVibrato is an effect you can get when you use your fretting finger to cause a rapid change in the pitch of a Note', 1, 'storage/public/thumbnails/89fe073.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/f1d1d6c.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:36:05', 1),
(233, 47, 'Bend with two fingers', 'The Technique of String bend has some tips that you need to know in order to bend successfully\r\nBending with two fingers is hight necessary for you to bend successfully', 2, 'storage/public/thumbnails/f3d7283.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/02b888c.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 17:19:16', 1),
(234, 47, 'Bend Lines', 'Let&#39;s get started with these sweet bend lines..', 3, 'storage/public/thumbnails/345a7d0.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/85dd18f.mp4', NULL, NULL, NULL, 'Please note that Bending needs sometime for your fingers to develop strength and familiarity with.\r\nSo give it some time. \r\nMake sure you rest your fingers when it hurts ', 0, '2021-03-31 17:21:27', 1),
(235, 47, 'Bend and Vibrato lines', 'Bend and Vibrato go hand in hand, so this lesson contains some sweet bend and Vibrato lines to give you a head start into playing Professional sounding notes', 4, 'storage/public/thumbnails/fffa0d3.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/00c1af2.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 17:23:27', 1),
(236, 48, 'Different pentatonic sequences', 'No Description', 1, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 14:40:07', 0),
(237, 48, 'Application of Different pentatonic sequences', 'No Description', 2, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 14:40:07', 0),
(238, 48, '7th sequence', 'No Description', 3, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 14:40:07', 0),
(239, 48, '7th sequence Licks', 'No Description', 4, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 14:40:07', 0),
(240, 48, 'Apply 7th sequence licks in songs', 'No Description', 5, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 14:40:07', 0),
(241, 48, 'Miscellaneous Sequences', 'No Description', 6, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 14:40:07', 0),
(242, 48, 'Licks and Application of miscellaneous sequences', 'No Description', 7, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 14:40:07', 0),
(243, 49, 'Chromatic - Blues - Diminished - Whole tone scale', 'We will learn the notes and good finger position of 4 must know music Scales\r\nChromatic \r\nBlues\r\nDiminished (Half step and full step) \r\nWhole Tone scales', 1, 'storage/public/thumbnails/9d72d65.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/ae06e68.mp4', NULL, 'storage/public/tutorials/practice/7c09de6.mpeg', NULL, NULL, 0, '2021-04-11 17:18:07', 1),
(244, 49, 'More Music Scales', 'No Description', 2, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-28 07:49:38', 1),
(245, 49, 'How to Mute unwanted string Vibrations', 'As you advance on the Guitar, your ears begin to hear unwanted notes vibrating as you play.\r\nThis lesson should be taken seriously because it teaches you how to curb those unwanted strings vibrating. \r\nAs a Guitarist, you can&#39;t sound professional is you don&#39;t know how to mute unwanted open strings Vibration', 3, 'storage/public/thumbnails/e771b64.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/8fc1984.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 17:31:18', 1),
(246, 49, 'Finger Exercise Major scale intro', 'This is a good finger exercise, efficient for both the Right and left hands', 4, 'storage/public/thumbnails/47a7657.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/91f1540.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 17:32:51', 1),
(247, 49, 'Major Scale speed Building Exercise', 'Okay...\r\nLet&#39;s get on to it... \r\nSpeed Building Exercise with our major scele.', 5, 'storage/public/thumbnails/acfcd1b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/0be976c.mp4', NULL, 'storage/public/tutorials/practice/adf6e88.mpeg', NULL, NULL, 0, '2021-04-11 18:27:31', 1),
(248, 49, 'Chromatic-like Finger Exercise', 'This exercise is very effective for both hands.\r\nWe will group sets of 4 notes and play it to and fro a single string then proceed to the next string and repeat thesame sequence \r\n.', 6, 'storage/public/thumbnails/323a34f.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/450ac0d.mp4', NULL, 'storage/public/tutorials/practice/1a0803c.mpeg', NULL, NULL, 0, '2021-04-11 18:30:24', 1),
(249, 49, 'Finger Exercises', 'This lesson contains different amazing and effective finger exercises', 7, 'storage/public/thumbnails/ca59e92.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/492d9c9.mp4', NULL, 'storage/public/tutorials/practice/6e462d8.mpeg', NULL, NULL, 0, '2021-04-11 18:32:40', 1),
(250, 49, 'Speed Building Exercise (Chromatic)', 'We will be using our chromatic scale to create an interesting finger Exercise for Speed Building', 8, 'storage/public/thumbnails/670e177.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b95a28b.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 17:50:33', 1),
(251, 49, 'Chromatic and Chromatic - like Finger Drill', 'More finger exercises', 9, 'storage/public/thumbnails/443b74f.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/db12893.mp4', NULL, 'storage/public/tutorials/practice/4419fb1.mpeg', NULL, NULL, 0, '2021-04-11 18:38:23', 1),
(252, 50, 'Chord Formation', 'There is a Formula to the Formation of All Chords.\r\nWe will study this principle in this Lesson so that you can know in depth how  Chords are Formed', 1, 'storage/public/thumbnails/c5aeafd.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/bc84d04.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 18:04:14', 1),
(253, 50, '7th Chords 1st position', 'All 7th chords\r\nLearn different shapes of our 7th chord on the first pattern', 2, 'storage/public/thumbnails/c159c77.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/78a31cf.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 18:05:19', 1),
(254, 50, '7th chords 2nd position', 'Now we move over to the 2nd Positions of our 7th Cbords', 3, 'storage/public/thumbnails/e5e47b1.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/e855a25.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 18:06:43', 1),
(255, 50, '7th Chord with B dim7 and Bmin7b5', 'This set of 7th Chord has a Minor 7th flat 5th for Ti ( B). The other ones had Diminished 7th for Ti ( B ). So study it so study it carefully so you understand the difference', 4, 'storage/public/thumbnails/67cfd83.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/e61f2b5.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 18:09:08', 1),
(256, 50, '7th Chord Changes', 'The first stage to making use of Chords we learn new is to fix em all in different Chord changes.\r\nHere we go guys.. \r\nLet&#39;s learn these 7th chord changes.', 5, 'storage/public/thumbnails/f3d3c87.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/f84f56e.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 18:11:25', 1),
(257, 52, 'Different chord types', 'This is an amazing way to be able to figure out chords by your self.\r\nIn this lesson we will learn to hold different chord type in a particular key and also how to move that chord shape to other keys and get thesame chord type but in a different key', 1, 'storage/public/thumbnails/ddc6333.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b63558c.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 10:15:59', 1),
(258, 52, 'Sus - Add - Extended and  Altered Chords', 'We are going to Learn different advanced Chords in this lesson\r\nSuspended chords, Add Chords, Chord Extensions and Altered Chords.', 2, 'storage/public/thumbnails/f731c83.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/909a936.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 10:17:48', 1),
(259, 52, 'Altered Chords', 'We will learn about altered Chords in this lesson', 3, 'storage/public/thumbnails/1a63c0b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/23747c7.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 14:15:12', 1),
(260, 52, 'Extended and Chord tone preference', 'As we begin to increase the chain of Chords adding extended notes, we will have a pile of notes that will out number what our fingers can hold. \r\nIn this lesson, we will learn how to filter the notes down to the  notes that are more important than the others', 4, 'storage/public/thumbnails/0c8fcd8.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/455a42f.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 14:18:45', 1),
(261, 52, 'Super imposed and Slash Chords', 'Chord Super imposition is bringing two different chords together and playing them as one..\r\nSlash Chord is play a Chord over a Bass that is not the Root. \r\nWe will learn about these two different chords in this lesson', 5, 'storage/public/thumbnails/ac99618.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/642ed87.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 14:29:15', 1),
(262, 52, 'Voice leading', 'A Chord is consist of Different Notes.\r\nThese Notes can be called Voices. \r\nVoice leading is the highest note ( Voice ) in the Chord, this note is most times the most Audio', 6, 'storage/public/thumbnails/926fea5.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/2635c4f.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 14:37:40', 1),
(263, 52, 'Different Chord progressions', 'In this lesson, we will use all the chords we have learnt Altered, Slash, Superimposed e.t.c in different Chord progressions', 7, 'storage/public/thumbnails/ff01e78.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/61f9d5d.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 14:40:28', 1),
(264, 53, 'Chord Substitution', 'Chord Substitution is replacing a chord with another. \r\nWe will learn different principles for Chord Substitution', 1, 'storage/public/thumbnails/8da2681.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/fb09c92.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 14:41:46', 1),
(265, 53, 'Substitute minor 6th for Major Chord', 'For chord Substitutions, there is a principle if well observed, we can easily substitute Major Chords for minor.\r\nLet&#39;s learn how that is possible in this lesson', 4, 'storage/public/thumbnails/11f713d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/1063397.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:53:43', 1),
(266, 53, 'Passing Chord', 'Passing Chords are Chords you play to get to another Chord.\r\nWe will learn different chords that are good passes to other chords in this lesson', 5, 'storage/public/thumbnails/a93af48.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/32bc4b8.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:54:27', 1),
(267, 53, 'Passing Chord ( Minor concept)', 'No Description', 4, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-03-27 11:49:52', 0),
(268, 53, 'Passing Chord ( Semi-Tone concept) Detail Breakdown', 'One concept of passing Chord is the Semi-Tone movement...\r\nFor this concept, we can always pass to a chord by playing the next note ( Semi-Tone) away from the Chord \r\nThat is, Chord B can pass to Chord C, or Chord C# can pass to Chord C', 6, 'storage/public/thumbnails/0654a71.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/ff835a7.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:57:18', 1),
(269, 53, 'Passing Chord ( 5 : 1 Concept )', 'No Description', 6, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-03-27 11:52:20', 0),
(270, 53, 'Turn Around', 'A Turn around is a set of movements at the end of a section that leads to the beginning or another section.\r\nIn this lesson, we will learn different turnaround movements', 8, 'storage/public/thumbnails/8e273c7.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/de37c8c.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:58:31', 1),
(271, 54, 'Circle of 4th (introduction)', 'Circle of 4th here is an arrangement of 4th interval movements.\r\nThe sequence continues until it circles back to the beginning. \r\nThis famous concept is very instrumental to our chord progressions when we learn how to use it.', 1, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-22 16:53:45', 0),
(272, 54, 'Circle of 4th Chords', 'No Description', 2, 'storage/public/thumbnails/7b08475.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a19eac4.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-27 14:03:16', 0),
(273, 54, 'Different Chord options for Circle of 4th', 'Circle of 4th has a standard formation, but the chords we use for the notes are not fixed.\r\nIn this lesson, we will learn more chord options for Circle of 4th', 7, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-22 16:58:20', 0),
(274, 54, 'Circle of 4th Application on different songs', 'Now we get to applying circle of 4th concept in different song progressions', 5, 'storage/public/thumbnails/0a74aec.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b457c3e.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-09 01:10:51', 1),
(275, 55, 'Getting started with Sweep A', 'No Description', 1, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-28 21:19:31', 0),
(276, 55, 'Getting started with Sweep picking A', 'Sweep is a picking Technique that can make you attain some crazy speed.\r\nSweep picking has rules. \r\nIn this lesson, we get started learning sweep picking', 1, 'storage/public/thumbnails/bff4d28.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/c47f6da.mp4', NULL, 'storage/public/tutorials/practice/e98be56.mpeg', NULL, NULL, 0, '2021-04-13 22:45:15', 1),
(277, 55, 'Getting started with Sweep picking B', 'More insights to sweep picking here.', 2, 'storage/public/thumbnails/061e94e.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/f0f4573.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 15:19:12', 1),
(278, 55, 'Minor and Major Arpeggio Sweep picking', 'We will learn how to sweep major and minor Chord Arpeggios in this lesson', 6, 'storage/public/thumbnails/231d57b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/51050a3.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 15:22:22', 1),
(279, 55, 'Record more sweep pick simple Exercises', 'No Description', 4, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-03-27 14:38:28', 0),
(280, 56, '4th sequence', 'No Description', 1, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, 'storage/public/tutorials/practice/ace19a9.mpeg', NULL, NULL, 0, '2021-06-22 17:07:38', 0),
(281, 56, '9th Sequence', 'We will sequence our chord Arpeggios and play them around the fret board.\r\nMajor 9th ( Doh, Fah and Soh ) \r\nminor 9th  ( Re, Mi, Lah ) \r\nminor 7th flat 5 ( Ti )', 2, 'storage/public/thumbnails/0a7ff7e.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/c5961dc.mp4', NULL, 'storage/public/tutorials/practice/7b6d167.mpeg', NULL, NULL, 0, '2021-04-12 13:46:42', 1),
(282, 56, 'Major add 9th Sequence', 'No Description', 3, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, 'storage/public/tutorials/practice/604c29d.mpeg', NULL, NULL, 0, '2021-06-22 17:13:41', 0),
(283, 56, 'Diminished 7th Arpeggio 1', 'We will learn an interesting Diminished 7th ( Doh Maw Fe Lah ) Arpeggio on the Guitar Fret Board', 3, 'storage/public/thumbnails/4cc8cd8.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/18b8656.mp4', NULL, 'storage/public/tutorials/practice/f80d7a3.mpeg', NULL, NULL, 0, '2021-06-22 17:19:11', 1),
(284, 56, 'Diminished 7th Arpeggio 2', 'More Diminished Arpeggio patterns', 4, 'storage/public/thumbnails/f5193a0.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/52700f4.mp4', NULL, 'storage/public/tutorials/practice/bc2731b.mpeg', NULL, NULL, 0, '2021-06-22 17:19:53', 1),
(285, 57, 'Glorious God - Onise Iyanu', 'Putting to use the chords we had learnt,  we learn this advanced chord progression of the song Glorious God', 1, 'storage/public/thumbnails/4f2e352.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d48165d.mp4', NULL, 'storage/public/tutorials/practice/92f912d.mpeg', NULL, NULL, 0, '2021-04-12 14:44:13', 1),
(286, 57, 'Pilllar that holds my Life - Bless the Lord o my soul', 'We will apply different concepts on the Song ( You are the pillar that holds my life )', 2, 'storage/public/thumbnails/3b545dd.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/da6d7df.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 16:05:51', 1),
(287, 57, 'IGWE only you are God', 'We will be applying some of the Chords we had already learnt in this Song ( IGWE, only you art God)', 3, 'storage/public/thumbnails/51b554a.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/4ec8b26.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 16:06:22', 1),
(288, 57, 'Nara - Eze you are Worthy of my Praise', 'We will improve these to song progressions with the different chords and concepts we have learnt\r\nNara and Eze song', 4, 'storage/public/thumbnails/478e3ac.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/8c0482c.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 16:07:35', 1),
(289, 57, 'Falling in Love with Jesus', 'Falling in love with Jesus Chord progression', 5, 'storage/public/thumbnails/c5e61ec.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/4dd5bf2.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 16:10:18', 1),
(290, 57, 'Olorun toda awon', 'We learn to apply the different concepts and Chords we have learnt on this song OLORUN TODA AWON', 6, 'storage/public/thumbnails/196611a.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/39da642.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 16:09:05', 1),
(291, 57, 'Amen Amen', 'Chord progression of song Amen Amen with Advanced Chords and concepts', 7, 'storage/public/thumbnails/1b52b49.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/fd478c0.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 16:11:25', 1),
(292, 60, 'Strong Beat', 'There is an interesting formula that works for improvisation. But before we really get into improvisations, let&#39;s learn about STRONG BEAT. \r\nThis is necessary because we will need the understanding when we improvise.', 1, 'storage/public/thumbnails/f90cd96.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/473b270.mp4', NULL, 'storage/public/tutorials/practice/7ca8778.mpeg', NULL, NULL, 0, '2021-04-12 14:46:20', 1),
(293, 58, 'Tritone Substitution', 'No Description', 1, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-01-28 21:43:56', 0),
(294, 58, 'Tritone Substitution 2', 'Get even more broader with the Tritone Substitution concept', 2, 'storage/public/thumbnails/2c64acf.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/f2f5fca.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 16:15:34', 1),
(295, 58, 'Tritone Substitution 1', 'Tri Substitution is first a simple substitution but the concept and principle behind it is replacing (substituting) a chord with another Chord that is 3 tones (tritone) away', 1, 'storage/public/thumbnails/a09b373.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6ee3ae7.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 16:13:49', 1),
(296, 60, 'Melody-Like improvisation (F : C : Am)', 'I like to start off teaching improvisation with the Melody-like Concept. \r\nIt&#39;s simple, it&#39;s basically playing melodious notes as if you are playing a song melody.\r\nMelody in improvisation is very import, Melody is the soul of improvisation', 2, 'storage/public/thumbnails/f80af8d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6f40425.mp4', NULL, 'storage/public/tutorials/practice/d661d04.mpeg', NULL, NULL, 0, '2021-04-14 08:57:12', 1),
(297, 60, 'Melody-Like improvisation (Am : C : F)', 'We will improvise over Amin C Maj  and  F Maj Chord progressions using the Melody-like improvisation concept', 3, 'storage/public/thumbnails/89a4c10.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/8f2b620.mp4', NULL, 'storage/public/tutorials/practice/59c2e34.mpeg', NULL, NULL, 0, '2021-04-14 10:28:03', 1),
(298, 60, 'Melody-Like improvisation', 'Here, we will have more talk on Melody-like improvisation', 4, 'storage/public/thumbnails/480c76c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a0a62dd.mp4', NULL, 'storage/public/tutorials/practice/a9a4777.mpeg', NULL, NULL, 0, '2021-04-14 10:30:56', 1),
(299, 60, 'Arpeggio Improvisation 1', 'Yes, Arpeggio Arpeggio.\r\nArpeggio is a go to in almost all different styles of music. \r\nIn this lesson, we will learn how to approach improvisation with arpeggio', 5, 'storage/public/thumbnails/3fc9359.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/11e67d9.mp4', NULL, 'storage/public/tutorials/practice/cabf599.mpeg', NULL, NULL, 0, '2021-04-14 10:44:44', 1),
(300, 60, 'Arpeggio Improvisation 2', 'More arpeggio improvisation licks', 6, 'storage/public/thumbnails/961bf6b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/2718397.mp4', NULL, 'storage/public/tutorials/practice/8f1fcda.mpeg', NULL, NULL, 0, '2021-04-14 10:46:26', 1),
(301, 60, 'Arpeggio Improvisation 3', 'Another set of Arpeggio licks for Arpeggio Improvisation concept', 7, 'storage/public/thumbnails/bd865c0.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/55222da.mp4', NULL, 'storage/public/tutorials/practice/2ae27eb.mpeg', NULL, NULL, 0, '2021-04-14 10:41:14', 1),
(302, 60, 'Minor Scales and their relative Major', 'Relative Minor and Relative Major. \r\nThis lesson will help us to read minor Scale from a Major perspective and Vise Versa', 8, 'storage/public/thumbnails/fb845ae.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a3a25b4.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 16:42:58', 1),
(303, 60, 'Natural Minor Scale Improvisation 1', 'Now we get into an interesting Improvisation formula that works. \r\nApplying our natural minor scale over thesame very minor Chord\r\nThat is C natural minor scale over C minor Chord. Thesame for Aminor and every other Minor Chords', 9, 'storage/public/thumbnails/422bda9.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/4f97c0a.mp4', NULL, 'storage/public/tutorials/practice/7c44fcc.mpeg', NULL, NULL, 0, '2021-04-12 16:05:20', 1),
(304, 60, 'Natural Minor Scale Improvisation 2', 'More Amazing Natural Minor licks', 10, 'storage/public/thumbnails/8b1bdce.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/688ea8c.mp4', NULL, 'storage/public/tutorials/practice/83db7b4.mpeg', NULL, NULL, 0, '2021-04-14 10:55:30', 1),
(305, 60, 'Talk Melodic minor Scale', 'Thesame formula that worked for Natural Minor, we will use it even for Melodic Minor.\r\nThat is, C Melodic minor Scale over C minor Chord', 12, 'storage/public/thumbnails/2a0fa90.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/999576c.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-14 10:57:50', 1),
(306, 60, 'Melodic Minor Licks', 'More interesting Melodic minor Licks over minor chords', 11, 'storage/public/thumbnails/bf79cf2.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/7b084bc.mp4', NULL, 'storage/public/tutorials/practice/f310dd9.mpeg', NULL, NULL, 0, '2021-04-14 10:58:44', 1),
(307, 60, 'Harmonic minor licks', 'In this lesson, we will learn different Harmonic minor Licks and apply them over a minor Chord', 14, 'storage/public/thumbnails/4282925.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/404d2bb.mp4', NULL, 'storage/public/tutorials/practice/9ac2510.mpeg', NULL, NULL, 0, '2021-06-22 17:29:38', 1),
(308, 61, 'Mode Part A', 'Mode is one topic that is over flogged amongst Guitarist.\r\nThat hype has made Mode seem harder than it really it. \r\nAnyway, let&#39;s take a look at modes here and try to Demystify it for us', 1, 'storage/public/thumbnails/7631641.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/3b726a5.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 16:55:40', 1),
(309, 61, 'Mode part B', 'More Breakdown on Modes', 2, 'storage/public/thumbnails/d4cd034.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d34c405.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 16:58:17', 1),
(310, 61, 'Dorian Mode Application', 'Dorian is the 2nd degree of our mode\r\nWe will learn how to use it and how to spot progressions that Dorian can work well with', 3, 'storage/public/thumbnails/a34c0d3.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/2f505ac.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 17:01:13', 1),
(311, 61, 'Phrygian Mode Application', 'Phrygian Mode Application..\r\nWhen to use Phrygian mode, let&#39;s get some tips here in this lesson', 4, 'storage/public/thumbnails/3d0cd6f.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6d5c318.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 17:02:24', 1),
(312, 61, 'Lydian Mode Application', 'Now we learn how we have use Lydian Mode in a chord progression', 5, 'storage/public/thumbnails/52cdaf7.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/7c6bb9b.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 17:05:26', 1),
(313, 61, 'Mixolydian Mode Application', 'This we go to Mixolydian', 6, 'storage/public/thumbnails/ec24f48.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a9c52cb.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 17:06:50', 1),
(314, 61, 'Aeolian Mode Application', 'This time we learn how to apply Aeolian mode application', 7, 'storage/public/thumbnails/7656282.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/7635c0b.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 17:07:34', 1),
(315, 61, 'Locrian Mode Application', 'Locrian Mode application', 8, 'storage/public/thumbnails/5c47ac8.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a4ace04.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-01 17:07:59', 1),
(316, 82, 'Worship Guitar Style intro', 'No Description', 1, 'storage/public/thumbnails/33a308b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/3c6decf.mp4', NULL, NULL, NULL, NULL, 0, '2021-02-01 18:23:38', 1),
(317, 82, 'Worship Guitar Arpeggio A', 'Arpeggio is always a good way to start playing different music styles.\r\nThesame is true for Worship Guitar. \r\nLet&#39;s get started learning these easy arpeggio lines', 2, 'storage/public/thumbnails/4325888.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/4967882.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 18:01:03', 1);
INSERT INTO `lesson_tbl` (`id`, `course`, `lesson`, `description`, `ord`, `thumbnail`, `tutor`, `low_video`, `high_video`, `audio`, `practice_audio`, `tablature`, `note`, `free`, `date_added`, `active`) VALUES
(318, 82, 'Worship Guitar Arpeggio B', 'More Worship Guitar Arpeggio lines here', 3, 'storage/public/thumbnails/a3e262a.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/c6244e3.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 18:01:41', 1),
(319, 82, 'Worship Guitar ( Repeated Notes)', 'You don&#39;t Strum all the time when you play Worship, sometimes u pick your notes and repeat some particular set of notes throughout the Chord Changes', 4, 'storage/public/thumbnails/e5fef70.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/ab97d45.mp4', NULL, NULL, NULL, NULL, 0, '2021-03-31 17:59:38', 1),
(320, 82, 'Worship Guitar 3rd Harmony', 'Playing worship with 3rd duet harmony is possible too.\r\nLet&#39;s hear how it sounds in this lesson', 5, 'storage/public/thumbnails/8fdead9.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/50c11dc.mp4', NULL, 'storage/public/tutorials/practice/be9a0fd.mpeg', NULL, NULL, 0, '2021-06-23 21:18:00', 1),
(321, 82, 'Worship Guitar Rhythm', 'This Rhythm works well for Worship.', 9, 'storage/public/thumbnails/117a084.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/219181b.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 06:02:03', 1),
(322, 82, 'Worship Guitar 10th Harmony', '10th duet harmony is very Suitable for Worship.\r\n10th duet harmony lines in worship are very sweet and cool', 6, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-22 06:04:20', 0),
(323, 82, 'Worship Guitar (Octave lines)', 'We will learn how we can accompany Worship songs using Octave Duets', 10, 'storage/public/thumbnails/654a401.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b9ee4bb.mp4', NULL, 'storage/public/tutorials/practice/b37523b.mpeg', NULL, NULL, 0, '2021-06-23 21:14:04', 1),
(324, 3, 'Welcome to Spicy Guitar Academy', 'We welcome all new students on board.\r\nThe journey promises to be engaging and fulfilling.\r\nSpicy Guitar Academy app is loaded with lessons and features that will shape you to  become the Guitarist you dreamed to be and beyond.\r\nMake the best of this guitar learning platform. \r\nAsk questions, engage the practice section, try to understand one lesson before jumping to the next and make enough time for practice. \r\nWe are here for you. \r\nWe are in this together. \r\n#guitarist igniting Creativity', 1, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, 'storage/public/tutorials/tablatures/c746935.pdf', 'These are the introductory messages for guitar newbies!', 1, '2021-04-08 17:14:11', 1),
(325, 3, 'Test Lesson', 'No Description', 8, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-03-09 13:16:32', 0),
(326, 3, 'Test Lesson', 'No Description', 18, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-03-09 13:16:32', 0),
(327, 3, 'Test Lesson', 'No Description', 7, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-03-09 13:16:32', 0),
(328, 26, 'Melodies and Chord changes on key D', 'No Description', 7, 'storage/public/thumbnails/3f728db.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/9e6331d.mp4', NULL, NULL, 'storage/public/tutorials/tablatures/1d186b1.pdf', NULL, 0, '2021-02-02 23:28:19', 1),
(329, 3, 'Test Lesson', 'No Description', 7, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-03-09 13:16:32', 0),
(330, 51, 'Octave duets', 'An Octave is an interval with a distance of 8.\r\nThe Octave of key C note will be another key C note on a higher or lower range which is the 8th note higher or lower than the original one..\r\n\r\nOctave duet is playing two thesame notes with different range at once. That&#039;s a high and low range of thesame notes at once', 7, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-22 15:09:37', 0),
(331, 3, 'Test Lesson', 'No Description', 1, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-03-09 13:16:32', 0),
(332, 3, 'Test Lesson', 'No Description', 2, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-03-09 13:16:32', 0),
(333, 38, 'Simple Arpeggio lines 1', 'Different simple Arpeggio lines', 3, 'storage/public/thumbnails/b3c8cd0.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/9054851.mp4', NULL, 'storage/public/tutorials/practice/dd926af.mpeg', NULL, NULL, 0, '2021-06-22 19:09:06', 1),
(334, 38, 'Simple Arpeggio lines 2', 'We learn more Simple Arpeggio lines here', 4, 'storage/public/thumbnails/3488ebf.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a1e6710.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 19:09:41', 1),
(335, 38, 'Simple melodies', 'No Description', 5, 'storage/public/thumbnails/0fb7b5b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/28af6f6.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-05 12:44:31', 0),
(336, 100, 'Simple song progressions 2', 'No Description', 2, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 09:22:58', 1),
(337, 31, 'Major Chord Shapes ( C and F)', 'We will learn different C and F Major shapes of Chords on different positions. \r\nThis lesson will help you to see the fret board better', 6, 'storage/public/thumbnails/9a310b9.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/00f9ddb.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 20:02:47', 1),
(338, 31, 'Minor Chord shapes ( D minor and A minor)', 'Now we will be exploring different minor Chords on the Guitar board using D minor and A minor Shapes\r\nThis lesson makes moving around the fret board with different shapes of the minor chords easy', 8, 'storage/public/thumbnails/6f13048.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/1fe2cd3.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 19:56:16', 1),
(339, 31, 'Minor Chord Shape ( E minor)', 'Now we learn different E minor  Shape of Chords on the Guitar board.\r\nThis lesson makes moving around the fret board with different shapes of the minor chords easy', 9, 'storage/public/thumbnails/655ddee.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/0164412.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 19:59:52', 1),
(340, 31, 'Key C complete Chords (2nd and 3rd positions)', 'We will learn how to hold Fuller chords, strumming through all 6 strings for some chords', 5, 'storage/public/thumbnails/4636284.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/188d3d1.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 19:16:35', 1),
(341, 5, 'Slur - Hammer-on and Pull-off', 'Slur, Hammer-on and pull-off technique will make your play dynamic and interesting.', 8, 'storage/public/thumbnails/1d78724.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a9e2f78.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-08 18:31:10', 1),
(342, 32, 'Sequence of 4th', 'No Description', 3, 'storage/public/thumbnails/9ee372d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/34559fd.mp4', NULL, 'storage/public/tutorials/practice/a7294b8.mpeg', NULL, NULL, 0, '2021-04-09 22:21:22', 1),
(343, 101, 'Percussive Guitar (Playing Beats)', 'Let&#39;s learn how to play some drum beats with our acoustic Guitar', 2, 'storage/public/thumbnails/71a1235.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/96fa07e.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 13:33:26', 1),
(344, 26, 'Eya adaba song progression on key A', 'The chord progression of the song EYA ADABA  on Key A', 11, 'storage/public/thumbnails/1fd7b5c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a6aa534.mp4', NULL, 'storage/public/tutorials/practice/12cf2ec.mpeg', NULL, NULL, 0, '2021-06-22 20:29:22', 1),
(345, 26, 'ElShadai (Song Progression) on Key A', 'The chord progression of the song ELSHADAI on Key A', 12, 'storage/public/thumbnails/d7dfce2.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/fc29ecb.mp4', NULL, 'storage/public/tutorials/practice/b168b4c.mpeg', NULL, NULL, 0, '2021-06-22 20:26:33', 1),
(346, 42, 'Finger style lines on key E (2)', 'This is another interesting Finger style line yet on key E', 8, 'storage/public/thumbnails/be0b724.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/31e6724.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:24:10', 1),
(347, 42, 'Finger Style line on Key D (1)', 'This finger Picking line on Key D.\r\nRemember these lines are too inspire you in different ways, that&#39;s why we are learning much', 11, 'storage/public/thumbnails/4ebfcd9.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d51e6c2.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:22:29', 1),
(348, 43, 'Ariaria Guitar Style Arpeggio', 'Arpeggio is a great way to start creating guitar lines for almost any music stye.\r\nIn this lesson, we will learn different arpeggio lines for Ariaria', 3, 'storage/public/thumbnails/bcc51f7.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/e101024.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-09 22:39:12', 1),
(349, 43, '3rd and 10th harmony Ariaria Guitar lines', 'In this Lesson, we will use 3rd and 10th duet harmonies to learn different Ariaria Guitar lines', 10, 'storage/public/thumbnails/41792b1.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/7e036c3.mp4', NULL, 'storage/public/tutorials/practice/c8f3691.mpeg', NULL, NULL, 0, '2021-04-25 22:24:43', 1),
(350, 43, 'Ariaria Octave Duet lines A', 'Now we learn Ariaria Guitar lines using Octave duets', 13, 'storage/public/thumbnails/7320f18.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/669bd2d.mp4', NULL, 'storage/public/tutorials/practice/e4b1890.mpeg', NULL, NULL, 0, '2021-04-25 22:23:09', 1),
(351, 43, 'Ariaria Octave Duet lines B', 'More Octave duet Ariaria Guitar lines', 14, 'storage/public/thumbnails/0a3464d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/c991abf.mp4', NULL, 'storage/public/tutorials/practice/3e236b0.mpeg', NULL, NULL, 0, '2021-04-25 22:22:38', 1),
(352, 43, 'Ariaria Guitar Style IGWE A', 'IGWE is another fast beat that yet has the vibe of Ariaria Music.\r\nSo this lesson starts us off with Ariaria Guitar lines for the IGWE Ariaria series', 15, 'storage/public/thumbnails/f23adfd.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/e1ee6da.mp4', NULL, 'storage/public/tutorials/practice/81fae35.mpeg', NULL, NULL, 0, '2021-04-25 22:21:50', 1),
(353, 43, 'Ariaria Guitar Style IGWE B', 'More Guitar lines on the IGWE Ariaria Song', 16, 'storage/public/thumbnails/dbdce85.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/e086d72.mp4', NULL, 'storage/public/tutorials/practice/4d737af.mpeg', NULL, NULL, 0, '2021-04-25 22:21:20', 1),
(354, 44, 'Joromi Guitar Solo', 'Now to the all time outstanding Joromi Guitar Solo by the Legend Sir Victor Uwaifo.\r\nThis lesson is a complete breakdown of the Joromi Guitar solo', 7, 'storage/public/thumbnails/aed788c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/9544c90.mp4', NULL, 'storage/public/tutorials/practice/478424c.mpeg', NULL, NULL, 0, '2021-04-25 22:32:36', 1),
(355, 101, 'African Queen (Percussive Guitar)', 'In this lesson, we will Learn a groovy combination of African Queen Guitar line played simultaneously with a Guitar Kick and Snare', 7, 'storage/public/thumbnails/b5f1469.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/460ca4a.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 13:38:37', 1),
(356, 101, 'Feel my Love ( Acoustic Percussive)', 'We will learn a percussive Strum pattern and play it with a song FEEL MY LOVE by ADELE', 3, 'storage/public/thumbnails/e174078.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/85c041e.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 13:33:58', 1),
(357, 101, 'Feel my Love (Chords explained)', 'This is the Chord progression breakdown of the SONG FEEL MY LOVE by ADELE', 4, 'storage/public/thumbnails/6c71762.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6e92b83.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 13:36:38', 1),
(358, 101, 'Thump Nailing Bass Notes', 'Here, we will learn how to Nail Bass note and Thump Simultaneously.\r\nNailing is simply using your right fingers nails to hit the Bass note.', 6, 'storage/public/thumbnails/42a17ff.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/9f15669.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 13:37:59', 1),
(359, 101, 'Thump Plucking Bass Notes and Chords', 'Thumping is Play Kick drum on the acoustic guitar\r\nSo Thump Plucking Bass Notes is Playing Kick and pluck a Bass note simultaneously. \r\nIn this lesson, we will learn with different exercises how to Kick and Play Bass Notes and also Kick and play chords simultaneously.', 5, 'storage/public/thumbnails/0b4a592.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b34f1df.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 13:37:13', 1),
(360, 101, 'Acoustic Percussive Guitar', 'Acoustic Percussive Guitar Style.\r\nThe concept s basically using the acoustic Guitar to achieve Alot more percussion and even Drumming with it.\r\nWe will learn different ways of achieving various percussive sound and combine it with Strums and melodies', 1, 'storage/public/thumbnails/f98f851.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/ca8edf8.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 13:19:57', 1),
(361, 101, 'Afro Beat (Acoustic Percussive Guitar)', 'We will learn this Groovy  Afro Drum Beat on the Guitar and combine it with a melody line', 8, 'storage/public/thumbnails/7e0fefd.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a9184b3.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 13:39:25', 1),
(362, 92, 'Right and left hand mute', 'Right and left hand muting is a Technique every guitarist should learn.\r\nIt&#39;s impossible to play some guitar lines if you don&#39;t know how to mute', 1, 'storage/public/thumbnails/1e6df63.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a549677.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 20:35:49', 1),
(363, 92, 'Left hand mute guitar lines 2 and 1', 'Guitar lines played with left hand mute.\r\nThis will give you an idea about muted Guitar lines', 2, 'storage/public/thumbnails/0d45cd2.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/51af001.mp4', NULL, 'storage/public/tutorials/practice/3f42b82.mpeg', NULL, NULL, 0, '2021-06-22 20:38:22', 1),
(364, 92, 'Left hand mute line 3', 'More left hand mute lines', 3, 'storage/public/thumbnails/fd6c089.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/da02e7e.mp4', NULL, 'storage/public/tutorials/practice/0e4e0d9.mpeg', NULL, NULL, 0, '2021-06-22 20:39:10', 1),
(365, 53, '5 - 1 Passing Chord concept', '5 - 1 is Soh to Doh or key G to Key C passing Chord.\r\nThis means the Fifth of a Chord can always pass to the root Chord. \r\nWe will learn the details in this lesson', 7, 'storage/public/thumbnails/c5d13ed.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d08291e.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:58:06', 1),
(366, 54, 'Adding Chords to Circle of 4th', 'Ok we have the 4th notes circle, now we represent each note with a chord.\r\nThis lesson shows us application chords to use for Circle of 4th', 2, 'storage/public/thumbnails/ec2ba25.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/3348a03.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-09 01:13:45', 1),
(367, 54, 'Circle of 4th Application 3 Moves', 'We will learn three set of Circle of 4th Chords that we can learn and almost immediately apply to sing progressions', 4, 'storage/public/thumbnails/0f6292e.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6178b1a.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-09 01:11:45', 1),
(368, 54, 'Circle of 4th Application 2 moves', 'In this lesson, we will learn two sets of Circle of 4th Chords that we can apply in chord progressions', 3, 'storage/public/thumbnails/aea3ad7.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/11dd0cc.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-09 01:12:37', 1),
(369, 54, 'Araba Ribiti chord progression with circle of 4th', 'We will apply circle of 4th chord movement on this song Araba_Ribiti.\r\nThe movement we applied here is the circle of 4th movement that passes to the 3rd Minor ( E minor)', 6, 'storage/public/thumbnails/8e14300.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/31a5916.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-09 01:09:35', 1),
(370, 54, 'More advanced use of Circle of 4th', 'What more can we do with circle of 4th?\r\nAnyway, let&#39;s see what we can here in this lesson', 9, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-22 17:04:55', 0),
(371, 55, 'Sweep picking exercises A', 'Let&#39;s learn some sweep picking exercise that will kick us off into sweeping', 3, 'storage/public/thumbnails/4080b37.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/8e50266.mp4', NULL, 'storage/public/tutorials/practice/88685a0.mpeg', NULL, NULL, 0, '2021-04-12 14:33:37', 1),
(372, 55, 'Sweep picking exercises B', 'More sweep picking exercises', 4, 'storage/public/thumbnails/a327461.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/09e1a53.mp4', NULL, 'storage/public/tutorials/practice/50f2999.mpeg', NULL, NULL, 0, '2021-04-12 14:30:37', 1),
(373, 55, 'Sweep picking exercises C', 'Learn these sweep picking exercises, it&#39;s a great way to getting a handle on Sweep picking', 5, 'storage/public/thumbnails/baa6d89.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/e5882cd.mp4', NULL, 'storage/public/tutorials/practice/42b0249.mpeg', NULL, NULL, 0, '2021-04-12 14:35:40', 1),
(374, 55, 'Chromatic Scale with Sweep picking', 'We will play our chromatic scale and PARTIALLY using sweep picking in the process', 7, 'storage/public/thumbnails/2a028ad.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/7972510.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-09 01:40:45', 1),
(375, 55, 'Major Chord sweep picking licks A', 'We will learn different licks that are PARTIALLY picked with Sweep picking.\r\nThese licks can be applied over a Major Chord', 8, 'storage/public/thumbnails/2373a62.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/0b64097.mp4', NULL, 'storage/public/tutorials/practice/c730d54.mpeg', NULL, NULL, 0, '2021-04-14 08:43:52', 1),
(376, 55, 'Major Chord Licks with Sweep picking B', 'Major Chord Licks played with Sweep picking at some point', 9, 'storage/public/thumbnails/cadbb0f.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/08dbf52.mp4', NULL, 'storage/public/tutorials/practice/9b1b5d2.mpeg', NULL, NULL, 0, '2021-04-14 08:49:56', 1),
(377, 55, 'Major Chord Licks with Sweep picking C', 'More major Chord Licks Partially played with Sweep picking', 10, 'storage/public/thumbnails/386224c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/191cc9f.mp4', NULL, 'storage/public/tutorials/practice/707b97c.mpeg', NULL, NULL, 0, '2021-04-14 08:48:32', 1),
(378, 55, 'Major Chord Licks with Sweep picking D', 'We learn more licks played partially with Sweep picking..\r\nLicks that can be applied over any Major Chord', 11, 'storage/public/thumbnails/f14bef4.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/8730398.mp4', NULL, 'storage/public/tutorials/practice/868d98d.mpeg', NULL, NULL, 0, '2021-04-14 08:51:05', 1),
(379, 55, 'Major Chord Licks with Sweep picking E', 'These licks can be well applied over Major Chords.\r\nThe licks are played with a combination of Sweep and alternate picking', 12, 'storage/public/thumbnails/5f715d6.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/52fbd68.mp4', NULL, 'storage/public/tutorials/practice/84c6619.mpeg', NULL, NULL, 0, '2021-04-14 08:41:37', 1),
(380, 55, 'Creative Tips for Sweep picking Lick (MAJOR)', 'This lesson is very important.\r\nWe talked about how you can transpose a lick, playing thesame C Major transposed to Key F. \r\nAlso about playing thesame lick but with different rhythm and groove', 13, 'storage/public/thumbnails/8189ea3.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/fba6b02.mp4', NULL, 'storage/public/tutorials/practice/89b323a.mpeg', NULL, NULL, 0, '2021-04-14 08:54:12', 1),
(381, 55, 'Minor Chord licks with Sweep picking A', 'This licks will work for Minor Chord.\r\nWe use a combination of Sweep and alternate picking on these', 15, 'storage/public/thumbnails/b4b4c56.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d9e865e.mp4', NULL, 'storage/public/tutorials/practice/0668d6f.mpeg', NULL, NULL, 0, '2021-04-12 13:27:55', 1),
(382, 55, 'Minor Chord licks with Sweep picking B', 'More Minor Chord Licks with Sweep and alternate picking', 16, 'storage/public/thumbnails/e1d56cf.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/3d6ca7f.mp4', NULL, 'storage/public/tutorials/practice/6df618d.mpeg', NULL, NULL, 0, '2021-04-12 13:29:15', 1),
(383, 55, 'More ideas on Sweep picking licks ( MAJOR)', 'Let&#39;s learn more tips and ideas on playing Licks for Major Chords with Sweep and alternate picking', 14, 'storage/public/thumbnails/a06503b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/47dcbaf.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-09 01:31:30', 1),
(384, 53, 'Chord Substitution ( Song 2 ) A', 'More Chord Substitutions', 2, 'storage/public/thumbnails/a2332ac.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/ca80d26.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-09 01:22:37', 1),
(385, 53, 'Chord Substitution B', 'We learn more Chord Substitution concepts', 3, 'storage/public/thumbnails/71ce36c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/7176e8a.mp4', NULL, NULL, NULL, NULL, 0, '2021-04-09 01:19:49', 1),
(386, 29, 'Major Scale on the Neck ( Alternate Picking)', 'No Description', 6, 'storage/public/thumbnails/e84c002.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/43f7456.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 13:11:38', 0),
(387, 93, 'Building Different song progressions', 'Here will build different song progressions using the information we have learnt on Basic song progression lesson', 2, 'storage/public/thumbnails/27eb321.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/44fe790.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 20:25:28', 1),
(388, 43, 'Ariaria Guitar Style IGWE C', 'This is the last set of Guitar lines for IGWE song', 17, 'storage/public/thumbnails/40c9e1d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/e8d8f5d.mp4', NULL, 'storage/public/tutorials/practice/2009684.mpeg', NULL, NULL, 0, '2021-04-25 22:20:39', 1),
(389, 94, 'Test Lesson &amp; More', 'No Description', 1, 'storage/public/thumbnails/default.jpg', 'Ebuka Odini', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-05-04 21:27:58', 0),
(390, 3, 'Test Lesson &amp; More', 'No Description', 8, 'storage/public/thumbnails/default.jpg', 'Ebuka Odini', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-05-04 21:27:18', 0),
(391, 96, '1 6 2 5 Highlife Guitar LICKS', 'Different Guitar Licks on  Highlife Beat with progression (Doh - Lah - Reh - Soh)', 1, 'storage/public/thumbnails/efb23b1.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/305d22e.mp4', NULL, 'storage/public/tutorials/practice/4366d7f.mpeg', NULL, NULL, 0, '2021-06-23 16:46:04', 1),
(392, 82, '10th duet harmony - IMELA', '10th duet harmony is a combination of a root and the 10th note way from it.\r\nThis duet harmony is used Alot in worship. \r\nHere we will use it on IMELA worship song', 7, 'storage/public/thumbnails/b2a5269.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d11613b.mp4', NULL, 'storage/public/tutorials/practice/834e6b6.mpeg', NULL, NULL, 0, '2021-06-23 21:15:47', 1),
(393, 21, 'Add different grooves to simple strum 1', 'Take a simple strum and make it more interesting by adding different grooves', 1, 'storage/public/thumbnails/66b1fad.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/af535cb.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 12:37:49', 1),
(394, 54, 'Advanced Circle of 4th Chords 1', 'Circle of 4th has a standard formation, but the chords we use for the notes are not fixed. In this lesson, we will learn more chord options for Circle of 4th', 7, 'storage/public/thumbnails/96baaae.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/4c3b216.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 17:01:52', 1),
(395, 54, 'Advanced Circle of 4th Chords 2', 'More advanced Chord choices for our Circle of 4th Modulations', 8, 'storage/public/thumbnails/decaa9c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a7c69ae.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 07:20:20', 1),
(396, 54, 'Advanced Circle of 4th Chords 3', 'Even more advanced Chord options for Circle of 4th', 9, 'storage/public/thumbnails/c783cea.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/0478b40.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 07:22:16', 1),
(397, 56, 'Advanced Sequences', 'These advanced sequences will begin to open up advanced patterns for us to build licks on the Guitar Fret board', 1, 'storage/public/thumbnails/dc22e67.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6eff4ea.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 07:29:51', 1),
(398, 97, 'Application of Sequences', 'Here, we will attempt to apply how to creatively apply some of the sequences we had learnt earlier', 6, 'storage/public/thumbnails/c429d4b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a79861d.mp4', NULL, 'storage/public/tutorials/practice/11c5bab.mpeg', NULL, NULL, 0, '2021-06-23 16:16:51', 1),
(399, 42, 'Cascading Finger Picking line', 'Finger picking with repeated strings', 6, 'storage/public/thumbnails/966ea0b.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 14:25:03', 1),
(400, 100, 'Chord Change 1 - 6 - 2 - 5', 'C major, A minor, D minor and G major chord changes', 6, 'storage/public/thumbnails/cda8c9f.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/9dd4eba.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 09:27:26', 1),
(401, 54, 'Circle of 4th INTRODUCTION', 'Circle of 4th is a Jazz Concept and it&#39;s basically a Modulation in 4ths that eventually circles back if you keep Modulating in 4ths.\r\nYou can&#39;t Play Jazz without Circle of 4th, so we get started with it here', 1, 'storage/public/thumbnails/56040d0.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/9f50157.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 07:19:17', 1),
(402, 54, 'Circle of 4th with Moving Bass - Advanced', 'In the lesson, we will learn how to play passing notes with our Bass strings on the Guitar into our circle of 4th Chords', 10, 'storage/public/thumbnails/3822cff.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/2c8714c.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 07:26:24', 1),
(403, 37, 'Different Guitar lines 1 - Sweet Mother', 'Here we will be playing different guitar lines on Sweet Mother song backing track (loop)', 7, 'storage/public/thumbnails/2aec73f.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d327145.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 20:52:12', 1),
(404, 37, 'Different Guitar lines 2 - Sweet Mother', 'We Learn more Guitar lines on Sweet Mother song backing track (loop)', 8, 'storage/public/thumbnails/e4a1434.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/cc2a732.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 20:51:35', 1),
(405, 98, 'Double Stop 1', 'Double Stop is basically playing two notes at once, but in this case, we will be attempting play both a static and a moving notes at together', 1, 'storage/public/thumbnails/f9710e7.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/868b4d8.mp4', NULL, 'storage/public/tutorials/practice/3b699d1.mpeg', NULL, NULL, 0, '2021-06-23 18:42:08', 1),
(406, 98, 'Double Stop 2', 'Double Stop line', 2, 'storage/public/thumbnails/5864ca5.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/681d07b.mp4', NULL, 'storage/public/tutorials/practice/1411dfb.mpeg', NULL, NULL, 0, '2021-06-23 18:43:10', 1),
(407, 98, 'Double Stop 3', 'More Double stop lines and Application in Songs', 3, 'storage/public/thumbnails/f5320da.jpg', 'OC Omofuma', NULL, NULL, NULL, 'storage/public/tutorials/practice/f044f1f.mpeg', NULL, NULL, 0, '2021-06-23 18:44:48', 1),
(408, 35, 'Ear Training', 'No Description', 2, 'storage/public/thumbnails/dabacc0.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/96c0376.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 14:54:12', 1),
(409, 96, 'Edikwansa Licks', 'We explore more Lick Patterns for this highlife song Edikwansa', 4, 'storage/public/thumbnails/6aa722c.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/4a4b6a5.mp4', NULL, 'storage/public/tutorials/practice/9a98fee.mpeg', NULL, NULL, 0, '2021-06-23 16:32:05', 1),
(410, 29, 'Finger Exercise - Chromatic Half.', 'Chromatic-like movement within two strings. A finger exercise to help flexibility and independence within your left fingers.', 5, 'storage/public/thumbnails/82fd382.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/931f472.mp4', NULL, 'storage/public/tutorials/practice/2522786.mpeg', NULL, NULL, 0, '2021-06-23 18:39:45', 1),
(411, 42, 'Finger picking line B - Brushing', 'We will learn Another finger picking line and also how to brush your strings with your right index finger to produce a cool sound', 2, 'storage/public/thumbnails/ded3222.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b87fbf5.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 21:26:46', 1),
(412, 42, 'Finger picking line on Key D', 'Finger picking line on Key D.\r\nPlaying open strings, applying Hammer-on and Pull-off', 12, 'storage/public/thumbnails/46ab64a.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/1dbdc31.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:21:59', 1),
(413, 96, 'Guitar Licks on AYO 1', 'Different Guitar Licks on this Highlife Song AYO by Simi.', 2, 'storage/public/thumbnails/8856316.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d08dcf2.mp4', NULL, 'storage/public/tutorials/practice/2a87065.mpeg', NULL, NULL, 0, '2021-06-23 16:44:46', 1),
(414, 96, 'Guitar Licks on AYO 2', 'More Licks for Ayo highlife sonh', 3, 'storage/public/thumbnails/9bdbe71.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6db00b6.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 06:41:17', 1),
(415, 96, 'Guitar Licks on IGWE', 'More Guitar licks on this fast Ariaria Beat IGWE', 5, 'storage/public/thumbnails/9b49283.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/dca3c4d.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 06:49:24', 1),
(416, 60, 'Harmonic Minor Improvisation intro', 'This is an introduction to learn how you play stunning licks and improvise over minor chords with our Harmonic minor Scale', 13, 'storage/public/thumbnails/bc78411.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/2dac71c.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 07:34:08', 1),
(417, 60, 'Harmonic Minor Licks 1', 'We learn more Harmonic minor Licks here', 18, 'storage/public/thumbnails/04b7a69.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/725be1a.mp4', NULL, 'storage/public/tutorials/practice/0b28fc8.mpeg', NULL, NULL, 0, '2021-06-23 17:52:23', 1),
(418, 60, 'Harmonic Minor Licks 2', 'Even more stunning harmonic minor Licks', 19, 'storage/public/thumbnails/6ca9453.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/c0faac2.mp4', NULL, 'storage/public/tutorials/practice/5e886bb.mpeg', NULL, NULL, 0, '2021-06-23 17:53:59', 1),
(419, 44, 'Highlife Guitar intro', 'Highlife music, a West African music. \r\nHighlife without Electric Guitar is no higher life music. \r\nWe begin to learn the art of high life Guitar', 1, 'storage/public/thumbnails/7b47065.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/21b85b3.mp4', NULL, 'storage/public/tutorials/practice/52d5dc6.mpeg', NULL, NULL, 0, '2021-06-23 16:41:10', 1),
(420, 44, 'Highlife Guitar intro 2', 'More High life tips and guitar lines to get you started', 2, 'storage/public/thumbnails/a5a5f53.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/515b33b.mp4', NULL, 'storage/public/tutorials/practice/7e1e309.mpeg', NULL, NULL, 0, '2021-06-23 16:42:08', 1),
(421, 3, 'Holding the Pick', 'In this lesson, we will learn a very efficient way to hold the Guitar pick. Please take this seriously, holding the pick the wrong way will eventually lead to different unforseen limitations.', 5, 'storage/public/thumbnails/73aa58a.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/59e45e2.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 12:58:51', 1),
(422, 60, 'Improvising with Harmonic minor scale', 'A simple use of harmonic Minor Scale over minor Chords.\r\nWatch and be inspired', 16, 'storage/public/thumbnails/8e54aa6.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/5e171fc.mp4', NULL, 'storage/public/tutorials/practice/d154327.mpeg', NULL, NULL, 0, '2021-06-23 17:46:36', 1),
(423, 97, 'Intermediate Sequence 1', 'Sequences help build road map on the Guitar for improvisation. \r\nHere we will learn Different amazing sequences for the intermediate level guitar players', 1, 'storage/public/thumbnails/764ee53.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6c1a11b.mp4', NULL, 'storage/public/tutorials/practice/5cf0fcd.mpeg', NULL, NULL, 0, '2021-06-23 16:07:12', 1),
(424, 97, 'Intermediate Sequence 2', 'More interesting sequences for the intermediate Guitarist', 2, 'storage/public/thumbnails/4017668.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/5021125.mp4', NULL, 'storage/public/tutorials/practice/ff74eab.mpeg', NULL, NULL, 0, '2021-06-23 16:08:18', 1),
(425, 97, 'Intermediate Sequence 3', 'More Unconventional and interesting intermediate sequences', 3, 'storage/public/thumbnails/6187874.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/55eeaa9.mp4', NULL, 'storage/public/tutorials/practice/ed4cb87.mpeg', NULL, NULL, 0, '2021-06-23 16:09:16', 1),
(426, 54, 'Jazzy Circle of 4th Moves', 'We begin to dare Jazzy Moves with this Concepts taught in the Circle of 4th lesson', 11, 'storage/public/thumbnails/4b65d2b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6d085b8.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 07:27:35', 1),
(427, 29, 'Major Scale Alternate Picking - beginner', 'Alternate pick is a consistent Down - Up striking.\r\nWe will play Key C major Scale with this Alternate picking Technique', 6, 'storage/public/thumbnails/a82bf3d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/def21e9.mp4', NULL, 'storage/public/tutorials/practice/6fae584.mpeg', NULL, NULL, 0, '2021-06-23 18:47:40', 1),
(428, 102, 'Major Scale Finger Exercise - single position', 'We will play 2 octaves of our Key C major Scale at the middle repeatedly as finger Exercises.\r\nThis is efficient for speed Building and coordination but both hands', 2, 'storage/public/thumbnails/c5129e3.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/1a07a5b.mp4', NULL, 'storage/public/tutorials/practice/99cb97f.mpeg', NULL, NULL, 0, '2021-06-23 16:24:03', 1),
(429, 17, 'Major Scale Transition', 'Learn to play major scale on C# also', 1, 'storage/public/thumbnails/07a8ec3.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/878d0b0.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 13:43:39', 1),
(430, 100, 'Minor Chord Changes', 'Different minor Chord changes', 7, 'storage/public/thumbnails/655a0bc.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/a7d3eb4.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 09:28:41', 1),
(431, 104, 'Octave duets', 'An Octave is an interval with a distance of 8. The Octave of key C note will be another key C note on a higher or lower range which is the 8th note higher or lower than the original one.. Octave duet is playing two thesame notes with different range at once. That&#39;s a high and low range of thesame notes at once', 6, 'storage/public/thumbnails/bbad7b8.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/3ced11b.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 14:04:31', 1),
(432, 97, 'Pentatonic Sequence 1', 'Different amazing sequences with our Pentatonic scale', 4, 'storage/public/thumbnails/f673694.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/c5bb085.mp4', NULL, 'storage/public/tutorials/practice/dcd783c.mpeg', NULL, NULL, 0, '2021-06-23 16:13:07', 1),
(433, 97, 'Pentatonic Sequence 2', 'More Amazing pentatonic sequences', 5, 'storage/public/thumbnails/79e12eb.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d5e3b53.mp4', NULL, 'storage/public/tutorials/practice/7fe4690.mpeg', NULL, NULL, 0, '2021-06-23 16:14:33', 1),
(434, 99, 'Quartal Licks', 'Licks created from the  Quartal harmony concept.\r\nThese licks moves will make you sound advanced', 2, 'storage/public/thumbnails/0e32a2d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6b50bdc.mp4', NULL, 'storage/public/tutorials/practice/34a5393.mpeg', NULL, NULL, 0, '2021-06-23 20:50:19', 1),
(435, 60, 'Relating Harmonic Minor Scale to other keys', 'We will how to narrow down our view on Harmonic Minor Scale by learning how to read it from other keys', 15, 'storage/public/thumbnails/ae8c691.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b25c46c.mp4', NULL, 'storage/public/tutorials/practice/a229ed1.mpeg', NULL, NULL, 0, '2021-06-23 17:43:53', 1),
(436, 32, 'Sequence of 3rd - 4th - 5th', 'Three different sequences in one lesson.\r\nSequence of 3rd, 4th and 5th. \r\nWe will learn an unusual movement with the sequence of 5th', 4, 'storage/public/thumbnails/d245695.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/cd3fb5a.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 13:38:55', 1),
(437, 28, 'Strike and hold notes', 'It is time, lets begin to hold some notes and strike the very notes we hold.', 2, 'storage/public/thumbnails/4450125.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/83bca40.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 13:03:52', 1),
(438, 38, 'Simple Arpeggio lines', 'Arpeggio means broken Chord. Playing the individual notes of a Chord one after the other.\r\nIn this lesson, we will learn some cool melodies using the arpeggios of different chords', 2, 'storage/public/thumbnails/764952f.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/5833dd6.mp4', NULL, 'storage/public/tutorials/practice/df3403e.mpeg', NULL, NULL, 0, '2021-06-23 20:47:55', 1),
(439, 100, 'Simple song progressions', 'Easy to play progressions of different songs. Now we begin to make use of the chords you have learnt on different songs.\r\nUyomeyo and Twinkle little Star song progressions \r\nEnjoy...', 1, 'storage/public/thumbnails/e72f00e.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/96c9158.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-23 09:22:00', 1),
(440, 82, 'So will i -10th Harmony', '10th Harmony worship Guitar line on SO WILL I worship song by Hilsong', 8, 'storage/public/thumbnails/e86d445.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/d6f50fd.mp4', NULL, 'storage/public/tutorials/practice/367d45f.mpeg', NULL, NULL, 0, '2021-06-23 21:14:50', 1),
(441, 44, 'Sweet Mother Guitar Solo', 'This lesson teaches a complication of different Highlife guitar solos from the Song SWEET Mother.\r\n Different cuts compiled as one guitar solo', 8, 'storage/public/thumbnails/2d2e5ac.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/999c285.mp4', NULL, 'storage/public/tutorials/practice/3f8414f.mpeg', NULL, NULL, 0, '2021-06-23 17:05:25', 1),
(442, 17, 'Transition from Key C to other keys 1', 'We will learn how to play on other Keys, the notes we can already play on Key C.\r\nPart 1', 2, 'storage/public/thumbnails/ba7f32b.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/836fa4d.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 14:10:01', 1),
(443, 17, 'Transition from Key C to other keys 2', 'Learn to move from one key to another on the Guitar part 2', 3, 'storage/public/thumbnails/aff14ba.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/bfbfe83.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 14:11:41', 1),
(444, 17, 'Transition to key F', 'Moving to key F from other keys', 4, 'storage/public/thumbnails/89577c4.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/b0cfc11.mp4', NULL, NULL, NULL, NULL, 0, '2021-06-22 14:13:20', 1),
(445, 82, 'Worship Guitar Accompaniment - IMELA', 'We will explore different approach to accompany a worship song.\r\nWe will use this song IMELA by Nathaniel Bassey to learn these', 6, 'storage/public/thumbnails/c623bc1.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/9f33259.mp4', NULL, 'storage/public/tutorials/practice/5924e58.mpeg', NULL, NULL, 0, '2021-06-23 21:17:01', 1),
(446, 44, 'You and i will live as one A', 'Different Guitar lines on this highlife song, YOU AND I WILL LIVE AS ONE. Song by Onyeka Owenu', 9, 'storage/public/thumbnails/bc64310.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/4676c48.mp4', NULL, 'storage/public/tutorials/practice/763b5f9.mpeg', NULL, NULL, 0, '2021-06-23 20:55:34', 1),
(447, 44, 'You and i will live as one B', 'More Highlife Guitar lines for the Song You and i will leave as one', 10, 'storage/public/thumbnails/3f3371d.jpg', 'OC Omofuma', NULL, 'storage/public/tutorials/videos/6cbada0.mp4', NULL, 'storage/public/tutorials/practice/308eaef.mpeg', NULL, NULL, 0, '2021-06-23 20:56:47', 1),
(448, 96, '1 - 6 - 2 - 5 Highlife guitar licks', 'No Description', 1, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-22 05:51:12', 0),
(449, 100, 'More Simple song progressions', 'No Description', 3, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 17:58:25', 0),
(450, 100, 'Different song progressions using the Capo', 'No Description', 12, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 09:41:23', 1),
(451, 100, 'More song progressions 1', 'No Description', 10, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 09:40:34', 1),
(452, 100, 'More song progressions 2', 'No Description', 11, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 09:40:59', 1),
(453, 38, 'More song melodies 1', 'No Description', 6, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 09:20:58', 1),
(454, 38, 'More song melodies 2', 'No Description', 7, 'storage/public/thumbnails/default.jpg', 'OC Omofuma', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-06-23 09:21:27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment_tbl`
--

CREATE TABLE `payment_tbl` (
  `payment_id` double NOT NULL,
  `paystack_ref_id` varchar(50) NOT NULL,
  `domain` varchar(20) NOT NULL COMMENT 'test, live',
  `reference` varchar(20) NOT NULL,
  `product` text NOT NULL,
  `amount` double NOT NULL,
  `currency` varchar(10) NOT NULL,
  `payment_channel` varchar(20) NOT NULL COMMENT 'card, bank, ussd, etc',
  `ip_address` varchar(20) NOT NULL,
  `payment_medium` varchar(10) NOT NULL COMMENT 'Website, Android App',
  `payment_log` text NOT NULL,
  `payment_status` varchar(10) NOT NULL,
  `gateway_response` varchar(50) NOT NULL,
  `message` varchar(50) NOT NULL,
  `created_at` varchar(50) NOT NULL,
  `paid_at` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment_tbl`
--

INSERT INTO `payment_tbl` (`payment_id`, `paystack_ref_id`, `domain`, `reference`, `product`, `amount`, `currency`, `payment_channel`, `ip_address`, `payment_medium`, `payment_log`, `payment_status`, `gateway_response`, `message`, `created_at`, `paid_at`) VALUES
(1, '', '', 'SGA.N2.72540455', '', 650000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(2, '', '', 'SGA.N2.73235700', '', 650000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(3, '', '', 'SGA.N3.55223561', '', 600000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(4, '894370208', 'test', 'SGA.N2.21292008', '{\"display_name\":\"3 Months Subscription Plan\",\"variable_name\":\"Subscription Plan\",\"value\":\"3 Months\"}', 650000, 'NGN', 'card', '162.241.253.12, 172.', 'Mobile App', 'null', 'success', 'Approved', 'NULL', '2020-11-22T17:01:18.000Z', '2020-11-22T17:01:36.000Z'),
(5, '', '', 'SGA.N1.79253693', '', 700000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(6, '', '', 'SGA.N1.70683709', '', 700000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(7, '', '', 'SGA.N1.32029794', '', 700000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(8, '', '', 'SGA.N1.46887032', '', 700000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(9, '', '', 'SGA.N1.94524081', '', 700000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(10, '', '', 'SGA.N2.67461320', '', 650000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(11, '', '', 'SGA.N1.64191477', '', 700000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(12, '', '', 'SGA.N1.62356453', '', 700000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(13, '', '', 'SGA.N4.75162517', '', 500000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(14, '', '', 'SGA.N4.40665544', '', 500000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(15, '', '', 'SGA.N4.34176845', '', 500000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(16, '', '', 'SGA.N3.28806478', '', 600000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(17, '', '', 'SGA.N1.84129741', '', 700000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(18, '', '', 'SGA.N2.54560082', '', 1950000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(19, '', '', 'SGA.N3.08534076', '', 3600000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(20, '', '', 'SGA.N3.89019403', '', 3600000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(21, '', '', 'SGA.N1.94719895', '', 700000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(22, '', '', 'SGA.N1.65153500', '', 700000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(23, '', '', 'SGA._(Featured_Cours', '', 0, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(24, '', '', 'SGA.Introduction_(Fe', '', 150000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(25, '', '', 'SGA.Q3.52651902', '', 150000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(26, '', '', 'SGA.Q3.53051168', '', 150000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(27, '', '', 'SGA.Q3.75288019', '', 150000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(28, '', '', 'SGA.Q3.34580456', '', 150000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(29, '1028609169', 'test', 'SGA.Q3.76383638', '{\"display_name\":\"Introduction (Featured Course)\",\"variable_name\":\"Featured Course\",\"value\":\"Introduction\"}', 150000, 'NGN', 'card', '162.241.253.12, 172.', 'Mobile App', 'null', 'success', 'Approved', 'NULL', '2021-03-06T19:05:42.000Z', '2021-03-06T19:07:59.000Z'),
(30, '', '', 'SGA.Q3.75673475', '', 150000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(31, '1028634132', 'test', 'SGA.Q3.64091574', '{\"display_name\":\"Introduction (Featured Course)\",\"variable_name\":\"Featured Course\",\"value\":\"3\"}', 150000, 'NGN', 'card', '162.241.253.12, 172.', 'Mobile App', 'null', 'success', 'Approved', 'NULL', '2021-03-06T19:27:02.000Z', '2021-03-06T19:27:22.000Z'),
(32, '1028639481', 'test', 'SGA.Q3.21049383', '{\"display_name\":\"Introduction (Featured Course)\",\"variable_name\":\"Featured Course\",\"value\":\"3\"}', 150000, 'NGN', 'card', '162.241.253.12, 172.', 'Mobile App', 'null', 'success', 'Approved', 'NULL', '2021-03-06T19:31:30.000Z', '2021-03-06T19:32:01.000Z'),
(33, '1028646152', 'test', 'SGA.Q3.41363291', '{\"display_name\":\"Introduction (Featured Course)\",\"variable_name\":\"Featured Course\",\"value\":\"3\"}', 150000, 'NGN', 'card', '162.241.253.12, 172.', 'Mobile App', 'null', 'success', 'Approved', 'NULL', '2021-03-06T19:35:38.000Z', '2021-03-06T19:35:58.000Z'),
(34, '1030291886', 'test', 'SGA.N1.20715937', '{\"display_name\":\"1 Month Subscription Plan\",\"variable_name\":\"Subscription Plan\",\"value\":\"1 Month\"}', 700000, 'NGN', 'card', '162.241.253.12, 172.', 'Mobile App', 'null', 'success', 'Approved', 'NULL', '2021-03-07T23:50:09.000Z', '2021-03-07T23:50:38.000Z'),
(35, '1030303704', 'test', 'SGA.N1.55793444', '{\"display_name\":\"1 Month Subscription Plan\",\"variable_name\":\"Subscription Plan\",\"value\":\"1 Month\"}', 700000, 'NGN', 'card', '162.241.253.12, 172.', 'Mobile App', 'null', 'success', 'Approved', 'NULL', '2021-03-08T00:08:08.000Z', '2021-03-08T00:08:28.000Z'),
(36, '', '', 'SGA.Q3.41543640', '', 150000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(37, '1030541548', 'test', 'SGA.Q31.48796464', '{\"display_name\":\"UNDERSTANDING THE GUITAR FRET BOARD (Featured Course)\",\"variable_name\":\"Featured Course\",\"value\":\"31\"}', 500000, 'NGN', 'card', '162.241.253.12, 172.', 'Mobile App', 'null', 'success', 'Approved', 'NULL', '2021-03-08T07:45:33.000Z', '2021-03-08T07:45:56.000Z'),
(38, '', '', 'SGA.Q31.52957019', '', 500000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(39, '', '', 'SGA.Q3.07332037', '', 150000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(40, '', '', 'SGA.Q31.85428977', '', 500000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(41, '', '', 'SGA.Q28.79408223', '', 200000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(42, '', '', 'SGA.Q28.42520112', '', 200000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(43, '', '', 'SGA.Q28.27129540', '', 200000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(44, '', '', 'SGA.N1.05842139', '', 700000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(45, '1109230894', 'test', 'SGA.N1.09196065', '{\"display_name\":\"1 Month Subscription Plan\",\"variable_name\":\"Subscription Plan\",\"value\":\"1 Month\"}', 700000, 'NGN', 'card', '162.241.253.12, 172.', 'Mobile App', 'null', 'success', 'Approved', 'NULL', '2021-05-02T05:27:53.000Z', '2021-05-02T05:29:44.000Z'),
(46, '', '', 'SGA.Q28.42888507', '', 200000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(47, '', '', 'SGA.Q28.42191413', '', 200000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(48, '', '', 'SGA.Q28.66446596', '', 200000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(49, '', '', 'SGA.Q28.33842039', '', 200000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(50, '', '', 'SGA.Q28.73991889', '', 200000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(51, '1112857377', 'test', 'SGA.N1.66000122', '{\"display_name\":\"1 Month Subscription Plan\",\"variable_name\":\"Subscription Plan\",\"value\":\"1 Month\"}', 700000, 'NGN', 'card', '162.241.253.12, 172.', 'Mobile App', 'null', 'success', 'Approved', 'NULL', '2021-05-04T16:57:32.000Z', '2021-05-04T16:58:11.000Z'),
(52, '', '', 'SGA.Q3.04940056', '', 150000, '', '', '', 'Mobile App', '', 'pending', '', '', '', ''),
(53, '', '', 'SGA.Q28.50449255', '', 200000, '', '', '', 'Mobile App', '', 'pending', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `quick_lessons_tbl`
--

CREATE TABLE `quick_lessons_tbl` (
  `id` double NOT NULL,
  `lesson_id` double NOT NULL,
  `price` double NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `free` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quick_lessons_tbl`
--

INSERT INTO `quick_lessons_tbl` (`id`, `lesson_id`, `price`, `status`, `free`) VALUES
(1, 331, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `studentcomments`
--

CREATE TABLE `studentcomments` (
  `id` double NOT NULL,
  `lesson_id` double NOT NULL,
  `sender` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `receiver` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `studentcomments`
--

INSERT INTO `studentcomments` (`id`, `lesson_id`, `sender`, `receiver`, `comment`, `date_added`) VALUES
(2, 120, 'j.hamlet@gmail.com', 'student', 'I need help with testing this app', '2020-12-29 17:41:50'),
(3, 120, 'j.hamlet@gmail.com', 'student', 'testing is going on right, Ebuka keep going', '2020-12-29 17:55:58'),
(4, 120, 'j.hamlet@gmail.com', '', 'bliv in urself always', '2020-12-29 18:09:30'),
(5, 120, 'j.hamlet@gmail.com', '', 'hi', '2020-12-29 18:53:51'),
(6, 120, 'j.hamlet@gmail.com', '', 'hello world', '2020-12-29 19:33:14'),
(7, 120, 'j.hamlet@gmail.com', '', 'Ebuka, you are the best there is!', '2020-12-29 19:40:25'),
(8, 120, 'j.hamlet@gmail.com', '', 'Ebele♥️ believes it', '2020-12-29 19:40:56'),
(9, 121, 'j.hamlet@gmail.com', '', 'what is grip', '2020-12-29 19:46:41'),
(10, 121, 'j.hamlet@gmail.com', '', 'and what is posture', '2020-12-29 19:47:35'),
(12, 122, 'j.hamlet@gmail.com', '', 'hello there', '2020-12-30 13:16:57'),
(13, 122, 'ebukaodini@gmail.com', 'j.hamlet@gmail.com', 'Hi, hello', '2021-01-02 01:17:03'),
(16, 120, 'ebukaodini@gmail.com', 'j.hamlet@gmail.com', 'Yh, she does', '2021-01-02 01:26:40'),
(17, 122, 'ebukaodini@gmail.com', 'j.hamlet@gmail.com', 'Do you know the Guitar anatomy now?', '2021-01-02 01:29:24'),
(18, 120, 'j.hamlet@gmail.com', 'OC Omofuma', 'Test check', '2021-01-03 23:31:27'),
(19, 122, 'spicyjazzy4u@gmail.com', 'j.hamlet@gmail.com', 'He now knows', '2021-01-03 23:50:44'),
(20, 120, 'j.hamlet@gmail.com', 'OC Omofuma', 'hi', '2021-01-18 06:33:36'),
(21, 123, 'j.hamlet@gmail.com', 'OC Omofuma', 'None at the moment', '2021-01-19 05:26:16'),
(22, 123, 'j.hamlet@gmail.com', 'OC Omofuma', 'Thanks Tutor. The lessons are Amazing ', '2021-01-27 14:51:58'),
(23, 7, 'j.hamlet@gmail.com', 'OC Omofuma', 'Sir what about the other way of holding the pic? ', '2021-01-28 03:41:24'),
(24, 122, 'j.hamlet@gmail.com', 'OC Omofuma', 'Hi again, testing', '2021-03-01 07:07:45'),
(25, 122, 'j.hamlet@gmail.com', 'OC Omofuma', 'Hello', '2021-03-01 07:09:56'),
(26, 118, 'j.hamlet@gmail.com', 'OC Omofuma', 'Hello there', '2021-03-01 07:17:23'),
(27, 121, 'j.hamlet@gmail.com', 'OC Omofuma', 'Hi, James.. ', '2021-03-10 23:47:52'),
(28, 121, 'j.hamlet@gmail.com', 'OC Omofuma', 'Hi James, Guitar Grip is the Right way you hold the guitar ', '2021-03-10 23:49:32'),
(29, 121, 'j.hamlet@gmail.com', 'OC Omofuma', 'Trying these ', '2021-03-10 23:49:57'),
(30, 324, 'j.hamlet@gmail.com', 'OC Omofuma', 'hi', '2021-05-03 10:20:53'),
(31, 324, 'j.hamlet@gmail.com', 'OC Omofuma', '', '2021-05-03 10:22:06'),
(32, 324, 'j.hamlet@gmail.com', 'OC Omofuma', '(*_*)', '2021-05-03 10:23:04'),
(33, 324, 'j.hamlet@gmail.com', 'OC Omofuma', 'fg', '2021-05-05 17:13:32'),
(34, 7, 'j.hamlet@gmail.com', 'OC Omofuma', 'Fi', '2021-05-05 17:15:59'),
(35, 123, 'j.hamlet@gmail.com', 'OC Omofuma', 'Hi', '2021-05-05 17:16:48'),
(36, 121, 'j.hamlet@gmail.com', 'OC Omofuma', 'Hi', '2021-05-05 17:18:47'),
(37, 122, 'j.hamlet@gmail.com', 'OC Omofuma', 'Hi', '2021-05-05 17:19:35'),
(38, 27, 'j.hamlet@gmail.com', 'OC Omofuma', 'hi here', '2021-05-10 17:00:50');

-- --------------------------------------------------------

--
-- Table structure for table `student_assignment_tbl`
--

CREATE TABLE `student_assignment_tbl` (
  `id` double NOT NULL,
  `course_id` double NOT NULL,
  `assignment_id` double NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `tutor_id` double NOT NULL,
  `note` text,
  `video` varchar(100) DEFAULT NULL,
  `review` text,
  `rating` int(10) DEFAULT '0',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0=NOTANSWERED, 1=ANSWERED'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_assignment_tbl`
--

INSERT INTO `student_assignment_tbl` (`id`, `course_id`, `assignment_id`, `student_id`, `tutor_id`, `note`, `video`, `review`, `rating`, `date_added`, `status`) VALUES
(2, 3, 1, 'j.hamlet@gmail.com', 1, 'To actually learn', 'storage/public/tutorials/answers/4d84a50.mp4', 'Good', 5, '2021-03-08 16:19:25', 1),
(3, 28, 4, 'j.hamlet@gmail.com', 1, 'I meant this is the video to the answer to the video I uploaded', 'storage/public/tutorials/answers/4b6542f.mp4', 'Okay, Good', 4, '2021-03-08 17:22:06', 1),
(4, 5, 5, 'j.hamlet@gmail.com', 5, NULL, NULL, NULL, 0, '2021-05-03 08:51:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `student_category_tbl`
--

CREATE TABLE `student_category_tbl` (
  `id` double NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `category_id` double NOT NULL,
  `date_started` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(10) NOT NULL DEFAULT '1' COMMENT '1=ACTIVE, 0=INACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_category_tbl`
--

INSERT INTO `student_category_tbl` (`id`, `student_id`, `category_id`, `date_started`, `status`) VALUES
(4, 'j.hamlet@gmail.com', 1, '2021-03-08 11:03:11', 1),
(8, 'khalidjanell@gmail.com', 2, '2021-05-04 17:19:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_course_tbl`
--

CREATE TABLE `student_course_tbl` (
  `id` double NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `course_id` double NOT NULL,
  `category_id` double NOT NULL,
  `medium` varchar(20) NOT NULL DEFAULT 'NORMAL' COMMENT 'NORMAL, FEATURED',
  `date_started` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(10) NOT NULL DEFAULT '0' COMMENT '0=INACTIVE, 1=ACTIVE, 2=ACCESSED'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_course_tbl`
--

INSERT INTO `student_course_tbl` (`id`, `student_id`, `course_id`, `category_id`, `medium`, `date_started`, `status`) VALUES
(3, 'j.hamlet@gmail.com', 31, 2, 'FEATURED', '2021-03-08 07:46:02', 1),
(15, 'j.hamlet@gmail.com', 3, 1, 'NORMAL', '2021-03-08 13:31:58', 1),
(16, 'j.hamlet@gmail.com', 28, 1, 'NORMAL', '2021-03-08 17:03:35', 1),
(17, 'j.hamlet@gmail.com', 5, 1, 'NORMAL', '2021-05-03 08:51:23', 1),
(18, 'khalidjanell@gmail.com', 38, 2, 'NORMAL', '2021-05-04 18:06:42', 1),
(19, 'khalidjanell@gmail.com', 6, 2, 'NORMAL', '2021-05-04 18:30:49', 1),
(20, 'j.hamlet@gmail.com', 38, 1, 'NORMAL', '2021-05-05 14:22:53', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_lesson_tbl`
--

CREATE TABLE `student_lesson_tbl` (
  `id` double NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `lesson_id` double NOT NULL,
  `course_id` double NOT NULL,
  `medium` varchar(10) NOT NULL COMMENT 'NORMAL, FEATURED',
  `date_accessed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(10) NOT NULL DEFAULT '0' COMMENT '0=ACCESSIBLE, 1=ACCESSED, 2=NOT_ACCESSIBLE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_lesson_tbl`
--

INSERT INTO `student_lesson_tbl` (`id`, `student_id`, `lesson_id`, `course_id`, `medium`, `date_accessed`, `status`) VALUES
(1, 'j.hamlet@gmail.com', 135, 31, 'FEATURED', '2021-03-08 08:14:19', 1),
(2, 'j.hamlet@gmail.com', 136, 31, 'FEATURED', '2021-03-08 08:43:22', 1),
(12, 'j.hamlet@gmail.com', 324, 3, 'NORMAL', '2021-03-08 17:00:08', 1),
(13, 'j.hamlet@gmail.com', 121, 3, 'NORMAL', '2021-03-08 17:01:23', 1),
(14, 'j.hamlet@gmail.com', 122, 3, 'NORMAL', '2021-03-08 17:02:03', 1),
(15, 'j.hamlet@gmail.com', 118, 3, 'NORMAL', '2021-03-08 17:02:16', 1),
(16, 'j.hamlet@gmail.com', 7, 3, 'NORMAL', '2021-03-08 17:02:22', 1),
(17, 'j.hamlet@gmail.com', 123, 3, 'NORMAL', '2021-03-08 17:02:32', 1),
(18, 'j.hamlet@gmail.com', 142, 31, 'FEATURED', '2021-03-09 07:03:07', 1),
(19, 'j.hamlet@gmail.com', 137, 31, 'FEATURED', '2021-03-09 07:41:04', 1),
(20, 'j.hamlet@gmail.com', 138, 31, 'FEATURED', '2021-03-09 07:41:45', 1),
(21, 'j.hamlet@gmail.com', 12, 28, 'NORMAL', '2021-03-10 21:54:32', 1),
(22, 'j.hamlet@gmail.com', 129, 28, 'NORMAL', '2021-03-10 21:55:11', 1),
(23, 'j.hamlet@gmail.com', 8, 28, 'NORMAL', '2021-03-10 22:53:25', 1),
(24, 'j.hamlet@gmail.com', 117, 28, 'NORMAL', '2021-03-11 07:28:26', 1),
(25, 'j.hamlet@gmail.com', 11, 28, 'NORMAL', '2021-05-02 16:25:31', 1),
(26, 'j.hamlet@gmail.com', 119, 28, 'NORMAL', '2021-05-02 16:25:37', 1),
(27, 'khalidjanell@gmail.com', 25, 6, 'NORMAL', '2021-05-04 18:32:07', 1),
(28, 'j.hamlet@gmail.com', 19, 38, 'NORMAL', '2021-05-10 17:00:13', 1),
(29, 'j.hamlet@gmail.com', 27, 38, 'NORMAL', '2021-05-10 17:00:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_subscription_tbl`
--

CREATE TABLE `student_subscription_tbl` (
  `id` double NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `txnref` varchar(50) NOT NULL,
  `plan` int(10) NOT NULL COMMENT '0=QUICK,1=1Month,2=3Months,3=6Months,4=12Months',
  `quicklesson_id` double NOT NULL,
  `sub_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sub_expire` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(10) NOT NULL DEFAULT 'ACTIVE' COMMENT 'ACTIVE, EXPIRED'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_subscription_tbl`
--

INSERT INTO `student_subscription_tbl` (`id`, `student_id`, `txnref`, `plan`, `quicklesson_id`, `sub_date`, `sub_expire`, `status`) VALUES
(1, 'j.hamlet@gmail.com', 'SGA.N2.21292008', 1, 0, '2020-11-23 01:01:43', '2020-12-23 01:01:43', 'EXPIRED'),
(4, 'j.hamlet@gmail.com', 'SGA.N1.55793444', 1, 0, '2021-03-08 08:08:35', '2021-04-08 07:08:35', 'EXPIRED'),
(5, 'j.hamlet@gmail.com', 'SGA.N1.09196065', 1, 0, '2021-05-02 12:29:55', '2021-06-02 12:29:55', 'EXPIRED'),
(6, 'khalidjanell@gmail.com', 'SGA.N1.66000122', 1, 0, '2021-05-04 23:58:17', '2021-06-04 23:58:17', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `student_tbl`
--

CREATE TABLE `student_tbl` (
  `id` double NOT NULL,
  `email` varchar(100) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_tbl`
--

INSERT INTO `student_tbl` (`id`, `email`, `firstname`, `lastname`, `avatar`, `telephone`, `date_added`) VALUES
(1, 'j.hamlet@gmail.com', 'James', 'Hamlett', 'storage/public/avatars/bd54ddf.jpg', '09093590559', '2021-05-02 04:25:48'),
(4, 'odiniaugustine@gmail.com', 'chiedozie', 'augustine', 'storage/public/avatars/default.png', '09054360984', '2021-03-08 18:34:11'),
(5, 'khalidjanell@gmail.com', 'khalid', 'Janell', 'storage/public/avatars/default.png', '09093590559', '2021-05-02 03:13:23'),
(6, 'grepublic47@gmail.com', 'Gamers', 'Republic', 'storage/public/avatars/default.png', '09093590559', '2021-05-05 09:15:12');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_tbl`
--

CREATE TABLE `subscription_tbl` (
  `plan_id` double NOT NULL,
  `plan` varchar(10) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subscription_tbl`
--

INSERT INTO `subscription_tbl` (`plan_id`, `plan`, `description`, `price`) VALUES
(1, '1 Month', 'This plan is for new students who are looking to try out this learning platform but are not sure if they want it.', 3500),
(2, '3 Months', NULL, 9600),
(3, '6 Months', '', 18000),
(4, '12 Months', '', 35000),
(5, 'Forever', NULL, 100000);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_tbl`
--

CREATE TABLE `transaction_tbl` (
  `txn_id` double NOT NULL,
  `student_id` varchar(40) NOT NULL,
  `txnref` varchar(20) NOT NULL,
  `amount` double NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction_tbl`
--

INSERT INTO `transaction_tbl` (`txn_id`, `student_id`, `txnref`, `amount`, `date`, `time`, `status`) VALUES
(1, 'j.hamlet@gmail.com', 'SGA.N2.21292008', 6500, '2020-11-22', '18:01:43', 'success'),
(2, 'j.hamlet@gmail.com', 'SGA.Q3.76383638', 1500, '2021-03-06', '20:08:05', 'success'),
(3, 'j.hamlet@gmail.com', 'SGA.Q3.64091574', 1500, '2021-03-06', '20:27:29', 'success'),
(4, 'j.hamlet@gmail.com', 'SGA.Q3.21049383', 1500, '2021-03-06', '20:32:08', 'success'),
(5, 'j.hamlet@gmail.com', 'SGA.Q3.41363291', 1500, '2021-03-06', '20:36:05', 'success'),
(6, 'j.hamlet@gmail.com', 'SGA.N1.20715937', 7000, '2021-03-08', '00:50:44', 'success'),
(7, 'j.hamlet@gmail.com', 'SGA.N1.55793444', 7000, '2021-03-08', '01:08:35', 'success'),
(8, 'j.hamlet@gmail.com', 'SGA.Q31.48796464', 5000, '2021-03-08', '08:46:02', 'success'),
(9, 'j.hamlet@gmail.com', 'SGA.N1.09196065', 7000, '2021-05-02', '06:29:55', 'success'),
(10, 'khalidjanell@gmail.com', 'SGA.N1.66000122', 7000, '2021-05-04', '17:58:17', 'success');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assignment_tbl`
--
ALTER TABLE `assignment_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `tutor_id` (`tutor_id`);

--
-- Indexes for table `auth_tbl`
--
ALTER TABLE `auth_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`email`),
  ADD KEY `password` (`password`),
  ADD KEY `role` (`role`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `category_tbl`
--
ALTER TABLE `category_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `course_tbl`
--
ALTER TABLE `course_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `course` (`course`),
  ADD KEY `description` (`description`),
  ADD KEY `ord` (`ord`);

--
-- Indexes for table `forums`
--
ALTER TABLE `forums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lesson_tbl`
--
ALTER TABLE `lesson_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course` (`course`),
  ADD KEY `lesson` (`lesson`),
  ADD KEY `description` (`description`);

--
-- Indexes for table `payment_tbl`
--
ALTER TABLE `payment_tbl`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `quick_lessons_tbl`
--
ALTER TABLE `quick_lessons_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `status` (`status`),
  ADD KEY `cost` (`price`);

--
-- Indexes for table `studentcomments`
--
ALTER TABLE `studentcomments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_assignment_tbl`
--
ALTER TABLE `student_assignment_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `assignment_id` (`assignment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `tutor_id` (`tutor_id`);

--
-- Indexes for table `student_category_tbl`
--
ALTER TABLE `student_category_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_course_tbl`
--
ALTER TABLE `student_course_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `student_lesson_tbl`
--
ALTER TABLE `student_lesson_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `visibility_status` (`status`),
  ADD KEY `medium` (`medium`);

--
-- Indexes for table `student_subscription_tbl`
--
ALTER TABLE `student_subscription_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `validity_status` (`status`);

--
-- Indexes for table `student_tbl`
--
ALTER TABLE `student_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_tbl`
--
ALTER TABLE `subscription_tbl`
  ADD PRIMARY KEY (`plan_id`),
  ADD KEY `price` (`price`),
  ADD KEY `plan` (`plan`);

--
-- Indexes for table `transaction_tbl`
--
ALTER TABLE `transaction_tbl`
  ADD PRIMARY KEY (`txn_id`),
  ADD KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `assignment_tbl`
--
ALTER TABLE `assignment_tbl`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `auth_tbl`
--
ALTER TABLE `auth_tbl`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `category_tbl`
--
ALTER TABLE `category_tbl`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `course_tbl`
--
ALTER TABLE `course_tbl`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `forums`
--
ALTER TABLE `forums`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `lesson_tbl`
--
ALTER TABLE `lesson_tbl`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=455;

--
-- AUTO_INCREMENT for table `payment_tbl`
--
ALTER TABLE `payment_tbl`
  MODIFY `payment_id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `quick_lessons_tbl`
--
ALTER TABLE `quick_lessons_tbl`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `studentcomments`
--
ALTER TABLE `studentcomments`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `student_assignment_tbl`
--
ALTER TABLE `student_assignment_tbl`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_category_tbl`
--
ALTER TABLE `student_category_tbl`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `student_course_tbl`
--
ALTER TABLE `student_course_tbl`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `student_lesson_tbl`
--
ALTER TABLE `student_lesson_tbl`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `student_subscription_tbl`
--
ALTER TABLE `student_subscription_tbl`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `student_tbl`
--
ALTER TABLE `student_tbl`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subscription_tbl`
--
ALTER TABLE `subscription_tbl`
  MODIFY `plan_id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transaction_tbl`
--
ALTER TABLE `transaction_tbl`
  MODIFY `txn_id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
