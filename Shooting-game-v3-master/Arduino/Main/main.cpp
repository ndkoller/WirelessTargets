/*
	Arduino Shooting Game
	Developed and coded by Andreas Olsson
	This system is free to use, modify if you need, but i cannot give you support then.
	To use this system you need Arduino and Raspberry Pi.
	Follow my facebook page for info on updates on the system.
	
	Please visit my blog page for more info and links to facebook and how to build it:
		https://shootinggameblog.wordpress.com
	
	If you need support, contact me thru the facebook page in English or Swedish
	If you find some bugs, please inform me.
  
We use serial to communicate. small letters to activate different functions.
It has built in failsafe so it not get stuck. You can set the time on myDesiredTime
Comment out DEBUG define tag before you use it live. This hide debug text on serial monitor.
*/
#include <Arduino.h>
#include <SPI.h>
#include "nRF24L01.h"
#include "RF24.h"
//Used for testing only over serial monitor.
#define DEBUG

RF24 myRadio(9, 10); //Connection for nRF24L01
const uint64_t addresses[4] = {0xF0F0F0F0E1LL, 0xABCDABCD71LL, 0xF0F0F0F0C3LL, 0xF0F0F0F0C1LL};

struct mottager
{
  int K = 1;   // The recive id number for the target
  int L = 0;   // Is recived
  float O = 1; //Battery volt
};
typedef struct mottager Package1;
Package1 inkommande;

struct sander
{
  int A = 1; // The recive id number for the target
  int B = 0; // If it hit mode or test.
};
typedef struct sander Package;
Package sandare;

void sendTest();
void quickDraw();
void timeMode();
void rapidFire();
void sendBattery();
void testShoot();

//The incoming information on serial

String user_input;
String targets;
String QuickData;
String timedData;
String rapidData;

//FailSafe
long startTime,stopTime;
//Set how long it will stay inside a while loop until failsafe will break it.
long myDesiredTime = 15000;
int missTarget = 0;

//Hold game information
int MaxRounds = 0;
unsigned long time1;
unsigned long time2;
float interval1;
float timedRun;

int RunTest = 0;
int getTarg = 0;
int quickAdd = 0;
int timedAdd = 0;
int rapidAdd = 0;
int AmTargets = 0; //The amount of targets.
int AmTargetsLeft = 0;
int amountPassed = 0;
int recIDAdd = 0;
/*
It's not possible to make dymanic arrays in Arduino, so this is predefinded with
20 slots for targets, if you need more. Then add more.
Don't add targets id here, they are added in the web system on Raspberry pi.
Until a better solution we will do like this.
*/
int targID[] = {0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0};
int recID[] = {0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0};
float batteryID[] = {0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0}; //To store battery status that sends over.

void setup()
{
  Serial.begin(9600);
  delay(100);
  myRadio.begin();
  myRadio.setPALevel(RF24_PA_MIN); //Use min when testing, then change to MAX for better distance.
  myRadio.setDataRate(RF24_250KBPS); //Better distance if sending low data.
  myRadio.setRetries(15, 15);
  myRadio.setChannel(108); //Keep abow wifi channels
  myRadio.openReadingPipe(1, addresses[0]);
  myRadio.openWritingPipe(addresses[1]);
  myRadio.startListening();
  delay(100);
#ifdef DEBUG
  Serial.println("Starting upp");
#endif
}

void loop()
{
  while (Serial.available())
  {
    /*
    The Main system will wait for serial communication from
    a Raspberry Pi computer.

    */
    if (getTarg == 1)
    {
      //First get the amount of targets.
      if (amountPassed == 1)
      {
        if (recIDAdd == 1)
        {
          targets = Serial.readStringUntil('\n');
          recID[AmTargetsLeft] = targets.toInt();
          #ifdef DEBUG
          Serial.println(targets);
          Serial.println("Recive ID Added");
          Serial.println(recID[AmTargetsLeft]);
#endif
          AmTargetsLeft = AmTargetsLeft + 1;
          targets = "";
          
          Serial.println("c"); //Confirm OK

          recIDAdd = 0;
          if (AmTargetsLeft == AmTargets)
          {
            getTarg = 0;
            amountPassed = 0;
#ifdef DEBUG
            Serial.println("Targets is done added");
#endif
          }
        }
        else
        {
          targets = Serial.readStringUntil('\n');
          targID[AmTargetsLeft] = targets.toInt();
          
#ifdef DEBUG
          Serial.println("Target Added");
          Serial.println(targID[AmTargetsLeft]);
#endif
          Serial.println("b"); //Confirm OK
          recIDAdd = 1;
          targets = "";
        }
      }
      else
      {
        targets = Serial.readStringUntil('\n');
        AmTargets = targets.toInt();
        Serial.println("a"); //Confirm OK
#ifdef DEBUG
        Serial.println("Targets amount added");
        Serial.println(AmTargets);
#endif
        amountPassed = 1;
        targets = "";
      }
    }
    else if (quickAdd == 1)
    {
      QuickData = Serial.readStringUntil('\n');
      MaxRounds = QuickData.toInt();
      #ifdef DEBUG
          Serial.println("Quick Added, start the game.");
#endif
      delay(500);
      quickAdd = 0;
      quickDraw(); //Start the Quickdraw
    }
    else if (timedAdd == 1)
    {
      timedData = Serial.readStringUntil('\n');
      timedRun = timedData.toFloat();
      timedRun = timedRun * 1000; //Convert to correct type
      delay(500);
      timedAdd = 0;
      timeMode(); //Start the Timemode
    }
    else if (rapidAdd == 1)
    {
      rapidData = Serial.readStringUntil('\n');
      MaxRounds = rapidData.toInt();
      delay(500);
      rapidAdd = 0;
      rapidFire(); //Start the Rapidfire
    }
    else
    {
      user_input = Serial.readStringUntil('\n');
    }
    //We use small letters to activate different function.
    if (user_input == "a")
    {
      //Here is to send over the targets id codes
      delay(500);
      Serial.println("x");
      getTarg = 1;
      user_input = "";
      AmTargetsLeft = 0;
      AmTargets = 0;
        #ifdef DEBUG
  Serial.println("Start add targets");
  #endif
    }
    if (user_input == "b")
    {
      //Here we are going to activate the communication test.
      delay(500);
      Serial.println("d");
      user_input = "";
      RunTest = 1;
      #ifdef DEBUG
  Serial.println("Start testing targets");
  #endif
      sendTest();
    }
    if (user_input == "c")
    {
      //Lets start quickdraw
      delay(500);
      Serial.println("x");
      user_input = "";
      quickAdd = 1; //Enable to get information for game.
      #ifdef DEBUG
  Serial.println("Quicktime Activated");
  #endif
    }
    if (user_input == "d")
    {
      //Lets start timed mode
      delay(500);
      Serial.println("x");
      user_input = "";
      timedAdd = 1; //Enable to get information for game.
      #ifdef DEBUG
  Serial.println("Timed Mode activated");
  #endif
    }
    if (user_input == "e")
    {
      //Lets start rapid fire
      delay(500);
      Serial.println("x");
      user_input = "";
      rapidAdd = 1;
      #ifdef DEBUG
  Serial.println("Rapid Activated");
  #endif
    }
    if (user_input == "f")
    {
      //Send battery status
      delay(500);
      #ifdef DEBUG
      Serial.println("Sending Battery");
      #endif
      sendBattery();
    }
    if (user_input == "g")
    {
      //Test Shoot
      delay(500);
      #ifdef DEBUG
      Serial.println("Test shoot each target");
      #endif
      testShoot();
    }
  }
}

void sendTest()
{
  /*
    This is the communication test.
    It will test the communication with the targets.
  */
  while (RunTest == 1)
  {
    // Hold it inside this loop until it's done with all targets.
    int i;
    int oTargets = AmTargets - 1;
    int WaitForResponse = 0;
    // Code to get some value of "n"

    for (i = 0; i <= oTargets; i++)
    {
      // Go thru the targets, one by one.
      sandare.A = targID[i];
      sandare.B = 2; //Enable test mode
      myRadio.stopListening();
      myRadio.write(&sandare, sizeof(sandare));
      myRadio.startListening();
      WaitForResponse = 1;
      //Add the send stuff first then
      #ifdef DEBUG
              Serial.println("Send to target " + String(sandare.A));
              Serial.println("Wait for response " + String(recID[i]));
              #endif
      startTime = millis();
      while (WaitForResponse == 1)
      {
      stopTime=millis();
    //Quit after 15 secounds.
    if (stopTime - startTime >= myDesiredTime) {
      #ifdef DEBUG
      Serial.println("Activate Failsafe");
      #endif
      WaitForResponse = 0;
      Serial.println(targID[oTargets]);
      Serial.println("z"); //Failsafe mode
    } 
        //Wait for an answer from the target.
        if (myRadio.available(addresses[0]))
        {
          
         myRadio.read(&inkommande, sizeof(inkommande));
            #ifdef DEBUG
              Serial.println("Radio Avalible");
              Serial.println(String(inkommande.K));
              #endif
            

            if (inkommande.K == recID[i])
            {
              //We got respons from it.
              batteryID[i] = inkommande.O; //Save battery status
              #ifdef DEBUG
              Serial.println("Target Responded");
              #endif
              Serial.println(String(inkommande.K));
              WaitForResponse = 0;
              delay(2000); //Delay so pi computer can confirm function.
              
            }
        }
      }
      if (i >= oTargets)
      {
        Serial.println("x"); //Confirm OK
        RunTest = 0;
      }
    }
  }
}

void testShoot()
{
  //This is simple test shoot for target by target.
  int oTargets = AmTargets; //Int always start with 0
  int sendAway = 0;

  for (int i = 0; i < oTargets; i++)
  {
    //We loop thru each target here.
    //First we need to get the target id
    sandare.A = targID[i];
    sandare.B = 1; //Activate hit target
    inkommande.K = recID[i];
    sendAway = 1;
    myRadio.stopListening();
    myRadio.write(&sandare, sizeof(sandare));
    myRadio.startListening();
      startTime=millis();
      while (sendAway == 1)
    {
      stopTime=millis();
    //Failsafe after X secounds
    if (stopTime - startTime >= myDesiredTime) {
      #ifdef DEBUG
      Serial.println("Activate Failsafe");
      #endif
      sendAway = 0;
      missTarget = 1;
    } 
      //Keep it here until we got the hit response.
      if (myRadio.available(addresses[0]))
      {
          myRadio.read(&inkommande, sizeof(inkommande));

          if (inkommande.K == recID[i])
          {
            batteryID[i] = inkommande.O; //Save battery status
            if (inkommande.L == 2) {
              //We did not get a registrated hit so we are going to reg as a non hit.
              missTarget = 1;
            }
            /*
                                 * If its the correct target
                                 */
            sendAway = 0;
          }
      }
    }
    if (missTarget == 1) {
      missTarget = 0;
      i = i - 1; //If one target goes in safe, don't count as a hit.
    }

  }
  Serial.println("x"); //Confirm Done!
}

void quickDraw()
{
  //The quickdraw function
  randomSeed(millis());
  int oTargets = AmTargets; //Int always start with 0
  int currentPort = random(oTargets); //Make the targets random.
  int newPort = random(oTargets);
  int sendAway = 0;

  //Make a loop thru the rounds.
  for (int i = 0; i < MaxRounds; i++)
  {
    delay(random(3000) + 1000); //Make a random delay
    time1 = millis();
    //We need to add the target info depending on what port it is.
    sandare.A = targID[currentPort];
    sandare.B = 1; //Activate hit target
    inkommande.K = recID[currentPort];
    sendAway = 1;
    myRadio.stopListening();
    myRadio.write(&sandare, sizeof(sandare));
    myRadio.startListening();
      startTime=millis();
    while (sendAway == 1)
    {
      stopTime=millis();
    //Failsafe after X secounds
    if (stopTime - startTime >= myDesiredTime) {
      #ifdef DEBUG
      Serial.println("Activate Failsafe");
      #endif
      sendAway = 0;
      missTarget = 1;
    } 
      //Keep it here until we got the hit response.
      if (myRadio.available(addresses[0]))
      {
       
          myRadio.read(&inkommande, sizeof(inkommande));

          if (inkommande.K == recID[currentPort])
          {
            batteryID[currentPort] = inkommande.O; //Save battery status
            if (inkommande.L == 2) {
              //We did not get a registrated hit so we are going to reg as a non hit.
              missTarget = 1;
            }
            /*
                                 * If its the correct target
                                 */
            sendAway = 0;
          }
      }
    }
    if (missTarget == 1) {
      missTarget = 0;
      i = i - 1; //If one target goes in safe, don't count as a hit.
    } else {
    time2 = millis();
    interval1 = (time2 - time1);
    interval1 = interval1 / 1000;
    Serial.println(interval1);
    }
    newPort = random(oTargets);
    while (newPort == currentPort)
      newPort = random(oTargets);
    currentPort = newPort;
  }
  delay(5000);
  Serial.println("x"); //Confirm Done!
}

void timeMode()
{
  //The timed mode
  randomSeed(millis());
  int oTargets = AmTargets; //Int always start with 0
  int currentPort = random(oTargets); //Make the targets random.
  int newPort = random(oTargets);
  int sendAway = 0;
  int hitCounter = 0;
  time1 = millis();
  interval1 = 0;

  while (interval1 < timedRun)
  {
    //The main loop that holds the time for playing
    sandare.A = targID[currentPort];
    sandare.B = 1; //Activate hit target
    inkommande.K = recID[currentPort];
    sendAway = 1;
    myRadio.stopListening();
    myRadio.write(&sandare, sizeof(sandare));
    myRadio.startListening();
    startTime=millis();
    while (sendAway == 1)
    {
      stopTime=millis();
    //Failsafe after X secounds
    if (stopTime - startTime >= myDesiredTime) {
      #ifdef DEBUG
      Serial.println("Activate Failsafe");
      #endif
      sendAway = 0;
      missTarget = 1;
    } 
      //Wait for response
      if (myRadio.available(addresses[0]))
      {
       
          myRadio.read(&inkommande, sizeof(inkommande));

          if (inkommande.K == recID[currentPort])
          {
            /*
                                 * If its the correct target
                                 */
            batteryID[currentPort] = inkommande.O;
            if (inkommande.L == 2) {
              //We did not get a registrated hit so we are going to reg as a non hit.
              missTarget = 1;
            }
            sendAway = 0;
          }
      }
    }
    if (missTarget == 1) {
      missTarget = 0;
      time2 = millis();
    interval1 = (time2 - time1);
    } else {
    hitCounter++;
    time2 = millis();
    interval1 = (time2 - time1);
    }
    newPort = random(oTargets);
    while (newPort == currentPort)
      newPort = random(oTargets);
    currentPort = newPort;
    
  }
  Serial.println(hitCounter);
  delay(5000);
  Serial.println("x"); //OK
}

void rapidFire()
{
  //Rapid fire mode
  randomSeed(millis());
  int oTargets = AmTargets; //Int always start with 0
  int currentPort = random(oTargets);
  int newPort = random(oTargets);
  int sendAway = 0;
  time1 = millis();
  for (int i = 0; i < MaxRounds; i++)
  {
    sandare.A = targID[currentPort];
    sandare.B = 1; //Activate hit target
    inkommande.K = recID[currentPort];
    sendAway = 1;
    myRadio.stopListening();
    myRadio.write(&sandare, sizeof(sandare));
    myRadio.startListening();
    startTime=millis();
    while (sendAway == 1)
    {
      stopTime=millis();
    //Failsafe after X secounds
    if (stopTime - startTime >= myDesiredTime) {
      #ifdef DEBUG
      Serial.println("Activate Failsafe");
      #endif
      sendAway = 0;
      missTarget = 1;
    } 
      //Wait for response
      if (myRadio.available(addresses[0]))
      {
       
          myRadio.read(&inkommande, sizeof(inkommande));

          if (inkommande.K == recID[currentPort])
          {
            /*
                                 * If its the correct target
                                 */
            batteryID[currentPort] = inkommande.O;
            if (inkommande.L == 2) {
              //We did not get a registrated hit so we are going to reg as a non hit.
              missTarget = 1;
            }
            sendAway = 0;
          }
      }
    }
    if (missTarget == 1) {
      missTarget = 0;
      i = i - 1; //Don't registrate as a hit
      newPort = random(oTargets);
    while (newPort == currentPort)
      newPort = random(oTargets);
    currentPort = newPort;
    } else {
    newPort = random(oTargets);
    while (newPort == currentPort)
      newPort = random(oTargets);
    currentPort = newPort;
    }
  }
  time2 = millis();
  interval1 = (time2 - time1);
  interval1 = interval1 / 1000;
  Serial.println(interval1);
  delay(5000);
  Serial.println("x"); //OK
}


void sendBattery() {
  //This is to send over battery to the pi.
  int i;
    int oTargets = AmTargets - 1;
    
    for (i = 0; i <= oTargets; i++)
    {
      delay(1000);
      Serial.println(targID[i]);
      delay(2000);
      Serial.println(batteryID[i]);
      delay(2000);
      
      if (i >= oTargets)
      {
        Serial.println("x"); //Confirm OK
      }
    }
}
