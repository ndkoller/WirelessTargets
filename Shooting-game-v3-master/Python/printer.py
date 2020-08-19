#!/usr/bin/python
#-*- coding: utf-8 -*-

# Arduino Shooting Game
# Developed and coded by Andreas Olsson
# This system is free to use, modify if you need, but i cannot give you support then.
# To use this system you need Arduino and Raspberry Pi.
# Follow my facebook page for info on updates on the system.
# Please visit my blog page for more info and links to facebook and how to build it:
# https://shootinggameblog.wordpress.com
# If you need support, contact me thru the facebook page in English or Swedish
# If you find some bugs, please inform me.

#This is the terminal printer script.
#If you don't use terminal printer, you don't need this.
#If you use it, make it run at boot
from __future__ import print_function
from Adafruit_Thermal import *
import mysql.connector as mysql
import time
printer = Adafruit_Thermal("/dev/serial0", 19200, timeout=5) #This is the connection for gpio serial. If your pi has bluetooth you need to disable that.
time.sleep(10) #Dont remove this
printresult = 0
quickround = 0
quickroundtotal = 0
quickresult = 0

idno = 0
gametype = 0
gamecode = "0"
startprint = 0
activegame = 0
gamename = ""
gadate = 0
gatime = 0
eanprint = 0


# Open the DB Connection
db = mysql.connect(
    host="localhost",
    user="username",
    passwd="password",
    database="database"
)
while True:
    db.commit()
    tacur = db.cursor()
    sqlo = "SELECT * FROM printjob"
    tacur.execute(sqlo)
    resa = tacur.fetchall()
    for arow in resa:
        idno = arow[1]
        gametype = arow[1]
        gamecode = arow[2]
        startprint = arow[3]
        activegame = arow[4]
        eanprint = arow[5]
    
    if startprint == 1:
       #Printer is activated so lets start printing the header.
        print("Gametype: "+str(gametype))
        print("Gamecode: "+str(gamecode))
        printer.justify('C')
        printer.setSize('L')
        printer.println("Arduino Shooting Game")
        printer.setSize('S')
        printer.println('Developed by Andreas Olsson')
        printer.feed(2)
        #Now we need to know what type of game we are going to print out.
        if gametype == 1:
            #We have an quicktime game
            printer.setSize('M')
            printer.println('Quicktime')
            printer.feed(1)
            if activegame == 1:
                #It's an active game so lets print that results. Then we don't need to check the gamecode.
                #Lets check the active players
                ocurs = db.cursor()
                sql = "SELECT * FROM activeplayers"
                atotrow = ocurs.execute(sql)
                results = ocurs.fetchall()
                atotrow = ocurs.rowcount
                #We need now to loop thru the players to print results.
                for row in results:
                    playid = row[0]
                    playname = row[1]
                    gameid = row[2]
                    printer.setSize('S')
                    printer.print("Players: ")
                    printer.println(playname)
                    #Get some info from the game
                    gocurs = db.cursor()
                    sql = "SELECT * FROM games WHERE id = %s"
                    val = (gameid,)
                    gocurs.execute(sql, val)
                    goresult = gocurs.fetchall()
                    for gorow in goresult:
                        gamename = gorow[1]
                        quickroundtotal = gorow[2]

                    #Now lets get game info
                    gcurs = db.cursor()
                    sql1 = "SELECT * FROM activequick WHERE gplayer =%s"
                    val = (playid,)
                    gcurs.execute(sql1, val)
                    garesults = gcurs.fetchall()
                    for arow in garesults:
                        #Now we can start printing game results
                        garound = arow[1]
                        garesult= arow[2]
                        winusr= arow[6]
                        winall= arow[7]
                        printer.justify('L')
                        printer.setSize('M')
                        printer.print('Round ')
                        printer.print(garound)
                        printer.print(' of ')
                        printer.print(quickroundtotal)
                        printer.print(': ')
                        printer.println(garesult)
            else:
                #It's a saved game so lets get results from there
                #Now lets get game info
                gcurs = db.cursor()
                sql1 = "SELECT * FROM savedquick WHERE gamecode =%s"
                val = (gamecode,)
                gcurs.execute(sql1, val)
                garesults = gcurs.fetchall()
                for arow in garesults:
                #Now we can start printing game results
                    garound = arow[1]
                    garesult= arow[2]
                    winusr= arow[6]
                    winall= arow[7]
                    ganame= arow[8]
                    printer.setSize('S')
                    printer.print('Player: ')
                    printer.println(ganame)
                    gocurs = db.cursor()
                    sql = "SELECT * FROM games WHERE id = %s"
                    val = (gameid,)
                    gocurs.execute(sql, val)
                    goresult = gocurs.fetchall()
                    for gorow in goresult:
                        gamename = gorow[1]
                        quickroundtotal = gorow[2]
                    printer.justify('L')
                    printer.setSize('M')
                    printer.print('Round ')
                    printer.print(garound)
                    printer.print(' of ')
                    printer.print(quickroundtotal)
                    printer.print(': ')
                    printer.println(garesult)
        if gametype == 2:
            #We have an quicktime game
            printer.setSize('M')
            printer.println('Timed mode')
            printer.feed(1)
            if activegame == 1:
                #It's an active game so lets print that results. Then we don't need to check the gamecode.
                #Lets check the active players
                ocurs = db.cursor()
                sql = "SELECT * FROM activeplayers"
                atotrow = ocurs.execute(sql)
                results = ocurs.fetchall()
                atotrow = ocurs.rowcount
                #We need now to loop thru the players to print results.
                for row in results:
                    playid = row[0]
                    playname = row[1]
                    gameid = row[2]
                    printer.justify('C')
                    printer.setSize('S')
                    printer.print('Player: ')
                    printer.println(playname)
                    #Get some info from the game
                    gocurs = db.cursor()
                    sql = "SELECT * FROM games WHERE id = %s"
                    val = (gameid,)
                    gocurs.execute(sql, val)
                    goresult = gocurs.fetchall()
                    for gorow in goresult:
                        gamename = gorow[1]
                        quickroundtotal = gorow[2]

                    #Now lets get game info
                    gcurs = db.cursor()
                    sql1 = "SELECT * FROM activetimed WHERE gplayer =%s"
                    val = (playid,)
                    gcurs.execute(sql1, val)
                    garesults = gcurs.fetchall()
                    for arow in garesults:
                        #Now we can start printing game results
                        garesult= arow[1]
                        winusr= arow[5]
                        winall= arow[6]
                        if winall == 1:
                            printer.justify('C')
                            printer.setSize('M')
                            printer.println("Winner")
                        printer.justify('L')
                        printer.setSize('M')
                        printer.print(garesult)
                        printer.print(' hits on ')
                        printer.print(quickroundtotal)
                        printer.println(' Secounds')
            else:
                #It's a saved game so lets get results from there
                #Now lets get game info
                gcurs = db.cursor()
                sql1 = "SELECT * FROM savedtimed WHERE gamecode =%s"
                val = (gamecode,)
                gcurs.execute(sql1, val)
                garesults = gcurs.fetchall()
                for arow in garesults:
                #Now we can start printing game results
                    garesult= arow[1]
                    winusr= arow[5]
                    winall= arow[6]
                    ganame= arow[7]
                    printer.justify('C')
                    printer.setSize('S')
                    printer.print('Player: ')
                    printer.println(ganame)
                    gocurs = db.cursor()
                    sql = "SELECT * FROM games WHERE id = %s"
                    val = (gameid,)
                    gocurs.execute(sql, val)
                    goresult = gocurs.fetchall()
                    for gorow in goresult:
                        gamename = gorow[1]
                        quickroundtotal = gorow[2]
                    if winall == 1:
                        printer.justify('C')
                        printer.setSize('M')
                        printer.println("Winner")
                    printer.justify('L')
                    printer.setSize('M')
                    printer.print(garesult)
                    printer.print(' hits on ')
                    printer.print(quickroundtotal)
                    printer.println(' Secounds')
        if gametype == "3":
            #We have an rapid fire game
            printer.setSize('M')
            printer.println('Rapidfire')
            printer.feed(1)
            if activegame == "1":
                #It's an active game so lets print that results. Then we don't need to check the gamecode.
                #Lets check the active players
                ocurs = db.cursor()
                sql = "SELECT * FROM activeplayers"
                atotrow = ocurs.execute(sql)
                results = ocurs.fetchall()
                atotrow = ocurs.rowcount
                #We need now to loop thru the players to print results.
                for row in results:
                    playid = row[0]
                    playname = row[1]
                    gameid = row[2]
                    printer.setSize('S')
                    printer.print('Player: ')
                    printer.println(playname)
                    #Get some info from the game
                    gocurs = db.cursor()
                    sql = "SELECT * FROM games WHERE id = %s"
                    val = (gameid,)
                    gocurs.execute(sql, val)
                    goresult = gocurs.fetchall()
                    for gorow in goresult:
                        gamename = gorow[1]
                        quickroundtotal = gorow[2]

                    #Now lets get game info
                    gcurs = db.cursor()
                    sql1 = "SELECT * FROM activerapid WHERE gplayer =%s"
                    val = (playid,)
                    gcurs.execute(sql1, val)
                    garesults = gcurs.fetchall()
                    for arow in garesults:
                        #Now we can start printing game results
                        garesult= arow[1]
                        winusr= arow[5]
                        winall= arow[6]
                        if winall == 1:
                            printer.justify('C')
                            printer.setSize('M')
                            printer.println("Winner")
                        printer.justify('L')
                        printer.setSize('M')
                        printer.print(garesult)
                        printer.print(' secounds with ')
                        printer.print(quickroundtotal)
                        printer.println(' Hits')
            else:
                #It's a saved game so lets get results from there
                #Now lets get game info
                gcurs = db.cursor()
                sql1 = "SELECT * FROM savedrapid WHERE gamecode =%s"
                val = (gamecode,)
                gcurs.execute(sql1, val)
                garesults = gcurs.fetchall()
                for arow in garesults:
                #Now we can start printing game results
                    garesult= arow[1]
                    winusr= arow[5]
                    winall= arow[6]
                    ganame= arow[8]
                    gadate= arow[10]
                    gatime= arow[11]
                    printer.justify('C')
                    printer.setSize('S')
                    printer.print('Player: ')
                    printer.println(ganame)
                    gocurs = db.cursor()
                    sql = "SELECT * FROM games WHERE id = %s"
                    val = (gameid,)
                    gocurs.execute(sql, val)
                    goresult = gocurs.fetchall()
                    for gorow in goresult:
                        gamename = gorow[1]
                        quickroundtotal = gorow[2]
                    if winall == 1:
                        printer.justify('C')
                        printer.setSize('M')
                        printer.println("Winner")
                    printer.justify('C')
                    printer.setSize('M')
                    printer.print(garesult)
                    printer.print(' secounds with ')
                    printer.print(quickroundtotal)
                    printer.println(' Hits')
        
        #Now we print the barcode last and also add print time and date.
        printer.justify('C')
        printer.setSize('M')
        printer.println("Game played: ")
        printer.println(gamename)
        printer.feed(1)
        ocode = gamecode.encode('utf-8')
        printer.printBarcode(ocode, printer.CODE128)
        #Done now remove printjob
        delcurs = db.cursor()
        sql = "DELETE FROM printjob"
        delcurs.execute(sql)
        db.commit()
        startprint = 0

        #printer.sleep()      # Tell printer to sleep
        #printer.wake()       # Call wake() before printing again, even if reset
        printer.setDefault() # Restore printer to defaults
        printer.begin()
