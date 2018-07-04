create database quickpayment2;
use quickpayment2;

CREATE TABLE `cards` (
  `id_card` varchar(12) NOT NULL,
  `id_member` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `cards` (`id_card`, `id_member`) VALUES
('', NULL);


CREATE TABLE `members` (
  `id_member` varchar(8) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(120) NOT NULL,
  `sex` varchar(5) NOT NULL,
  `dayofbirth` date NOT NULL,
  `phone` int(11) DEFAULT NULL,
  `ID_UG` varchar(8) NOT NULL,
  `ID_WP` varchar(8) NOT NULL,
  `balance` int(11) NOT NULL DEFAULT '0',
  `is_service_staff` varchar(3) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `members` (`id_member`, `password`, `name`, `sex`, `dayofbirth`, `phone`, `ID_UG`, `ID_WP`, `balance`, `is_service_staff`, `email`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'nam', '2018-10-03', 3, '2', 'C1', 0, 'no', 'admin@gmail.com'),
('deposit', '21232f297a57a5a743894a0e4a801fc3', 'deposit', 'nam', '2018-07-12', 2, '3', 'C2', 0, 'no', 'deposit@gmail.com'),
('iadmin', '21232f297a57a5a743894a0e4a801fc3', 'Initial Admin', 'nam', '1996-02-06', 1224099996, '1', 'DI', 0, NULL, NULL),
('service', '21232f297a57a5a743894a0e4a801fc3', 'service', 'nu', '2017-06-01', 2, '4', 'B1', 0, 'yes', 'service@gmail.com'),
('student', '21232f297a57a5a743894a0e4a801fc3', 'student', 'nam', '2018-07-12', 3, '5', 'CN', 30000, 'no', 'student@gmail.com'),
('student2', '21232f297a57a5a743894a0e4a801fc3', 'student2', 'nam', '2018-07-12', 3, '5', 'CN', 30000, 'no', 'student2@gmail.com'),
('student3', '21232f297a57a5a743894a0e4a801fc3', 'student3', 'nam', '2018-07-12', 3, '5', 'CN', 30000, 'no', 'student3@gmail.com'),
('student4', '21232f297a57a5a743894a0e4a801fc3', 'student4', 'nam', '2018-07-12', 3, '5', 'CN', 30000, 'no', 'student4@gmail.com'),
('student5', '21232f297a57a5a743894a0e4a801fc3', 'student5', 'nam', '2018-07-14', 1, '5', 'C1', 50000, 'no', 'student5@gmail.com'),
('student6', '21232f297a57a5a743894a0e4a801fc3', 'student6', 'nam', '2018-07-18', 3, '5', 'CN', 0, 'no', 'service@gmail.com'),
('student7', '21232f297a57a5a743894a0e4a801fc3', 'student7', 'nam', '2018-07-06', 5, '5', 'SH', 0, 'no', 'student7@gmail.com');


CREATE TABLE `payments` (
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_pay_member` varchar(8) NOT NULL,
  `id_collect_member` varchar(8) NOT NULL,
  `amountofmoney` int(11) NOT NULL,
  `type_payment` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `payments` (`date_time`, `id_pay_member`, `id_collect_member`, `amountofmoney`, `type_payment`) VALUES
('2018-07-03 15:00:37', 'student', 'deposit', 10000, '+'),
('2018-07-03 15:00:48', 'student', 'deposit', 20000, '+'),
('2018-07-03 15:00:56', 'student5', 'deposit', 50000, '+');


CREATE TABLE `usergroups` (
  `ID_UG` varchar(8) NOT NULL,
  `Name_UG` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `usergroups` (`ID_UG`, `Name_UG`) VALUES
('1', 'Initial admin'),
('2', 'Admin'),
('3', 'Deposit staff'),
('4', 'service staff'),
('5', 'student/lecturer');


CREATE TABLE `workplaces` (
  `ID_WP` varchar(8) NOT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `workplaces` (`ID_WP`, `description`) VALUES
('A1', 'Nha hoc A1'),
('A3', 'Nha hoc A3'),
('B1', 'Nha hoc B1'),
('C1', 'Nha hoc C1'),
('C2', 'Nha hoc C2'),
('CN', 'Khoa CN'),
('DB', 'Khoa DBDT'),
('DI', 'Khoa CNTT&TT'),
('HA', 'Khoa PTNT'),
('HL', 'TTHL'),
('KH', 'Khoa KHTN'),
('KT', 'Khoa KT-QTKD'),
('MT', 'Khoa KHCT'),
('MTN', 'Khoa MT&TNTN'),
('NDH', 'Nha dieu hanh'),
('NN', 'Khoa NN&SHUD'),
('SH', 'Vien NC&PTCNSH'),
('TS', 'Khoa TS'),
('XH', 'Khoa KHXHNV');

ALTER TABLE `cards`
  ADD PRIMARY KEY (`id_card`),
  ADD KEY `id_member` (`id_member`);

ALTER TABLE `members`
  ADD PRIMARY KEY (`id_member`),
  ADD KEY `ID_WP` (`ID_WP`),
  ADD KEY `ID_UG` (`ID_UG`);

ALTER TABLE `payments`
  ADD PRIMARY KEY (`date_time`,`id_pay_member`),
  ADD KEY `id_pay_member` (`id_pay_member`),
  ADD KEY `id_collect_member` (`id_collect_member`);

ALTER TABLE `usergroups`
  ADD PRIMARY KEY (`ID_UG`);

ALTER TABLE `workplaces`
  ADD PRIMARY KEY (`ID_WP`);


ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`id_member`) REFERENCES `members` (`id_member`) ON UPDATE CASCADE;

ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`ID_WP`) REFERENCES `workplaces` (`ID_WP`) ON UPDATE CASCADE,
  ADD CONSTRAINT `members_ibfk_2` FOREIGN KEY (`ID_UG`) REFERENCES `usergroups` (`ID_UG`) ON UPDATE CASCADE;

ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`id_pay_member`) REFERENCES `members` (`id_member`) ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`id_collect_member`) REFERENCES `members` (`id_member`) ON UPDATE CASCADE;
COMMIT;
