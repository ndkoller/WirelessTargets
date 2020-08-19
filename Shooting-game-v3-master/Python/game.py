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

# The game function will be build from here. Uses serial and mysql to work.
# The webpage updates mysql that this python script will check and start working.
# This must be run on boot
# ID number for game types: 1 = Quickdraw, 2 = Timedmode, 3 = Rapidfire

#import MySQLdb
import mysql.connector as mysql
import serial
import time
from datetime import date
from datetime import datetime

now = datetime.now()
# Add support for serial, uncomment when arduino is connected
ser = serial.Serial('/dev/ttyUSB0', 9600) #Check with usb port is used for the arduino
time.sleep(10) #Don't remove this

# Define some main functions
actid = 0
gameid = 0
isdone = 0
isactive = 0
beginplay = 0
sendover = 0
testtargets = 0
checkplayer = 0
rungame = 0
gtype = 0
playcount = 0

# For targets
mid = 0
tottargets = 0
totargets1 = 0
targid = 0
sendid = 0
testok = 0
sendok = 0
waitard = 0
waitser = 0
sendbat = 0


# Open the DB Connection
db = mysql.connect(
    host="localhost",
    user="username",
    passwd="password",
    database="database"
)



while True:
    db.commit()
    # Check if we are going to send over targets.
    #print("Wait for job")
    tacur = db.cursor()
    sqlo = "SELECT * FROM settings WHERE id = 1"
    tacur.execute(sqlo)
    resa = tacur.fetchall()
    for arow in resa:
        sendover = arow[4]
        testtargets = arow[5]
        sendbat = arow[6]
        #print(arow)
        #print(sendover)
    while sendover == 1:
        print("Send over targets")
        cur = db.cursor()
        sql = "SELECT * FROM targets WHERE sendok = 0"
        tottargets = cur.execute(sql)
        # Fetch all the rows in a list of lists.
        results = cur.fetchall()
        tottargets = cur.rowcount
        print(tottargets)
        print("Activate sendover")
        ser.write(b"a")
        waitard = 1
        # Send the amount of targets
        while waitard == 1:
            #print("Wait for serial")
            if ser.in_waiting > 0:
                line = ser.readline()
                print(line)
                oline = str(line.strip())
                print(oline)
                if oline == "x":
                    print("Submit amount targets")
                    ser.write(str(tottargets))
                    #ser.write(b"%d" % str(tottargets))
                    waitser = 1
                    while waitser == 1:
                        if ser.in_waiting > 0:
                            line = ser.readline()
                            oline = str(line.strip())
                            print(oline)
                            if oline == "a":
                                #Targets submitted go on.
                                time.sleep(5)
                                waitser = 0
                                waitard = 0

            
        rowsum = 0
        for row in results:
            mid = row[0]
            targid = row[1]
            sendid = row[3]
            testok = row[4]
            sendok = row[5]
            # Now we send to arduino
            rowsum = rowsum + 1
            waitard = 1
            print("Target ID:")
            print(mid)
            while waitard == 1:
                ser.write("%s" % str(targid))
                #ser.write(b"%d" % targid)
                waitser = 1
                while waitser == 1:
                    if ser.in_waiting > 0:
                        line = ser.readline()
                        oline = str(line.strip())
                        print(oline)
                        if oline == "b":
                            print("Target ID Submitted")
                            print(targid)
                            waitser = 0
                # ser.write(str(sendid))
                ser.write("%s" % str(sendid))
                #ser.write(b"%d" % sendid)
                waitsera = 1
                while waitsera == 1:
                    if ser.in_waiting > 0:
                        line = ser.readline()
                        print(line)
                        oline = str(line.strip())
                        print(oline)
                        if oline == "c":
                            print("Sendid submited")
                            print(sendid)
                            waitsera = 0
                            cura = db.cursor()
                            print("Save as sendok")
                            savesql = "UPDATE targets SET sendok = %s WHERE id =%s"
                            val = ("1", mid)
                            cura.execute(savesql, val)
                            db.commit()
                            waitard = 0
                            print(rowsum)
                            print(tottargets)
                            if rowsum == tottargets:
                                #
                                waitseri = 1
                                while waitseri == 1:
                                    #if ser.in_waiting > 0:
                                    #    line = ser.readline()
                                    #    print(line)
                                    #    testrin = str(line.strip())
                                    #    if testrin == "x":
                                    print("Save Results")
                                    curi = db.cursor()
                                    savesqla = "UPDATE settings SET sendover = '0', testtargets = '1' WHERE id ='1'"
                                    curi.execute(savesqla)
                                    db.commit()
                                    sendover = 0
                                    testtargets = 1
                                    waitseri = 0
    
    while sendbat == 1:
        print("Get battery status")
        bacursor = db.cursor()
        sql = "SELECT * FROM targets"
        bacursor.execute(sql)
        tarcount = 0
        resbat = bacursor.fetchall()
        totisrows = bacursor.rowcount
        ser.write("f")
        print(totisrows)

        for row in resbat:
            tarid = row[0]
            waitser = 1
            while waitser == 1:
                if ser.in_waiting > 0:
                    line = ser.readline()
                    batline = str(line.strip())
                    print(batline)
                    mycursor = db.cursor()
                    sql = "UPDATE targets SET batstatus = %s, batok = %s WHERE id =%s"
                    val = (batline, "1", tarid)
                    mycursor.execute(sql, val)
                    db.commit()
                    if tarcount < totisrows:
                        tarcount = tarcount + 1
                    waitser = 0
                    print(tarcount)
            else:
                if tarcount == totisrows:
                    print("Exit battery")
                    waitser = 1
                    tarcount = 0
                    while waitser == 1:
                        if ser.in_waiting > 0:
                            line = ser.readline()
                            batline = str(line.strip())
                            print(batline)
                            if batline == "x":
                                curi = db.cursor()
                                savesql = "UPDATE settings SET sendbat = '0' WHERE id ='1'"
                                curi.execute(savesql)
                                db.commit()
                                waitser = 0
                                sendbat = 0



    
    while testtargets == 1:
        # We now have added all the targets to arduino, so lets test the communication.
        print("Test targets")
        ser.flushInput()
        time.sleep(10)
        cursor = db.cursor()
        sql = "SELECT * FROM targets"
        total = cursor.execute(sql)
        results = cursor.fetchall()
        ser.write("b")
        waitser = 1
        while waitser == 1:
            if ser.in_waiting > 0:
                line = ser.readline()
                noline = str(line.strip())
                print(noline)
                if noline == "d":
                    waitser = 0
        for row in results:
            mid = row[0]
            sendid = row[3]
            waitser = 1
            print(sendid)
            while waitser == 1:
                #print("Test")
                if ser.in_waiting > 0:
                    line = ser.readline()
                    print("Got from serial:")
                    print(line)
                    oline = str(line.strip())
                    print(oline)
                    if oline == "z":
                        #Problem connecting to it
                        curi = db.cursor()
                        savesql = "UPDATE targets SET testok = %s WHERE id =%s"
                        val = ("2", mid)
                        curi.execute(savesql, val)
                        db.commit()
                        print("Save error to target")
                        waitser = 0
                    if oline == sendid:
                    #if line.rstrip() == sendid:
                        curi = db.cursor()
                        savesql = "UPDATE targets SET testok = %s WHERE id =%s"
                        val = ("1", mid)
                        curi.execute(savesql, val)
                        db.commit()
                        print("Save test to target")
                        waitser = 0
        waitser = 1
        while waitser == 1:
            if ser.in_waiting > 0:
                line = ser.readline()
                print(line)
                if line.rstrip() == "x":
                    waitser = 0
                    curi = db.cursor()
                    savesql = "UPDATE settings SET testtargets = '0' WHERE id ='1'"
                    curi.execute(savesql)
                    db.commit()
                    testtargets = 0

    cursor = db.cursor()

    sql = "SELECT * FROM activegame WHERE beginplay = 1"
    totrows = cursor.execute(sql)
    results = cursor.fetchall()
    totrows = cursor.rowcount
    for row in results:
        actid = row[0]
        gameid = row[1]
        isdone = row[2]
        beginplay = row[3]
        print(row)

    if totrows > 0:
        print("Game ID:")
        print(gameid)
        cursor1 = db.cursor()
        sql1 = "SELECT * FROM games WHERE id =%s"
        val = (gameid,)
        totrowss =  cursor1.execute(sql1, val)
        results1 = cursor1.fetchall()
        totrowss = cursor1.rowcount
        for row1 in results1:
            gamount = row1[2]
            gtype = row1[3]
            print("Row:")
            print(gamount)
            print(gtype)
            print("Begin play")
            print(beginplay)
        
            while beginplay == 1:
                # So long the isactive is enable we will be enable the game function.
                # Todo here is to activate the serial communication to the arduino
                #print("Begin play now")
                if gtype == "1":
                    print ("Quickdraw")
                    # We need to start quickdraw. First wait for active player
                    # Count total players
                    ocurs = db.cursor()
                    sql = "SELECT * FROM activeplayers"
                    atotrow = ocurs.execute(sql)
                    miresults = ocurs.fetchall()
                    atotrow = ocurs.rowcount #Now we know total players
                    checkplayer = 1
                    while checkplayer == 1:
                        db.commit()
                        cursor = db.cursor()
                        sql = "SELECT * FROM activeplayers WHERE isactive = 1 AND runnow = 1"
                        totisrows = cursor.execute(sql)
                        results = cursor.fetchall()
                        totisrows = cursor.rowcount
                        for row in results:
                            pid = row[0]
                        if totisrows == 1:
                            rungame = 1
                            while rungame == 1:
                                # Now we have a player we can start game we already have the info to pass over.
                                ser.write(b"c")  # Letter c is for quicktime
                                waitser = 1
                                while waitser == 1:
                                    if ser.in_waiting > 0:
                                        line = ser.readline()
                                        line = str(line.strip())
                                        if line == "x":
                                            waitser = 0
                                            time.sleep(5)  # wait for arduino to get ready
                                # pass ower rounds, now we start listening
                                ser.write("%s" % str(gamount))
                                waitser = 1
                                roundnow = 1
                                while waitser == 1:
                                    # wait for serial to pass over the results to save.

                                    if ser.in_waiting > 0:
                                        line = ser.readline()
                                        line = str(line.strip())
                                        print("Incoming")
                                        print(line)
                                        if line == "x":
                                            # Player is done save it
                                            curi = db.cursor()
                                            savesql = "UPDATE activeplayers SET isactive = %s, isplayed = %s, runnow = %s WHERE id =%s"
                                            val = ("0", "1", "0", pid)
                                            curi.execute(savesql, val)
                                            db.commit()
                                            waitser = 0
                                            rungame = 0
                                            playcount = playcount + 1
                                            if playcount >= atotrow:
                                                checkplayer = 0
                                                playcount = 0
                                                print("Players done")


                                        else:
                                            # Save result to mysql
                                            mycursor = db.cursor()
                                            sql = "INSERT INTO activequick (garound, garesult, isdone, isadded, gplayer, gamedate, gametime) VALUES (%s, %s, %s, %s, %s, %s, %s)"
                                            val = (roundnow, line, "1", "0", pid, date.today(), now.strftime("%H:%M:%S"))
                                            mycursor.execute(sql, val)
                                            db.commit()
                                            if roundnow < gamount:
                                                roundnow = roundnow + 1
                    else:
                        # No more players lets mark as done.
                        curi = db.cursor()
                        savesql = "UPDATE activegame SET isdone = %s, beginplay = %s WHERE id =%s"
                        val = ("1", "0", actid)
                        curi.execute(savesql, val)
                        db.commit()
                        beginplay = 0


                if gtype == "2":
                    print ("Timed mode")
                    # We need to start quickdraw. First wait for active player
                    # Count total players
                    ocurs = db.cursor()
                    sql = "SELECT * FROM activeplayers"
                    atotrow = ocurs.execute(sql)
                    miresults = cursor.fetchall()
                    atotrow = ocurs.rowcount #Now we know total players
                    checkplayer = 1
                    while checkplayer == 1:
                        db.commit()
                        cursor = db.cursor()
                        sql = "SELECT * FROM activeplayers WHERE isactive = 1 AND runnow = 1"
                        totisrows = cursor.execute(sql)
                        results = cursor.fetchall()
                        totisrows = cursor.rowcount
                        for row in results:
                            pid = row[0]
                        if totisrows == 1:
                            rungame = 1
                            while rungame == 1:
                                # Now we have a player we can start game we already have the info to pass over.
                                ser.write(b"d")  # Letter c is for quicktime
                                waitser = 1
                                while waitser == 1:
                                    if ser.in_waiting > 0:
                                        line = ser.readline()
                                        line = str(line.strip())
                                        if line == "x":
                                            waitser = 0
                                            time.sleep(5)  # wait for arduino to get ready
                                # pass ower rounds, now we start listening
                                ser.write("%s" % str(gamount))
                                print(gamount)
                                waitser = 1
                                roundnow = 1
                                while waitser == 1:
                                    # wait for serial to pass over the results to save.

                                    if ser.in_waiting > 0:
                                        line = ser.readline()
                                        line = str(line.strip())
                                        print("Incoming")
                                        print(line)
                                        if line == "x":
                                            # Player is done save it
                                            curi = db.cursor()
                                            savesql = "UPDATE activeplayers SET isactive = %s, isplayed = %s, runnow = %s WHERE id =%s"
                                            val = ("0", "1", "0", pid)
                                            curi.execute(savesql, val)
                                            db.commit()
                                            waitser = 0
                                            rungame = 0
                                            playcount = playcount + 1
                                            if playcount >= atotrow:
                                                checkplayer = 0
                                                playcount = 0

                                        else:
                                            # Save result to mysql
                                            mycursor = db.cursor()
                                            sql = "INSERT INTO activetimed (garesults, isdone, isadded, gplayer, gamedate, gametime) VALUES (%s, %s, %s, %s, %s, %s)"
                                            val = (line, "1", "0", pid, date.today(), now.strftime("%H:%M:%S"))
                                            mycursor.execute(sql, val)
                                            db.commit()
                    else:
                        # No more players lets mark as done.
                        curi = db.cursor()
                        savesql = "UPDATE activegame SET isdone = %s, beginplay = %s WHERE id =%s"
                        val = ("1", "0", actid)
                        curi.execute(savesql, val)
                        db.commit()
                        beginplay = 0
                if gtype == "3":
                    print ("Rapidfire")
                    # We need to start quickdraw. First wait for active player
                    # Count total players
                    ocurs = db.cursor()
                    sql = "SELECT * FROM activeplayers"
                    atotrow = ocurs.execute(sql)
                    miresults = cursor.fetchall()
                    atotrow = ocurs.rowcount #Now we know total players
                    checkplayer = 1
                    while checkplayer == 1:
                        db.commit()
                        cursor = db.cursor()
                        sql = "SELECT * FROM activeplayers WHERE isactive = 1  AND runnow = 1"
                        totisrows = cursor.execute(sql)
                        results = cursor.fetchall()
                        totisrows = cursor.rowcount
                        for row in results:
                            pid = row[0]
                        if totisrows == 1:
                            rungame = 1
                            while rungame == 1:
                                # Now we have a player we can start game we already have the info to pass over.
                                ser.write(b"e")  # Letter c is for quicktime
                                waitser = 1
                                while waitser == 1:
                                    if ser.in_waiting > 0:
                                        line = ser.readline()
                                        line = str(line.strip())
                                        if line == "x":
                                            waitser = 0
                                            time.sleep(5)  # wait for arduino to get ready
                                # pass ower rounds, now we start listening
                                ser.write("%s" % str(gamount))
                                waitser = 1
                                roundnow = 1
                                while waitser == 1:
                                    # wait for serial to pass over the results to save.

                                    if ser.in_waiting > 0:
                                        line = ser.readline()
                                        line = str(line.strip())
                                        print("Incoming")
                                        print(line)
                                        if line == "x":
                                            # Player is done save it
                                            curi = db.cursor()
                                            savesql = "UPDATE activeplayers SET isactive = %s, isplayed = %s, runnow = %s WHERE id =%s"
                                            val = ("0", "1", "0", pid)
                                            curi.execute(savesql, val)
                                            db.commit()
                                            waitser = 0
                                            rungame = 0
                                            playcount = playcount + 1
                                            if playcount >= atotrow:
                                                checkplayer = 0
                                                playcount = 0

                                        else:
                                            # Save result to mysql
                                            mycursor = db.cursor()
                                            sql = "INSERT INTO activerapid (garesults, isdone, isadded, gplayer, gamedate, gametime) VALUES (%s, %s, %s, %s, %s, %s)"
                                            val = (line, "1", "0", pid, date.today(), now.strftime("%H:%M:%S"))
                                            mycursor.execute(sql, val)
                                            db.commit()
                    else:
                        # No more players lets mark as done.
                        curi = db.cursor()
                        savesql = "UPDATE activegame SET isdone = %s, beginplay = %s WHERE id =%s"
                        val = ("1", "0", actid)
                        curi.execute(savesql, val)
                        db.commit()
                        beginplay = 0
