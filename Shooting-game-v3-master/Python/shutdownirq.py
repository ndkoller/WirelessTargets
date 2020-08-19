#!/usr/bin/python
# ATXRaspi/MightyHat interrupt based shutdown/reboot script
# Script by Tony Pottier, Felix Rusu

# Arduino Shooting Game
# Developed and coded by Andreas Olsson
# This system is free to use, modify if you need, but i cannot give you support then.
# To use this system you need Arduino and Raspberry Pi.
# Follow my facebook page for info on updates on the system.
# Please visit my blog page for more info and links to facebook and how to build it:
# https://shootinggameblog.wordpress.com
# If you need support, contact me thru the facebook page in English or Swedish
# If you find some bugs, please inform me.

#This is an modified version for the ATXRaspi : https://lowpowerlab.com/shop/product/91
#Replace the original in /etc/ folder with this.
#What this will do is to clear the database of active games and other active stuff that needs to be
#Reseted each start. So when the system turns down or restarted it will clear that out from the database.
import RPi.GPIO as GPIO
import mysql.connector as mysql
import os
import sys
import time
time.sleep(10)
GPIO.setmode(GPIO.BCM) 
db = mysql.connect(
    host="localhost",
    user="username",
    passwd="password",
    database="database"
)
pulseStart = 0.0
REBOOTPULSEMINIMUM = 0.2	#reboot pulse signal should be at least this long (seconds)
REBOOTPULSEMAXIMUM = 1.0	#reboot pulse signal should be at most this long (seconds)
SHUTDOWN = 7							#GPIO used for shutdown signal
BOOT = 8								#GPIO used for boot signal

# Set up GPIO 8 and write that the PI has booted up
GPIO.setup(BOOT, GPIO.OUT, initial=GPIO.HIGH)

# Set up GPIO 7  as interrupt for the shutdown signal to go HIGH
GPIO.setup(SHUTDOWN, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)

print "\n=========================================================================================="
print "   ATXRaspi shutdown IRQ script started: asserted pins (",SHUTDOWN, "=input,LOW; ",BOOT,"=output,HIGH)"
print "   Waiting for GPIO", SHUTDOWN, "to become HIGH (short HIGH pulse=REBOOT, long HIGH=SHUTDOWN)..."
print "=========================================================================================="
try:
	while True:	
		GPIO.wait_for_edge(SHUTDOWN, GPIO.RISING)
		shutdownSignal = GPIO.input(SHUTDOWN)
		pulseStart = time.time() #register time at which the button was pressed
		while shutdownSignal:
			time.sleep(0.2)
			if(time.time() - pulseStart >= REBOOTPULSEMAXIMUM):
				print "\n====================================================================================="
				print "            SHUTDOWN request from GPIO", SHUTDOWN, ", halting Rpi ..."
				print "====================================================================================="
				savesql = "UPDATE targets SET sendok = 0, testok = '0'"
				cura = db.cursor()
				cura.execute(savesql)
				db.commit()
				curi = db.cursor()
				savesqla = "UPDATE settings SET sendover = '0', testtargets = '0' WHERE id ='1'"
				curi.execute(savesqla)
				db.commit()
				delsql = "DELETE FROM activegame"
				cudel = db.cursor()
				cudel.execute(delsql)
				db.commit()
				delsql = "DELETE FROM activeplayers"
				cudel = db.cursor()
				cudel.execute(delsql)
				db.commit()
				delsql = "DELETE FROM activequick"
				cudel = db.cursor()
				cudel.execute(delsql)
				db.commit()
				delsql = "DELETE FROM activetimed"
				cudel = db.cursor()
				cudel.execute(delsql)
				db.commit()
				delsql = "DELETE FROM activerapid"
				cudel = db.cursor()
				cudel.execute(delsql)
				db.commit()
				delsql = "DELETE FROM printjob"
				cudel = db.cursor()
				cudel.execute(delsql)
				db.commit()
				os.system("sudo poweroff")
				sys.exit()
			shutdownSignal = GPIO.input(SHUTDOWN)
		if time.time() - pulseStart >= REBOOTPULSEMINIMUM:
			print "\n====================================================================================="
			print "            REBOOT request from GPIO", SHUTDOWN, ", recycling Rpi ..."
			print "====================================================================================="
			savesql = "UPDATE targets SET sendok = 0, testok = '0'"
			cura = db.cursor()
			cura.execute(savesql)
			db.commit()
			curi = db.cursor()
			savesqla = "UPDATE settings SET sendover = '0', testtargets = '0' WHERE id ='1'"
			curi.execute(savesqla)
			db.commit()
			delsql = "DELETE FROM activegame"
			cudel = db.cursor()
			cudel.execute(delsql)
			db.commit()
			delsql = "DELETE FROM activeplayers"
			cudel = db.cursor()
			cudel.execute(delsql)
			db.commit()
			delsql = "DELETE FROM activequick"
			cudel = db.cursor()
			cudel.execute(delsql)
			db.commit()
			delsql = "DELETE FROM activetimed"
			cudel = db.cursor()
			cudel.execute(delsql)
			db.commit()
			delsql = "DELETE FROM activerapid"
			cudel = db.cursor()
			cudel.execute(delsql)
			db.commit()
			delsql = "DELETE FROM printjob"
			cudel = db.cursor()
			cudel.execute(delsql)
			db.commit()
			os.system("sudo reboot")
			sys.exit()
		if GPIO.input(SHUTDOWN): #before looping we must make sure the shutdown signal went low
			GPIO.wait_for_edge(SHUTDOWN, GPIO.FALLING)
except:
	pass 
finally:
	GPIO.cleanup()
