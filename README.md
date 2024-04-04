# LedBox python 3 
## Tech4sport for RPi 4 : 64-bit or 32-bit OS ?
It has flushBuffer binary file which only provided in 32-bit. You can easily check with CLI command file :
```
file flushBuffer
#32
```
32-bit Raspbian OS required instead of 64-bit.

## RPi 4 and Raspbian OS-lite Bookworm

> [!IMPORTANT]
> library installation not required if we recompile from flushBuffer source

Issue with RaspbianOS 32 Lite : when runnin ./startled
> /home/pi/ledbox/bin/flushBuffer : error while loadiing shared libraries libcrypto.so.1.1 
This [reference](https://domoticz.com/forum/viewtopic.php?t=40906) might help
```
wget http://security.debian.org/debian-security/pool/updates/main/o/openssl/libssl1.1_1.1.1n-0+deb11u5_armhf.deb
sudo dpkg -i libssl1.1_1.1.1n-0+deb11u5_armhf.deb
```
> /home/pi/ledbox/bin/flushBuffer : error while loadiing shared libraries libgraphicsmagick++Q16.12.so 
```
wget http.us.debian.org/debian/pool/main/g/graphicsmagick/libgraphicsmagick++-q16-12_1.4+really1.3.40-4_armhf.deb
sudo dpkg -i libgraphicsmagick++-q16-12_1.4+really1.3.40-4_armhf.deb
```
> /home/pi/ledbox/bin/flushBuffer : error while loadiing shared libraries libwebp.so.6 
```
wget http://ftp.de.debian.org/debian/pool/main/libw/libwebp/libwebp6_0.6.1-2+deb10u1_armhf.deb
sudo dpkg -i libwebp6_0.6.1-2+deb10u1_armhf.deb
```
> /home/pi/ledbox/bin/flushBuffer : error while loadiing shared libraries libtiff.so.5 
```
wget http://ftp.de.debian.org/debian/pool/main/t/tiff/libtiff5_4.2.0-1+deb11u5_armhf.deb
sudo dpkg -i libtiff5_4.2.0-1+deb11u5_armhf.deb
```




## installation/getserialnumber.py
### Data flow

![This is data flow to get serial number as response](/images/getserialnumber_small.png)

### Conversion

1. ConfigParser
import ConfigParset  #python2
import configparser  #python3

3. urllib
import urllib   #python2
import urllib.request #python3
Reference [here](https://python-forum.io/thread-15740.html)

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
`sudo nano /etc/apache2/apache2.conf`
```
After :
 <Directory />
     Options Indexes FollowSymLinks Includes ExecCGI
     AllowOverride All
     Require all granted
 </Directory>
```

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


## hostapd
Installation :
`sudo apt-get install hostapd`

Configure hostapd.conf /etc/hostapd/hostapd
`sudo nano /etc/hostapd/hostapd.conf`

Run /etc/hostapd/hostapd
`sudo hostapd /etc/hostapd/hostapd`

Start service hostapd
`systemctl start hostapd`

Check service hostapd
`systemctl status hostapd`

## ----------------------
## Fresh Installation
## ----------------------
1. Prepare 16GB sdcard
2. Choose Raspbian OS Bookworm 32-bit lite
3. update installation
   `sudo apt-get install`
4. install apache2
   `sudo apt-get install apache2`
5. install git  and copy source firmware
   `sudo git clone https://github.com/ledbox_phy3.git`
6.  install graphics library :
   `sudo apt-get install libgraphicsmagick++-dev libwebp-dev -y`
7. install serial :
   `sudo apt-get install python3-serial`
8. install bluetooth
   `sudo apt-get install python3-bluez`
9. install pygame
    `sudo apt-get install python3-pygame`
10. install PIL
`sudo apt-get install python3-pil`
11. install opencv2
`sudo apt-get install python3-opencv`
12. install netifaces
`sudo apt-get install python3-netifaces`
13. install pexpect
`sudo apt-get install python3-pexpect`
14. install dbus
`sudo apt-get install python3-dbus`
15. 
This is for autopairing without type anything on raspberry pi side.
FileNotfoundError : No such file or directory : /usr/local/bin/auto-agent
`sudo cp ~/ledbox/auto-agent /usr/local/bin`

16. line 275, in advertise_service Bluetooth Error
check solution in [here](https://forums.raspberrypi.com/viewtopic.php?t=132470)

`sudo nano /lib/systemd/system/bluetooth.service`
Change :
`[Service]
ExecStart=/usr/lib/bluetooth/bluetoothd`
To :
`ExecStart=/usr/lib/bluetooth/bluetoothd --compat`
then `sudo reboot`

17. _bluetooth.error: (13, 'Permission denied')
`sudo chmod o+rw /var/run/sdp`
need to run that each time booting

18. copy ledbox firmware to home/pi, then launch ledbox.py
`python ledbox.py`

19. if nothing show on ledbox,
need to disable sound card alsa from this [tutorial] (https://www.instructables.com/Disable-the-Built-in-Sound-Card-of-Raspberry-Pi/)

`cd /etc/probe.d`
`vi alsa-blacklist.conf`


