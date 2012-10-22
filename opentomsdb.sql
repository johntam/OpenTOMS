-- MySQL dump 10.13  Distrib 5.5.24, for debian-linux-gnu (i686)
--
-- Host: asapdb01.cqezga1cxvxz.us-east-1.rds.amazonaws.com    Database: ASAPDB01
-- ------------------------------------------------------
-- Server version	5.5.12-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `account_name` varchar(50) NOT NULL,
  `account_type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acos`
--

DROP TABLE IF EXISTS `acos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT '',
  `foreign_key` int(10) unsigned DEFAULT NULL,
  `alias` varchar(255) DEFAULT '',
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=406 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aros`
--

DROP TABLE IF EXISTS `aros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT '',
  `foreign_key` int(10) unsigned DEFAULT NULL,
  `alias` varchar(255) DEFAULT '',
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aros_acos`
--

DROP TABLE IF EXISTS `aros_acos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aros_acos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) unsigned NOT NULL,
  `aco_id` int(10) unsigned NOT NULL,
  `_create` char(2) NOT NULL DEFAULT '0',
  `_read` char(2) NOT NULL DEFAULT '0',
  `_update` char(2) NOT NULL DEFAULT '0',
  `_delete` char(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attachments`
--

DROP TABLE IF EXISTS `attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(250) NOT NULL,
  `size` int(11) NOT NULL,
  `content` mediumblob NOT NULL,
  `type` varchar(50) NOT NULL,
  `f_table` varchar(50) DEFAULT NULL,
  `f_id` int(11) DEFAULT NULL,
  `f_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `balances`
--

DROP TABLE IF EXISTS `balances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `balances` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act` tinyint(1) NOT NULL,
  `locked` tinyint(1) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `balance_date` date NOT NULL,
  `fund_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `custodian_id` int(11) DEFAULT NULL,
  `balance_debit` decimal(18,4) NOT NULL,
  `balance_credit` decimal(18,4) NOT NULL,
  `balance_cfd` tinyint(1) NOT NULL,
  `balance_accrued` tinyint(1) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `balance_quantity` decimal(18,4) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `trinv` text,
  `ref_id` text,
  `unsettled` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19131 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brokers`
--

DROP TABLE IF EXISTS `brokers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brokers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act` bit(1) NOT NULL DEFAULT b'1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `oid` int(11) DEFAULT NULL,
  `broker_name` varchar(50) NOT NULL,
  `broker_long_name` varchar(255) DEFAULT NULL,
  `commission_rate` decimal(6,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country_code` varchar(3) NOT NULL,
  `country_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `currency_iso_code` varchar(3) NOT NULL,
  `currency_name` varchar(100) NOT NULL,
  `sec_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `custodians`
--

DROP TABLE IF EXISTS `custodians`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custodians` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `custodian_name` varchar(50) NOT NULL,
  `custodian_long_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exchanges`
--

DROP TABLE IF EXISTS `exchanges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exchanges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `exchange_code` varchar(20) NOT NULL,
  `exchange_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `funds`
--

DROP TABLE IF EXISTS `funds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `funds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act` bit(1) NOT NULL DEFAULT b'1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `oid` int(11) DEFAULT NULL,
  `fund_name` varchar(150) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `management_fee` decimal(6,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `group_permissions`
--

DROP TABLE IF EXISTS `group_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `fund_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `holidays`
--

DROP TABLE IF EXISTS `holidays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `holidays` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country_id` int(11) NOT NULL,
  `holiday_day` tinyint(4) NOT NULL,
  `holiday_month` tinyint(4) NOT NULL,
  `holiday_desc` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `industries`
--

DROP TABLE IF EXISTS `industries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `industries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `industry_code` varchar(100) NOT NULL,
  `industry_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ledgers`
--

DROP TABLE IF EXISTS `ledgers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ledgers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act` tinyint(1) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fund_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `custodian_id` int(11) DEFAULT NULL,
  `ledger_date` date NOT NULL,
  `trade_date` date NOT NULL,
  `settlement_date` date DEFAULT NULL,
  `trade_id` int(11) NOT NULL,
  `trade_crd` datetime NOT NULL,
  `ledger_debit` decimal(18,4) NOT NULL,
  `ledger_credit` decimal(18,4) NOT NULL,
  `ledger_cfd` tinyint(1) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `ledger_quantity` decimal(18,6) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `other_account_id` int(11) NOT NULL,
  `trinv` text,
  `ref_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55220 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pdq_actives`
--

DROP TABLE IF EXISTS `pdq_actives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pdq_actives` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sec_id` int(11) NOT NULL,
  `sec_name` varchar(100) NOT NULL,
  `sec_type` int(11) NOT NULL,
  `provider_id` tinyint(3) unsigned NOT NULL,
  `ticker` varchar(50) DEFAULT NULL,
  `ric_code` varchar(50) DEFAULT NULL,
  `yahoo_done` tinyint(1) NOT NULL,
  `google_done` tinyint(1) NOT NULL,
  `bloomberg_done` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2950 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pdq_prices`
--

DROP TABLE IF EXISTS `pdq_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pdq_prices` (
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sec_id` int(11) NOT NULL,
  `provider_id` tinyint(1) NOT NULL,
  `price` decimal(18,6) NOT NULL,
  `price_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pdq_updates`
--

DROP TABLE IF EXISTS `pdq_updates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pdq_updates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sec_id` int(11) NOT NULL,
  `provider_id` tinyint(1) NOT NULL,
  `price` decimal(18,6) DEFAULT NULL,
  `price_date` datetime DEFAULT NULL,
  `yahoo_price` decimal(18,6) DEFAULT NULL,
  `yahoo_date` datetime DEFAULT NULL,
  `google_price` decimal(18,6) DEFAULT NULL,
  `google_date` datetime DEFAULT NULL,
  `bloomberg_price` decimal(18,6) DEFAULT NULL,
  `bloomberg_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=747 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `portfolios`
--

DROP TABLE IF EXISTS `portfolios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portfolios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `report_id` int(11) NOT NULL,
  `portfolio_type` varchar(10) NOT NULL,
  `run_date` date NOT NULL,
  `fund_id` int(11) NOT NULL,
  `trade_id` int(11) DEFAULT NULL,
  `sec_id` int(11) DEFAULT NULL,
  `sec_name` varchar(100) DEFAULT NULL,
  `position` decimal(18,6) DEFAULT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `price` decimal(18,6) DEFAULT NULL,
  `mkt_val_local` decimal(18,4) DEFAULT NULL,
  `mkt_val_fund` decimal(18,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=706 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prices`
--

DROP TABLE IF EXISTS `prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `price` decimal(18,6) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `price_source` varchar(50) NOT NULL,
  `price_date` date NOT NULL,
  `fx_rate` decimal(18,6) DEFAULT NULL,
  `final` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_prices` (`sec_id`,`price_source`,`price_date`)
) ENGINE=InnoDB AUTO_INCREMENT=5081 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `providers`
--

DROP TABLE IF EXISTS `providers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `providers` (
  `id` tinyint(3) unsigned NOT NULL,
  `provider_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reasons`
--

DROP TABLE IF EXISTS `reasons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reasons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act` bit(1) NOT NULL DEFAULT b'1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `oid` int(11) DEFAULT NULL,
  `reason_desc` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act` tinyint(1) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `report_type` varchar(10) NOT NULL,
  `run_date` date NOT NULL,
  `fund_id` int(11) NOT NULL,
  `calc_start_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=392 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sec_types`
--

DROP TABLE IF EXISTS `sec_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sec_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act` tinyint(1) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `oid` int(11) DEFAULT NULL,
  `sec_type` int(11) NOT NULL,
  `sec_type_name` varchar(50) NOT NULL,
  `bond` tinyint(1) DEFAULT NULL,
  `deriv` tinyint(1) DEFAULT NULL,
  `exchrate` tinyint(1) DEFAULT NULL,
  `cfd` tinyint(1) DEFAULT NULL,
  `equity` tinyint(1) DEFAULT NULL,
  `fx` tinyint(1) DEFAULT NULL,
  `otc` tinyint(1) DEFAULT NULL,
  `yellow_key` varchar(50) DEFAULT NULL,
  `supported` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `secs`
--

DROP TABLE IF EXISTS `secs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `secs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act` tinyint(1) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `oid` int(11) DEFAULT NULL,
  `sec_type_id` int(11) NOT NULL,
  `sec_name` varchar(100) NOT NULL,
  `ticker` varchar(50) DEFAULT NULL,
  `country_id` int(11) NOT NULL,
  `underlying_secid` varchar(50) DEFAULT NULL,
  `tradarid` varchar(50) DEFAULT NULL,
  `sedol` varchar(50) DEFAULT NULL,
  `beta` decimal(18,6) DEFAULT NULL,
  `delta` decimal(18,6) DEFAULT NULL,
  `strike` decimal(18,6) DEFAULT NULL,
  `prev_coupon_date` date DEFAULT NULL,
  `valpoint` decimal(18,6) DEFAULT NULL,
  `isin_code` varchar(50) DEFAULT NULL,
  `industry_id` int(11) NOT NULL,
  `first_settles_date` date DEFAULT NULL,
  `first_accrual_date` date DEFAULT NULL,
  `exchange_id` int(11) NOT NULL,
  `first_coupon_date` date DEFAULT NULL,
  `dividend_amount` decimal(18,6) DEFAULT NULL,
  `ex_date` date DEFAULT NULL,
  `dividend_date` date DEFAULT NULL,
  `cusip_code` varchar(50) DEFAULT NULL,
  `coupon_pay` decimal(18,6) DEFAULT NULL,
  `coupon` decimal(18,6) DEFAULT NULL,
  `ric_code` varchar(50) DEFAULT NULL,
  `price` decimal(18,6) DEFAULT NULL,
  `maturity` date DEFAULT NULL,
  `currency_id` int(11) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `price_source` varchar(20) DEFAULT NULL,
  `amount_shares_out` bigint(20) DEFAULT NULL,
  `coupon_frequency` varchar(5) DEFAULT NULL,
  `calc_type` varchar(20) DEFAULT NULL,
  `provider_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5325 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `settlements`
--

DROP TABLE IF EXISTS `settlements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settlements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country_id` int(11) NOT NULL,
  `sec_type_id` int(11) NOT NULL,
  `settlement_days` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `trade_types`
--

DROP TABLE IF EXISTS `trade_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trade_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act` bit(1) NOT NULL DEFAULT b'1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trade_type` varchar(50) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `credit_debit` varchar(6) DEFAULT NULL,
  `debit_account_id` int(11) NOT NULL,
  `credit_account_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `traders`
--

DROP TABLE IF EXISTS `traders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `traders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act` bit(1) NOT NULL DEFAULT b'1',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `oid` int(11) DEFAULT NULL,
  `trader_name` varchar(100) NOT NULL,
  `trader_login` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `trades`
--

DROP TABLE IF EXISTS `trades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trades` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act` tinyint(1) DEFAULT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `oid` int(11) DEFAULT NULL,
  `fund_id` int(11) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `trade_type_id` int(11) NOT NULL,
  `reason_id` int(11) DEFAULT NULL,
  `broker_id` int(11) DEFAULT NULL,
  `trader_id` int(11) DEFAULT NULL,
  `custodian_id` int(11) DEFAULT NULL,
  `order_quantity` decimal(18,6) DEFAULT NULL,
  `quantity` decimal(18,6) NOT NULL,
  `broker_contact` varchar(100) DEFAULT NULL,
  `trade_date` date NOT NULL,
  `settlement_date` date DEFAULT NULL,
  `currency_id` int(11) NOT NULL,
  `price` decimal(40,20) DEFAULT NULL,
  `commission` decimal(18,6) DEFAULT NULL,
  `tax` decimal(18,6) DEFAULT NULL,
  `other_costs` decimal(18,6) DEFAULT NULL,
  `order_time` datetime DEFAULT NULL,
  `decision_time` datetime DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `cancelled` tinyint(1) DEFAULT NULL,
  `executed` tinyint(1) DEFAULT NULL,
  `consideration` decimal(18,6) DEFAULT NULL,
  `notional_value` decimal(18,6) DEFAULT NULL,
  `is_cfd` tinyint(1) DEFAULT NULL,
  `filled` decimal(18,6) DEFAULT NULL,
  `execution_price` decimal(40,20) DEFAULT NULL,
  `charge_accrual` tinyint(1) DEFAULT NULL,
  `accrued` decimal(18,6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11483 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` char(40) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `valuation_reports`
--

DROP TABLE IF EXISTS `valuation_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `valuation_reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `act` tinyint(1) NOT NULL,
  `crd` datetime NOT NULL,
  `final` tinyint(1) NOT NULL,
  `pos_date` date NOT NULL,
  `fund_id` int(11) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `sec_type_id` int(11) NOT NULL,
  `quantity` decimal(18,6) NOT NULL,
  `price` decimal(18,6) DEFAULT NULL,
  `currency_id` int(11) NOT NULL,
  `fx_rate` decimal(18,6) DEFAULT NULL,
  `accrued` decimal(18,4) DEFAULT NULL,
  `notion_val_local` decimal(18,4) DEFAULT NULL,
  `mkt_val_local` decimal(18,4) DEFAULT NULL,
  `mkt_val_usd` decimal(18,4) DEFAULT NULL,
  `mkt_val_fund` decimal(18,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5953 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-10-22 15:05:31
