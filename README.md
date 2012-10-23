# OpenTOMS - Open Trade Order Management System

In the current financial software sector, there is a distinct lack
of open source offerings. The trade order management software sector
is currently dominated by the likes of Tradar, LineData, Charles River,
etc. I wanted to create an open source alternative to these systems. One
that would match the aspirations of the vast majority of young investment
start-ups.

The OpenTOMS system is written in PHP and uses the CakePHP MVC framework.
This will hopefully allow for a broad spectrum of contributors to the project.

We encourage you to participate in this project and to help make it the first
and best open source tool for trade and order management.

## Setting Up OpenTOMS on your computer
1. Get the clone from here onto your target system.

2. Install a web server, e.g. Apache.

3. Install MySQL on your target system. Create a database, call it what you like,
say "opentomsdb" and create the database from the database script file "opentomsdb.sql":

mysql -u [username] -p [password] < opentomsdb.sql
mysql -u [username] -p [password] < add_currencies.sql

This should create the database structure with no data.

CakePHP
AllowOverride ALL
Mod_Rewrite
check permissions on directories

php5-mysql

4. run groups/build_acl

run users/initDB

5. Create a fund
Create a Trader
