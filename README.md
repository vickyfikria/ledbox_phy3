# This is repository / history log for convert pyhton 2 to python 3 of product ledbox tech4sport
## installation/getserialnumber.py

!serial number [This is data flow to get serial number as response](images/getserialnumber.png)


## ledbox/www Web Server : Apache 
### Install apache2 and php
Install apache2 :

`sudo apt-get install apache2`

To render index.php files, requre to install php :

`sudo apt-get install php`

Check version : 
`php --version`

Because /ledbox/www use `simplexml_load_file()` that might cause exception , need to install php-xml

currently php version is 8.2, so :

`sudo apt-get install php 8.2-xml`

`php -m | grep SimpleXML`
this will return SimpleXML which means simple XML installed

### Change Defult Directory
Reference [here](https://askubuntu.com/questions/337874/change-apache-document-root-folder-to-secondary-hard-drive)

`sudo nano /etc/apache2/sites-available/000-default.conf`
```
<VirtualHost *:80>

...
...

    #DocumentRoot /var/www/html
    DocumentRoot /home/pi/ledbox/www
    DirectoryIndex index.php

</VirtualHost>
```
> [!IMPORTANT]
> Make sure that /home/pi (top folder of ledbox/www) is executable.
> 
> Always `sudo service apache2 restart` after make a change


### Apache Error log
`sudo nano /var/log/apache2/error.log` usefull to trace error 


