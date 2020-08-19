# Shooting-game-v3
This is the repository for the upcoming version of the new rebuild Arduino Shooting Game.
An game for target hit when you shoot with soft airguns and other type of guns.
This version is complete wireless and 100 % rewritten sins the older versions.

# How it works
The Raspberry pi holds the software (php based website & python script) and send thru serial
over to arduino to start the game.
For more detail information please visit:
https://shootinggameblog.wordpress.com/
https://www.facebook.com/arduinoshooting

# Before you begin
For making this game please you need arduino and raspberry pi computer.
The raspberry pi needs a webserver with php support and mysql database.
The python script need pyserial and mysql.connect
On arduino you need the nrf24 library (see link below to download)

Please note that is programmed in platformIO so the code is little different than programming on
arduino IDE: https://platformio.org 

# Library for Arduino
You need the NRF24 library to make it work with Arduino, that is the onlything you need.
You can download it here: https://github.com/nRF24/RF24
