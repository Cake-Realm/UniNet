-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 22, 2017 at 08:46 PM
-- Server version: 5.6.36-cll-lve
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `universi_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `answerId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `questionId` int(11) NOT NULL,
  `text` varchar(5000) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`answerId`, `userId`, `questionId`, `text`, `timestamp`, `deleted`) VALUES
(1, 1, 2, 'From Android Studio 1.0.1\r\n\r\nGo to\r\n\r\nFile -> project Structure into Project Structure\r\nLeft -> SDK Location\r\nSDK location select Android SDK location (old version use Press +, add another sdk)', 1475402834, 0),
(2, 1, 2, 'With the current Studio 1.3 each project has a local.properties file where you can edit the SDK!', 1475406844, 0),
(10, 1, 1, '{subtitle}Short Answer{/subtitle}Gradle is a build system.\n{subtitle}Long Answer{/subtitle}Before Android Studio you were using Eclipse for your development purposes, and, chances are, you didn\'t know how to build your Android APK without Eclipse.\n\nYou can do this on the command line, but you have to learn what each tool (dx, aapt) does in the SDK. Eclipse saved us all from these low level but important, fundamental details by giving us their own build system.\n\nNow, have you ever wondered why the res folder is in the same directory as your src folder?\n\nThis is where the build system enters the picture. The build system automatically takes all the source files ({code}.java{/code} or {code}.xml{/code}), then applies the appropriate tool (e.g. takes java class files and converts them to dex files), and groups all of them into one compressed file, our beloved APK.\n\nThis build system uses some conventions: an example of one is to specify the directory containing the source files (in Eclipse it is {code}\\src{/code} folder) or resources files (in Eclipse it is {code}\\res{/code} folder).\n\nNow, in order to automate all these tasks, there has to be a script; you can write your own build system using shell scripting in linux or batch files syntax in windows. Got it?\n\nGradle is another build system that takes the best features from other build systems and combines them into one. It is improved based off of their shortcomings. It is a JVM based build system, what that means is that you can write your own script in Java, which Android Studio makes use of.\n\nOne cool thing about gradle is that it is a plugin based system. This means if you have your own programming language and you want to automate the task of building some package (output like a JAR for Java) from sources then you can write a complete plugin in Java or Groovy, and distribute it to rest of world.\n\nWhy did Google use it?\n\nGoogle saw one of the most advanced build systems on the market and realized that you could write scripts of your own with little to no learning curve, and without learning Groovy or any other new language. So they wrote the Android plugin for Gradle.\n\nYou must have seen build.gradle file(s) in your project. That is where you can write scripts to automate your tasks. The code you saw in these files is Groovy code. If you write {code}System.out.println(&quot;Hello Gradle!&quot;);{/code} then it will print on your console.\n\nWhat can you do in a build script?\n\nA simple example is that you have to copy some files from one directory to another before the actual build process happens. A Gradle build script can do this.', 1478969591, 0),
(12, 6, 3, 'i\'m pretty confused by the example as well. It says once both are on the bridge, they both sleep for a random time to simulate crossing the bridge.\r\n', 1479070656, 0),
(13, 8, 3, 'Another test ayy lmao', 1479330557, 0),
(19, 8, 16, 'This depends on the programming language, however most commonly the ! is used to express the NOT operand.', 1490023836, 0),
(20, 8, 16, 'This depends on the programming language, however most commonly the ! is used to express the NOT operand.', 1490023858, 0),
(21, 8, 16, 'This depends on the programming language, however most commonly the ! is used to express the NOT operand.', 1490023868, 0),
(22, 8, 16, 'This depends on the programming language, however most commonly the ! is used to express the NOT operand.', 1490023871, 0),
(23, 8, 16, 'This depends on the programming language, however most commonly the ! is used to express the NOT operand.', 1490023874, 0),
(24, 8, 16, 'This depends on the programming language, however most commonly the ! is used to express the NOT operand.', 1490023882, 0),
(25, 29, 15, 'Cos Why not? ', 1490024118, 0),
(26, 30, 16, 'Dankest memers', 1490024174, 0),
(27, 30, 15, 'For the holy land\r\nWe will take Jerusalem DEUS VULT DEUS VULT', 1490024236, 0),
(28, 33, 15, 'More spoilers coming soon!', 1490037835, 0);

-- --------------------------------------------------------

--
-- Table structure for table `answerVotes`
--

CREATE TABLE `answerVotes` (
  `voteId` int(11) NOT NULL,
  `answerId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `isUp` tinyint(1) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `answerVotes`
--

INSERT INTO `answerVotes` (`voteId`, `answerId`, `userId`, `isUp`, `timestamp`) VALUES
(1, 1, 1, 1, 1478700486),
(5, 12, 1, 1, 1479250271),
(6, 12, 8, 1, 1479330579),
(7, 13, 1, 1, 1479827596),
(12, 20, 8, 1, 1490023967),
(11, 19, 5, 1, 1490023849),
(13, 21, 8, 1, 1490023970),
(14, 22, 8, 1, 1490023972),
(15, 23, 8, 1, 1490023974),
(16, 24, 8, 1, 1490023977),
(17, 25, 29, 1, 1490024123),
(18, 27, 30, 1, 1490024245),
(19, 28, 33, 1, 1490037839),
(20, 25, 33, 1, 1490037841);

-- --------------------------------------------------------

--
-- Table structure for table `awardLookup`
--

CREATE TABLE `awardLookup` (
  `lookupId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `awardId` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `awardLookup`
--

INSERT INTO `awardLookup` (`lookupId`, `userId`, `awardId`, `timestamp`) VALUES
(1, 1, 1, 1478711350),
(2, 1, 2, 1478711350),
(3, 1, 3, 1478711350),
(4, 1, 4, 1478711350),
(5, 1, 5, 1478711350),
(6, 1, 6, 1478711350);

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE `awards` (
  `awardId` int(11) NOT NULL,
  `name` varchar(75) NOT NULL,
  `info` varchar(300) NOT NULL,
  `image` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `awards`
--

INSERT INTO `awards` (`awardId`, `name`, `info`, `image`) VALUES
(1, 'Bronze Contributor', 'This user has posted 10 accepted answered.', 2),
(2, 'Silver Contributor', 'This user has posted 25 accepted answered.', 3),
(3, 'Gold Contributor', 'This user has posted 50 accepted answered.', 4),
(4, 'Platinum Contributor', 'This user has posted 100 accepted answered.', 5),
(5, 'Respected Member', 'This user has over 1000 reputation points.', 8),
(6, 'Trustworthy Member', 'This user has over 2500 reputation points.', 9);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryId` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL,
  `icon` varchar(15) NOT NULL DEFAULT 'graduation-cap'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryId`, `name`, `description`, `icon`) VALUES
(1, 'Architecture, building and planning', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'building'),
(2, 'Business, management and maths', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'bar-chart'),
(3, 'Computer science', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'desktop'),
(4, 'Creative arts', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'paint-brush'),
(5, 'Education', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'graduation-cap'),
(6, 'Engineering', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'plane'),
(7, 'English and media', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'book'),
(8, 'Geography', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'globe'),
(9, 'History', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'university'),
(10, 'Languages', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'graduation-cap'),
(11, 'Law', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'gavel'),
(12, 'Medicine and dentistry', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'medkit'),
(13, 'Nursing and health', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'heartbeat'),
(14, 'Politics, philosophy and theology', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'graduation-cap'),
(15, 'Psychology and sociology', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'graduation-cap'),
(16, 'Sciences', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'flask'),
(17, 'Veterinary studies and agriculture', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.', 'bug');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `countryId` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`countryId`, `name`) VALUES
(1, 'United Kingdom'),
(2, 'United States of America');

-- --------------------------------------------------------

--
-- Table structure for table `friend`
--

CREATE TABLE `friend` (
  `lookupID` int(11) NOT NULL,
  `userId1` int(11) NOT NULL,
  `userId2` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friend`
--

INSERT INTO `friend` (`lookupID`, `userId1`, `userId2`, `timestamp`) VALUES
(7, 6, 1, 1479293929),
(8, 1, 9, 1479309684),
(9, 8, 1, 1488483341),
(12, 5, 1, 1490023703),
(13, 5, 6, 1490023704),
(14, 5, 11, 1490023705);

-- --------------------------------------------------------

--
-- Table structure for table `friendRequest`
--

CREATE TABLE `friendRequest` (
  `requestId` int(11) NOT NULL,
  `senderId` int(11) NOT NULL,
  `recieverId` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friendRequest`
--

INSERT INTO `friendRequest` (`requestId`, `senderId`, `recieverId`, `timestamp`) VALUES
(17, 11, 7, 1479741997),
(14, 1, 7, 1479250209);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msgId` int(11) NOT NULL,
  `senderId` int(11) NOT NULL,
  `recieverId` int(11) NOT NULL,
  `content` varchar(254) NOT NULL,
  `msgRead` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msgId`, `senderId`, `recieverId`, `content`, `msgRead`, `timestamp`) VALUES
(16, 6, 1, 'test message', 1, 1480675886),
(17, 8, 1, 'Test!', 1, 1488484462);

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `moduleId` int(11) NOT NULL,
  `subjectId` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`moduleId`, `subjectId`, `name`, `description`) VALUES
(1, 12, 'Computer Science and Mathematics', 'This module is both knowledge-based and practice-based, and is designed to provide students with an introductory understanding and perspective of basic computing science and mathematical concepts as they relate to the activity of software development.'),
(2, 12, 'Hardware', 'This module will provide the fundamental knowledge of computer hardware, software and interconnection required by a learner on a computing degree course. Learners will develop practical skills in using typical examples of contemporary computer systems.'),
(3, 12, 'Networking', 'Knowledge of typical local and wide area networks will be developed, from local communication up to the World Wide Web. Learners will develop practical skills in using typical examples of contemporary computer systems.'),
(4, 12, 'Software Design and Development', 'This module will introduce the concepts of abstraction techniques, modelling notations, structured and object-oriented programming, algorithm design, data structure design, and program implementation, testing, and documentation.'),
(5, 12, 'Android Development', 'Students will plan, design and implement  technical solutions to a given brief. Hardware and software capabilities will also need to be considered within the development process.'),
(6, 12, 'Unix Systems', 'The module aims to develop the student\'s technical skills and knowledge through the development of software prototypes and artefacts appropriate to their course. Students will be introduced to the concepts and principles of programming/scripting using an object-based language to control interactions, actions and animations.'),
(7, 12, 'Computational Mathematics', 'This module introduces fundamental concepts in algebra and develops both analytical and numerical methods for solving equations in one variable and for the interpolation and approximation of functions.  There is a first encounter with error analysis and proof.  Numerical methods are applied using spreadsheet packages and hand-held calculators.'),
(8, 12, 'Algorithms Processes and Data', 'This module exposes students to both a range of classic algorithms and data structures and encourages them to develop well structured, appropriate and efficient algorithms and data structures using both sequential and concurrent paradigms.'),
(9, 12, 'Relational Databases and Web Integration', 'The module equips students with the knowledge and skills necessary to design, implement and query a relational database from a requirements specification. Students are expected to become familiar with the needs and functionality of the Database Administrator (DBA) in supporting the creation of and subsequent maintenance of a commercial, multi-access database. Issues such as optimisation, concurrent access control, database security and recovery are included.  '),
(10, 12, 'Operating Systems', 'An overview of computer systems and operating systems; Processes, threads and concurrency; Memory management - virtual memory, paging and segmentation;  Scheduling;  File systems and I/O; Networks and Distributed Systems;  Client/Server architectures;  User Interfaces and command interpreters;  Security issues;  Operating System development tools; modern OS case studies including embedded operating systems; multiprocessor and multicore OS; parallel, distributed, grid and cloud computing systems.'),
(11, 12, 'Language Translators', 'An overview of Lexical analysis and lexical analyser generators;  Syntax analysis and parsers; Parser  generators such as JavaCup, ANTLR, or JavaCC;  Operator precedence;  LR-parsers; LL-parsers;  Internal representations - syntax trees and graphs;  Code generation and Storage allocation;  Case Study using a Parser Generator.'),
(12, 12, 'Object-Oriented Systems Development', 'Modern programming languages include features geared towards the production of re-usable software built out of separate software components.  Modern analysis techniques view systems as the interaction between \'objects\' of different types.  Object-oriented design seeks to specify reusable software components which implement the desired behaviour of object-based systems.');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `questionId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `moduleId` int(11) NOT NULL,
  `answerId` int(11) DEFAULT NULL,
  `question` varchar(100) NOT NULL,
  `text` varchar(500) NOT NULL,
  `views` int(11) NOT NULL DEFAULT '1',
  `timestamp` int(11) NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `moderation` int(11) NOT NULL DEFAULT '0' COMMENT 'If true, current question is hidden and needs checking before being visible.'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`questionId`, `userId`, `moduleId`, `answerId`, `question`, `text`, `views`, `timestamp`, `deleted`, `moderation`) VALUES
(1, 1, 5, NULL, 'What is Gradle in Android Studio?', '{b}Gradle{/b} is bit confusing to me and also for new Android developer. Can anyone explain what {b}gradle{/b} in Android Studio is and what its purpose is? Why is {b}Gradle{/b} included in Android Studio?', 271, 1475402834, 0, 0),
(2, 1, 5, 1, 'How to Change Android SDK Path', 'When I open Android SDK Manager from Android Studio, the SDK Path displayed is:\n\n\\android-studio\\sdk\nI want to change this path. How do I do it?', 220, 1475402834, 0, 0),
(3, 1, 10, NULL, 'How do semaphores work in Java?', 'For example, to model a single lane bridge where only one farmer can cross the bridge at one time, and the bridge can become deadlocked if a farmer from both sides get on the bridge at the same time. \r\n\r\nThe model also needs to work so that it is starvation-free.', 135, 1478891622, 0, 0),
(17, 29, 8, NULL, 'APD Logbook', 'So is this where we get APD logbook answers? ', 4, 1490024198, 0, 1),
(15, 8, 4, NULL, 'Why did that guy spoil Logan?', 'Like for real why did he do that?', 94, 1490022858, 0, 0),
(16, 5, 4, 19, 'what is a ! operator used for in programming?', 'see title ', 120, 1490023796, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `reportId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `unquieId` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1-Question 2-Answer',
  `content` varchar(300) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`reportId`, `userId`, `unquieId`, `type`, `content`, `timestamp`, `deleted`) VALUES
(8, 8, 20, 2, 'Website is broken dude', 1490023909, 0),
(9, 5, 2, 1, 'offensive langugage ', 1490024261, 0);

-- --------------------------------------------------------

--
-- Table structure for table `reputation`
--

CREATE TABLE `reputation` (
  `reputationId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `reason` varchar(1000) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reputation`
--

INSERT INTO `reputation` (`reputationId`, `userId`, `amount`, `reason`, `timestamp`) VALUES
(4, 1, 10, 'Commented on the question <a href=\'/modules/android-development/what-is-gradle-in-android-studio/q1/p1\'>What is Gradle in Android Studio?</a>', 1478969591),
(7, 1, 10, 'Commented an answer on the question <a href=\'/modules/android-development/what-is-gradle-in-android-studio/q1/p1\'>What is Gradle in Android Studio?</a>', 1478976618),
(12, 6, 10, 'Commented an answer on the question <a href=\'/modules/operating-systems/how-do-semaphores-work-in-java/q3/p1\'>How do semaphores work in Java?</a>', 1479070656),
(13, 8, 10, 'Commented an answer on the question <a href=\'/modules/operating-systems/how-do-semaphores-work-in-java/q3/p1\'>How do semaphores work in Java?</a>', 1479330557),
(14, 13, 25, 'Asked the following question <a href=\'/modules/android-development/ltbgttestltbgt/q10/p1\'>&lt;b&gt;Test&lt;/b&gt;</a>', 1486996405),
(15, 13, 10, 'Commented an answer on the question <a href=\'/modules/android-development/ltbgttestltbgt/q10/p1\'>&lt;b&gt;Test&lt;/b&gt;</a>', 1486996439),
(16, 6, 10, 'Commented an answer on the question <a href=\'/modules/operating-systems/how-do-semaphores-work-in-java/q3/p1\'>How do semaphores work in Java?</a>', 1489694621),
(17, 8, 25, 'Asked the following question <a href=\'/modules/computer-science-and-mathematics/ramirez---fix-this-bug/q11/p1\'>Ramirez - Fix this bug</a>', 1489698895),
(18, 1, 25, 'Asked the following question <a href=\'/modules/computational-mathematics/question-test/q12/p1\'>Question Test</a>', 1489702306),
(28, 5, 25, 'Asked the following question <a href=\'/modules/software-design-and-development/what-is-a--operator-used-for-in-programming/q16/p1\'>what is a ! operator used for in programming?</a>', 1490023796),
(26, 1, 50, 'Had a answer marked as the accepted answer for the question <a href=\'/modules/android-development/android-studio-installation/q14/p1\'>Android Studio Installation?</a>', 1489866126),
(25, 1, 10, 'Commented an answer on the question <a href=\'/modules/android-development/android-studio-installation/q14/p1\'>Android Studio Installation?</a>', 1489866111),
(27, 8, 25, 'Asked the following question <a href=\'/modules/software-design-and-development/why-did-that-guy-spoil-logan/q15/p1\'>Why did that guy spoil Logan?</a>', 1490022858),
(29, 8, 10, 'Commented an answer on the question <a href=\'/modules/software-design-and-development/what-is-a--operator-used-for-in-programming/q16/p1\'>what is a ! operator used for in programming?</a>', 1490023836),
(30, 8, 50, 'Had a answer marked as the accepted answer for the question <a href=\'/modules/software-design-and-development/what-is-a--operator-used-for-in-programming/q16/p1\'>what is a ! operator used for in programming?</a>', 1490023852),
(31, 8, 10, 'Commented an answer on the question <a href=\'/modules/software-design-and-development/what-is-a--operator-used-for-in-programming/q16/p1\'>what is a ! operator used for in programming?</a>', 1490023858),
(32, 8, 10, 'Commented an answer on the question <a href=\'/modules/software-design-and-development/what-is-a--operator-used-for-in-programming/q16/p1\'>what is a ! operator used for in programming?</a>', 1490023868),
(33, 8, 10, 'Commented an answer on the question <a href=\'/modules/software-design-and-development/what-is-a--operator-used-for-in-programming/q16/p1\'>what is a ! operator used for in programming?</a>', 1490023871),
(34, 8, 10, 'Commented an answer on the question <a href=\'/modules/software-design-and-development/what-is-a--operator-used-for-in-programming/q16/p1\'>what is a ! operator used for in programming?</a>', 1490023874),
(35, 8, 10, 'Commented an answer on the question <a href=\'/modules/software-design-and-development/what-is-a--operator-used-for-in-programming/q16/p1\'>what is a ! operator used for in programming?</a>', 1490023882),
(36, 29, 10, 'Commented an answer on the question <a href=\'/modules/software-design-and-development/why-did-that-guy-spoil-logan/q15/p1\'>Why did that guy spoil Logan?</a>', 1490024118),
(37, 30, 10, 'Commented an answer on the question <a href=\'/modules/software-design-and-development/what-is-a--operator-used-for-in-programming/q16/p1\'>what is a ! operator used for in programming?</a>', 1490024174),
(38, 29, 25, 'Asked the following question <a href=\'/modules/algorithms-processes-and-data/apd-logbook/q17/p1\'>APD Logbook</a>', 1490024198),
(39, 30, 10, 'Commented an answer on the question <a href=\'/modules/software-design-and-development/why-did-that-guy-spoil-logan/q15/p1\'>Why did that guy spoil Logan?</a>', 1490024236),
(40, 33, 10, 'Commented an answer on the question <a href=\'/modules/software-design-and-development/why-did-that-guy-spoil-logan/q15/p1\'>Why did that guy spoil Logan?</a>', 1490037835);

-- --------------------------------------------------------

--
-- Table structure for table `social`
--

CREATE TABLE `social` (
  `socialId` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `shortIcon` varchar(10) NOT NULL,
  `url` varchar(100) NOT NULL,
  `urlEnd` varchar(25) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `social`
--

INSERT INTO `social` (`socialId`, `name`, `icon`, `shortIcon`, `url`, `urlEnd`) VALUES
(1, 'Twitter', 'twitter', 'tw', 'https://twitter.com/', NULL),
(2, 'Facebook', 'facebook', 'fb', 'https://www.facebook.com/', NULL),
(3, 'Skype', 'skype', 'sk', 'skype:', '?chat'),
(4, 'Google Plus', 'google-plus', 'gp', 'https://plus.google.com/', NULL),
(5, 'Google Mail', 'envelope', 'gm', 'mailto:', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `socialUserLookup`
--

CREATE TABLE `socialUserLookup` (
  `socialId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `name` varchar(75) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `socialUserLookup`
--

INSERT INTO `socialUserLookup` (`socialId`, `userId`, `name`) VALUES
(2, 1, 'UniStu'),
(1, 1, 'UniStu'),
(5, 5, 'hello'),
(4, 34, 'https://www.labelle.in/non-surgical-hair-replacement.php');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subjectId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subjectId`, `categoryId`, `name`, `description`) VALUES
(1, 1, 'Architecture', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(2, 1, 'Building and surveying', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(3, 1, 'Planning', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(4, 2, 'Accounting', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(5, 2, 'Business and management studies', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(6, 2, 'Economics', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(7, 2, 'Finance', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(8, 2, 'Marketing', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(9, 2, 'Maths', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(10, 2, 'Statistics', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(11, 3, 'Computer games design and programming', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(12, 3, 'Computer science', 'This course is designed to equip you with the theoretical and practical skills needed to tackle the challenges in this rapidly expanding industry. Our aim is to support you in developing knowledge and skills in programming, software engineering, mathematics, mobile computing and much more.'),
(13, 3, 'Information systems', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(14, 4, 'Creative writing', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(15, 4, 'Dance', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(16, 4, 'Design', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(17, 4, 'Drama and theatre studies', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(18, 4, 'Fine art', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(19, 4, 'Music', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(20, 4, 'Photography and film', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(21, 5, 'Teacher training', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(22, 6, 'Aerospace engineering', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(23, 6, 'Chemical engineering', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(24, 6, 'Civil engineering', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(25, 6, 'Electronic and electrical engineering', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(26, 6, 'Mechanical engineering', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(27, 7, 'American studies', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(28, 7, 'English language and literature', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(29, 7, 'Journalism', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(30, 7, 'Linguistics', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(31, 7, 'Media studies', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(32, 7, 'Public relations', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(33, 7, 'Publishing', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(34, 8, 'Environmental science', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(35, 8, 'Geography', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(36, 8, 'Geology', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(37, 9, 'Archaeology', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(38, 9, 'Art history', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(39, 9, 'Classics', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(40, 9, 'History', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(41, 9, 'Information management and museum studies', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(42, 10, 'French', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(43, 10, 'German', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(44, 10, 'Japanese', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(45, 10, 'Spanish', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(46, 11, 'Law', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(47, 12, 'Dentistry', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(48, 12, 'Medicine', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(49, 13, 'Midwifery', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(50, 13, 'Nursing', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(51, 13, 'Nutrition', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(52, 13, 'Optometry', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(53, 13, 'Physiotherapy', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(54, 13, 'Radiography and medical technology', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(55, 13, 'Social work', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(56, 13, 'Speech therapy and audiology', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(57, 14, 'Philosophy', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(58, 14, 'Policy', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(59, 14, 'Politics', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(60, 14, 'Theology and religious studies', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(61, 15, 'Anthropology', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(62, 15, 'Psychology', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(63, 15, 'Sociology', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(64, 16, 'Anatomy', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(65, 16, 'Astronomy', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(66, 16, 'Biology', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(67, 16, 'Chemistry', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(68, 16, 'Forensic science', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(69, 16, 'Genetics', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(70, 16, 'Marine and ocean sciences', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(71, 16, 'Microbiology', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(72, 16, 'Pharmacy', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(73, 16, 'Physics', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(74, 16, 'Zoology', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(75, 17, 'Agriculture', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(76, 17, 'Animal science', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(77, 17, 'Forestry', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(78, 17, 'Plant science', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.'),
(79, 17, 'Veterinary medicine', 'Nunc in nibh a augue posuere lobortis et eu odio. Donec mollis sollicitudin laoreet metus.');

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

CREATE TABLE `universities` (
  `uniId` int(11) NOT NULL,
  `countryId` int(11) NOT NULL,
  `name` varchar(75) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`uniId`, `countryId`, `name`) VALUES
(1, 1, 'University of Aberdeen'),
(2, 1, 'Abertay University'),
(3, 1, 'Aberystwyth University'),
(4, 1, 'Anglia Ruskin University'),
(5, 1, 'Arden University'),
(6, 1, 'Aston University'),
(7, 1, 'Bangor University'),
(8, 1, 'University of Bath'),
(9, 1, 'Bath Spa University'),
(10, 1, 'University of Bedfordshire'),
(11, 1, 'University of Birmingham'),
(12, 1, 'Birmingham City University'),
(13, 1, 'University College Birmingham'),
(14, 1, 'Bishop Grosseteste University'),
(15, 1, 'University of Bolton'),
(16, 1, 'The Arts University Bournemouth'),
(17, 1, 'Bournemouth University'),
(18, 1, 'BPP University'),
(19, 1, 'University of Bradford'),
(20, 1, 'University of Brighton'),
(21, 1, 'University of Bristol'),
(22, 1, 'Brunel University'),
(23, 1, 'University of Buckingham'),
(24, 1, 'Buckinghamshire New University'),
(25, 1, 'University of Cambridge'),
(26, 1, 'Canterbury Christ Church University'),
(27, 1, 'Cardiff Metropolitan University'),
(28, 1, 'Cardiff University'),
(29, 1, 'University of Chester'),
(30, 1, 'University of Chichester'),
(31, 1, 'Coventry University'),
(32, 1, 'Cranfield University'),
(33, 1, 'University for the Creative Arts'),
(34, 1, 'University of Cumbria'),
(35, 1, 'De Montfort University'),
(36, 1, 'University of Derby'),
(37, 1, 'University of Dundee'),
(38, 1, 'Durham University'),
(39, 1, 'University of East Anglia, Norwich'),
(40, 1, 'University of East London'),
(41, 1, 'Edge Hill University'),
(42, 1, 'University of Edinburgh'),
(43, 1, 'Edinburgh Napier University'),
(44, 1, 'University of Essex'),
(45, 1, 'University of Exeter'),
(46, 1, 'Falmouth University'),
(47, 1, 'University of Glasgow'),
(48, 1, 'Glasgow Caledonian University'),
(49, 1, 'University of Gloucestershire'),
(50, 1, 'Glynd?r University'),
(51, 1, 'University of Greenwich'),
(52, 1, 'Harper Adams University'),
(53, 1, 'Heriot-Watt University'),
(54, 1, 'University of Hertfordshire'),
(55, 1, 'University of the Highlands & Islands'),
(56, 1, 'University of Huddersfield'),
(57, 1, 'University of Hull'),
(58, 1, 'Imperial College London'),
(59, 1, 'Keele University'),
(60, 1, 'University of Kent'),
(61, 1, 'Kingston University'),
(62, 1, 'University of Central Lancashire'),
(63, 1, 'Lancaster University'),
(64, 1, 'University of Leeds'),
(65, 1, 'Leeds Beckett University'),
(66, 1, 'Leeds Trinity University'),
(67, 1, 'University of Leicester'),
(68, 1, 'University of Lincoln'),
(69, 1, 'University of Liverpool'),
(70, 1, 'Liverpool Hope University'),
(71, 1, 'Liverpool John Moores University'),
(72, 1, 'University of London'),
(73, 1, 'London Metropolitan University'),
(74, 1, 'London South Bank University'),
(75, 1, 'Loughborough University'),
(76, 1, 'University of Manchester'),
(77, 1, 'Manchester Metropolitan University'),
(78, 1, 'Middlesex University'),
(79, 1, 'Newcastle University'),
(80, 1, 'Newman University'),
(81, 1, 'University of Northampton'),
(82, 1, 'Northumbria University'),
(83, 1, 'Norwich University of the Arts'),
(84, 1, 'University of Nottingham'),
(85, 1, 'Nottingham Trent University'),
(86, 1, 'The Open University'),
(87, 1, 'University of Oxford'),
(88, 1, 'Oxford Brookes University'),
(89, 1, 'University of Plymouth'),
(90, 1, 'University of Portsmouth'),
(91, 1, 'Queen Margaret University'),
(92, 1, 'Queens University Belfast'),
(93, 1, 'University of Reading'),
(94, 1, 'Regents University London'),
(95, 1, 'The Robert Gordon University'),
(96, 1, 'Roehampton University'),
(97, 1, 'Royal Agricultural University'),
(98, 1, 'University of Salford'),
(99, 1, 'University of Sheffield'),
(100, 1, 'Sheffield Hallam University'),
(101, 1, 'University of South Wales'),
(102, 1, 'University of Southampton'),
(103, 1, 'Southampton Solent University'),
(104, 1, 'University of St Andrews'),
(105, 1, 'University of St Mark & St John'),
(106, 1, 'St Marys University'),
(107, 1, 'Staffordshire University'),
(108, 1, 'University of Stirling'),
(109, 1, 'University of Strathclyde'),
(110, 1, 'University of Suffolk'),
(111, 1, 'University of Sunderland'),
(112, 1, 'University of Surrey'),
(113, 1, 'University of Sussex'),
(114, 1, 'Swansea University'),
(115, 1, 'Teesside University'),
(116, 1, 'University of the Arts London'),
(117, 1, 'Ulster University'),
(118, 1, 'University of Law'),
(119, 1, 'University of Wales'),
(120, 1, 'University of Wales, Trinity Saint David (UWTSD)'),
(121, 1, 'University of Warwick'),
(122, 1, 'University of the West of England'),
(123, 1, 'University of the West of Scotland'),
(124, 1, 'University of West London'),
(125, 1, 'University of WestminsteR'),
(126, 1, 'University of Winchester'),
(127, 1, 'University of Wolverhampton'),
(128, 1, 'University of Worcester'),
(129, 1, 'University of York'),
(130, 1, 'York St John University');

-- --------------------------------------------------------

--
-- Table structure for table `usergroup`
--

CREATE TABLE `usergroup` (
  `groupId` int(11) NOT NULL,
  `name` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`groupId`, `name`) VALUES
(1, 'User'),
(2, 'Admin'),
(3, 'Moderator');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(60) NOT NULL,
  `rememberMeHash` varchar(60) DEFAULT NULL,
  `email` varchar(254) NOT NULL,
  `emailVerified` int(11) NOT NULL DEFAULT '0',
  `emailCode` varchar(12) NOT NULL,
  `resetCode` varchar(12) NOT NULL DEFAULT '0',
  `usergroup` int(11) NOT NULL DEFAULT '1',
  `image` varchar(30) NOT NULL DEFAULT 'default.jpg',
  `bio` varchar(500) DEFAULT NULL,
  `uniId` int(11) NOT NULL DEFAULT '0',
  `subjectId` int(11) NOT NULL DEFAULT '0',
  `register` int(11) NOT NULL,
  `lastOnline` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `username`, `password`, `rememberMeHash`, `email`, `emailVerified`, `emailCode`, `resetCode`, `usergroup`, `image`, `bio`, `uniId`, `subjectId`, `register`, `lastOnline`) VALUES
(1, 'UniStu', '$2y$12$t0l1H2xbinKPIlAXRZtj8OumUy67Srmruszd3AEtbAhc0kDBd/jh6', NULL, 'unistu@hotmail.com', 1, '641743641743', '248731699307', 2, 'default.jpg', NULL, 56, 12, 1475402307, 1490024712),
(27, 'test2', '$2y$12$gq31q4ZHjWPrSSNC13734enSpm7.BDKa/PtHJcF7DXiyy/mX7fYG6', NULL, 'u1559399@unimail.hud.ac.uk', 1, '324396728538', '260004304350', 1, 'default.jpg', NULL, 56, 12, 1490016892, 1490016892),
(28, 'ThatLoganGuy', '$2y$12$60O1OFo3CDloz473KIQZ/.AryTLtEsZ.6BswoNYJ64gHE.9up9iq2', NULL, 'logan123@gmail.com', 1, '938107302878', '162956425455', 1, 'default.jpg', NULL, 0, 0, 1490023739, 1490023739),
(5, 'Danb', '$2y$12$VpnDioXILzhbRiVzUhIYZ.rEPAKd4q6gh.xUXYBhuVaznDjvCeo5G', NULL, 'DanBrew@outlook.com', 1, '241565068253', '928211505990', 1, 'default.jpg', 'i enjoy taking part in demos with my team project,i also enjoy dr pepper', 56, 12, 1478099647, 1490024917),
(6, 'test', '$2y$12$pMkaf.5tKY/yIxnGYdZo3.mPA10t8RGZ/nRz2URFrRqej6M4eTPKO', NULL, 'dshreef@gmail.com', 1, '456316770520', '456316770520', 1, 'default.jpg', 'The test profile for one of the team members at Project407. This profile will be used only for testing purposes and reviews.', 56, 12, 1478100295, 1490017755),
(7, 'Danbrew21', '$2y$12$X.pBEvD01wqa78XMzHFiROh8pUvfFsrvrIENlVS3qcN7FTbc8hvMy', NULL, 'daniel.brewerton.singh@gmail.com', 1, '384440267459', '384440267459', 1, 'default.jpg', NULL, 4, 12, 1478101404, 1478101404),
(8, 'Logis', '$2y$12$KNajc34S.Ym/VTx1lglJFuCn159hPVu.STwyTLmU6Z2Nti9nYQmaO', NULL, 'Lewis@Avenger.org.uk', 1, '491623567883', '491623567883', 1, 'default.jpg', NULL, 56, 12, 1478204253, 1490025591),
(9, 'UniversityNet', '$2y$12$zcvcCljl771X9LdrhF9fY.iov1aXSri/Fl1XHRvBYoU5VI5ud0MIO', '$2y$12$CXl37jIUJTApL16oDa4sA.2UaIfkRPUB8BeCG8BsfL.lm6zQ.otU6', 'ehitine-0837@yopmail.com', 1, '515996548347', '250731950160', 1, 'default.jpg', NULL, 0, 0, 1479309645, 1479310588),
(10, 'Peter1995', '$2y$12$x4vDlBZV.bd0JhCSfoPxpe9HAqp.CQAw58rExUZYhrizzpeKtsVf.', NULL, 'Peter@derby.ac.uk', 0, '158991491888', '703968043905', 1, 'default.jpg', NULL, 36, 40, 1479741868, 1479741868),
(11, 'Peter1996', '$2y$12$A0SUKGQhFbU0Mv50mv43OOFUyaPdF5NumgfFvCDh4IUzhSM5fd4NK', NULL, 'tewyfida-0849@yopmail.com', 1, '124000447803', '710303805303', 1, 'default.jpg', NULL, 36, 40, 1479741943, 1479742779),
(12, 'truejodi', '$2y$12$RYyti80j5NI0CA6nXPQr2u4.BiypXLG8m8D0ej9xGOXYGqFTKgDtW', NULL, 'truejodi.com@gmail.com', 1, '210914620199', '309738033451', 1, 'default.jpg', 'Trusted by millions of matrimonials brides and grooms in India for best matches!\r\n\r\n', 1, 0, 1483680588, 1483680874),
(13, 'TestAcc', '$2y$12$XFOTrSDv5OKGaz6O50PW3e0coToqB.saLmONy7SDBP.tVocK97M6G', NULL, 'qeluppiboffu-1451@yopmail.com', 1, '767297013197', '449575257953', 1, 'default.jpg', '&lt;h1&gt;Test&lt;/h1&gt;', 0, 12, 1486996333, 1486997735),
(15, 'faketyfakefake', '$2y$12$zuPVn289rgBPY/uwYv5xJeFS3ajIoq9MhJIewRhuvwTOI/ZCaDwaO', NULL, 'fakeAddress@fakemail.fake', 1, '597154673654', '197988498491', 1, 'default.jpg', NULL, 2, 2, 1486998385, 1486998385),
(14, 'CyberGhostq', '$2y$12$yFBjlaXYGesUsi7FqbJ7SeYci21NmbmpSxxVuDdJ2ZDyPJ2SIJ4yq', NULL, 'u1364096@unimail.hud.ac.uk', 1, '92731362675', '519492866471', 1, 'default.jpg', NULL, 56, 0, 1486997447, 1486999105),
(16, 'Hack223', '$2y$12$019z8At/NYJvBvw6rXhkRuNPfttbp4TPqKNrwh4UrZGmnsPk.EDUu', NULL, 'hackethack@hack.hack', 1, '723471390549', '729708960280', 1, 'default.jpg', NULL, 3, 2, 1486998790, 1486998790),
(17, 'testerrrr', '$2y$12$CvozBPwyhS73zywk3oelBO1/DPJXfOVtVutiDr1MAbeT50EDZ29Ia', NULL, 'nomail@mail.mai.mailorg', 1, '442518756725', '45174832922', 1, 'default.jpg', NULL, 0, 0, 1486999284, 1486999284),
(18, 'fakeaccount', '$2y$12$6aNqXeOgKQ6MKQ9S0TNK4uDEAuCsw/cA8OkRkGL49PLidfBFa1aUm', NULL, 'fake@fake.co.uk', 0, '950927214231', '793819047045', 1, 'default.jpg', NULL, 1, 0, 1488208352, 1488208352),
(19, 'testAccount123', '$2y$12$A8M5MNkRY9xkZbiOaKEds.ICtd353xVVGRgTEgIoCrVcM4hza2JNy', NULL, 'fakeemail@fake.fake', 0, '203401422594', '613733389880', 1, 'default.jpg', NULL, 1, 1, 1489416173, 1489416173),
(20, 's4itb4g123', '$2y$12$RQQaT2V.frj5I8Vyi59aweAeY6wr5S6CrvJCpRn0V.YPotzgNwT26', NULL, 'fake@fake.fake', 0, '522357941605', '576410160400', 1, 'default.jpg', NULL, 0, 0, 1489416854, 1489416854),
(21, 'testSQL', '$2y$12$0MongemSf9Lw9lWx618p4.lHliBOwMIneFW5MdnL4TqFBhK61Na1C', NULL, 'dshreef@outlook.com', 1, '431928626262', '408503855113', 1, 'default.jpg', NULL, 1, 1, 1489694928, 1489694928),
(29, 'oreos', '$2y$12$eQLVYUpAMQpDwl5oIESU1e1DY/EVH9Dsh4R5Fc3fnphhsqlcQFy0q', NULL, 'mdelair144@gmail.com', 1, '863599715288', '218663448934', 1, 'default.jpg', NULL, 56, 12, 1490023744, 1490025835),
(30, 'Dankestmemer', '$2y$12$WQj8BH3k3RkU6TgOIaIKi.4QUTpyKnapky670UTEjSkjuiFcphYnu', NULL, 'simonsiu1552@gmail.com', 1, '631197202951', '342029767577', 1, 'default.jpg', NULL, 56, 12, 1490023782, 1490024871),
(31, 'whyspoillogan', '$2y$12$asghB44rM9bHKJon4D42NeIsQ0hmc5zEw2ROyF6rVsjtK8q65jCzS', NULL, 'DanBrew21@gmail.com', 1, '270070646890', '310941277072', 1, 'default.jpg', NULL, 56, 12, 1490023976, 1490023976),
(32, 'seapotato', '$2y$12$1sTCq4I7fc7PPVd/AVSbL.ZJ5uTtOIAY7eZPV.4PksDfp/eze45bC', NULL, 'spide098192@gmail.com', 0, '460860599298', '919680301100', 1, 'default.jpg', NULL, 56, 12, 1490036747, 1490036747),
(33, 'seapotatos', '$2y$12$CH9NDcVKf9ncHGqSsY02Au5lOnIHmtOzkqT0VGZ9cLLB6Tmd556N2', NULL, 'spidey098192@gmail.com', 1, '83964650520', '726329050958', 1, 'default.jpg', NULL, 56, 12, 1490037625, 1490037868),
(34, 'labelle', '$2y$12$F9ZItnsPR3f33EW9qDd/E.irBet306Oy0FAaTRNctB5Opxavfek.q', NULL, 'labelles.in@gmail.com', 1, '70903893095', '293470159173', 1, 'default.jpg', 'labelle', 1, 2, 1495694274, 1495694792);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`answerId`);

--
-- Indexes for table `answerVotes`
--
ALTER TABLE `answerVotes`
  ADD PRIMARY KEY (`voteId`);

--
-- Indexes for table `awardLookup`
--
ALTER TABLE `awardLookup`
  ADD PRIMARY KEY (`lookupId`);

--
-- Indexes for table `awards`
--
ALTER TABLE `awards`
  ADD PRIMARY KEY (`awardId`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`countryId`);

--
-- Indexes for table `friend`
--
ALTER TABLE `friend`
  ADD PRIMARY KEY (`lookupID`);

--
-- Indexes for table `friendRequest`
--
ALTER TABLE `friendRequest`
  ADD PRIMARY KEY (`requestId`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msgId`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`moduleId`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`questionId`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`reportId`);

--
-- Indexes for table `reputation`
--
ALTER TABLE `reputation`
  ADD PRIMARY KEY (`reputationId`);

--
-- Indexes for table `social`
--
ALTER TABLE `social`
  ADD PRIMARY KEY (`socialId`);

--
-- Indexes for table `socialUserLookup`
--
ALTER TABLE `socialUserLookup`
  ADD PRIMARY KEY (`socialId`,`userId`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subjectId`);

--
-- Indexes for table `universities`
--
ALTER TABLE `universities`
  ADD PRIMARY KEY (`uniId`);

--
-- Indexes for table `usergroup`
--
ALTER TABLE `usergroup`
  ADD PRIMARY KEY (`groupId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `answerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `answerVotes`
--
ALTER TABLE `answerVotes`
  MODIFY `voteId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `awardLookup`
--
ALTER TABLE `awardLookup`
  MODIFY `lookupId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `awards`
--
ALTER TABLE `awards`
  MODIFY `awardId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `countryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `friend`
--
ALTER TABLE `friend`
  MODIFY `lookupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `friendRequest`
--
ALTER TABLE `friendRequest`
  MODIFY `requestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msgId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `moduleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `questionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `reportId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `reputation`
--
ALTER TABLE `reputation`
  MODIFY `reputationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `social`
--
ALTER TABLE `social`
  MODIFY `socialId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `subjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
  MODIFY `uniId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;
--
-- AUTO_INCREMENT for table `usergroup`
--
ALTER TABLE `usergroup`
  MODIFY `groupId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
