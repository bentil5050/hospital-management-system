-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2025 at 09:13 PM
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
-- Database: `hospital_database`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetPatientAppointments` (IN `patientID` INT)   BEGIN
    SELECT * FROM appointments WHERE patient_id = patientID;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_datetime` datetime NOT NULL,
  `purpose` text NOT NULL,
  `status` enum('Booked','Completed','Canceled') NOT NULL
) ;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `patient_id`, `doctor_id`, `appointment_datetime`, `purpose`, `status`) VALUES
(9, 1, 9, '2025-05-06 10:00:00', 'Routine Checkup', 'Canceled'),
(11, 2, 9, '2025-04-14 09:00:00', 'Follow-up', 'Completed'),
(12, 1, 9, '2025-04-16 13:00:00', 'Surgery Prep', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `auditlogs`
--

CREATE TABLE `auditlogs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_performed` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auditlogs`
--

INSERT INTO `auditlogs` (`log_id`, `user_id`, `action_performed`, `timestamp`) VALUES
(1, 1, 'Updated medical record', '2025-03-11 06:01:21'),
(2, 3, 'Booked an appointment', '2025-03-11 06:01:21'),
(3, 2, 'Changed payment status', '2025-03-11 06:01:21'),
(4, 4, 'Deleted an old record', '2025-03-11 06:01:21');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `specialization_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `availability_schedule` text NOT NULL,
  `contact_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `user_id`, `specialization_id`, `department_id`, `availability_schedule`, `contact_number`) VALUES
(9, 2, 1, 1, 'Mon-Fri: 9AM-5PM', '9876543210'),
(10, 5, 2, 2, 'Tue-Thu: 10AM-6PM', '9991112222'),
(11, 6, 3, 1, 'Mon-Wed: 8AM-4PM', '8882223333');

-- --------------------------------------------------------

--
-- Table structure for table `hospitaldepartments`
--

CREATE TABLE `hospitaldepartments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospitaldepartments`
--

INSERT INTO `hospitaldepartments` (`department_id`, `department_name`) VALUES
(1, 'Emergency'),
(3, 'General Medicine'),
(4, 'Pediatrics'),
(2, 'Surgery');

-- --------------------------------------------------------

--
-- Table structure for table `insurancecards`
--

CREATE TABLE `insurancecards` (
  `insurance_card_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `insurance_provider` varchar(100) NOT NULL,
  `insurance_card_number` varchar(50) NOT NULL,
  `insurance_expiry` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insurancecards`
--

INSERT INTO `insurancecards` (`insurance_card_id`, `patient_id`, `insurance_provider`, `insurance_card_number`, `insurance_expiry`) VALUES
(1, 1, 'BlueCross', 'BC123456789', '2026-12-31'),
(2, 3, 'UnitedHealth', 'UH987654321', '2027-08-15'),
(3, 2, 'Aetna', 'AET654321789', '2026-06-30'),
(4, 4, 'Cigna', 'CIG456123789', '2028-09-20');

-- --------------------------------------------------------

--
-- Table structure for table `medicalrecords`
--

CREATE TABLE `medicalrecords` (
  `record_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `diagnosis` text NOT NULL,
  `treatment_plan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicalrecords`
--

INSERT INTO `medicalrecords` (`record_id`, `patient_id`, `doctor_id`, `diagnosis`, `treatment_plan`) VALUES
(1, 1, 9, 'Routine Checkup', 'Continue exercise and balanced diet. Follow up in 6 months.'),
(3, 3, 9, 'Asthma', 'Use inhaler as needed and avoid allergens.'),
(5, 2, 9, 'Follow-up', 'Everything looks normal');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `insurance_provider` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `user_id`, `date_of_birth`, `gender`, `blood_type`, `insurance_provider`) VALUES
(1, 1, '1990-05-15', 'Male', 'O+', 'BlueCross'),
(2, 3, '1985-09-23', 'Male', 'A-', 'UnitedHealth'),
(3, 4, '1992-12-11', 'Female', 'B+', 'Aetna'),
(4, 2, '1998-03-29', 'Female', 'AB-', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('Pending','Completed','Failed') NOT NULL,
  `payment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `appointment_id`, `patient_id`, `amount`, `payment_status`, `payment_date`) VALUES
(1, 9, 1, 100.00, 'Completed', '2025-04-10'),
(3, 11, 2, 200.00, 'Failed', '2025-04-14');

--
-- Triggers `payments`
--
DELIMITER $$
CREATE TRIGGER `update_appointment_status` AFTER INSERT ON `payments` FOR EACH ROW BEGIN
    UPDATE appointments
    SET status = 'Completed'
    WHERE appointment_id = NEW.appointment_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `prescription_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `medicine_name` varchar(100) NOT NULL,
  `dosage` varchar(50) NOT NULL,
  `instructions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`prescription_id`, `record_id`, `medicine_name`, `dosage`, `instructions`) VALUES
(1, 1, 'Multivitamin', '1 tablet daily', 'Take with breakfast'),
(3, 3, 'Albuterol Inhaler', '2 puffs as needed', 'Use during asthma attack or before exercise');

-- --------------------------------------------------------

--
-- Table structure for table `specializations`
--

CREATE TABLE `specializations` (
  `specialization_id` int(11) NOT NULL,
  `specialization_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specializations`
--

INSERT INTO `specializations` (`specialization_id`, `specialization_name`) VALUES
(1, 'Cardiology'),
(2, 'Neurology'),
(3, 'Orthopedics'),
(4, 'Pediatrics');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Patient','Doctor','Admin') NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `full_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `role`, `email`, `phone_number`, `full_name`) VALUES
(1, 'johndoe', '$2y$10$rn5wo/RHMf1UbGe8fE8dZOzSYW.Q3EJSjzbbuaeTFycQGXd8fAi02', 'Patient', 'johndoe@example.com', '1234567890', 'John Doe'),
(2, 'janesmith', '$2y$10$mGXYH/NDziMxAnZiGlD47e/csBU8I7JlPloThj73VaG6fdRmCKA4O', 'Doctor', 'janesmith@yahoo.com', '9876543210', 'Jane Smith'),
(3, 'mikebrown', '$2y$10$dNNv7zxFu4WCn6AgQx0VzehYlhj4t5SjizbOMMbGejDoH3lnZMNTO', 'Patient', 'mikebrown@example.com', '4567891230', 'Mike Brown'),
(4, 'adminuser', '$2y$10$URCkkGMuNW6KmfaA3FaRTO3V5EuoAUu4JQrJ0eZXieZV5Sj.aRkEa', 'Admin', 'admin@example.com', '5555555555', 'Admin User'),
(5, 'drjohn', '$2y$10$Wtsm2tj0pV7mceviNl4QnOChjBKsyJSczf1GcksFocC.CQOtBhjE2', 'Doctor', 'drjohn@example.com', '9991112222', 'Dr. John Williams'),
(6, 'drmaria', '$2y$10$bYQzOFv0UjGyZEd3JqsvxOTvJYKIjE5YqxMiKb.hHeqqijS04LDyu', 'Doctor', 'drmaria@example.com', '8882223333', 'Dr. Maria Gomez');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `auditlogs`
--
ALTER TABLE `auditlogs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `contact_number` (`contact_number`),
  ADD KEY `specialization_id` (`specialization_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `hospitaldepartments`
--
ALTER TABLE `hospitaldepartments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_name` (`department_name`);

--
-- Indexes for table `insurancecards`
--
ALTER TABLE `insurancecards`
  ADD PRIMARY KEY (`insurance_card_id`),
  ADD UNIQUE KEY `insurance_card_number` (`insurance_card_number`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `medicalrecords`
--
ALTER TABLE `medicalrecords`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `appointment_id` (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`prescription_id`),
  ADD KEY `record_id` (`record_id`);

--
-- Indexes for table `specializations`
--
ALTER TABLE `specializations`
  ADD PRIMARY KEY (`specialization_id`),
  ADD UNIQUE KEY `specialization_name` (`specialization_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auditlogs`
--
ALTER TABLE `auditlogs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `hospitaldepartments`
--
ALTER TABLE `hospitaldepartments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `insurancecards`
--
ALTER TABLE `insurancecards`
  MODIFY `insurance_card_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `medicalrecords`
--
ALTER TABLE `medicalrecords`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `specializations`
--
ALTER TABLE `specializations`
  MODIFY `specialization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `auditlogs`
--
ALTER TABLE `auditlogs`
  ADD CONSTRAINT `auditlogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctors_ibfk_2` FOREIGN KEY (`specialization_id`) REFERENCES `specializations` (`specialization_id`),
  ADD CONSTRAINT `doctors_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `hospitaldepartments` (`department_id`);

--
-- Constraints for table `insurancecards`
--
ALTER TABLE `insurancecards`
  ADD CONSTRAINT `insurancecards_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `medicalrecords`
--
ALTER TABLE `medicalrecords`
  ADD CONSTRAINT `medicalrecords_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `medicalrecords_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `medicalrecords` (`record_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
