# web-interface-raspberry-BLE

* Install bluez
<br />Get the last version of bluez here (http://www.bluez.org/download/)
<br /> Follow these instructions: https://codeyarns.com/2017/06/05/how-to-build-and-install-bluez/
* Install bluepy with pip
Follow these instructions: https://github.com/IanHarvey/bluepy
* Install apache
<br />Open sudoers file with -> `sudo visudo` command
<br />Add `www-data ALL=(ALL) NOPASSWD: ALL` under `#includedir /etc/sudoers.d`
* Give root access apache user name
* Copy these files to www repertory
