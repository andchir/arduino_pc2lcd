Arduino PC to LCD
=================

Data output from the computer to the LCD screen. In the current version the only supported Linux server.

![Arduino PC to LCD](https://raw.githubusercontent.com/andchir/arduino_pc2lcd/master/img/pc2lcd001-sm.jpg)

To find the address of the Arduino:
~~~
ls /dev/tty*
~~~

Example address: ``/dev/ttyACM0``

TTY settings:
~~~
stty -F /dev/ttyACM0 cs8 115200 ignbrk \
-brkint -icrnl -imaxbel -opost -onlcr \
-isig -icanon -iexten -echo -echoe \
-echok -echoctl -echoke noflsh -ixon \
-crtscts
~~~

Test display:
~~~
echo "Hello Arduino" > /dev/ttyACM0
~~~

Additional information: [http://playground.arduino.cc/Interfacing/LinuxTTY](http://playground.arduino.cc/Interfacing/LinuxTTY)

Run application. Print action output to LCD and auto switch:
~~~
cd /var/www/html/arduino_pc2lcd
php -f index.php
~~~

Print action output to CLI:
~~~
php -f index.php print <action_name>
~~~

Print action output to LCD once:
~~~
php -f index.php print_lcd <action_name>
~~~
