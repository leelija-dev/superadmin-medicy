-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2023 at 11:56 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medicy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(1) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `username` varchar(12) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(80) NOT NULL,
  `mobile_no` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `fname`, `lname`, `username`, `password`, `email`, `mobile_no`, `address`, `city`) VALUES
(11, 'DIPAK', 'MAJUMDAR', 'dipak', '$2y$10$PvCIIqoFJxDSdbxNHXpHQuNW3C9iDp6Y.1/ehMxtlO2wl2LeLxWDK', 'rahulmajumdar400@gmail.com', '7699753019', 'Rampur', 'Coochbehar'),
(12, 'Sahanaj', 'Khatun', 'sahanaj', '$2y$10$kb.GeIAeWjzcdwdlTpYqiOZ7gHq4qUBE6cJ9GMXG8YwERatrNtSK.', 'sahanajkhatun@gmail.com', '7699753019', 'Thanar More, Daulatabad, Murshidabad, Murshidabad, 742302', 'Murshidabad');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(10) NOT NULL,
  `appointment_id` varchar(16) NOT NULL,
  `patient_id` varchar(14) NOT NULL,
  `appointment_date` varchar(12) NOT NULL,
  `patient_name` varchar(30) NOT NULL,
  `patient_gurdian_name` varchar(30) NOT NULL,
  `patient_email` varchar(30) NOT NULL,
  `patient_phno` varchar(10) NOT NULL,
  `patient_dob` varchar(12) NOT NULL,
  `patient_weight` int(3) NOT NULL,
  `patient_gender` varchar(8) NOT NULL,
  `patient_addres1` varchar(255) NOT NULL,
  `patient_addres2` varchar(255) NOT NULL,
  `patient_ps` varchar(50) NOT NULL,
  `patient_dist` varchar(50) NOT NULL,
  `patient_pin` varchar(7) NOT NULL,
  `patient_state` varchar(50) NOT NULL,
  `doctor_id` varchar(6) NOT NULL,
  `patient_doc_shift` varchar(255) NOT NULL,
  `appointment_on` varchar(12) NOT NULL,
  `appointment_by` varchar(30) NOT NULL,
  `appointment_modified_on` varchar(12) NOT NULL,
  `appointment_modified_by` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `appointment_id`, `patient_id`, `appointment_date`, `patient_name`, `patient_gurdian_name`, `patient_email`, `patient_phno`, `patient_dob`, `patient_weight`, `patient_gender`, `patient_addres1`, `patient_addres2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`, `doctor_id`, `patient_doc_shift`, `appointment_on`, `appointment_by`, `appointment_modified_on`, `appointment_modified_by`) VALUES
(114, 'ME202202236461', 'PE563109629', '2022-02-23', 'Dipak Majumdar', 'No Gurdian', '', '4565465655', '24', 56, 'Male', 'Rampur', 'Rampur', 'Rampur', 'Rampur', '656565', 'West bengal', '4', '', '', '', '', ''),
(116, 'ME202204012312', 'PEA0000000012', '2022-04-01', 'Sumana', 'sumkana', 'rahulmajumdar400@gmail.com', '7854121545', '19', 45, 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '6', '', '', '', '', ''),
(117, 'ME202203314047', 'PEA0000000013', '2022-03-31', 'sukla sah', 'Rajat Das', 'rahulmajumdar400@gmail.com', '7854121545', '19', 45, 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '4', '', '', '', '', ''),
(118, 'ME202204017917', 'PEA0000000014', '2022-04-01', 'Samma sah', 'Rajat Das', 'rahulmajumdar400@gmail.com', '7854121545', '22', 45, 'Female', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '7', '', '', '', '', ''),
(119, 'ME202204018844', 'PEA0000000015', '2022-04-01', 'Sujay', 'Rajat Das', 'rahulmajumdar400@gmail.com', '7854121545', '22', 45, 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '5', '', '', '', '', ''),
(120, 'ME202203315246', 'PEA0000000016', '2022-03-31', 'SSS', 'subham sah', 'rahulmajumdar400@gmail.com', '7854121545', '22', 45, 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '8', '', '', '', '', ''),
(122, 'ME220331A000010', 'PEA0000000018', '2022-03-31', 'sukla sah', 'subham sah', 'rahulmajumdar400@gmail.com', '7854121545', '22', 45, 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'Other', '3', '', '', '', '', ''),
(123, 'ME220331A000009', 'PEA0000000019', '2022-03-31', 'sukla sah1', 'subham sah', 'rahulmajumdar400@gmail.com', '7854121545', '22', 45, 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '2', '', '', '', '', ''),
(124, 'ME010422A000010', 'PEA0000000020', '2022-04-01', 'sukla sah', 'subham sah', 'rahulmajumdar400@gmail.com', '7854121545', '22', 45, 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '5', '', '', '', '', ''),
(125, 'ME310322A000011', 'PEA0000000021', '31-03-2022', 'Subash Das', 'subham sah', 'rahulmajumdar400@gmail.com', '7854121545', '22', 45, 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '5', '', '', '', '', ''),
(129, 'ME310322A000012', 'PEA0000000023', '31-03-2022', 'Akash Gope', 'subham sah', 'rahulmajumdar400@gmail.com', '7854121545', '19', 45, 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '8', '', '', '', '', ''),
(130, 'ME060422A000002', 'PEA0000000330', '2022-04-06', 'Dipak Majumdar', 'Dipak Majumdar', '', '9878768767', '23', 65, 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '736207', 'West bengal', '5439', '', '', '', '', ''),
(131, 'ME202207099648', 'PE818984816', '2022-07-09', 'Dipak Majumdar', 'Dipak Majumdar', 'subhashishdas@gmail.com', '4565645656', '22', 45, 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '867867', 'West bengal', '6', '', '', '', '', ''),
(132, 'ME140722A000013', 'PEA0000000037', '2022-07-14', 'sukla sah', 'Rajat Das', 'rahulmajumdar400@gmail.com', '7854121545', '22', 45, 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '5', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `contact_details`
--

CREATE TABLE `contact_details` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(40) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `message` varchar(355) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contact_details`
--

INSERT INTO `contact_details` (`id`, `name`, `email`, `subject`, `message`) VALUES
(1, 'eff', '', 'gz', 'zdg<h1>This is headline.</h1>'),
(2, 'hhb', '', 'hiii', 'daf<h1>This is headline.</h1>'),
(3, 'DDD', '', 'send', 'DSD<h1>This is headline.</h1>'),
(4, 'DTTT', '', 'send mail', '120<h1>This is headline.</h1>'),
(5, 'ashim', '', 'ddc', 'wqd<h1>This is headline.</h1>'),
(6, 'ashim', '', 'ddc', 'wqd<h1>This is headline.</h1>'),
(7, 'name', '', 'subject', 'message<h1>This is headline.</h1>'),
(8, 'ashim', '', 'ashim2', 'ashim message<h1>This is headline.</h1>'),
(9, 'ASHIM GHOSH', '', 'ashim3', 'ashim4<h1>This is headline.</h1>'),
(10, 'Ashim Ghosh', '', 'Hi', 'Hello<h1>This is headline.</h1>'),
(11, '', '', '', '<h1>This is headline.</h1>'),
(12, 'Rahul Majumdar', '', 'Testing Purpose', 'This is a testing mail sending from Dipak Majumdar.<h1>This is headline.</h1>'),
(13, 'DIPAK MAJUMDAR', '', 'Nameserver Query', 'hi<h1>This is headline.</h1>'),
(14, 'Dipak Majumdar', '', 'No Subject', 'Hi How are you?<h1>This is headline.</h1>');

-- --------------------------------------------------------

--
-- Table structure for table `current_stock`
--

CREATE TABLE `current_stock` (
  `id` int(10) NOT NULL,
  `product_id` varchar(55) NOT NULL,
  `batch_no` varchar(14) NOT NULL,
  `exp_date` varchar(10) NOT NULL,
  `distributor_id` int(4) NOT NULL,
  `loosely_count` int(8) NOT NULL,
  `loosely_price` decimal(8,2) NOT NULL,
  `weightage` int(6) NOT NULL,
  `unit` varchar(24) NOT NULL,
  `qty` int(12) NOT NULL,
  `mrp` decimal(8,2) NOT NULL,
  `ptr` decimal(8,2) NOT NULL,
  `gst` int(3) NOT NULL,
  `added_by` varchar(155) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `current_stock`
--

INSERT INTO `current_stock` (`id`, `product_id`, `batch_no`, `exp_date`, `distributor_id`, `loosely_count`, `loosely_price`, `weightage`, `unit`, `qty`, `mrp`, `ptr`, `gst`, `added_by`, `added_on`) VALUES
(54, 'PR3780154777', 'CVN8A5', '12/29', 6, 240, '110.50', 8, 'cap', 30, '884.00', '700.00', 12, '', '2022-06-15 09:03:32'),
(55, 'PR6531139060', 'CVN8A4 ', '12/29', 6, 300, '3.00', 10, 'tab', 30, '30.00', '20.00', 5, '', '2022-06-15 09:23:07'),
(56, 'PR3248786091', 'CVN8A5', '09/27', 6, 200, '3.00', 10, 'tab', 20, '30.00', '20.00', 12, '', '2022-06-15 09:23:07');

-- --------------------------------------------------------

--
-- Table structure for table `distributor`
--

CREATE TABLE `distributor` (
  `id` int(4) NOT NULL,
  `name` varchar(155) NOT NULL,
  `address` varchar(255) NOT NULL,
  `area_pin_code` varchar(7) NOT NULL,
  `phno` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `dsc` varchar(355) NOT NULL,
  `added_by` varchar(155) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `distributor`
--

INSERT INTO `distributor` (`id`, `name`, `address`, `area_pin_code`, `phno`, `email`, `dsc`, `added_by`, `added_on`) VALUES
(2, 'Radheshyam', 'Kolkata New Town', '7767667', '7656564655', 'radheshyamtiwari@gmail.com', 'Hello World', '', '2022-01-19 17:11:55'),
(3, 'Subham Roy', 'Kolkata', '7867675', '9878347387', 'subhamroy@gmail.com', 'Hi', '', '2022-01-19 18:34:11'),
(6, 'Ram Kanai Enterprise', '55 BIPLABI RASH BEHARI ROAD MEHTA BUILDING 3RD FLOOR ROOM NO A37, Block F, Kolkata, West Bengal', '700001', '9830640030', '', 'MEDICINE WHOLESALER MEDICINE DISTRIBUTOR', '', '2022-06-15 06:21:26'),
(7, 'Super Pharma', 'JFJJ+VFV, Reckjoani Rd, Majarhati, Newtown, Kolkata, West Bengal', '700135', '9813214587', '', 'Super Pharma, (Wholesale Medicine Distributor) is located at Reckjoani Road, Majarhati, Newtown, Kolkata', '', '2022-06-15 06:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(6) NOT NULL,
  `doctor_reg_no` varchar(12) NOT NULL,
  `doctor_name` varchar(50) NOT NULL,
  `doctor_specialization` int(6) NOT NULL,
  `doctor_degree` varchar(50) NOT NULL,
  `also_with` varchar(100) NOT NULL,
  `doctor_address` varchar(255) NOT NULL,
  `doctor_email` varchar(30) NOT NULL,
  `doctor_phno` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `doctor_reg_no`, `doctor_name`, `doctor_specialization`, `doctor_degree`, `also_with`, `doctor_address`, `doctor_email`, `doctor_phno`) VALUES
(1, '298F40W', 'Dr. Shantu Roy', 1, 'MBBS, BS, MD', 'Nabatara NGO', '375, PK Guha Rd, Arabinda Sarani, Rajbari, Dum Dum, Kolkata, West Bengal 700028', 'dr.sahnturoy@apollo.com', '8312304518'),
(2, 'SS0956NGL766', 'Dr. Ajay Ghosh', 2, 'MBBS,MD/DNB', 'Kolkata Child Foundation', 'Sahayog A/7 Dadabhai X Road No 2 Andheri ), Mumbai,Kolkata,400058,India', 'ajayghosh@gmail.com', '6595654563'),
(3, 'SS0956NGL766', 'Dr. Ashidh Roy', 3, 'MBBS,MD/DNB', 'Kolkata Child Foundation', 'Sahayog A/7 Dadabhai X Road No 2 Andheri ), Mumbai,Kolkata,400058,India', 'ranjanjana@gmail.com', '9932896754'),
(4, 'SS0956NGL766', 'Dr. Ranjan Jana', 4, 'MBBS,MD/DNB', 'Kolkata Child Foundation', 'Sahayog A/7 Dadabhai X Road No 2 Andheri ), Mumbai,Kolkata,400058,India', 'ranjanjana@gmail.com', '9932896754'),
(5, 'SS0956NGL766', 'Dr. Suman Roy', 4, 'MBBS,MD/DNB', 'Kolkata Child Foundation', 'Sahayog A/7 Dadabhai X Road No 2 Andheri ), Mumbai,Kolkata,400058,India', 'ranjanjana@gmail.com', '9932896754'),
(6, 'SS0956NGL766', 'Dr. Pooja Mondal', 7, 'MBBS,MD/DNB', 'Kolkata Child Foundation', 'Sahayog A/7 Dadabhai X Road No 2 Andheri ), Mumbai,Kolkata,400058,India', 'poojamondal@gmail.com', '9932896754'),
(7, 'SS0956NGL766', 'Dr. Ranjan Dey', 7, 'MBBS,MD/DNB', 'Kolkata Child Foundation', 'Sahayog A/7 Dadabhai X Road No 2 Andheri ), Mumbai,Kolkata,400058,India', 'ranjanjana@gmail.com', '9932896754'),
(8, 'SS0956NGL766', 'Dr. Debashish Jana', 7, 'MBBS,MD/DNB', 'Kolkata Child Foundation', 'Sahayog A/7 Dadabhai X Road No 2 Andheri ), Mumbai,Kolkata,400058,India', 'ranjanjana@gmail.com', '9932896754');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_category`
--

CREATE TABLE `doctor_category` (
  `doctor_category_id` int(6) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_descreption` varchar(500) NOT NULL,
  `doctor_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_category`
--

INSERT INTO `doctor_category` (`doctor_category_id`, `category_name`, `category_descreption`, `doctor_id`) VALUES
(1, 'Dermatology', 'Dermatology is a branch of study in medicine that deals with the problems of skin, scalp, hair and nails. In an age, when the level of pollution is escalating every day, many people are suffering from skin, scalp and hair related problems and disorders. Hence, the demand of the dermatologists is on the rise.', ''),
(2, 'Paediatrics', 'Paediatrics also spelled pediatrics or pædiatrics) is the branch of medicine that involves the medical care of infants, children, and adolescents. The American Academy of Pediatrics recommends people seek pediatric care through the age of 21. In the United Kingdom, paediatrics covers patients until age 18.', ''),
(3, 'Psychiatry', 'Psychiatry refers to a field of medicine focused specifically on the mind, aiming to study, prevent, and treat mental disorders in humans. It has been described as an intermediary between the world from a social context and the world from the perspective of those who are mentally ill.', ''),
(4, 'Urologist', 'Examining, diagnosing, and treating patient conditions and disorders of the genitourinary organs and tracts.Documenting and reviewing patients histories. Ordering, performing, and interpreting diagnostic tests. Using specialized equipment, such as X-rays, fluoroscopes, and catheters.', ''),
(5, 'Hematologists Hematologists', 'Receiving and preparing blood samples for analysis. Analysing blood samples using computer-aided and manual techniques. Reviewing initial data that reveals, for example, white or red blood cell abnormalities. Making decisions on further haematological analysis; Liaising with other medical professionals to discuss patient treatment plans;', ''),
(6, 'Internists', 'These primary-care doctors treat both common and complex illnesses, usually only in adults. You’ll likely visit them or your family doctor first for any condition. Internists often have advanced training in a host of subspecialties, like heart disease, cancer, or adolescent or sleep medicine.', ''),
(7, 'Physician', 'Physician responsibilities include:\r\nJob brief. We are looking for a responsible Physician to provide high quality medical care by examining patients and treating diseases. Responsibilities. Examine and provide treatments to injuries and refer patients to other physicians when needed (ophthalmologists, orthopedists, neurologists etc.)', ''),
(8, 'Neurologists', 'These are specialists in the nervous system, which includes the brain, spinal cord, and nerves. They treat strokes, brain and spinal tumors, epilepsy, Parkinson\'s disease, and Alzheimer\'s disease.', ''),
(9, 'Ophthalmologists', 'You call them eye doctors. They can prescribe glasses or contact lenses and diagnose and treat diseases like glaucoma. Unlike optometrists, they’re medical doctors who can treat every kind of eye condition as well as operate on the eyes.', ''),
(10, 'Pathologists', 'These lab doctors identify the causes of diseases by examining body tissues and fluids under microscopes.', '');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_timing`
--

CREATE TABLE `doctor_timing` (
  `doc_timing_id` int(6) NOT NULL,
  `doctor_id` int(6) NOT NULL,
  `days` varchar(25) NOT NULL,
  `shift` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_timing`
--

INSERT INTO `doctor_timing` (`doc_timing_id`, `doctor_id`, `days`, `shift`) VALUES
(1, 1, 'Monday', 'Morning'),
(2, 1, 'Wednesday', 'Evening'),
(3, 2, 'Satarday', 'Full Day'),
(4, 2, 'Sunday', 'Morning - Evening'),
(5, 3, 'Tuesday', 'Morning'),
(6, 3, 'Thrusday', 'Evening'),
(7, 4, 'Satarday', 'Full Day'),
(8, 4, 'Wednesday', 'Evening'),
(9, 5, 'Satarday', 'Full Day'),
(10, 5, 'Sunday', 'Morning - Evening'),
(11, 6, 'Monday', 'Morning'),
(12, 6, 'Tuesday', 'Morning'),
(13, 7, 'Thrusday', 'Evening'),
(14, 7, 'Monday', 'Evening'),
(15, 8, 'Tuesday', 'Morning'),
(16, 8, 'Wednesday', 'Morning'),
(17, 9, 'Thrusday', 'Morning-Evening'),
(22, 9, 'Tuesday', 'Morning'),
(23, 10, 'Wednesday', 'Morning'),
(24, 10, 'Thursday', 'Morning');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(6) NOT NULL,
  `employee_username` varchar(12) NOT NULL,
  `employee_name` varchar(30) NOT NULL,
  `emp_role` varchar(100) NOT NULL,
  `emp_email` varchar(100) NOT NULL,
  `emp_address` varchar(255) NOT NULL,
  `employee_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_username`, `employee_name`, `emp_role`, `emp_email`, `emp_address`, `employee_password`) VALUES
(12, 'subhankar', 'Subhankar Roy', 'Pharmacist', 'subhankarroy@gmail.com', 'Kolkata', '$2y$10$jRLXRCuQi2nLlGDv8dRxaeBCuuwUWhD3s/eC5BShVcHOOB2H56ntm'),
(13, 'jayshree', 'Jayshree Roy', 'Receptionist', 'jayshree@gmail.com', 'Kolkata', '$2y$10$ebTKccaN2IhIlu2VC3PaFuUWNZFPWV62tGTpNNsdTaO.3OeJ3N3zy');

-- --------------------------------------------------------

--
-- Table structure for table `gst`
--

CREATE TABLE `gst` (
  `id` int(4) NOT NULL,
  `percentage` varchar(2) NOT NULL,
  `added_by` varchar(12) NOT NULL,
  `added_on` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hospital_info`
--

CREATE TABLE `hospital_info` (
  `hospital_id` varchar(12) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `hospital_name` varchar(150) NOT NULL,
  `address_1` varchar(50) NOT NULL,
  `address_2` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `dist` varchar(50) NOT NULL,
  `pin` varchar(7) NOT NULL,
  `health_care_state` varchar(50) NOT NULL,
  `hospital_email` varchar(50) NOT NULL,
  `hospital_phno` varchar(10) NOT NULL,
  `appointment_help_line` varchar(10) NOT NULL,
  `main_desc` varchar(400) NOT NULL,
  `footer_desc` varchar(150) NOT NULL,
  `book_appointment_text` varchar(255) NOT NULL,
  `subscribe_text` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital_info`
--

INSERT INTO `hospital_info` (`hospital_id`, `logo`, `hospital_name`, `address_1`, `address_2`, `city`, `dist`, `pin`, `health_care_state`, `hospital_email`, `hospital_phno`, `appointment_help_line`, `main_desc`, `footer_desc`, `book_appointment_text`, `subscribe_text`) VALUES
('555555', 'img/logo.png', 'Medicy Health Care', 'Thanar More', 'Daulatabad', 'Murshidabad', 'Murshidabad', '742302', 'West Bengal', 'medicyhealthcare@gmail.com', '7384091895', '8695494415', 'Excellence of Medicy Health Care is recognized faster than ever thought across West Bengal as well as India. Caring nature and a supportive attitude is adding strength further as Best health care services. Without making any compromise to the quality, Medicy Health Care provides high quality service. We keep you at ease with convenience and confidence.', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ipsam soluta tempora enim exercitationem labore incidunt. Rem porro omnis iure cum blanditii', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum  Lorem Ip has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting indus');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` bigint(30) NOT NULL,
  `customer_id` varchar(20) NOT NULL,
  `total_amount` int(20) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(155) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_appointments`
--

CREATE TABLE `lab_appointments` (
  `id` int(12) NOT NULL,
  `bill_id` varchar(30) NOT NULL,
  `patient_id` varchar(14) NOT NULL,
  `prefered_doctor_id` varchar(60) NOT NULL,
  `test_ids` varchar(255) NOT NULL,
  `prices` varchar(355) NOT NULL,
  `discount` varchar(355) NOT NULL,
  `after_discount` varchar(355) NOT NULL,
  `total_amount` varchar(10) NOT NULL,
  `cgst` varchar(12) NOT NULL,
  `sgst` varchar(12) NOT NULL,
  `paid_amount` varchar(12) NOT NULL,
  `test_date` varchar(12) NOT NULL,
  `added_by` varchar(12) NOT NULL,
  `added_on` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_appointments`
--

INSERT INTO `lab_appointments` (`id`, `bill_id`, `patient_id`, `prefered_doctor_id`, `test_ids`, `prices`, `discount`, `after_discount`, `total_amount`, `cgst`, `sgst`, `paid_amount`, `test_date`, `added_by`, `added_on`) VALUES
(90, '01', 'PE317395727', '3', '18,19', '160, 180', '0, 9', '160, 163.8', '323.8', '', '', '', '2022-02-25', '', '14-02-2022'),
(91, '02', 'PE317395727', '3', '18,19', '160, 180', '0, 9', '160, 163.8', '323.8', '', '', '', '2022-02-25', '', '14-02-2022'),
(92, '03', 'PE317395727', '3', '18,19', '160, 180', '0, 9', '160, 163.8', '323.8', '', '', '', '2022-02-25', '', '14-02-2022'),
(93, '04', 'PE317395727', '3', '18,19', '160, 180', '0, 9', '160, 163.8', '323.8', '', '', '', '2022-02-25', '', '14-02-2022'),
(94, '05', 'PE216948882', '2', '20', '450', '0', '450', '450', '', '', '', '2022-02-16', '', '14-02-2022'),
(95, '06', 'PE216948882', '2', '20', '450', '0', '450', '450', '22.', '22.', '495', '2022-02-16', '', '14-02-2022'),
(96, '07', 'PE216948882', '2', '20', '450', '0', '450', '450', '22.', '22.', '495', '2022-02-16', '', '14-02-2022'),
(97, '08', 'PE367336319', '1', '17,6', '80, 400', '0, 19', '80, 324', '404', '20.', '20.', '444.4', '2022-02-15', '', '14-02-2022'),
(98, '09', 'PE260986546', '1', '11,8', '700, 350', '0, 4', '700, 336', '1036', '51.', '51.', '1139.6', '2022-02-15', '', '14-02-2022'),
(99, '10', 'PE725663040', '1', '20', '450', '0', '450', '450', '22.5', '22.5', '495', '2022-02-26', '', '14-02-2022'),
(100, '11', 'PE389880987', '2', '14', '600', '0', '600', '600', '0', '0', '0', '2022-02-19', '', '15-02-2022'),
(101, '12', 'PE389880987', '3', '11, 8', '700, 350', '0, 0', '700, 350', '1050', '0', '0', '0', '2022-02-16', '', '15-02-2022'),
(102, '13', 'PE389880987', '5', '13, 16', '100, 500', '0, 10', '100, 450', '550', '0', '0', '0', '2022-02-16', '', '15-02-2022'),
(103, '14', 'PE389880987', '5', '13, 16', '100, 500', '0, 10', '100, 450', '550', '0', '0', '0', '2022-02-16', '', '15-02-2022'),
(104, '15', 'PE725663040', 'Dr. Dipak Majumdar', '8', '350', '0', '350', '350', '0', '0', '0', '2022-02-24', '', '15-02-2022'),
(105, '16', 'PE725663040', 'Dr. Ashish Saha', '17, 7', '80, 100', '0, 9', '80, 91', '171', '0', '0', '0', '2022-02-17', '', '15-02-2022'),
(106, '17', 'PE725663040', 'Dr. aaa', '15, 13, 17, 12', '200, 100, 80, 650', '0, 0, 3, 0', '200, 100, 77.6, 650', '1027.6', '0', '0', '0', '2022-02-18', '', '16-02-2022'),
(107, '18', 'PE725663040', 'Dr. aaa', '15, 13, 17, 12', '200, 100, 80, 650', '0, 0, 3, 0', '200, 100, 77.6, 650', '1027.6', '0', '0', '0', '2022-02-18', '', '16-02-2022'),
(108, '19', 'PE725663040', '4', '18, 14', '160, 600', '0, 0', '160, 600', '760', '0', '0', '750', '2022-02-18', '', '16-02-2022'),
(109, '20', 'PE725663040', 'Dr. Suman Das', '18, 13, 19', '160, 100, 180', '0, 9, 12', '160, 91, 158.4', '409.4', '0', '0', '400', '2022-02-18', '', '16-02-2022'),
(110, '21', 'PE725663040', 'Dr. Suman Das', '18, 13, 19', '160, 100, 180', '0, 9, 12', '160, 91, 158.4', '409.4', '0', '0', '400', '2022-02-18', '', '16-02-2022'),
(111, '22', 'PE725663040', '2', '15', '200', '0', '200', '200', '0', '0', '200', '2022-02-24', '', '16-02-2022'),
(112, '23', 'PE725663040', '3', '16', '500', '10', '450', '450', '0', '0', '450', '2022-02-18', '', '17-02-2022');

-- --------------------------------------------------------

--
-- Table structure for table `lab_billing`
--

CREATE TABLE `lab_billing` (
  `bill_id` int(24) NOT NULL,
  `bill_date` varchar(24) NOT NULL DEFAULT current_timestamp(),
  `patient_id` varchar(14) NOT NULL,
  `refered_doctor` varchar(55) NOT NULL,
  `test_date` date NOT NULL,
  `total_amount` varchar(8) NOT NULL,
  `discount` varchar(6) NOT NULL,
  `total_after_discount` varchar(8) NOT NULL,
  `cgst` varchar(3) NOT NULL,
  `sgst` varchar(3) NOT NULL,
  `paid_amount` varchar(8) NOT NULL,
  `due_amount` varchar(8) NOT NULL,
  `status` varchar(12) NOT NULL,
  `added_by` varchar(24) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_billing`
--

INSERT INTO `lab_billing` (`bill_id`, `bill_date`, `patient_id`, `refered_doctor`, `test_date`, `total_amount`, `discount`, `total_after_discount`, `cgst`, `sgst`, `paid_amount`, `due_amount`, `status`, `added_by`, `added_on`) VALUES
(1, '24-03-2022 :: 15:55:38', 'PE725663040', 'Self', '2022-03-25', '200', '0', '200', '0', '0', '200', '00', 'Completed', '', '2022-03-24 06:59:40'),
(2, '24-03-2022 :: 15:33:55', 'PE260986546', '1', '2022-03-25', '1250', '50', '1200', '0', '0', '500', '700', 'Cancelled', '', '2022-03-24 09:47:55'),
(3, '24-03-2022 :: 16:04:30', 'PE153515245', '2', '2022-03-30', '160', '0', '160', '0', '0', '00', '160', 'Cancelled', '', '2022-03-24 09:53:17'),
(4, '24-03-2022 :: 17:23:31', 'PE146583635', '3', '2022-03-25', '3530', '30', '3500', '0', '0', '3500', '00', 'Cancelled', '', '2022-03-24 11:53:31'),
(5, '20-04-2022 :: 17:12:31', 'PE559923458', 'Self', '2022-04-28', '600', '00', '600', '0', '0', '600', '00', 'Completed', '', '2022-04-20 11:42:31'),
(6, '06-07-2022 :: 15:05:11', 'PE559923458', 'Self', '2022-07-09', '450', '00', '450', '0', '0', '450', '00', 'Completed', '', '2022-07-06 09:35:11'),
(7, '06-07-2022 :: 15:05:19', 'PE559923458', 'Self', '2022-07-09', '450', '00', '450', '0', '0', '450', '00', 'Completed', '', '2022-07-06 09:35:19');

-- --------------------------------------------------------

--
-- Table structure for table `lab_billing_details`
--

CREATE TABLE `lab_billing_details` (
  `id` int(24) NOT NULL,
  `bill_id` int(24) NOT NULL,
  `billing_date` varchar(24) NOT NULL,
  `test_date` date NOT NULL,
  `test_id` varchar(12) NOT NULL,
  `test_price` varchar(8) NOT NULL,
  `percentage_of_discount_on_test` varchar(6) NOT NULL,
  `price_after_discount` varchar(8) NOT NULL,
  `added_by` varchar(55) NOT NULL,
  `added_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_billing_details`
--

INSERT INTO `lab_billing_details` (`id`, `bill_id`, `billing_date`, `test_date`, `test_id`, `test_price`, `percentage_of_discount_on_test`, `price_after_discount`, `added_by`, `added_on`) VALUES
(206, 2, '24-03-2022 :: 15:33:55', '2022-03-25', '20', '450', '0', '450', '', '2022-03-24 15:33:55'),
(207, 2, '24-03-2022 :: 15:33:55', '2022-03-25', '11', '700', '0', '700', '', '2022-03-24 15:33:55'),
(208, 2, '24-03-2022 :: 15:33:55', '2022-03-25', '13', '100', '0', '100', '', '2022-03-24 15:33:55'),
(212, 3, '24-03-2022 :: 16:04:30', '2022-03-30', '18', '160', '0', '160', '', '2022-03-24 16:04:30'),
(247, 4, '24-03-2022 :: 17:23:31', '2022-03-25', '21', '50', '0', '50', '', '2022-03-29 16:05:07'),
(248, 4, '24-03-2022 :: 17:23:31', '2022-03-25', '16', '500', '0', '500', '', '2022-03-29 16:05:07'),
(249, 4, '24-03-2022 :: 17:23:31', '2022-03-25', '9', '100', '10', '90', '', '2022-03-29 16:05:07'),
(250, 4, '24-03-2022 :: 17:23:31', '2022-03-25', '39', '100', '10', '90', '', '2022-03-29 16:05:07'),
(251, 4, '24-03-2022 :: 17:23:31', '2022-03-25', '53', '400', '0', '400', '', '2022-03-29 16:05:07'),
(252, 4, '24-03-2022 :: 17:23:31', '2022-03-25', '68', '600', '0', '600', '', '2022-03-29 16:05:07'),
(253, 4, '24-03-2022 :: 17:23:31', '2022-03-25', '84', '500', '0', '500', '', '2022-03-29 16:05:07'),
(254, 4, '24-03-2022 :: 17:23:31', '2022-03-25', '88', '1300', '0', '1300', '', '2022-03-29 16:05:07'),
(259, 1, '24-03-2022 :: 15:55:38', '2022-03-25', '30', '150', '0', '150', '', '2022-03-24 12:29:40'),
(260, 1, '24-03-2022 :: 15:55:38', '2022-03-25', '21', '50', '0', '50', '', '2022-03-24 12:29:40'),
(261, 5, '20-04-2022 :: 17:12:31', '2022-04-28', '14', '600', '0', '600', '', '2022-04-20 17:12:31'),
(262, 6, '06-07-2022 :: 15:05:11', '2022-07-09', '20', '450', '0', '450', '', '2022-07-06 15:05:11'),
(263, 7, '06-07-2022 :: 15:05:19', '2022-07-09', '20', '450', '0', '450', '', '2022-07-06 15:05:19');

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer`
--

CREATE TABLE `manufacturer` (
  `id` int(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dsc` varchar(400) NOT NULL,
  `added_by` varchar(155) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manufacturer`
--

INSERT INTO `manufacturer` (`id`, `name`, `dsc`, `added_by`, `added_on`) VALUES
(18, 'Farmson Pharmaceutical Gujarat Private Limited', 'Plot No: 28-35, 40/1&2, B1/38, Gidc Industrial Estate, Nandesari, Vadodara, Gujarat 391340', '', '2022-03-31 10:15:00'),
(20, 'Turtle Pharma Private Limited', 'B307, Kemp Plaza, Mind Space, Malad (West), Mumbai, Maharashtra 400064', '', '2022-03-31 10:21:16'),
(21, 'Turtle Pharma Private Limited', 'B307, Kemp Plaza, Mind Space, Malad (West), Mumbai, Maharashtra 400064', '', '2022-03-31 10:22:24'),
(22, 'Turtle Pharma Private Limited', 'B307, Kemp Plaza, Mind Space, Malad (West), Mumbai, Maharashtra 400064', '', '2022-03-31 10:24:28'),
(24, 'S.K. Pharmaceuticals', 'S.K. Pharmaceuticals', '', '2022-04-01 06:06:41'),
(31, 'S.K. Pharmaceuticals', '', '', '2022-04-01 06:22:02'),
(32, 'Ipca Laboratories Ltd', 'Ipca Laboratories is a leading pharmaceutical company across the continents that work on its core values of quality, safety, integrity, dignity, responsibility.', '', '2022-04-25 10:21:56'),
(35, 'Dr Reddy&#39s Laboratories Ltd	', 'Dr. Reddy&#39s Laboratories, a leading multinational pharmaceutical company based in India and overseas, committed to providing affordable and innovative.	', '', '2022-04-26 05:53:50'),
(37, 'Alkem Laboratories Ltd.', 'With an illustrious journey which stands at striking distance of completing 5 decades, Alkem Laboratories commands the status of one of world&#39s leading', '', '2022-04-26 07:28:10'),
(38, 'Novartis India Ltd', 'Novartis is a global healthcare company based in Switzerland that provides solutions to address the evolving needs of patients worldwide.', '', '2022-05-05 06:47:40'),
(39, 'Allenge India', 'Allenge India (Pharmaceutical Company) (Allenge India Pharma) is india based ISO certified pharmaceutical marketing company with focus on making available quality pharmaceutical products at affordable costs to all.', '', '2022-06-15 06:19:40'),
(40, 'Glaxo Smith Kline', 'GlaxoSmithKline (GSK)&#39s 42-year-old brand Eno (the brand was born in 1850 but launched in India in 1972), the leader in the Rs 750-crore antacid market in India, is looking to connect with the youth.', '', '2022-06-15 06:37:14');

-- --------------------------------------------------------

--
-- Table structure for table `measure_of_unit`
--

CREATE TABLE `measure_of_unit` (
  `id` int(11) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  `full_name` varchar(30) NOT NULL,
  `added_by` varchar(25) NOT NULL,
  `added_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `measure_of_unit`
--

INSERT INTO `measure_of_unit` (`id`, `short_name`, `full_name`, `added_by`, `added_on`) VALUES
(1, 'Cartridge', 'Cartridge', '', '2022-04-05 10:50:55'),
(2, 'tab', 'Tablet', '', '2022-04-05 10:52:54'),
(3, 'cap', 'Capsules', '', '2022-04-05 10:53:08'),
(4, 'gm', 'Gram', '', '2022-04-05 10:53:31'),
(5, 'ml', 'Milliliter', '', '2022-04-05 10:54:05'),
(6, 'pc', 'Piece', '', '2022-04-05 10:54:25'),
(7, 'kg', 'Kilogram', '', '2022-04-05 10:54:36'),
(8, 'ltr', 'Litre', '', '2022-04-05 10:55:03'),
(9, 'mg', 'Miligram', '', '2022-04-05 10:57:41');

-- --------------------------------------------------------

--
-- Table structure for table `packaging_unit`
--

CREATE TABLE `packaging_unit` (
  `id` int(11) NOT NULL,
  `unit_name` varchar(10) NOT NULL,
  `added_by` varchar(25) NOT NULL,
  `added_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packaging_unit`
--

INSERT INTO `packaging_unit` (`id`, `unit_name`, `added_by`, `added_on`) VALUES
(1, 'Strip', ' ', '2022-04-05 10:41:39'),
(2, 'Bottle', ' ', '2022-04-05 10:42:15'),
(3, 'Tube', ' ', '2022-04-05 10:42:24'),
(4, 'Box', ' ', '2022-04-05 10:42:32'),
(5, 'Sachet', ' ', '2022-04-05 10:42:50'),
(6, 'Packet', ' ', '2022-04-05 10:42:57'),
(7, 'Jar', ' ', '2022-04-05 10:43:02'),
(8, 'Kit', ' ', '2022-04-05 10:43:06'),
(9, 'Bag', ' ', '2022-04-05 10:43:12'),
(10, 'Vial', ' ', '2022-04-05 10:43:17'),
(11, 'Ampoule', ' ', '2022-04-05 10:43:28'),
(12, 'Respoules', ' ', '2022-04-05 10:43:51'),
(13, 'Cartridge', ' ', '2022-04-05 10:44:11'),
(14, 'Pouch', ' ', '2022-06-15 12:06:03');

-- --------------------------------------------------------

--
-- Table structure for table `patient_details`
--

CREATE TABLE `patient_details` (
  `id` int(10) NOT NULL,
  `patient_id` varchar(20) NOT NULL,
  `name` varchar(80) NOT NULL,
  `gurdian_name` varchar(80) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phno` varchar(10) NOT NULL,
  `age` varchar(12) NOT NULL,
  `gender` varchar(8) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) NOT NULL,
  `patient_ps` varchar(50) NOT NULL,
  `patient_dist` varchar(50) NOT NULL,
  `patient_pin` varchar(7) NOT NULL,
  `patient_state` varchar(50) NOT NULL,
  `visited` varchar(10) NOT NULL,
  `lab_visited` varchar(10) NOT NULL,
  `added_on` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_details`
--

INSERT INTO `patient_details` (`id`, `patient_id`, `name`, `gurdian_name`, `email`, `phno`, `age`, `gender`, `address_1`, `address_2`, `patient_ps`, `patient_dist`, `patient_pin`, `patient_state`, `visited`, `lab_visited`, `added_on`, `added_by`) VALUES
(205, 'PE725663040', 'Dipak Majumdar', 'Dipak Majumdar', 'subhashishdas@gmail.com', '4565645656', '44', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '867867', 'West bengal', '1', '112', '2022-02-12 17:23:53', ''),
(214, 'PE559923458', 'Dipak Majumdar', 'Dipak Majumdar', 'subhashishdas@gmail.com', '4565645656', '44', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '867867', 'West bengal', '1', '12', '2022-02-12 19:29:15', ''),
(218, 'PE643890410', 'Suman Ghosh', 'Ashish Ghosh', '', '9887876756', '21', 'Male', 'Rampur', 'Rampur', 'Rampur', 'Rampur', '732098', 'West bengal', '1', '15', '2022-02-13 21:54:38', ''),
(227, 'PE260986546', 'Suraj Babu', 'Sannya babu', '', '7676565656', '23', 'Male', 'Salbari', 'Salbari', 'Salbari', 'Salbari', '736207', 'West bengal', '', '10', '2022-02-14 14:56:28', ''),
(229, 'PE566979735', 'Akshay Kumar', 'Subhash Kumar', '', '7676656454', '35', 'Male', 'Mumbai', 'Mumbai', 'Mumbai', 'Mumbai', '600076', 'Other', '', '12', '2022-02-19 11:50:50', ''),
(230, 'PE153515245', 'Akshay Kumar', 'Subhash Kumar', '', '7676656454', '35', 'Male', 'Mumbai', 'Mumbai', 'Mumbai', 'Mumbai', '600076', 'Other', '', '7', '2022-02-19 11:53:22', ''),
(231, 'PE126938053', 'AAAAA', 'No Gurdian', 'radheshyamtiwari@gmail.com', '292734616', '34', 'Male', 'Kolkata New Town', 'AAAAAA', 'AAAAA', 'AAAAAA', '6756566', 'West bengal', '', '', '2022-02-22 17:56:32', ''),
(232, 'PE363044856', 'AAAAA', 'No Gurdian', 'radheshyamtiwari@gmail.com', '292734616', '34', 'Male', 'Kolkata New Town', 'AAAAAA', 'AAAAA', 'AAAAAA', '6756566', 'West bengal', '', '', '2022-02-22 18:12:55', ''),
(233, 'PE146583635', 'Atul Roy', 'Atul Roy', 'rahulmajumdar400@gmail.com', '7665654545', '34', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '867867', 'West bengal', '', '6', '2022-03-02 12:37:25', ''),
(234, 'PE118330952', 'sukla sah', 'subham sah', '', '7854121545', '22', 'Female', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '', '1', '2022-03-09 12:28:42', ''),
(235, 'PEA0000000011', 'Rony Das', 'Rajat Das', '', '7545454514', '19', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', '', '1', '', '2022-03-30 16:30:13', ''),
(236, 'PEA0000000012', 'Sumana', 'sumkana', 'rahulmajumdar400@gmail.com', '7854121545', '19', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '1', '', '2022-03-30 16:33:47', ''),
(237, 'PEA0000000013', 'sukla sah', 'Rajat Das', 'rahulmajumdar400@gmail.com', '7854121545', '19', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '1', '', '2022-03-30 18:10:43', ''),
(238, 'PEA0000000014', 'Samma sah', 'Rajat Das', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Female', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '1', '', '2022-03-31 12:20:20', ''),
(239, 'PEA0000000015', 'Sujay', 'Rajat Das', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '1', '', '2022-03-31 12:22:24', ''),
(240, 'PEA0000000016', 'SSS', 'subham sah', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '1', '', '2022-03-31 12:32:13', ''),
(241, 'PEA0000000017', 'sd', 'Rajat Das', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '1', '', '2022-03-31 12:33:49', ''),
(242, 'PEA0000000018', 'sukla sah', 'subham sah', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'Other', '1', '', '2022-03-31 12:42:27', ''),
(243, 'PEA0000000019', 'sukla sah1', 'subham sah', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '1', '', '2022-03-31 12:45:04', ''),
(244, 'PEA0000000020', 'sukla sah', 'subham sah', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '1', '', '2022-03-31 13:08:23', ''),
(245, 'PEA0000000021', 'Subash Das', 'subham sah', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '1', '', '2022-03-31 13:11:57', ''),
(246, 'PEA0000000022', 'Dipak Majumdar', 'Rajat Das', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '1', '', '2022-03-31 14:10:58', ''),
(247, 'PEA0000000023', 'Akash Gope', 'subham sah', 'rahulmajumdar400@gmail.com', '7854121545', '19', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '1', '', '2022-03-31 14:12:23', ''),
(248, 'PEA0000000024', 'dsfgbd', 'fgnbf', 'rahulmajumdar400@gmail.com', '7854121545', '22', '', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '0', '', '2022-04-18 11:32:53', ''),
(249, 'sdgb', 'dsfgbd', 'fgnbf', 'rahulmajumdar400@gmail.com', '7854121545', '22', '', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '0', '', '2022-04-18 11:36:00', ''),
(250, 'sdgb', 'dsfgbd', 'fgnbf', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '0', '', '2022-04-18 11:36:31', ''),
(251, 'sdgb', 'dsfgbd', 'fgnbf', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '0', '', '2022-04-18 11:37:23', ''),
(252, 'sdgb', 'dsfgbd', 'fgnbf', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '0', '', '2022-04-18 11:37:26', ''),
(253, 'sdgb', 'dsfgbd', 'fgnbf', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '0', '', '2022-04-18 11:39:11', ''),
(254, 'sdgb', 'dsfgbd', 'fgnbf', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '0', '', '2022-04-18 11:39:13', ''),
(255, 'sdgb', 'dsfgbd', 'fgnbf', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '0', '', '2022-04-18 11:39:55', ''),
(256, 'sdgb', 'dsfgbd', 'fgnbf', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '0', '', '2022-04-18 11:42:21', ''),
(257, 'sdgb', 'dsfgbd', 'fgnbf', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '0', '', '2022-04-18 11:43:52', ''),
(258, 'sdgb', 'dsfgbd', 'fgnbf', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '0', '', '2022-04-18 11:44:53', ''),
(259, 'sdgb', 'dsfgbd', 'fgnbf', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '0', '', '2022-04-18 11:45:35', ''),
(260, 'sdgb', 'sukla sah', 'subham sah', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Female', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '0', '', '2022-04-18 11:46:08', ''),
(262, 'PEA0000000037', 'sukla sah', 'Rajat Das', 'rahulmajumdar400@gmail.com', '7854121545', '22', 'Male', 'Kolkata', 'Kolkata', 'Kolkata', 'Kolkata', '754514', 'West bengal', '1', '', '2022-07-06 15:03:05', ''),
(263, 'PEA0000000038', 'Dipak Majumdar', 'No Gurdian', 'abhijitdas@gmail.com', '9878768767', '22', 'Male', 'Rampur', 'Rampur', 'Kolkata', 'Kolkata', '736207', 'West bengal', '1', '', '2022-07-16 13:14:37', '');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_invoice`
--

CREATE TABLE `pharmacy_invoice` (
  `id` int(12) NOT NULL,
  `invoice_id` bigint(30) NOT NULL,
  `item_id` varchar(12) NOT NULL,
  `item_name` varchar(28) NOT NULL,
  `batch_no` varchar(16) NOT NULL,
  `weatage` varchar(16) NOT NULL,
  `exp_date` varchar(7) NOT NULL,
  `qty` int(4) NOT NULL,
  `loosely_count` int(8) NOT NULL,
  `mrp` decimal(8,2) NOT NULL,
  `disc` varchar(5) NOT NULL,
  `d_price` decimal(8,2) NOT NULL,
  `gst` int(2) NOT NULL,
  `gst_amount` decimal(8,2) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `added_by` varchar(16) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(20) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `manufacturer_id` int(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  `power` varchar(6) NOT NULL,
  `dsc` varchar(255) NOT NULL,
  `packaging_type` varchar(12) NOT NULL,
  `unit_quantity` varchar(12) NOT NULL,
  `unit` varchar(30) NOT NULL,
  `mrp` varchar(12) NOT NULL,
  `gst` varchar(6) NOT NULL,
  `added_by` varchar(13) NOT NULL,
  `added_on` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_id`, `manufacturer_id`, `name`, `power`, `dsc`, `packaging_type`, `unit_quantity`, `unit`, `mrp`, `gst`, `added_by`, `added_on`) VALUES
(47, 'PR1411967617', 38, 'Paracetamol 650 MG', '650 MG', 'Paracetamol 650 MG Tablet is the most widely used over-the-counter (OTC) medication. It is used for the treatment of fever. It also provides relief from headache, toothache, backache, nerve pain, musculoskeletal pain, etc. associated with/without fever. P', '1', '15', 'tab', '25', '12', '', ''),
(48, 'PR6531139060', 37, 'Omee Capsule', '', 'Omee Capsule is a medicine that reduces the amount of acid produced in your stomach. It helps treat acid-related diseases of the stomach and intestine such as heartburn, acid reflux, and peptic ulcer disease.', '1', '10', 'tab', '30', '5', '', ''),
(49, 'PR406382559', 35, 'Alkaston B-6 Oral Solution', '00', 'Alkaston-B6 Syrup contains Magnesium citrate, Potassium citrate and vitamin B6 (pyridoxine) as main active ingredients.\r\n\r\nRole of active ingredients:\r\nPotassium citrate increases urinary citrate principally by modifying the renal handling of citrate, rat', '2', '200', 'ml', '179', '12', '', ''),
(50, 'PR3248786091', 39, 'Acepar', '100mg/', 'Acepar 100mg/325mg Tablet is a pain-relieving medicine. It is used to reduce pain and inflammation in conditions like rheumatoid arthritis, ankylosing spondylitis, and osteoarthritis. It may also be used to relieve muscle pain, back pain, toothache, or pa', '1', '10', 'tab', '30', '12', '', ''),
(51, 'PR8478887014', 40, 'Eno Fruit Salt Powder', 'Orange', 'ENO gets to work in 6 seconds to neutralize acid in your stomach on contact. ENO Powder gets to work faster than liquid and tablet antacids, so that you get fast relief when you need it most. Eno is Indias No 1 Antacid brand.', '4', '30', 'pc', '210', '12', '', ''),
(52, 'PR2419835473', 39, 'ENO', '', 'Dispovan are made of non-toxic, medical-grade polypropylene compatible with any medication.', '4', '50', 'pc', '150', '12', '', ''),
(53, 'PR3780154777', 38, 'Lyrica', '75mg', 'Lyrica 75mg Capsule is a medicine used to relieve pain caused by nerve damage (neuropathic pain) due to diabetes, shingles (herpes zoster infection), spinal cord injury, or other conditions. It is also used to treat widespread muscle pain and stiffness in', '1', '8', 'cap', '884', '12', '', ''),
(54, 'PR3738768180', 35, 'Alfoo', '10mg', 'ALFOO 10MG contains Alfuzosin which belongs to a group of medicines called alpha-1-blockers. It is used to treat moderate to severe symptoms of Benign Prostate Hyperplasia. It is a condition where the prostate gland enlarges, but the growth in itself is n', '1', '30', 'tab', '624', '12', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(24) NOT NULL,
  `product_id` varchar(24) NOT NULL,
  `image` varchar(355) NOT NULL,
  `back_image` varchar(355) NOT NULL,
  `side_image` varchar(355) NOT NULL,
  `added_by` varchar(16) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `back_image`, `side_image`, `added_by`, `added_on`) VALUES
(27, 'PR1411967617', 'paracetamol-650-mg.jpg', 'paracetamol-650-mg-tablet.jpg', 'paracetamol-650-mg-effects.jpg', '', '2022-05-05 06:57:05'),
(28, 'PR6531139060', 'omee-tablet.jpg', '', '', '', '2022-05-12 10:05:25'),
(29, 'PR406382559', 'alkaston-b6.jpg', 'alkaston-b6-side.jpg', 'alkaston-b6-back.jpg', '', '2022-05-13 07:06:02'),
(30, 'PR3248786091', 'Acepar.jpg', 'acepar-sp-500x500.jpg', 'medicy-', '', '2022-06-15 06:29:00'),
(31, 'PR8478887014', 'medicy-', 'medicy-', 'medicy-', '', '2022-06-15 06:40:21'),
(32, 'PR2419835473', 'dispovan_21g_syringe_20_ml_0_1.jpg', 'oip-1--500x500.jpg', 'dispovan_21g_syringe_20_ml_1_0.jpg', '', '2022-06-15 06:43:45'),
(33, 'PR3780154777', 'lyrica_75mg_capsule_14_s_0.jpg', 'LYRICA 75.jpg', 'medshouse64-1.jpg', '', '2022-06-15 06:48:52'),
(34, 'PR3738768180', 'a34pbmqti99hu1y1zz5r.jpg', '0047218_alfoo_10mg_tab_30s_300.jpg', '71LFnm8GdQL._SL1200_.jpg', '', '2022-06-15 06:52:40');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(6) NOT NULL,
  `mobile_number` varchar(10) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `mobile_number`, `otp`, `time`) VALUES
(1, '7699753019', '311350', '2021-12-01 11:29:54'),
(2, '7699753019', '896988', '2021-12-01 11:31:04'),
(3, '7699753019', '132641', '2021-12-01 11:34:06'),
(4, '7699753019', '971622', '2021-12-01 11:45:34'),
(5, '2222222222', '871789', '2021-12-01 11:54:21'),
(6, '7699753019', '142423', '2021-12-01 12:14:13'),
(7, '7699753019', '732832', '2021-12-01 12:16:32'),
(8, '7699753019', '683545', '2021-12-01 12:16:50'),
(9, '7699753019', '856481', '2021-12-01 12:18:08'),
(10, '7699753019', '655531', '2021-12-01 12:18:24'),
(11, '7699753019', '336480', '2021-12-01 12:23:00'),
(12, '7699753019', '923605', '2021-12-01 12:24:13'),
(13, '7699753019', '232369', '2021-12-01 12:26:02'),
(14, '7699753019', '170744', '2021-12-01 12:28:46'),
(15, '7699753019', '810219', '2021-12-01 12:30:06'),
(16, '7699753019', '977504', '2021-12-01 12:30:29'),
(17, '7547254154', '673647', '2021-12-01 12:48:16'),
(18, '8372145745', '896120', '2021-12-01 12:49:18'),
(19, '1111111111', '331614', '2021-12-01 12:59:49'),
(20, '1111111111', '918594', '2021-12-01 13:01:00'),
(21, '7699753019', '709686', '2021-12-01 13:02:18'),
(22, '7699753019', '293858', '2021-12-01 13:05:28'),
(23, '1232343738', '288545', '2021-12-01 13:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `sales_return`
--

CREATE TABLE `sales_return` (
  `id` bigint(14) NOT NULL,
  `invoice_id` bigint(24) NOT NULL,
  `patient_id` varchar(18) NOT NULL,
  `bill_date` date NOT NULL,
  `return_date` date NOT NULL,
  `items` int(8) NOT NULL,
  `gst_amount` decimal(8,2) NOT NULL,
  `refund_amount` decimal(10,2) NOT NULL,
  `refund_mode` varchar(12) NOT NULL,
  `status` varchar(14) NOT NULL,
  `added_by` varchar(14) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_return`
--

INSERT INTO `sales_return` (`id`, `invoice_id`, `patient_id`, `bill_date`, `return_date`, `items`, `gst_amount`, `refund_amount`, `refund_mode`, `status`, `added_by`, `added_on`) VALUES
(25, 0, '', '1970-01-01', '0000-00-00', 0, '0.00', '0.00', '', '', 'subhankar', '2022-09-21 05:19:47');

-- --------------------------------------------------------

--
-- Table structure for table `sales_return_details`
--

CREATE TABLE `sales_return_details` (
  `id` bigint(14) NOT NULL,
  `invoice_id` bigint(14) NOT NULL,
  `product_id` varchar(14) NOT NULL,
  `batch_no` varchar(14) NOT NULL,
  `weatage` varchar(14) NOT NULL,
  `exp` varchar(5) NOT NULL,
  `qty` int(6) NOT NULL,
  `disc` int(3) NOT NULL,
  `gst` int(3) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `return` int(8) NOT NULL,
  `refund` decimal(8,2) NOT NULL,
  `added_by` varchar(12) NOT NULL,
  `added_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_in`
--

CREATE TABLE `stock_in` (
  `id` bigint(10) NOT NULL,
  `distributor_id` int(4) NOT NULL,
  `distributor_bill` varchar(12) NOT NULL,
  `items` int(6) NOT NULL,
  `total_qty` varchar(12) NOT NULL,
  `bill_date` varchar(10) NOT NULL,
  `due_date` varchar(10) NOT NULL,
  `payment_mode` varchar(24) NOT NULL,
  `gst` decimal(8,2) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `added_by` varchar(155) NOT NULL,
  `added_on` date NOT NULL DEFAULT current_timestamp(),
  `added_time` time NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_in`
--

INSERT INTO `stock_in` (`id`, `distributor_id`, `distributor_bill`, `items`, `total_qty`, `bill_date`, `due_date`, `payment_mode`, `gst`, `amount`, `added_by`, `added_on`, `added_time`) VALUES
(87, 6, 'GHM76', 2, '50', '15-06-2022', '16-06-2022', 'UPI', '76.50', '1046.50', '', '2022-06-15', '14:33:32');

-- --------------------------------------------------------

--
-- Table structure for table `stock_in_details`
--

CREATE TABLE `stock_in_details` (
  `id` bigint(10) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `distributor_bill` varchar(12) NOT NULL,
  `batch_no` varchar(12) NOT NULL,
  `exp_date` varchar(10) NOT NULL,
  `weightage` int(6) NOT NULL,
  `unit` varchar(24) NOT NULL,
  `qty` int(8) NOT NULL,
  `free_qty` int(8) NOT NULL,
  `loosely_count` int(8) NOT NULL,
  `mrp` decimal(8,2) NOT NULL,
  `ptr` decimal(8,2) NOT NULL,
  `discount` int(3) NOT NULL,
  `base` decimal(6,2) NOT NULL,
  `gst` int(3) NOT NULL,
  `gst_amount` decimal(8,2) NOT NULL,
  `margin` decimal(6,2) NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `added_by` varchar(24) NOT NULL,
  `added_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_in_details`
--

INSERT INTO `stock_in_details` (`id`, `product_id`, `distributor_bill`, `batch_no`, `exp_date`, `weightage`, `unit`, `qty`, `free_qty`, `loosely_count`, `mrp`, `ptr`, `discount`, `base`, `gst`, `gst_amount`, `margin`, `amount`, `added_by`, `added_on`) VALUES
(138, 'PR6531139060', 'GHM76', 'CVN8A4 ', '12/29', 10, 'tab', 30, 0, 300, '30.00', '20.00', 5, '19.00', 5, '28.50', '33.50', '598.50', '', '2022-06-15 14:53:07'),
(139, 'PR3248786091', 'GHM76', 'CVN8A5', '09/27', 10, 'tab', 20, 0, 200, '30.00', '20.00', 0, '20.00', 12, '48.00', '25.33', '448.00', '', '2022-06-15 14:53:07');

-- --------------------------------------------------------

--
-- Table structure for table `stock_out`
--

CREATE TABLE `stock_out` (
  `invoice_id` bigint(30) NOT NULL,
  `customer_id` varchar(14) NOT NULL,
  `reff_by` varchar(55) NOT NULL,
  `items` int(8) NOT NULL,
  `qty` int(6) NOT NULL,
  `mrp` decimal(8,2) NOT NULL,
  `disc` varchar(5) NOT NULL,
  `gst` varchar(5) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `payment_mode` varchar(25) NOT NULL,
  `status` varchar(16) NOT NULL,
  `bill_date` varchar(10) NOT NULL,
  `added_by` varchar(16) NOT NULL,
  `added_on` date NOT NULL DEFAULT current_timestamp(),
  `added_time` time NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_out_details`
--

CREATE TABLE `stock_out_details` (
  `invoice_id` bigint(30) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `batch_no` varchar(12) NOT NULL,
  `exp_date` varchar(10) NOT NULL,
  `weightage` int(6) NOT NULL,
  `unit` varchar(24) NOT NULL,
  `qty` int(8) NOT NULL,
  `loosely_count` tinyint(1) NOT NULL,
  `mrp` decimal(8,2) NOT NULL,
  `ptr` decimal(8,2) NOT NULL,
  `discount` int(3) NOT NULL,
  `gst` int(3) NOT NULL,
  `margin` decimal(6,2) NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `added_by` varchar(24) NOT NULL,
  `added_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_return`
--

CREATE TABLE `stock_return` (
  `id` bigint(14) NOT NULL,
  `distributor_id` int(4) NOT NULL,
  `return_date` date NOT NULL DEFAULT current_timestamp(),
  `items` int(8) NOT NULL,
  `total_qty` int(8) NOT NULL,
  `gst_amount` decimal(8,0) NOT NULL,
  `refund_mode` varchar(14) NOT NULL,
  `refund_amount` decimal(8,0) NOT NULL,
  `status` varchar(10) NOT NULL,
  `added_by` varchar(14) NOT NULL,
  `added_on` date NOT NULL DEFAULT current_timestamp(),
  `added_time` time NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_return_details`
--

CREATE TABLE `stock_return_details` (
  `id` int(11) NOT NULL,
  `stock_return_id` int(11) NOT NULL,
  `product_id` varchar(14) NOT NULL,
  `batch_no` varchar(18) NOT NULL,
  `exp_date` varchar(5) NOT NULL,
  `unit` int(8) NOT NULL,
  `purchase_qty` int(8) NOT NULL,
  `free_qty` int(8) NOT NULL,
  `mrp` int(8) NOT NULL,
  `ptr` int(8) NOT NULL,
  `purchase_amount` decimal(8,0) NOT NULL,
  `gst` int(3) NOT NULL,
  `return_qty` int(6) NOT NULL,
  `refund_amount` decimal(8,0) NOT NULL,
  `added_by` varchar(14) NOT NULL,
  `added_on` date NOT NULL DEFAULT current_timestamp(),
  `added_time` time NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_tests`
--

CREATE TABLE `sub_tests` (
  `id` int(11) NOT NULL,
  `sub_test_name` varchar(300) NOT NULL,
  `parent_test_id` varchar(6) NOT NULL,
  `age_group` varchar(15) NOT NULL,
  `test_preparation` varchar(500) NOT NULL,
  `test_dsc` varchar(300) NOT NULL,
  `price` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_tests`
--

INSERT INTO `sub_tests` (`id`, `sub_test_name`, `parent_test_id`, `age_group`, `test_preparation`, `test_dsc`, `price`) VALUES
(4, 'PUS AFB', '4', 'Any Age Group', '', '', '150'),
(5, 'GRAM STAIN', '4', 'Any Age Group', '', '', '150'),
(6, 'PUS C/S', '4', 'Any Age Group', '', '', '400'),
(7, 'URINE RE/ME', '3', 'Any Age Group', '', '', '100'),
(8, 'URINE C/S', '3', 'Any Age Group', '', '', '350'),
(9, 'URINE BILE SALT', '3', 'Any Age Group', '', '', '100'),
(10, 'URINE BILE PIGMENT', '3', 'Any Age Group', '', '', '100'),
(11, 'URINE ACR', '3', 'Any Age Group', '', '', '700'),
(12, 'MICRO ALBUMIN', '3', 'Any Age Group', '', '', '650'),
(13, 'URINE PREGNANCY TEST', '3', 'Any Age Group', '', '', '100'),
(14, '24 Hrs. TOTAL PROTEIN', '3', 'Any Age Group', '', '', '600'),
(15, 'SPUTAM FOR AFB 1 DAY', '2', 'Any Age Group', '', '', '200'),
(16, 'SPUTAM FOR AFB 3 DAY', '2', 'Any Age Group', '', '', '500'),
(17, 'HB%', '1', 'Any Age Group', '', '', '80'),
(18, 'HB%,TC,DC\r\n', '1', 'Any Age Group', '', '', '160'),
(19, 'HB%,TC,DC,ESR\r\n', '1', 'Any Age Group', '', '', '180'),
(20, 'CBC\r\n', '1', 'Any Age Group', '', '', '450'),
(21, 'SUGER\r\n', '1', 'Any Age Group', '', '', '50'),
(22, 'UREA\r\n', '1', 'Any Age Group', '', '', '150'),
(23, 'CREATININE\r\n', '1', 'Any Age Group', '', '', '150'),
(24, 'BT,CT\r\n', '1', 'Any Age Group', '', '', '100'),
(25, 'GROUP (ABO & RH)\r\n', '1', 'Any Age Group', '', '', '50'),
(26, 'PLATELATE COUNT\r\n', '1', 'Any Age Group', '', '', '150'),
(27, 'BILIRUBIN\r\n', '1', 'Any Age Group', '', '', '250'),
(28, 'SGOT\r\n', '1', 'Any Age Group', '', '', '150'),
(29, 'SGPT\r\n', '1', 'Any Age Group', '', '', '150'),
(30, 'ALKALANE PHOSHPHATE\r\n', '1', 'Any Age Group', '', '', '150'),
(31, 'LFT\r\n', '1', 'Any Age Group', '', '', '600'),
(32, 'CHOLESTEROL\r\n', '1', 'Any Age Group', '', '', '150'),
(33, 'TRIGLICERIDE\r\n', '1', 'Any Age Group', '', '', '150'),
(34, 'LIPID PROFILE\r\n', '1', 'Any Age Group', '', '', '700'),
(35, 'VDRL\r\n', '1', 'Any Age Group', '', '', '200'),
(36, 'HBSAG\r\n', '1', 'Any Age Group', '', '', '300'),
(37, 'HIV I & II\r\n', '1', 'Any Age Group', '', '', '500'),
(38, 'ANTI HCV\r\n', '1', 'Any Age Group', '', '', '650'),
(39, 'ABNORMAL CELL\r\n', '1', 'Any Age Group', '', '', '100'),
(40, 'ALDEHYDE\r\n', '1', 'Any Age Group', '', '', '400'),
(41, 'DENGUE ELISA\r\n', '1', 'Any Age Group', '', '', '1500'),
(42, 'S.TYPHOID ANTIGEN\r\n', '1', 'Any Age Group', '', '', '750'),
(43, 'LIPASE\r\n', '1', 'Any Age Group', '', '', '500'),
(44, 'AMYLASE\r\n', '1', 'Any Age Group', '', '', '500'),
(45, 'WIDAL\r\n', '1', 'Any Age Group', '', '', '150'),
(46, 'MP ANTIGEN\r\n', '1', 'Any Age Group', '', '', '450'),
(47, 'MP', '1', 'Any Age Group', '', '', '100'),
(48, 'RK-39\r\n', '1', 'Any Age Group', '', '', '700'),
(49, 'P-TIME\r\n', '1', 'Any Age Group', '', '', '300'),
(50, 'TROP-T\r\n', '1', 'Any Age Group', '', '', '1200'),
(51, 'CPK-MB\r\n', '1', 'Any Age Group', '', '', '600'),
(52, 'CALCIUM(CA++)\r\n', '1', 'Any Age Group', '', '', '350'),
(53, 'SODIUM(NA+)\r\n', '1', 'Any Age Group', '', '', '400'),
(54, 'POTASSIUM(K+)\r\n', '1', 'Any Age Group', '', '', '400'),
(55, 'RBC MORPHOLOGY\r\n', '1', 'Any Age Group', '', '', '150'),
(56, 'RETICULOCYTE COUNT\r\n', '1', 'Any Age Group', '', '', '250'),
(57, 'CRP\r\n', '1', 'Any Age Group', '', '', '400'),
(58, 'RA-FACTOR\r\n', '1', 'Any Age Group', '', '', '400'),
(59, 'ASO-TITRE\r\n', '1', 'Any Age Group', '', '', '400'),
(60, 'TSH\r\n', '1', 'Any Age Group', '', '', '300'),
(61, 'T3\r\n', '1', 'Any Age Group', '', '', '300'),
(62, 'T4', '1', 'Any Age Group', '', '', '300'),
(63, 'T4,TSH\r\n', '1', 'Any Age Group', '', '', '500'),
(64, 'T3,T4,TSH\r\n', '1', 'Any Age Group', '', '', '650'),
(65, 'IGE\r\n', '1', 'Any Age Group', '', '', '600'),
(66, 'FREE T3\r\n', '1', 'Any Age Group', '', '', '650'),
(67, 'FREE T4\r\n', '1', 'Any Age Group', '', '', '650'),
(68, 'G6PD\r\n', '1', 'Any Age Group', '', '', '600'),
(69, 'ESR\r\n', '1', 'Any Age Group', '', '', '60'),
(70, 'ADA\r\n', '1', 'Any Age Group', '', '', '650'),
(71, 'MANTOUX TEST\r\n', '1', 'Any Age Group', '', '', '300'),
(72, 'DIRECT COOMB TEST\r\n', '1', 'Any Age Group', '', '', '300'),
(73, 'VITAMIN D3\r\n', '1', 'Any Age Group', '', '', '1500'),
(74, 'CPK\r\n', '1', 'Any Age Group', '', '', '600'),
(75, 'PCV\r\n', '1', 'Any Age Group', '', '', '150'),
(76, 'SEMEN ANALYSIS\r\n', '1', 'Any Age Group', '', '', '300'),
(77, 'ABSOLUTE NEUTROPHIL COUNT\r\n', '1', 'Any Age Group', '', '', '200'),
(78, 'ABSOLUTE EOSINOPHIL COUNT \r\n', '1', 'Any Age Group', '', '', '200'),
(79, 'HBA1C\r\n', '1', 'Any Age Group', '', '', '700'),
(80, 'PSA\r\n', '1', 'Any Age Group', '', '', '600'),
(81, 'CA-125\r\n', '1', 'Any Age Group', '', '', '1000'),
(82, 'ESTRADIOL(E2)\r\n', '1', 'Any Age Group', '', '', '900'),
(83, 'FSH\r\n', '1', 'Any Age Group', '', '', '500'),
(84, 'LH\r\n', '1', 'Any Age Group', '', '', '500'),
(85, 'PROGESTERONE\r\n', '1', 'Any Age Group', '', '', '1000'),
(86, 'PROLACTIN\r\n', '1', 'Any Age Group', '', '', '600'),
(87, 'BLOOD FOR B-HCG\r\n', '1', 'Any Age Group', '', '', '550'),
(88, 'FREE T3, FREE T4, TSH\r\n', '1', 'Any Age Group', '', '', '1300'),
(89, 'FREE T4, TSH\r\n', '1', 'Any Age Group', '', '', '850'),
(90, 'FREE T3,TSH\r\n', '1', 'Any Age Group', '', '', '850');

-- --------------------------------------------------------

--
-- Table structure for table `tests_types`
--

CREATE TABLE `tests_types` (
  `id` int(4) NOT NULL,
  `image` varchar(155) NOT NULL,
  `test_type_name` varchar(100) NOT NULL,
  `provided_by` text NOT NULL,
  `dsc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tests_types`
--

INSERT INTO `tests_types` (`id`, `image`, `test_type_name`, `provided_by`, `dsc`) VALUES
(1, 'img/lab-tests/medicy health care blood test.jpg', 'Blood Test', 'Medicy Health Care', 'A blood test is a lab analysis of things that may be found in your blood. You may have blood tests to keep track of how well you are managing a condition such as diabetes or high cholesterol. You may also have them for routine checkups or when you are ill'),
(2, 'img/lab-tests/medicy health care sputum test.jpg', 'Sputam Test', 'Medicy Health Care', 'A sputum test, also known as a sputum culture, is a test that your doctor may order when you have a respiratory tract infection or other lung-related disorder to determine what is growing in the lungs. Sputum is a thick substance that accumulates when bac'),
(3, 'img/lab-tests/medicy health care urine test.jpg', 'Urine Test', 'Medicy Health Care', 'Clinical urine tests are examinations of the physical and chemical properties of urine and its microscopic appearance to aid in medical diagnosis.'),
(4, 'img/lab-tests/medicy health care pus test.jpg', 'Pus Test', 'Medicy Health care', 'A blood test is a lab analysis of things that may be found in your blood. You may have blood tests to keep track of how well you are managing a condition such as diabetes or high cholesterol. You may also have them for routine checkups or when you are ill');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `contact_details`
--
ALTER TABLE `contact_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `current_stock`
--
ALTER TABLE `current_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `distributor`
--
ALTER TABLE `distributor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`),
  ADD KEY `doc_specialization_fk` (`doctor_specialization`);

--
-- Indexes for table `doctor_category`
--
ALTER TABLE `doctor_category`
  ADD PRIMARY KEY (`doctor_category_id`);

--
-- Indexes for table `doctor_timing`
--
ALTER TABLE `doctor_timing`
  ADD PRIMARY KEY (`doc_timing_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gst`
--
ALTER TABLE `gst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hospital_info`
--
ALTER TABLE `hospital_info`
  ADD PRIMARY KEY (`hospital_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_appointments`
--
ALTER TABLE `lab_appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_billing`
--
ALTER TABLE `lab_billing`
  ADD PRIMARY KEY (`bill_id`);

--
-- Indexes for table `lab_billing_details`
--
ALTER TABLE `lab_billing_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `measure_of_unit`
--
ALTER TABLE `measure_of_unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packaging_unit`
--
ALTER TABLE `packaging_unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_details`
--
ALTER TABLE `patient_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `pharmacy_invoice`
--
ALTER TABLE `pharmacy_invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_fk` (`invoice_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `manufacturer_fk` (`manufacturer_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_return`
--
ALTER TABLE `sales_return`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_return_details`
--
ALTER TABLE `sales_return_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_in`
--
ALTER TABLE `stock_in`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_in_details`
--
ALTER TABLE `stock_in_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_out`
--
ALTER TABLE `stock_out`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `stock_return`
--
ALTER TABLE `stock_return`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_return_details`
--
ALTER TABLE `stock_return_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_tests`
--
ALTER TABLE `sub_tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tests_types`
--
ALTER TABLE `tests_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `contact_details`
--
ALTER TABLE `contact_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `current_stock`
--
ALTER TABLE `current_stock`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `distributor`
--
ALTER TABLE `distributor`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5434;

--
-- AUTO_INCREMENT for table `doctor_category`
--
ALTER TABLE `doctor_category`
  MODIFY `doctor_category_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `gst`
--
ALTER TABLE `gst`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_appointments`
--
ALTER TABLE `lab_appointments`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `lab_billing_details`
--
ALTER TABLE `lab_billing_details`
  MODIFY `id` int(24) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=264;

--
-- AUTO_INCREMENT for table `manufacturer`
--
ALTER TABLE `manufacturer`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `measure_of_unit`
--
ALTER TABLE `measure_of_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `packaging_unit`
--
ALTER TABLE `packaging_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `patient_details`
--
ALTER TABLE `patient_details`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=264;

--
-- AUTO_INCREMENT for table `pharmacy_invoice`
--
ALTER TABLE `pharmacy_invoice`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(24) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `sales_return`
--
ALTER TABLE `sales_return`
  MODIFY `id` bigint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `sales_return_details`
--
ALTER TABLE `sales_return_details`
  MODIFY `id` bigint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `stock_in`
--
ALTER TABLE `stock_in`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `stock_in_details`
--
ALTER TABLE `stock_in_details`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `stock_return_details`
--
ALTER TABLE `stock_return_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sub_tests`
--
ALTER TABLE `sub_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `tests_types`
--
ALTER TABLE `tests_types`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doc_specialization_fk` FOREIGN KEY (`doctor_specialization`) REFERENCES `doctor_category` (`doctor_category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pharmacy_invoice`
--
ALTER TABLE `pharmacy_invoice`
  ADD CONSTRAINT `invoice_fk` FOREIGN KEY (`invoice_id`) REFERENCES `stock_out` (`invoice_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `manufacturer_fk` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
