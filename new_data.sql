-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2026 at 02:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_course_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `AdminID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`AdminID`, `Username`, `PasswordHash`) VALUES
(1, 'admin2', 'admin234');

-- --------------------------------------------------------

--
-- Table structure for table `interestedstudents`
--

CREATE TABLE `interestedstudents` (
  `InterestID` int(11) NOT NULL,
  `ProgrammeID` int(11) NOT NULL,
  `StudentName` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `RegisteredAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `IsActive` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interestedstudents`
--

INSERT INTO `interestedstudents` (`InterestID`, `ProgrammeID`, `StudentName`, `Email`, `RegisteredAt`, `IsActive`) VALUES
(1, 1, 'John Doe', 'john.doe@example.com', '2026-03-08 18:19:44', 1),
(2, 4, 'Jane Smith', 'jane.smith@example.com', '2026-03-08 18:19:44', 1),
(3, 6, 'Alex Brown', 'alex.brown@example.com', '2026-03-08 18:19:44', 1),
(4, 9, 'Priya Patel', 'priya.patel@example.com', '2026-03-08 18:19:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `LevelID` int(11) NOT NULL,
  `LevelName` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`LevelID`, `LevelName`) VALUES
(1, 'Undergraduate'),
(2, 'Postgraduate');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `ModuleID` int(11) NOT NULL,
  `ModuleName` text NOT NULL,
  `ModuleLeaderID` int(11) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`ModuleID`, `ModuleName`, `ModuleLeaderID`, `Description`, `Image`) VALUES
(1, 'Introduction to Programming', 1, 'Covers the fundamentals of programming using Python and Java.', NULL),
(2, 'Mathematics for Computer Science', 2, 'Teaches discrete mathematics, linear algebra, and probability theory.', NULL),
(3, 'Computer Systems & Architecture', 3, 'Explores CPU design, memory management, and assembly language.', NULL),
(4, 'Databases', 4, 'Covers SQL, relational database design, and NoSQL systems.', NULL),
(5, 'Software Engineering', 5, 'Focuses on agile development, design patterns, and project management.', NULL),
(6, 'Algorithms & Data Structures', 6, 'Examines sorting, searching, graphs, and complexity analysis.', NULL),
(7, 'Cyber Security Fundamentals', 7, 'Provides an introduction to network security, cryptography, and vulnerabilities.', NULL),
(8, 'Artificial Intelligence', 8, 'Introduces AI concepts such as neural networks, expert systems, and robotics.', NULL),
(9, 'Machine Learning', 9, 'Explores supervised and unsupervised learning, including decision trees and clustering.', NULL),
(10, 'Ethical Hacking', 10, 'Covers penetration testing, security assessments, and cybersecurity laws.', NULL),
(11, 'Computer Networks', 1, 'Teaches TCP/IP, network layers, and wireless communication.', NULL),
(12, 'Software Testing & Quality Assurance', 2, 'Focuses on automated testing, debugging, and code reliability.', NULL),
(13, 'Embedded Systems', 3, 'Examines microcontrollers, real-time OS, and IoT applications.', NULL),
(14, 'Human-Computer Interaction', 4, 'Studies UI/UX design, usability testing, and accessibility.', NULL),
(15, 'Blockchain Technologies', 5, 'Covers distributed ledgers, consensus mechanisms, and smart contracts.', NULL),
(16, 'Cloud Computing', 6, 'Introduces cloud services, virtualization, and distributed systems.', NULL),
(17, 'Digital Forensics', 7, 'Teaches forensic investigation techniques for cybercrime.', NULL),
(18, 'Final Year Project', 8, 'A major independent project where students develop a software solution.', NULL),
(19, 'Advanced Machine Learning', 11, 'Covers deep learning, reinforcement learning, and cutting-edge AI techniques.', NULL),
(20, 'Cyber Threat Intelligence', 12, 'Focuses on cybersecurity risk analysis, malware detection, and threat mitigation.', NULL),
(21, 'Big Data Analytics', 13, 'Explores data mining, distributed computing, and AI-driven insights.', NULL),
(22, 'Cloud & Edge Computing', 14, 'Examines scalable cloud platforms, serverless computing, and edge networks.', NULL),
(23, 'Blockchain & Cryptography', 15, 'Covers decentralized applications, consensus algorithms, and security measures.', NULL),
(24, 'AI Ethics & Society', 16, 'Analyzes ethical dilemmas in AI, fairness, bias, and regulatory considerations.', NULL),
(25, 'Quantum Computing', 17, 'Introduces quantum algorithms, qubits, and cryptographic applications.', NULL),
(26, 'Cybersecurity Law & Policy', 18, 'Explores digital privacy, GDPR, and international cyber law.', NULL),
(27, 'Neural Networks & Deep Learning', 19, 'Delves into convolutional networks, GANs, and AI advancements.', NULL),
(28, 'Human-AI Interaction', 20, 'Studies AI usability, NLP systems, and social robotics.', NULL),
(29, 'Autonomous Systems', 11, 'Focuses on self-driving technology, robotics, and intelligent agents.', NULL),
(30, 'Digital Forensics & Incident Response', 12, 'Teaches forensic analysis, evidence gathering, and threat mitigation.', NULL),
(31, 'Postgraduate Dissertation', 13, 'A major research project where students explore advanced topics in computing.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `programmemodules`
--

CREATE TABLE `programmemodules` (
  `ProgrammeModuleID` int(11) NOT NULL,
  `ProgrammeID` int(11) DEFAULT NULL,
  `ModuleID` int(11) DEFAULT NULL,
  `Year` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programmemodules`
--

INSERT INTO `programmemodules` (`ProgrammeModuleID`, `ProgrammeID`, `ModuleID`, `Year`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 3, 1),
(4, 1, 4, 1),
(5, 2, 1, 1),
(6, 2, 2, 1),
(7, 2, 3, 1),
(8, 2, 4, 1),
(9, 3, 1, 1),
(10, 3, 2, 1),
(11, 3, 3, 1),
(12, 3, 4, 1),
(13, 4, 1, 1),
(14, 4, 2, 1),
(15, 4, 3, 1),
(16, 4, 4, 1),
(17, 5, 1, 1),
(18, 5, 2, 1),
(19, 5, 3, 1),
(20, 5, 4, 1),
(21, 1, 5, 2),
(22, 1, 6, 2),
(23, 1, 7, 2),
(24, 1, 8, 2),
(25, 2, 5, 2),
(26, 2, 6, 2),
(27, 2, 12, 2),
(28, 2, 14, 2),
(29, 3, 5, 2),
(30, 3, 9, 2),
(31, 3, 8, 2),
(32, 3, 10, 2),
(33, 4, 7, 2),
(34, 4, 10, 2),
(35, 4, 11, 2),
(36, 4, 17, 2),
(37, 5, 5, 2),
(38, 5, 6, 2),
(39, 5, 9, 2),
(40, 5, 16, 2),
(41, 1, 11, 3),
(42, 1, 13, 3),
(43, 1, 15, 3),
(44, 1, 18, 3),
(45, 2, 13, 3),
(46, 2, 15, 3),
(47, 2, 16, 3),
(48, 2, 18, 3),
(49, 3, 13, 3),
(50, 3, 15, 3),
(51, 3, 16, 3),
(52, 3, 18, 3),
(53, 4, 15, 3),
(54, 4, 16, 3),
(55, 4, 17, 3),
(56, 4, 18, 3),
(57, 5, 9, 3),
(58, 5, 14, 3),
(59, 5, 16, 3),
(60, 5, 18, 3),
(61, 6, 19, 1),
(62, 6, 24, 1),
(63, 6, 27, 1),
(64, 6, 29, 1),
(65, 6, 31, 1),
(66, 7, 20, 1),
(67, 7, 26, 1),
(68, 7, 30, 1),
(69, 7, 23, 1),
(70, 7, 31, 1),
(71, 8, 21, 1),
(72, 8, 22, 1),
(73, 8, 27, 1),
(74, 8, 28, 1),
(75, 8, 31, 1),
(76, 9, 19, 1),
(77, 9, 24, 1),
(78, 9, 28, 1),
(79, 9, 29, 1),
(80, 9, 31, 1),
(81, 10, 23, 1),
(82, 10, 22, 1),
(83, 10, 25, 1),
(84, 10, 26, 1),
(85, 10, 31, 1);

-- --------------------------------------------------------

--
-- Table structure for table `programmes`
--

CREATE TABLE `programmes` (
  `ProgrammeID` int(11) NOT NULL,
  `ProgrammeName` text NOT NULL,
  `LevelID` int(11) DEFAULT NULL,
  `ProgrammeLeaderID` int(11) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Image` text DEFAULT NULL,
  `IsPublished` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programmes`
--

INSERT INTO `programmes` (`ProgrammeID`, `ProgrammeName`, `LevelID`, `ProgrammeLeaderID`, `Description`, `Image`, `IsPublished`) VALUES
(1, 'BSc Computer Science', 1, 1, 'A broad computer science degree covering programming, AI, cybersecurity, and software engineering.', NULL, 1),
(2, 'BSc Software Engineering', 1, 2, 'A specialized degree focusing on the development and lifecycle of software applications.', NULL, 1),
(3, 'BSc Artificial Intelligence', 1, 3, 'Focuses on machine learning, deep learning, and AI applications.', NULL, 1),
(4, 'BSc Cyber Security', 1, 4, 'Explores network security, ethical hacking, and digital forensics.', NULL, 1),
(5, 'BSc Data Science', 1, 5, 'Covers big data, machine learning, and statistical computing.', NULL, 1),
(6, 'MSc Machine Learning', 2, 11, 'A postgraduate degree focusing on deep learning, AI ethics, and neural networks.', NULL, 1),
(7, 'MSc Cyber Security', 2, 12, 'A specialized programme covering digital forensics, cyber threat intelligence, and security policy.', NULL, 1),
(8, 'MSc Data Science', 2, 13, 'Focuses on big data analytics, cloud computing, and AI-driven insights.', NULL, 1),
(9, 'MSc Artificial Intelligence', 2, 14, 'Explores autonomous systems, AI ethics, and deep learning technologies.', NULL, 1),
(10, 'MSc Software Engineering', 2, 15, 'Emphasizes software design, blockchain applications, and cutting-edge methodologies.', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `StaffID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`StaffID`, `Name`, `Username`, `Password`) VALUES
(1, 'Dr. Alice Johnson', 's1', '$2y$10$fPTYNVyz8X1YabNXQ6vi9etD8y720pvwBay4YtL3Qt2bcySH/Mqqm'),
(2, 'Dr. Brian Lee', 's2', '$2y$10$ovUkiyL1MSU9lAEwZTIDu.mCMOuw/BDoj0WqM7xXOmm8k0nxOcnh6'),
(3, 'Dr. Carol White', 's3', '$2y$10$oN6gEd3o8p2PbMIh/g.pQ.RLkEoS3FGNU25glIitlbP283A0JjB6G'),
(4, 'Dr. David Green', 's4', '$2y$10$VefbjDgeofBmXuU2zMrxHum8ppyEiZLrkoQ0z9Z7DPKGdeHQcXb1m'),
(5, 'Dr. Emma Scott', 's5', '$2y$10$4M8egM8yoWjzkk5sz.wmYOrvDvl20JGFoSRV9obMOn.cY/USTA5Re'),
(6, 'Dr. Frank Moore', 's6', '$2y$10$8DJ2FhmmnEXLUMaZfYC.X./g3E7i1F5y4btvLhgzPRpT7HOKYjvIm'),
(7, 'Dr. Grace Adams', 's7', '$2y$10$u2JKnQKyRmIZyJ8O5/xfMuK3fxTmaNdp/sLkUwCd9MImP.dnkoB7S'),
(8, 'Dr. Henry Clark', 's8', '$2y$10$hD3Yb/.pSoMYEgIsRafcJegJOzXfAwHCh7JazzRv6M8XDazGGYyKm'),
(9, 'Dr. Irene Hall', 's9', '$2y$10$NN2iPFL0StXfL4JP2us76.6h0dBPpVd/vtZmI0II58pQ/MvM2jgpS'),
(10, 'Dr. James Wright', 's10', '$2y$10$tCciWoYSKDeOfJtvQ.bFTeKP5XmNxXOL3z2QuJ9xKPOXM0J0jSOb6'),
(11, 'Dr. Sophia Miller', 's11', '$2y$10$M9Ho0Z0Y/yBEQflh2U8bGuAimiC3lNivU0dK4M46Hjq.o7tM49drW'),
(12, 'Dr. Benjamin Carter', 's12', '$2y$10$6XLcZ37zSr.thGX1LywUd.XFbp2BbuVe7L6LeZtfWUQQjURvw0.wW'),
(13, 'Dr. Chloe Thompson', 's13', '$2y$10$eUflD4p4R9ncMF4uo.TWnu.DdgpclEpeDCZ3i/VrZvd67KkXczGy6'),
(14, 'Dr. Daniel Robinson', 's14', '$2y$10$gaZo6WGsMWsmErv0FFTg.Ot9C8GceWxhZi4x9x/XnzovlZhH2HDZa'),
(15, 'Dr. Emily Davis', 's15', '$2y$10$8eRzuC7.qRLuw97AgdREKezCmfLc2sy7r5yCL.Obg0nq7r0YRxOku'),
(16, 'Dr. Nathan Hughes', 's16', '$2y$10$wBU9rQq8JqxUE9uM3d4VAuXM26LqGX3d.mGDUfygjnRaRAry3pHs.'),
(17, 'Dr. Olivia Martin', 's17', '$2y$10$sROyiO9qdxr8Cj1IM827Uup716SUQTeFw8O/O9BcmrzN0hopOumJW'),
(18, 'Dr. Samuel Anderson', 's18', '$2y$10$FCCvuje0CJvkDNlW3LsQeOLIpNx3eteBrt/MnCeiipp0HOB7s3QQy'),
(19, 'Dr. Victoria Hall', 's19', '$2y$10$FZBNy0Ybaf3G.Yo8aRZ/BexMP3GKvOCJ8LMVSKrDG2CaDK.nvIxKW'),
(20, 'Dr. William Scott', 's20', '$2y$10$nS0uzNd9pqhXfpWG2AnrTehqFecvMi3j1hKLTIZKWardSCwtKL3mi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `interestedstudents`
--
ALTER TABLE `interestedstudents`
  ADD PRIMARY KEY (`InterestID`),
  ADD UNIQUE KEY `unique_interest` (`ProgrammeID`,`Email`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`LevelID`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`ModuleID`),
  ADD KEY `ModuleLeaderID` (`ModuleLeaderID`);

--
-- Indexes for table `programmemodules`
--
ALTER TABLE `programmemodules`
  ADD PRIMARY KEY (`ProgrammeModuleID`),
  ADD KEY `ProgrammeID` (`ProgrammeID`),
  ADD KEY `ModuleID` (`ModuleID`);

--
-- Indexes for table `programmes`
--
ALTER TABLE `programmes`
  ADD PRIMARY KEY (`ProgrammeID`),
  ADD KEY `LevelID` (`LevelID`),
  ADD KEY `ProgrammeLeaderID` (`ProgrammeLeaderID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`StaffID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `interestedstudents`
--
ALTER TABLE `interestedstudents`
  MODIFY `InterestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `programmemodules`
--
ALTER TABLE `programmemodules`
  MODIFY `ProgrammeModuleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `programmes`
--
ALTER TABLE `programmes`
  MODIFY `ProgrammeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `interestedstudents`
--
ALTER TABLE `interestedstudents`
  ADD CONSTRAINT `interestedstudents_ibfk_1` FOREIGN KEY (`ProgrammeID`) REFERENCES `programmes` (`ProgrammeID`) ON DELETE CASCADE;

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_ibfk_1` FOREIGN KEY (`ModuleLeaderID`) REFERENCES `staff` (`StaffID`);

--
-- Constraints for table `programmemodules`
--
ALTER TABLE `programmemodules`
  ADD CONSTRAINT `programmemodules_ibfk_1` FOREIGN KEY (`ProgrammeID`) REFERENCES `programmes` (`ProgrammeID`),
  ADD CONSTRAINT `programmemodules_ibfk_2` FOREIGN KEY (`ModuleID`) REFERENCES `modules` (`ModuleID`);

--
-- Constraints for table `programmes`
--
ALTER TABLE `programmes`
  ADD CONSTRAINT `programmes_ibfk_1` FOREIGN KEY (`LevelID`) REFERENCES `levels` (`LevelID`),
  ADD CONSTRAINT `programmes_ibfk_2` FOREIGN KEY (`ProgrammeLeaderID`) REFERENCES `staff` (`StaffID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
