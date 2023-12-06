-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2023 at 12:01 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chms`
--

-- --------------------------------------------------------

--
-- Table structure for table `allergies_logs`
--

CREATE TABLE `allergies_logs` (
  `P_ID` int(11) NOT NULL,
  `Allergies` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allergies_logs`
--

INSERT INTO `allergies_logs` (`P_ID`, `Allergies`) VALUES
(1001, 'Dust Mite Allergies'),
(1002, 'Dust Mite Allergies'),
(1002, 'Food Allergies(tree nuts)');

-- --------------------------------------------------------

--
-- Table structure for table `appoinment`
--

CREATE TABLE `appoinment` (
  `ID` int(11) NOT NULL,
  `P_ID` int(11) NOT NULL,
  `D_ID` int(11) NOT NULL,
  `H_ID` int(11) NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appoinment`
--

INSERT INTO `appoinment` (`ID`, `P_ID`, `D_ID`, `H_ID`, `Date`) VALUES
(1000, 1001, 1003, 1004, '2023-11-13'),
(1001, 1001, 1003, 1004, '2023-11-24');

-- --------------------------------------------------------

--
-- Table structure for table `awards_honors`
--

CREATE TABLE `awards_honors` (
  `D_ID` int(11) NOT NULL,
  `Award_Name` varchar(150) NOT NULL,
  `Awarding_Organization` varchar(150) NOT NULL,
  `Year_Received` year(4) NOT NULL,
  `Description` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consultation`
--

CREATE TABLE `consultation` (
  `D_ID` int(11) NOT NULL,
  `H_ID` int(11) NOT NULL,
  `Day` char(10) NOT NULL,
  `Start_Time` time NOT NULL,
  `End_Time` time NOT NULL,
  `Room` int(4) NOT NULL,
  `Fees` decimal(6,2) NOT NULL,
  `MAX_Capacity` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultation`
--

INSERT INTO `consultation` (`D_ID`, `H_ID`, `Day`, `Start_Time`, `End_Time`, `Room`, `Fees`, `MAX_Capacity`) VALUES
(1003, 1004, 'Friday', '18:00:00', '21:00:00', 306, 1200.00, 50),
(1003, 1004, 'Monday', '15:00:00', '18:00:00', 205, 1200.00, 50),
(1003, 1004, 'Saturday', '13:00:00', '15:00:00', 306, 1200.00, 30);

-- --------------------------------------------------------

--
-- Table structure for table `degrees`
--

CREATE TABLE `degrees` (
  `D_ID` int(11) NOT NULL,
  `Degree` varchar(50) NOT NULL,
  `Field` varchar(100) DEFAULT NULL,
  `Institution` varchar(150) NOT NULL,
  `Year` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `degrees`
--

INSERT INTO `degrees` (`D_ID`, `Degree`, `Field`, `Institution`, `Year`) VALUES
(1003, 'D. Card', 'N/A', 'London', '2004'),
(1003, 'MBBS', 'N/A', 'CMC', '2002'),
(1003, 'Msc', 'Cardiology', 'UK', '2008');

-- --------------------------------------------------------

--
-- Table structure for table `diagnostic_center`
--

CREATE TABLE `diagnostic_center` (
  `ID` int(11) NOT NULL,
  `License_Number` int(11) NOT NULL,
  `Name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diagnostic_center`
--

INSERT INTO `diagnostic_center` (`ID`, `License_Number`, `Name`) VALUES
(1005, 5564769, 'Popular Diagnostic Centre');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `ID` int(11) NOT NULL,
  `License_Number` int(11) NOT NULL,
  `Fname` varchar(30) NOT NULL,
  `Lname` varchar(30) DEFAULT NULL,
  `Title` varchar(30) DEFAULT NULL,
  `DOB` date NOT NULL,
  `Gender` char(10) NOT NULL,
  `Experience_From` date NOT NULL,
  `Language` varchar(100) NOT NULL,
  `Current_Position` varchar(150) NOT NULL,
  `P_Location` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`ID`, `License_Number`, `Fname`, `Lname`, `Title`, `DOB`, `Gender`, `Experience_From`, `Language`, `Current_Position`, `P_Location`) VALUES
(1003, 6548916, 'Asif', 'Manwar', '', '1980-04-19', 'Male', '2002-09-01', 'Bangla, English', 'Resident Medical Officer', 'BIRDEM');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_reviews`
--

CREATE TABLE `doctor_reviews` (
  `P_ID` int(11) NOT NULL,
  `D_ID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Comment` varchar(200) DEFAULT NULL,
  `Ratings` decimal(1,0) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_reviews`
--

INSERT INTO `doctor_reviews` (`P_ID`, `D_ID`, `Date`, `Comment`, `Ratings`, `Status`) VALUES
(1001, 1003, '2023-11-01', 'Good Behaviour and Best for cardiolgy', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hospital`
--

CREATE TABLE `hospital` (
  `ID` int(11) NOT NULL,
  `License_Number` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital`
--

INSERT INTO `hospital` (`ID`, `License_Number`, `Name`) VALUES
(1004, 51415461, 'SQUARE HOSPITALS LTD.');

-- --------------------------------------------------------

--
-- Table structure for table `journals_publications`
--

CREATE TABLE `journals_publications` (
  `D_ID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Year` year(4) NOT NULL,
  `Link` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Strength` varchar(10) NOT NULL,
  `Group_Name` varchar(50) NOT NULL,
  `Brand_Name` varchar(50) NOT NULL,
  `Price` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`ID`, `Name`, `Strength`, `Group_Name`, `Brand_Name`, `Price`) VALUES
(1000, 'Xcel', '500 mg', 'Paracetamol', 'ACI Limited', 12.00),
(1001, 'Xcel ER', '665 mg', 'Paracetamol', 'ACI Limited', 20.00),
(1002, 'Fast', '500 mg', 'Paracetamol', 'ACME Laboratories', 12.00),
(1003, 'Fast XR', '665 mg', 'Paracetamol', 'ACME Laboratories', 20.00),
(1004, 'Xpa', '500 mg', 'Paracetamol', 'Aristropharma Ltd.', 12.00),
(1005, 'Xpa XR', '665 mg', 'Paracetamol', 'Aristropharma Ltd.', 20.00),
(1006, 'Reset', '500 mg', 'Paracetamol', 'Incepta Pharmaceuticals Ltd', 12.00),
(1007, 'Reset ER', '665 mg', 'Paracetamol', 'Incepta Pharmaceuticals Ltd', 20.00),
(1008, 'Napa', '500 mg', 'Paracetamol', 'Beximco Pharmaceuticals Ltd.', 12.00),
(1009, 'Napa Extend', '665 mg', 'Paracetamol', 'Beximco Pharmaceuticals Ltd.', 20.00),
(1010, 'Napa One', '1000 mg', 'Paracetamol', 'Beximco Pharmaceuticals Ltd.', 22.50),
(1011, 'Napa Rapid(Actizorb)', '500 mg', 'Paracetamol', 'Beximco Pharmaceuticals Ltd.', 22.50);

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `ID` int(11) NOT NULL,
  `Fname` varchar(30) NOT NULL,
  `Lname` varchar(30) DEFAULT NULL,
  `DOB` date NOT NULL,
  `Gender` char(10) NOT NULL,
  `Emergency_Number` char(15) NOT NULL,
  `Blood` char(3) DEFAULT NULL,
  `Weight` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`ID`, `Fname`, `Lname`, `DOB`, `Gender`, `Emergency_Number`, `Blood`, `Weight`) VALUES
(1001, 'Joydev Ghosh', 'Joy', '1999-09-14', 'Male', '+8801746549585', 'A+', 45.00),
(1002, 'Amanullah', 'Ahsan', '1998-07-06', 'Male', '+8801764895615', 'O+', 65.00);

-- --------------------------------------------------------

--
-- Table structure for table `prescribed_in`
--

CREATE TABLE `prescribed_in` (
  `Pr_ID` int(11) NOT NULL,
  `M_ID` int(11) NOT NULL,
  `Morning` tinyint(1) NOT NULL,
  `M_After_Meal` tinyint(1) NOT NULL,
  `Noon` tinyint(1) NOT NULL,
  `N_After_Meal` tinyint(1) NOT NULL,
  `Night` tinyint(1) NOT NULL,
  `Nt_After_Meal` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescribed_in`
--

INSERT INTO `prescribed_in` (`Pr_ID`, `M_ID`, `Morning`, `M_After_Meal`, `Noon`, `N_After_Meal`, `Night`, `Nt_After_Meal`) VALUES
(1002, 1008, 1, 1, 1, 1, 1, 1),
(1002, 1009, 1, 0, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `ID` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `D_ID` int(11) NOT NULL,
  `P_ID` int(11) NOT NULL,
  `Problem` varchar(200) NOT NULL,
  `Suggestions` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`ID`, `Date`, `D_ID`, `P_ID`, `Problem`, `Suggestions`) VALUES
(1002, '2023-11-01 22:29:03', 1003, 1001, 'Fever', 'Take Rest');

-- --------------------------------------------------------

--
-- Table structure for table `speciality`
--

CREATE TABLE `speciality` (
  `D_ID` int(11) NOT NULL,
  `Field` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `speciality`
--

INSERT INTO `speciality` (`D_ID`, `Field`) VALUES
(1003, 'Cardiology'),
(1003, 'Internal Medicine');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Price` decimal(8,2) NOT NULL,
  `Status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`ID`, `Name`, `Price`, `Status`) VALUES
(1000, 'Complete Blood Count (CBC)', 300.00, 1),
(1001, 'Erythrocyte Sedimentation Rate (ESR)', 200.00, 1),
(1002, 'C-Reactive Protein (CRP)', 300.00, 1),
(1003, 'Blood Sugar Test', 200.00, 1),
(1004, 'Lipid Profile', 1200.00, 1),
(1005, 'Liver Function Test (LFT)', 1000.00, 1),
(1006, 'Kidney Function Test (KFT)', 1000.00, 1),
(1007, 'Thyroid Function Test (TFT)', 1000.00, 1),
(1008, 'Urine Routine Examination (URE)', 200.00, 1),
(1009, 'Stool Routine Examination (SRE)', 200.00, 1),
(1010, 'Sputum Routine Examination (SME)', 200.00, 1),
(1011, 'X-ray Chest PA View', 450.00, 1),
(1012, 'Ultrasound Abdomen', 800.00, 1),
(1013, 'Electrocardiogram (ECG)', 300.00, 1),
(1014, 'Echocardiogram (ECHO)', 2000.00, 1),
(1015, 'Complete Blood Count (CBC) with Differential', 500.00, 1),
(1016, 'Platelet Count', 200.00, 1),
(1017, 'Prothrombin Time (PT)', 200.00, 1),
(1018, 'Activated Partial Thromboplastin Time (APTT)', 200.00, 1),
(1019, 'D-Dimer', 1500.00, 1),
(1020, 'Blood Urea Nitrogen (BUN)', 200.00, 1),
(1021, 'Creatinine', 200.00, 1),
(1022, 'Uric Acid', 300.00, 1),
(1023, 'Electrolytes (Sodium, Potassium, Chloride)', 300.00, 1),
(1024, 'Liver Enzymes (ALT, AST, ALP)', 500.00, 1),
(1025, 'Bilirubin (Total and Direct)', 500.00, 1),
(1026, 'Albumin', 300.00, 1),
(1027, 'Total Protein', 300.00, 1),
(1028, 'Globulin', 300.00, 1),
(1029, 'Thyroid Hormones (T3, T4, TSH)', 1000.00, 1),
(1030, 'Prolactin', 1000.00, 1),
(1031, 'Testosterone', 1000.00, 1),
(1032, 'Estradiol', 1000.00, 1),
(1033, 'Prostate-specific Antigen (PSA)', 1000.00, 1),
(1034, 'Human Chorionic Gonadotropin (hCG)', 500.00, 1),
(1035, 'Viral Markers (HIV, Hepatitis B, Hepatitis C)', 1500.00, 1),
(1036, 'Malaria Parasite Detection', 300.00, 1),
(1037, 'Dengue NS1 Antigen Test', 500.00, 1),
(1038, 'Typhoid Antibody Test', 500.00, 1),
(1039, 'Leptospirosis Antibody Test', 500.00, 1),
(1040, 'Scrub Typhus Antibody Test', 500.00, 1),
(1041, 'Chikungunya NS1 Antigen Test', 500.00, 1),
(1042, 'Zika Virus NS1 Antigen Test', 500.00, 1),
(1043, 'HCL', 450.00, 1),
(1044, 'DEMO TEST', 999.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `test_log`
--

CREATE TABLE `test_log` (
  `ID` int(11) NOT NULL,
  `T_ID` int(11) NOT NULL,
  `P_ID` int(11) NOT NULL,
  `Prescription_ID` int(11) DEFAULT NULL,
  `DC_ID` int(11) DEFAULT NULL,
  `Test_Date` datetime DEFAULT NULL,
  `Result_Date` datetime DEFAULT NULL,
  `Result` char(50) DEFAULT NULL,
  `Comment` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_log`
--

INSERT INTO `test_log` (`ID`, `T_ID`, `P_ID`, `Prescription_ID`, `DC_ID`, `Test_Date`, `Result_Date`, `Result`, `Comment`) VALUES
(1000, 1037, 1001, 1002, 1005, '2023-11-01 23:34:19', '2023-11-01 23:39:01', 'report/report-1000.pdf', 'Negative'),
(1001, 1003, 1001, NULL, 1005, '2023-11-01 23:36:40', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `ID` int(11) NOT NULL,
  `User_Name` varchar(255) NOT NULL,
  `Password` char(255) NOT NULL,
  `Role` char(20) NOT NULL,
  `Phone_Number` char(15) NOT NULL,
  `Email` char(150) DEFAULT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `Photo` char(50) DEFAULT NULL,
  `Status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`ID`, `User_Name`, `Password`, `Role`, `Phone_Number`, `Email`, `Address`, `Photo`, `Status`) VALUES
(1000, 'admin', '$2y$10$ufjV43EmPBXVRW.APgK/COzcA72j6Uw1g8tdw9jaWavIHZmAVDpYC', 'Admin', '+8801704449534', NULL, NULL, NULL, 1),
(1001, 'joy508', '$2y$10$ufjV43EmPBXVRW.APgK/COzcA72j6Uw1g8tdw9jaWavIHZmAVDpYC', 'Patient', '+8801746549585', 'joy508@gmail.com', 'Bashundhara, Dhaka', 'img/profile-1001.png', 1),
(1002, 'amanullah', '$2y$10$ufjV43EmPBXVRW.APgK/COzcA72j6Uw1g8tdw9jaWavIHZmAVDpYC', 'Patient', '+8801764895615', 'amanullah@gmail.com', 'Bashundhara, Dhaka', 'img/profile-1002.jpg', 1),
(1003, 'doctor', '$2y$10$ufjV43EmPBXVRW.APgK/COzcA72j6Uw1g8tdw9jaWavIHZmAVDpYC', 'Doctor', '+8801794628456', 'doctor@gmail.com', 'Dhaka, Bangladesh', 'img/profile-1003.jpg', 1),
(1004, 'hospital', '$2y$10$ufjV43EmPBXVRW.APgK/COzcA72j6Uw1g8tdw9jaWavIHZmAVDpYC', 'Hospital', '+8801798651984', 'hospital@gmail.com', 'House # 1, Road # 11,\r\nBlock # F, Banani,\r\nDhaka- 1213', 'img/profile-1004.png', 1),
(1005, 'diagnostic', '$2y$10$ufjV43EmPBXVRW.APgK/COzcA72j6Uw1g8tdw9jaWavIHZmAVDpYC', 'Diagnostic Center', '+8801987195987', 'diagnostic@gmail.com', 'Badda, Dhaka, Bangladesh', 'img/profile-1005.jpg', 1),
(1012, 'patient', '$2y$10$ufjV43EmPBXVRW.APgK/COzcA72j6Uw1g8tdw9jaWavIHZmAVDpYC', 'Patient', '+8801719814656', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vaccination_logs`
--

CREATE TABLE `vaccination_logs` (
  `P_ID` int(11) NOT NULL,
  `Vaccine_Name` varchar(100) NOT NULL,
  `Doss_Number` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccination_logs`
--

INSERT INTO `vaccination_logs` (`P_ID`, `Vaccine_Name`, `Doss_Number`, `Date`, `Status`) VALUES
(1001, 'Covid', 1, '2021-09-15', 1),
(1001, 'Covid', 2, '2021-10-19', 1),
(1001, 'Covid', 3, '2022-04-03', 0),
(1001, 'Covid', 4, '2023-02-08', 0),
(1002, 'Covid', 1, '2021-08-15', 1),
(1002, 'Covid', 2, '2021-12-18', 1),
(1002, 'Covid', 3, '2022-05-25', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allergies_logs`
--
ALTER TABLE `allergies_logs`
  ADD PRIMARY KEY (`P_ID`,`Allergies`);

--
-- Indexes for table `appoinment`
--
ALTER TABLE `appoinment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `P_ID` (`P_ID`),
  ADD KEY `D_ID` (`D_ID`),
  ADD KEY `H_ID` (`H_ID`);

--
-- Indexes for table `awards_honors`
--
ALTER TABLE `awards_honors`
  ADD PRIMARY KEY (`D_ID`,`Award_Name`);

--
-- Indexes for table `consultation`
--
ALTER TABLE `consultation`
  ADD PRIMARY KEY (`D_ID`,`H_ID`,`Day`),
  ADD KEY `consultation_ibfk_2` (`H_ID`);

--
-- Indexes for table `degrees`
--
ALTER TABLE `degrees`
  ADD PRIMARY KEY (`D_ID`,`Degree`);

--
-- Indexes for table `diagnostic_center`
--
ALTER TABLE `diagnostic_center`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `License_Number` (`License_Number`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `License_Number` (`License_Number`);

--
-- Indexes for table `doctor_reviews`
--
ALTER TABLE `doctor_reviews`
  ADD PRIMARY KEY (`P_ID`,`D_ID`),
  ADD KEY `reviews_ibfk_2` (`D_ID`);

--
-- Indexes for table `hospital`
--
ALTER TABLE `hospital`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `License_Number` (`License_Number`);

--
-- Indexes for table `journals_publications`
--
ALTER TABLE `journals_publications`
  ADD PRIMARY KEY (`D_ID`,`Title`);

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `prescribed_in`
--
ALTER TABLE `prescribed_in`
  ADD PRIMARY KEY (`Pr_ID`,`M_ID`),
  ADD KEY `M_ID` (`M_ID`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `prescriptions_ibfk_1` (`D_ID`),
  ADD KEY `prescriptions_ibfk_2` (`P_ID`);

--
-- Indexes for table `speciality`
--
ALTER TABLE `speciality`
  ADD PRIMARY KEY (`D_ID`,`Field`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `test_log`
--
ALTER TABLE `test_log`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `test_ibfk_1` (`P_ID`),
  ADD KEY `test_ibfk_2` (`Prescription_ID`),
  ADD KEY `DC_ID` (`DC_ID`),
  ADD KEY `T_ID` (`T_ID`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Phone_Number` (`Phone_Number`),
  ADD UNIQUE KEY `User_Name` (`User_Name`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Photo` (`Photo`);

--
-- Indexes for table `vaccination_logs`
--
ALTER TABLE `vaccination_logs`
  ADD PRIMARY KEY (`P_ID`,`Vaccine_Name`,`Doss_Number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appoinment`
--
ALTER TABLE `appoinment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1002;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1012;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1003;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1045;

--
-- AUTO_INCREMENT for table `test_log`
--
ALTER TABLE `test_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1002;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1013;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `allergies_logs`
--
ALTER TABLE `allergies_logs`
  ADD CONSTRAINT `allergies_logs_ibfk_1` FOREIGN KEY (`P_ID`) REFERENCES `patient` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `appoinment`
--
ALTER TABLE `appoinment`
  ADD CONSTRAINT `appoinment_ibfk_1` FOREIGN KEY (`P_ID`) REFERENCES `patient` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appoinment_ibfk_2` FOREIGN KEY (`D_ID`) REFERENCES `doctor` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appoinment_ibfk_3` FOREIGN KEY (`H_ID`) REFERENCES `hospital` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `awards_honors`
--
ALTER TABLE `awards_honors`
  ADD CONSTRAINT `awards_honors_ibfk_1` FOREIGN KEY (`D_ID`) REFERENCES `doctor` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `consultation`
--
ALTER TABLE `consultation`
  ADD CONSTRAINT `consultation_ibfk_1` FOREIGN KEY (`D_ID`) REFERENCES `doctor` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `consultation_ibfk_2` FOREIGN KEY (`H_ID`) REFERENCES `hospital` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `degrees`
--
ALTER TABLE `degrees`
  ADD CONSTRAINT `degrees_ibfk_1` FOREIGN KEY (`D_ID`) REFERENCES `doctor` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `diagnostic_center`
--
ALTER TABLE `diagnostic_center`
  ADD CONSTRAINT `diagnostic_center_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `user_details` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `user_details` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_reviews`
--
ALTER TABLE `doctor_reviews`
  ADD CONSTRAINT `doctor_reviews_ibfk_1` FOREIGN KEY (`P_ID`) REFERENCES `patient` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `doctor_reviews_ibfk_2` FOREIGN KEY (`D_ID`) REFERENCES `doctor` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hospital`
--
ALTER TABLE `hospital`
  ADD CONSTRAINT `hospital_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `user_details` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `journals_publications`
--
ALTER TABLE `journals_publications`
  ADD CONSTRAINT `journals_publications_ibfk_1` FOREIGN KEY (`D_ID`) REFERENCES `doctor` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `user_details` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prescribed_in`
--
ALTER TABLE `prescribed_in`
  ADD CONSTRAINT `prescribed_in_ibfk_2` FOREIGN KEY (`M_ID`) REFERENCES `medicine` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `prescribed_in_ibfk_3` FOREIGN KEY (`Pr_ID`) REFERENCES `prescriptions` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`D_ID`) REFERENCES `doctor` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `prescriptions_ibfk_2` FOREIGN KEY (`P_ID`) REFERENCES `patient` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `speciality`
--
ALTER TABLE `speciality`
  ADD CONSTRAINT `speciality_ibfk_1` FOREIGN KEY (`D_ID`) REFERENCES `doctor` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `test_log`
--
ALTER TABLE `test_log`
  ADD CONSTRAINT `test_log_ibfk_1` FOREIGN KEY (`P_ID`) REFERENCES `patient` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `test_log_ibfk_3` FOREIGN KEY (`DC_ID`) REFERENCES `diagnostic_center` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `test_log_ibfk_4` FOREIGN KEY (`T_ID`) REFERENCES `test` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `test_log_ibfk_5` FOREIGN KEY (`Prescription_ID`) REFERENCES `prescriptions` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `vaccination_logs`
--
ALTER TABLE `vaccination_logs`
  ADD CONSTRAINT `vaccination_logs_ibfk_1` FOREIGN KEY (`P_ID`) REFERENCES `patient` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
