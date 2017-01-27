Arduino PC to LCD (beta)
=================

Data output from the computer to the LCD screen. In the current version the only supported Linux server.

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

Run application:
~~~
cd /var/www/html/arduino_pc2lcd
php -f index.php
~~~

