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
The following instructions help you to get the system up and running quickly on your
computer. The target platform is LAMP so if you are already starting out with such a
system, then you can skip to step 1 now. Otherwise, please install the LAMP components
on your development platform. 

For Linux users, install apache2, mysql-server and php5 from your distro repositories. You
will also need libapache2-mod-php5 and php5-mysql.

For Windows users you can download WAMP from <http://www.wampserver.com/>

1.	Put the OpenTOMS source somewhere onto your target system.

2.	In MySQL create a database, call it anything you want, e.g. opentomsdb.

3.	Set up the database schema by using the scripts in the database_setup folder as follows:

	> mysql -u [username] -p [password] [database-name] < database_schema

	Optionally, I recommend you populate the tables with some sample data as follows:

	> mysql -u [username] -p [password] [database-name] < database_data

4.	Set Apache to point to the app/webroot folder.
	
	Below is a cut out from the /etc/apache2/sites-available/default file.

		DocumentRoot /home/jt/Projects/OpenTOMS/app/webroot/
		<Directory />
	               	Options FollowSymLinks
	               	AllowOverride All
        	</Directory>
        	<Directory /home/jt/Projects/OpenTOMS/app/webroot/>
                	Options Indexes FollowSymLinks MultiViews
                	AllowOverride All
                	Order allow,deny
                	allow from all
        	</Directory>

5.	In the /app/config/database.php file, put in the details for the database that you
	created in step 2 above so that the system can locate its database.

6.	Things to make sure that it will all work.

	> Ensure that you set AllowOverride All for the DocumentRoot
	> 
	> Ensure that Mod_Rewrite is enabled in Apache2. This will require an Apache restart.
	> 
	> Ensure that the directories have the proper permissions so that the www-data (default
	> Apache user) can read the files. Also important that www-data can browse all folders (set the
	> x on the folders)

7. 	CakePHP uses an ACL system to control access to pages. The ACL parameters are stored in the
	aros, acos and aros_acos tables in the database. These tables can be initialised by running the
	following two pages from your browser. I'm assuming you have set up Apache according to the above
	steps so that http://localhost is pointing to the root of the project source folder.

	> http://localhost/groups/build_acl
	> http://localhost/users/initDB

	The first of the two pages above must be run again every time you add a new page or element 
	to the project!

8. 	Login in the system using the admin user (no password) set up in step 7 above and go to the
	Fund page (under Standing Data menu). Add a new fund and call it whatever you like. Then go the
	Trader page also under Standing menu and add a new trader calling him/her whatever you like.
	You are now ready to start adding trades and running valuation reports.

	Any problems, please email me John at jt@lprc.co.uk or catch me on Skype (jtam123) and I will
	be very happy to help you!
