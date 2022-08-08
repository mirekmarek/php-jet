# PHP Jet Framework

PHP Jet is a powerful **PHP8** framework. Of course, it includes **MVC architecture**, **ORM**, **modularity**, **REST API server** and so on, but above all, it offers **incredible performance, flexibility and freedom**.

It is a modern framework that will **help** you and **not restrict and limit** you.

It is a field-proven framework that has been developed for 12 years and practically used in various kinds of online applications and has recently been fully adapted to PHP8.

Framework places great emphasis not only on security and speed, but also on flexibility and adaptability.

PHP Jet is not only a library of classes, but includes **integrated development tools** such as **Jet Profiler** and **Jet Studio**, which **make work incredibly efficient and easy**.

This framework is developed for applications operated in the **European Union**. For this reason, it places great emphasis on the development of **localizable and cross-border applications**.

This is **not a new** experimental project, but a practically used and **mature framework** that leverages more than twenty years of experience with the development of online applications of the author of this framework.

Project website and documentation: https://www.php-jet.net/

##Installation

PHP Jet is distributed as one package which includes everything:
* PHP Jet library
* Example application
* Example application installer
* Profiler
* Powerful development tool Jet Studio

All you have to do to try PHP Jet is:
* Make sure you have PHP8 installed - PHP Jet requires PHP8 and newer.
* Create some "virtual domain" in yours hosts file. For example: jet.lc.
* You can create database (MySQL / MariaDB) for testing if you want. But it is not necessary, because PHP Jet supports SQLite database, and it's good enough for testing.
* Configure your Apache (add virtual configuration) or NGINX (add server block) webserver.
* Unpack package contains Jet and its example application into the directory which is the root directory of apache virtual / nginx server block.
* Open virtual domain (for example https://jet.lc/) in your web browser and install example application.
* Let's start to discover the world of PHP Jet ;-)

###Apache web server
You probably know how to create apache virtual. If not then check [documentation](https://httpd.apache.org/docs/2.4/vhosts/index.html).

PHP Jet needs nothing more than**mod_rewrite enabled**to run with the Apache web server (see documentation of your system).

And watch out for a possible catch with the necessity to enable [AllowOverride All](https://httpd.apache.org/docs/current/mod/core.html)!

###NGINX web server
If you are not familiar with NGINX server blocks then check out NGINX [documentation](https://www.nginx.com/resources/wiki/start/topics/examples/server_blocks/).

To run Jet (or your projects already built on Jet), it is necessary to create such a server block.

Below you will find an example of how such a server block should look:

`server {
    listen 80;
    listen [::]:80;
    listen 443 ssl;
    listen [::]:443 ssl;
    ssl_certificate /etc/ssl/certs/ssl-cert-snakeoil.pem;
    ssl_certificate_key /etc/ssl/private/ssl-cert-snakeoil.key;

    server_name jet.lc;
    root /home/user/projects/Jet;

    index index.html;

    location ~ /\.ht {
        deny all;
    }
    
    location ~ ^/(css|js|images)/ {
        try_files $uri $uri/ =404;
    }
    location / {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root/application/bootstrap.php;
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
    }


    location ~ ^/_tools/studio/(css|js|images)/ {
        try_files $uri $uri/ =404;
    }
    location /_tools/studio/ {
        include fastcgi_params;
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
    }
}`

Notes on the NGINX "server block":

* Of course, you need to set the server_name and root parameters according to the situation on your computer.
* An example includes the use of SSL certificates intended for local testing offered by NGINX itself. Of course, these must not be used in heavy traffic.
* The "location" related to the Jet Studio tool (/_tools/studio/) **should not exist at all on the production server** as well as the tool itself.