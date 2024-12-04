# [Yield Exchange](https://www.yieldexchange.ca)
## Intro

Yield Exchange is a platform for Canadian organizations to get the best rates for their GICs from Canadian financial institutions.

## Uses
* MySQL
* PHP
* Bootstrap
* Jquery
* Phinx (PHP Database Migrations) [https://book.cakephp.org/phinx/0/en/intro.html]

## Install & Run
1. Install Composer:https://getcomposer.org/download/
2. Install Git: https://git-scm.com/downloads
3. Install PHP(Version 5.6 or greater) and Apache (latest version)
4. Install Mysql(latest Version and make sure PDO MYSQL is enabled)
5. Create a mysql db with the name "yield_prod"
6. Open the computer terminal and cd /path/to/your/local/server (eg /var/www/html/ or eg C://xampp/htdocs)
7. Type git clone https://github.com/GICEE/Yield-Exchange.git and press ENTER (wait for the git to clone/download this repository to the location you are in (eg /var/www/html/ or eg C://xampp/htdocs))
8. From the project create a file config/ini.php and add the content shared on teams
9. From the project directory (eg /var/www/html/ or eg C://xampp/htdocs) open terminal and run composer install
10. Once step 9 is complete run vendor/bin/phinx migrate && vendor/bin/phinx seed:run
11. once step 10 is complete you should have the project database tables.
12. You are done!
    
    ## Managing database changes/update
    - Read more from this doc https://book.cakephp.org/phinx/0/en/intro.html on how to create new tables and update existing tables
    
    
