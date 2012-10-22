CREATE TABLE trades(
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	act bit NOT NULL DEFAULT 1,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	oid INT NULL,
	fund_id INT NOT NULL,
	sec_id INT NOT NULL,
	trade_type_id INT NOT NULL,
	reason_id INT NULL,
	broker_id INT NULL,
	trader_id INT NULL,
	quantity DECIMAL(18,6) NOT NULL,
	broker_contact VARCHAR(100),
	trade_date date NOT NULL,
	settlement_date date NULL,
	currency VARCHAR(3) NOT NULL,
	price DECIMAL(18,6) NOT NULL,
	commission DECIMAL(18,6) NULL,
	tax DECIMAL(18,6) NULL,
	other_costs DECIMAL(18,6) NULL,
	order_time datetime NULL,
	decision_time datetime NULL,
	notes VARCHAR(255) ,
	cancelled bit NOT NULL,
	executed bit NOT NULL,
	consideration DECIMAL(18,6) NULL,
	notional_value DECIMAL(18,6) NULL,
	is_cfd bit NULL,
	filled DECIMAL(18,6) NULL,
	execution_price DECIMAL(18,6) NULL,
	charge_accrual bit NULL
);

CREATE TABLE funds(
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	act bit NOT NULL DEFAULT 1,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	oid INT NULL,
	fund_name VARCHAR(150) NULL,
	fund_currency VARCHAR(3) NULL,
	management_fee DECIMAL(6,4) NULL
);

CREATE TABLE secs (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	act bit NOT NULL DEFAULT 1,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	oid INT NULL,
	sectype_id INT NOT NULL,
	sec_name VARCHAR(100)  NOT NULL,
	ticker VARCHAR(50)  NULL,
	country VARCHAR(50)  NULL,
	underlying_secid VARCHAR(50)  NULL,
	tradarid VARCHAR(50)  NULL,
	sedol VARCHAR(50)  NULL,
	beta DECIMAL(18,6) NULL,
	delta DECIMAL(18,6) NULL,
	prev_coupon_date date NULL,
	valpoint DECIMAL(18,6) NULL,
	isin_code VARCHAR(50) NULL,
	industry VARCHAR(50)  NULL,
	first_settles_date date NULL,
	first_accrual_date date NULL,
	exchange VARCHAR(50)  NULL,
	first_coupon_date date NULL,
	dividend_amount DECIMAL(18,6) NULL,
	ex_date date NULL,
	dividend_date date NULL,
	cusip_code VARCHAR(50)  NULL,
	coupon_pay DECIMAL(18,6) NULL,
	coupon DECIMAL(18,6) NULL,
	ric_code VARCHAR(50)  NULL,
	price DECIMAL(18,6) NULL,
	maturity date NULL,
	currency VARCHAR(3)  NULL,
	expiry_date date NULL,
	price_source VARCHAR(20)  NULL,
	amount_shares_out bigint NULL
);

CREATE TABLE trade_types (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	act bit NOT NULL DEFAULT 1,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	oid INT NULL,
	trade_type VARCHAR(50)  NOT NULL,
	category VARCHAR(50)  NULL ,
	credit_debit VARCHAR(6)  NULL
);

CREATE TABLE reasons (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	act bit NOT NULL DEFAULT 1,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	oid INT NULL,
	reason_desc VARCHAR(100) NOT NULL
);


CREATE TABLE brokers (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	act bit NOT NULL DEFAULT 1,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	oid INT NULL,
	broker_name VARCHAR(50) NOT NULL,
	broker_long_name VARCHAR(255) NULL,
	commission_rate DECIMAL(6,4) NULL
);

CREATE TABLE traders (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	act bit NOT NULL DEFAULT 1,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	oid INT NULL,
	trader_name VARCHAR(100) NOT NULL,
	trader_login VARCHAR(50) NULL
);

CREATE TABLE sec_types (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	act bit NOT NULL DEFAULT 1,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	oid INT NULL,
	sec_type INT NOT NULL,
	sec_type_name VARCHAR(50) NOT NULL,
	bond boolean NULL,
	deriv boolean NULL,
	exchrate boolean NULL,
	cfd boolean NULL,
	equity boolean NULL,
	fx boolean NULL,
	otc boolean NULL,
	yellow_key VARCHAR(50) NULL,
	supported boolean NULL
);

CREATE TABLE prices (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	price DECIMAL(18,6) NOT NULL,
	sec_id INT NOT NULL,
	price_source VARCHAR(50) NOT NULL,
	price_date date NOT NULL
);


ALTER TABLE prices DROP COLUMN act;
ALTER TABLE prices DROP COLUMN oid;
ALTER TABLE prices ADD CONSTRAINT ux_prices UNIQUE (sec_id, price_source, price_date);

CREATE TABLE countries (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	country_code VARCHAR(3) NOT NULL,
	country_name VARCHAR(100) NOT NULL
);

CREATE TABLE exchanges (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	exchange_code VARCHAR(20) NOT NULL,
	exchange_name VARCHAR(100) NOT NULL
);

CREATE TABLE industries (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	industry_code VARCHAR(20) NOT NULL,
	industry_name VARCHAR(100) NOT NULL
);

CREATE TABLE currencies (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	currency_iso_code VARCHAR(3) NOT NULL,
	currency_name VARCHAR(100) NOT NULL
);

CREATE TABLE portfolios (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	run_date DATE NOT NULL,
	fund_id INT NOT NULL,
	trade_id INT NOT NULL
);

CREATE TABLE reports (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	report_type VARCHAR(10) NOT NULL,
	run_date DATE NOT NULL,
	fund_id INT NOT NULL,
	stock_portfolioid INT NOT NULL
);

CREATE TABLE holidays(
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	country_id INT NOT NULL,
	holiday_day TINYINT NOT NULL,
	holiday_month TINYINT NOT NULL,
	holiday_desc VARCHAR(100) NULL
);

CREATE TABLE settlements(
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	country_id INT NOT NULL,
	sectype_id INT NOT NULL,
	settlement_days TINYINT NOT NULL
);

CREATE TABLE accounts(
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	account_name VARCHAR(50) NOT NULL
);

CREATE TABLE ledgers(
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	act bit NOT NULL DEFAULT 1,
	locked bit NOT NULL DEFAULT 0,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	account_id INT NOT NULL,
	ledger_month TINYINT NOT NULL,
	ledger_year SMALLINT NOT NULL,
	ledger_date DATE NOT NULL,
	trade_id INT NOT NULL,
	ledger_debit DECIMAL(18,4) NOT NULL,
	ledger_credit DECIMAL(18,4) NOT NULL,
	ledger_balance DECIMAL(18,4) NOT NULL
);


CREATE TABLE balances(
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	act bit NOT NULL DEFAULT 1,
	locked bit NOT NULL DEFAULT 0,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	fund_id INT NOT NULL,
	account_id INT NOT NULL,
	ledger_month TINYINT NOT NULL,
	ledger_year SMALLINT NOT NULL,
	balance_debit DECIMAL(18,4) NOT NULL,
	balance_credit DECIMAL(18,4) NOT NULL,
	currency_id INT NOT NULL,
	balance_quantity DECIMAL(18,6) NOT NULL,
	sec_id INT NOT NULL
);


CREATE TABLE position_reports(
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	act bit NOT NULL DEFAULT 1,
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	final tinyint(1) NOT NULL,
	pos_date date NOT NULL,
	fund_id INT NOT NULL,
	sec_id INT NOT NULL,
	quantity DECIMAL(18,6) NOT NULL,
	price DECIMAL(18,6) NULL,
	currency_id INT NOT NULL,
	fx_rate decimal(18,6) NULL,
	mkt_val_local decimal(18,4) NULL,
	mkt_val_fund decimal(18,4) NULL
);

CREATE TABLE custodians (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	act bit NOT NULL,
	crd datetime NOT NULL,
	custodian_name VARCHAR(50) NOT NULL,
	custodian_long_name VARCHAR(255) NULL
);

CREATE TABLE group_permissions (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	group_id INT NOT NULL,
	fund_id INT NOT NULL
);

CREATE TABLE attachments (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	file MEDIUMBLOB NULL
);

CREATE TABLE pdq_actives (
	id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	done bit NOT NULL,
	crd datetime NOT NULL,
	sec_id INT NOT NULL,
	ticker VARCHAR(50) NULL,
	ric_code VARCHAR(50) NULL,
	isin_code VARCHAR(50) NULL,
	provider VARCHAR(50) NOT NULL,
	price DECIMAL(18,6) NOT NULL
);

CREATE TABLE providers (
	id TINYINT UNSIGNED PRIMARY KEY NOT NULL,
	provider_name VARCHAR(50) NOT NULL
);

CREATE TABLE pdq_prices (
	crd timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	sec_id INT NOT NULL,
	provider_id TINYINT(1) NOT NULL,
	price DECIMAL(18,6) NOT NULL
);

CREATE TABLE pdq_updates (
	id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
	sec_id INT NOT NULL,
	provider_id TINYINT(1) NOT NULL,
	price DECIMAL(18,6) NOT NULL,
	yahoo_price DECIMAL(18,6) NULL,
	google_price DECIMAL(18,6) NULL,
	update_price_table TINYINT(1) NOT NULL
);