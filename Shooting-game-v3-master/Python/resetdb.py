#!/usr/bin/python

# Arduino Shooting Game
# Developed and coded by Andreas Olsson
# This system is free to use, modify if you need, but i cannot give you support then.
# To use this system you need Arduino and Raspberry Pi.
# Follow my facebook page for info on updates on the system.
# Please visit my blog page for more info and links to facebook and how to build it:
# https://shootinggameblog.wordpress.com
# If you need support, contact me thru the facebook page in English or Swedish
# If you find some bugs, please inform me.

#Incase you don't use ATXRaspi make this script run only ones first before everything at start up
#Or before the system shuts down. To reset the database incase something has happend.
import mysql.connector as mysql
import time
time.sleep(10)
db = mysql.connect(
    host="localhost",
    user="username",
    passwd="password",
    database="database"
)
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
