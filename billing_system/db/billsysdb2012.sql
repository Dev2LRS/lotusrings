-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 12, 2013 at 12:03 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `billsysdb2012`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE IF NOT EXISTS `tbl_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `last_logged_in` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `email`, `password`, `first_name`, `last_name`, `last_logged_in`, `ip`) VALUES
(1, 'admin@admin.com', '123456', 'Admin', '', '2012-09-02 21:59:06', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bridge`
--

CREATE TABLE IF NOT EXISTS `tbl_bridge` (
  `pk_bridge_id` int(11) NOT NULL AUTO_INCREMENT,
  `bridge_type` enum('b','nb') NOT NULL,
  `service_provider_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `conference_id` int(11) NOT NULL,
  `cal_detail_id` int(11) NOT NULL,
  `units` int(11) NOT NULL,
  `unit_of_measure` char(30) NOT NULL,
  `item_type` varchar(30) NOT NULL,
  `chargeable_item` varchar(30) NOT NULL,
  `charge_amount` decimal(10,0) NOT NULL,
  `currency` char(3) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `timezone` varchar(30) NOT NULL,
  `bridge_id` char(5) NOT NULL,
  `port_id` char(10) NOT NULL,
  `a_number` char(20) NOT NULL,
  `b_number` char(20) NOT NULL,
  `privacy_bit` char(1) NOT NULL,
  `participant_name` varchar(30) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`pk_bridge_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=190 ;

--
-- Dumping data for table `tbl_bridge`
--



-- --------------------------------------------------------

--
-- Table structure for table `tbl_conference`
--

CREATE TABLE IF NOT EXISTS `tbl_conference` (
  `pk_conference_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_povider_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `conference_id` int(11) NOT NULL,
  `authorization_string` char(10) NOT NULL,
  `first_touched_timestamp` datetime NOT NULL,
  `resv_begin` datetime NOT NULL,
  `resv_begin_timezone` char(3) NOT NULL,
  `resv_end` datetime NOT NULL,
  `resv_end_timezone` char(3) NOT NULL,
  `invoice_ref` char(30) NOT NULL,
  `status` char(10) NOT NULL,
  `last_touched_timestamp` datetime NOT NULL,
  `reserver` char(30) NOT NULL,
  `reserver_phone` char(20) NOT NULL,
  `reserved_total_lines` char(4) NOT NULL,
  `ra_master_id` int(11) NOT NULL,
  `access_number` varchar(30) NOT NULL,
  `access_code` varchar(50) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`pk_conference_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `tbl_conference`
--



-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE IF NOT EXISTS `tbl_customer` (
  `pk_customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_povider_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(30) NOT NULL,
  `organization _id` int(11) NOT NULL,
  `subaccount_id` int(11) NOT NULL,
  `subaccount_name` varchar(30) NOT NULL,
  `chairperson_id` int(11) NOT NULL,
  `chairperson_name` varchar(30) NOT NULL,
  `chairperson_phone` varchar(20) NOT NULL,
  `account_type` char(1) NOT NULL,
  `address1` varchar(30) NOT NULL,
  `address2` char(30) NOT NULL,
  `address3` char(30) NOT NULL,
  `city_name` char(20) NOT NULL,
  `country_name` char(15) NOT NULL,
  `state_code` char(2) NOT NULL,
  `zip` char(10) NOT NULL,
  `country_code` char(3) NOT NULL,
  `anniversary_date` char(8) NOT NULL,
  `account_status` char(1) NOT NULL,
  `sales_code` char(9) NOT NULL DEFAULT '000000000',
  `payment_type` enum('DIRE','MASC','VISA','AMEX') NOT NULL,
  `wholesale_unique_id` char(30) NOT NULL,
  `sp_unique_id` char(20) NOT NULL,
  `credit_card_number` char(30) NOT NULL,
  `cardholder_name` char(30) NOT NULL,
  `expiration_date` int(11) NOT NULL,
  `finance_charge_flag` char(1) NOT NULL DEFAULT 'N',
  `late_notice_flag` char(1) NOT NULL DEFAULT 'N',
  `federal_tax_exempt` char(1) NOT NULL DEFAULT 'N',
  `state_tax_exempt` char(1) NOT NULL DEFAULT 'N',
  `local _tax_exempt` char(1) NOT NULL DEFAULT 'N',
  `misc_tax_exempt` char(1) NOT NULL DEFAULT 'N',
  `volume_discount_plan` int(2) NOT NULL DEFAULT '0',
  `flexbill _flag` char(1) NOT NULL DEFAULT 'N',
  `floppy_detail_flag` char(1) NOT NULL DEFAULT 'N',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`pk_customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `tbl_customer`
--



-- --------------------------------------------------------

--
-- Table structure for table `tbl_header`
--

CREATE TABLE IF NOT EXISTS `tbl_header` (
  `header_id` int(11) NOT NULL AUTO_INCREMENT,
  `no_of_records` int(11) NOT NULL,
  `total_charges` double NOT NULL,
  PRIMARY KEY (`header_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_header`
--

INSERT INTO `tbl_header` (`header_id`, `no_of_records`, `total_charges`) VALUES
(1, 242, 63.61);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_processed_files`
--

CREATE TABLE IF NOT EXISTS `tbl_processed_files` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(25) NOT NULL,
  `status` enum('success','error') NOT NULL DEFAULT 'success',
  `error_message` text NOT NULL,
  `error_row_numbers` text NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tbl_processed_files`
--



-- --------------------------------------------------------

--
-- Table structure for table `tbl_pr_data`
--

CREATE TABLE IF NOT EXISTS `tbl_pr_data` (
  `uniquerowid` int(11) NOT NULL,
  `uniqueconfid` int(11) NOT NULL,
  `confid` varchar(12) NOT NULL,
  `bridgename` varchar(10) NOT NULL,
  `countrycode` char(3) NOT NULL,
  `intlcompanyid` int(11) NOT NULL,
  `intlclientid` varchar(25) NOT NULL,
  `intlcountrycode` char(3) NOT NULL,
  `participantname` varchar(50) NOT NULL,
  `conferencetitle` varchar(50) NOT NULL,
  `connecttime` datetime NOT NULL,
  `disconnecttime` datetime NOT NULL,
  `duration` int(6) NOT NULL,
  `bridgetype` char(1) NOT NULL,
  `accesstype` char(1) NOT NULL,
  `pin` varchar(31) NOT NULL,
  `ponumber` varchar(20) NOT NULL,
  `phone` varchar(60) NOT NULL,
  `reccreated` datetime NOT NULL,
  `prepostcomm` tinyint(1) NOT NULL,
  `scheduleddate` datetime NOT NULL,
  `conferencetype` int(11) NOT NULL,
  `reservationtype` int(11) NOT NULL,
  `dialedout` bit(1) NOT NULL,
  `soundbyte` bit(1) NOT NULL,
  `prairiesoundbyte` bit(1) NOT NULL,
  `ani` varchar(30) NOT NULL,
  `dnis` varchar(30) NOT NULL,
  `destinationcountrycode` char(3) NOT NULL,
  `externalid` varchar(20) NOT NULL,
  `recordcount` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uniquerowid` (`uniquerowid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_pr_data`
--


