/*   ~ Simple Arduino - xBee Receiver sketch ~

  Read an PWM value from Arduino Transmitter to fade an LED
  The receiving message starts with '<' and closes with '>' symbol.
  
  Dev: Michalis Vasilakis // Date:2/3/2016 // Info: www.ardumotive.com // Licence: CC BY-NC-SA                    */
#include <Arduino.h>

//Constants
const int ledPin = 3;    //Led to Arduino pin 3 (PWM)
const int sensor = A0;   //sensor to Arduino pin A0
const int xbeeID = 0002; //Unique ID for each xbee module

//Variables
bool started = false; //True: Message is strated
bool ended = false;   //True: Message is finished
char incomingByte;    //Variable to store the incoming byte
char msg[3];          //Message - array from 0 to 2 (3 values - PWM - e.g. 240)
byte index;           //Index of array
unsigned long startTime = 0;
unsigned long currentTime = 0;
int sensorval = 0;

void setup()
{

    //Start the serial communication
    Serial.begin(9600); //Baud rate must be the same as is on xBee module
    pinMode(ledPin, OUTPUT);
    digitalWrite(ledPin, LOW);
}

void loop()
{

    while (Serial.available() > 0)
    {
        //Read the incoming byte
        incomingByte = Serial.read();
        //Start the message when the '<' symbol is received
        if (incomingByte == '<')
        {
            started = true;
            index = 0;
            msg[index] = '\0'; // Throw away any incomplete packet
        }
        //End the message when the '>' symbol is received
        else if (incomingByte == '>')
        {
            ended = true;
            break; // Done reading - exit from while loop!
        }
        //Read the message!
        else
        {
            if (index < 4) // Make sure there is room
            {
                msg[index] = incomingByte; // Add char to array
                index++;
                msg[index] = '\0'; // Add NULL to end
            }
        }
    }

    if (started && ended)
    {
        int value = atoi(msg);
        if (value == xbeeID)
        {
            digitalWrite(ledPin, HIGH);
            startTime = millis();
            while (sensorval < 50)
            {
                sensorval = analogRead(sensor);
            }
            currentTime = millis();
            Serial.print("Target 2 ");
            Serial.println(currentTime - startTime);
            Serial.println(sensorval);
            digitalWrite(ledPin, LOW);
        }
        //Serial.println("I'm can hear you"); //used for range debug
        index = 0;
        msg[index] = '\0';
        started = false;
        ended = false;
        sensorval = 0;
    }
}
