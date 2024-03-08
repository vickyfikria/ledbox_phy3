# This is repository / history log for convert pyhton 2 to python 3 of product ledbox tech4sport

### Install apache2 and php
To render php files :
Because /ledbox/www use `simplexml_load_file()` that might cause exception , need to install php-xml

currently php version is 8.2, so :

`sudo apt-get install php 8.2-xml`

`php -m | grep SimpleXML`
this will return SimpleXML which means simple XML installed

### Change Defult Directory
`sudo nano /etc/apache2/site-available/000-default.conf`
```
<VirtualHost *:80>

...
...

DocumentRoot /var/www/html

DocumentRoot /home/pi/ledbox/www

DirectoryIndex index.php

</VirtualHost>
```



### Apache Error log
`sudo nano /var/log/apache2/error.log`


