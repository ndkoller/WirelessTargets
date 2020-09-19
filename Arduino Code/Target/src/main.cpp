#include <Arduino.h>
#include <Adafruit_NeoPixel.h>

//NeoPixel veriables
#define PIN 6       // Which pin on the Arduino is connected to the NeoPixels?
#define NUMPIXELS 9 // How many NeoPixels are attached to the Arduino?
Adafruit_NeoPixel pixels(NUMPIXELS, PIN, NEO_GRB + NEO_KHZ800);

//declare functions
int readline(int readch, char *buffer, int len);
int Process(char *buffer);

//Constants
const int ledPin = 13;        //Led to Arduino pin 3 (PWM)
const int sensor = 3;         //sensor to Arduino pin A0
const char xbeeID[] = "|T01"; //Unique ID for each xbee module

//Variables
unsigned long startTime = 0;
unsigned long currentTime = 0;
int sensorval = 0;
int result;
int standbyColor = 0;

void setup()
{
  //Enable debug serial port
  Serial.begin(9600);
  pixels.begin(); // INITIALIZE NeoPixel strip object (REQUIRED)
  pixels.clear(); // Set all pixel colors to 'off'
  pixels.show();
  pinMode(ledPin, OUTPUT);
  pinMode(sensor, INPUT);
  //digitalWrite(ledPin, LOW);
  //Display arduino setup complete
  //Serial.println("Arduino Start");
  //delay(50);
}

void loop()
{
  static char buffer[80];
  if (readline(Serial.read(), buffer, 80) > 0)
  {
    result = Process(buffer);
    /*if (result == 1)
    {
      Serial.println("ACK");
    }*/
  }
}

//-----------------------------------------------------------------
int readline(int readch, char *buffer, int len)
{
  static int pos = 0;
  int rpos;

  if (readch > 0)
  {
    switch (readch)
    {
    case '\n': // Ignore new-lines
      break;
    case '\r': // Return on CR
      rpos = pos;
      pos = 0; // Reset position index ready for next time
      return rpos;
    default:
      if (pos < len - 1)
      {
        buffer[pos++] = readch;
        buffer[pos] = 0;
      }
    }
  }
  // No end of line has been found, so return -1.
  return -1;
}
//------------------------------------------------------------
int Process(char *buffer)
{
  if (strncmp(xbeeID, buffer, 4) == 0)
  {
    if (strstr(buffer, "ARDUINO") != NULL)
    {
      Serial.println("ARDUINO");
      return 1;
    }
    if (strstr(buffer, "START") != NULL)
    {
      standbyColor = 100;
      setLEDColor(0, 0, standbyColor);
      return 1;
    }
    if (strstr(buffer, "STOP") != NULL)
    {
      standbyColor = 0;
      setLEDColor(0, 0, standbyColor);
      return 1;
    }
    if (strstr(buffer, "HELP") != NULL)
    {
      Serial.println("ARDUINO - Used to verify communication to arduino");
      Serial.println("TARGET - LED RED, start timer and sensor, wait for vibration");
      Serial.println("TARGET FRIEND - LED YELLOW, vibration equals fail");
      Serial.println("LED - Test LED");
      return 1;
    }
    if (strstr(buffer, "TARGET") != NULL)
    {
      sensorval = 0;
      //Check to see if it is an ALL command
      if (strstr(buffer, "FRIEND") != NULL)
      {
        setLEDColor(100, 100, 0);
        startTime = millis();
        while (sensorval == 0)
        {
          sensorval = digitalRead(sensor);
          if ((millis() - startTime) >= 1500)
          {
            //digitalWrite(ledPin, LOW);
            setLEDColor(0, 0, standbyColor);
            return 1;
          }
        }
        Serial.println(sensorval);
        Serial.println("FAIL");
        setLEDColor(0, 0, standbyColor);
        return 1;
      }
      //If not friend then foe
      setLEDColor(100, 0, 0);
      startTime = millis();
      while (sensorval == 0)
      {
        sensorval = digitalRead(sensor);
      }
      currentTime = millis();
      Serial.print("Target 2 ");
      Serial.println(currentTime - startTime);
      Serial.println(sensorval);
      setLEDColor(0, 0, standbyColor);
      return 1;
    }
    if (strstr(buffer, "LED") != NULL)
    {
      setLEDColor(100, 0, 0);
      delay(500);
      setLEDColor(0, 100, 0);
      delay(500);
      setLEDColor(0, 0, 100);
      delay(500);
      setLEDColor(0, 0, 0);
    }
    //Serial.println(buffer);
    return 0;
  }
}

void setLEDColor(int red, int green, int blue)
{
  for (int x = 0; x < NUMPIXELS; x++)
  {
    pixels.setPixelColor(x, pixels.Color(red, green, blue));
  }
  pixels.show();
}
//------------------------------------------------------------
