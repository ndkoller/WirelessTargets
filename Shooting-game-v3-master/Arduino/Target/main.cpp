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
  
This is the targets code. Each target need it's own identification code.
During the test process, you can use the DEBUG if you need to serial text.
But don't use it during the live process.
If you are going to use the old piezo sensor, that is used in old version of game,
comment out the SHAKE define.
And if you don't need the battery checker comment out the BATTERY define.
*/
#include <Arduino.h>
#include <SPI.h>
#include "nRF24L01.h"
#include "RF24.h"

#define DEBUG
#define BATTERY
#define SHAKE //IF SHAKE SWITCH IS USED INSTEAD OF THE OLD PIEZO

#ifdef SHAKE
int sensor;
#endif
//FailSafe
long startTime,stopTime;
//Set how long it will stay inside a while loop until failsafe will break it.
long myDesiredTime = 15000; //1000 per secound. Default is 15000 (15 Sec)

//Below you enter the ID numbers for target.
int targID = 3401; //This is the target ID
int sendID = 2401; //This is the response ID
#ifdef BATTERY
//Change the limit in milivolt for battery before the targets will warn for low battery.
float batLevel = 3200;
#endif 
//Always send and recive a package.
struct mottaget
{
  int I = 1; // The id number for the target
  int J = 0; // If it active target or test signal.
};
typedef struct mottaget Package;
Package inkommande;

struct skickar
{
  int K = 1; // The send id number for the target
  int L = 0; // Is recived
  float O = 1; //Battery volt
};
typedef struct skickar Package1;
Package1 utgaende;

RF24 myRadio(9, 10); //Connection for nRF24L01
const uint64_t addresses[4] = {0xF0F0F0F0E1LL, 0xABCDABCD71LL, 0xF0F0F0F0C3LL, 0xF0F0F0F0C1LL};
//Led pins
int LEDblue = 3;  //Blue
int LEDred = 5;   //Red
int LEDgreen = 7; //Green

//Sensor pin, used for both shake and piezo sensor.
const int knockSensor = A0;

int shootMode = 0; //When the target is active.
//This is needed when using platformIO when using custom loops.
//Remove if using Arduino IDE
void testTransmiter();
void targetHit();
void printVolts();
#ifdef BATTERY
long readVcc() {
  /* This is used to check the battery in the targets. 
    It uses the internal system to check the power.
    If the power is to low, the red light will go on.
    Undefine BATTERY if not needed.
  */
  #if defined(__AVR_ATmega32U4__) || defined(__AVR_ATmega1280__) || defined(__AVR_ATmega2560__)
    ADMUX = _BV(REFS0) | _BV(MUX4) | _BV(MUX3) | _BV(MUX2) | _BV(MUX1);
  #elif defined (__AVR_ATtiny24__) || defined(__AVR_ATtiny44__) || defined(__AVR_ATtiny84__)
    ADMUX = _BV(MUX5) | _BV(MUX0);
  #elif defined (__AVR_ATtiny25__) || defined(__AVR_ATtiny45__) || defined(__AVR_ATtiny85__)
    ADMUX = _BV(MUX3) | _BV(MUX2);
  #else
    ADMUX = _BV(REFS0) | _BV(MUX3) | _BV(MUX2) | _BV(MUX1);
  #endif  

  delay(2); // Wait for Vref to settle
  ADCSRA |= _BV(ADSC); // Start conversion
  while (bit_is_set(ADCSRA,ADSC)); // measuring

  uint8_t low  = ADCL; // must read ADCL first - it then locks ADCH  
  uint8_t high = ADCH; // unlocks both

  long result = (high<<8) | low;

  result = 1125300L / result; // Calculate Vcc (in mV); 1125300 = 1.1*1023*1000
  return result; // Vcc in millivolts
}
#endif

void setup()
{
  Serial.begin(9600);
#ifdef DEBUG
  Serial.println("Setting up Target");
#endif
  pinMode(LEDblue, OUTPUT);
  digitalWrite(LEDblue, LOW);
  pinMode(LEDgreen, OUTPUT);
  digitalWrite(LEDgreen, HIGH); //Turn on so the system shows that is online.
  pinMode(LEDred, OUTPUT);
  digitalWrite(LEDred, LOW);

  //Setup the NRF transmitter
  delay(100);
  myRadio.begin();
  myRadio.setPALevel(RF24_PA_MIN); //Use MIN on testing, change to MAX when they working good.
  myRadio.setDataRate(RF24_250KBPS); //Lower datarate gives longer distans
  myRadio.setRetries(15, 15);
  myRadio.setChannel(108); //Keep abow wifi channels
  myRadio.openReadingPipe(1, addresses[1]);
  myRadio.openWritingPipe(addresses[0]);
  myRadio.startListening();
  delay(100);
}

void loop()
{
  #ifdef BATTERY
        printVolts();
  #endif
  if (myRadio.available(addresses[1]))
  {
    #ifdef DEBUG
      Serial.println("Radio Avalible");
      #endif
    
      myRadio.read(&inkommande, sizeof(inkommande));

      if (inkommande.I == targID)
      {
        #ifdef DEBUG
      Serial.println("Target ID "+ String(inkommande.I));
      #endif
        /*
                                 * If its the correct id number
                                 */

        if (inkommande.J == 1)
        {
          //Enable the target
          shootMode = 1;
          targetHit();
        }
        else if (inkommande.J == 2)
        {
          //Enable the test
          testTransmiter();
        }
      }
   
  }
}

void targetHit()
{
//For the target hit
#ifdef DEBUG
  Serial.println("Start hit target");
#endif
  startTime = millis(); //Add start time for failsafe
  digitalWrite(LEDblue, HIGH);

  while (shootMode == 1)
  {
    stopTime=millis(); //Count stoptime for failsafe
    if (stopTime - startTime >= myDesiredTime) {
      #ifdef DEBUG
      Serial.println("Activate Failsafe");
      #endif
      utgaende.K = sendID;
      utgaende.L = 2; //For report failsafe
      #ifdef DEBUG
      Serial.println("Sending failsafe response");
#endif
      shootMode = 0;
      digitalWrite(LEDblue, LOW);
    }
    //We run inside a while loop until a hit is made.
    #ifdef SHAKE
    sensor = analogRead(knockSensor);
    if (sensor<1022){
    #else
    int val = analogRead(knockSensor);
    if (digitalRead(knockSensor) == HIGH)
    {
#endif
#ifdef DEBUG
      Serial.println("Knock");
#endif
      digitalWrite(LEDblue, LOW); //Knock is made stop light
      utgaende.K = sendID;
      utgaende.L = 1;
#ifdef DEBUG
      Serial.println("Sending recive response");
#endif
      myRadio.stopListening();
      delay(100);
      myRadio.write(&utgaende, sizeof(utgaende));
     
      shootMode = 0;
        #ifdef DEBUG
      Serial.println("Send OK");
      #endif
      
      delay(100);
      myRadio.startListening();
    }
  }
}

void testTransmiter()
{
//When test is activated, this will be started.
#ifdef DEBUG
  Serial.println("Run test signal");
#endif
  utgaende.K = sendID;
  utgaende.L = 1;
#ifdef DEBUG
  Serial.println("Sending recive response " + String(utgaende.K));
#endif
  
  myRadio.stopListening();
  delay(100);
  myRadio.write(&utgaende, sizeof(utgaende));
  
       delay(100);
  digitalWrite(LEDblue, HIGH);
  digitalWrite(LEDgreen, LOW);
  digitalWrite(LEDred, LOW);
  delay(500);
  digitalWrite(LEDblue, LOW);
  digitalWrite(LEDgreen, HIGH);
  digitalWrite(LEDred, LOW);
  delay(500);
  digitalWrite(LEDblue, LOW);
  digitalWrite(LEDgreen, LOW);
  digitalWrite(LEDred, HIGH);
  delay(500);
  utgaende.L = 0;
  delay(100);
  digitalWrite(LEDgreen, HIGH);
     
  myRadio.startListening();
  
  #ifdef DEBUG
  Serial.println("Test is done");
  #endif
}
#ifdef BATTERY
void printVolts()
{
        /*
         * Checks the battery level and turns on red if low power.
         */
        
        utgaende.O = readVcc ();
        if (utgaende.O < batLevel) //In millivolt when we are going to warn on low power
        {
                digitalWrite(LEDred, HIGH);
        } else {
          digitalWrite(LEDred, LOW);
        }
}
#endif
