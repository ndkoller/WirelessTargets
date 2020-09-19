#include <Arduino.h>
#include <Wire.h>
#include "..\lib\tca9555\TCA9555.h"
#include <Servo.h>

//declare functions
int readline(int readch, char *buffer, int len);
int Process(char *buffer);
void toggleRelay(int _relay, char _state[]);

//relay board I2C address physically set with solder bridges.
TCA9555 tca9555_1(0, 0, 0);

//define global veriables
int result;
Servo myservo;

void setup()
{
  //Enable debug serial port
  Serial.begin(57600);
  Wire.begin();
  //Initialize servo on pin 9 and set to home position
  myservo.attach(9);
  myservo.write(8); // sets the servo 0.34" position
  delay(250);
  //Set relay board pins low and enable pins as outputs
  tca9555_1.setOutputStates(0);
  tca9555_1.setPortDirection(TCA9555::DIR_OUTPUT);
  //Display arduino setup complete
  Serial.println("Arduino Start");
  delay(50);
}

void loop()
{
  static char buffer[80];
  if (readline(Serial.read(), buffer, 80) > 0)
  {
    result = Process(buffer);
    if (result == 1)
    {
      Serial.println("ACK");
    }
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
  if (strcmp("|ARDUINO", buffer) == 0)
  {
    return 1;
  }
  if (strcmp("|HELLO", buffer) == 0)
  {
    return 1;
  }
  if (strcmp("|HELP", buffer) == 0)
  {
    Serial.println("|ARDUINO - Used to verify communication to arduino");
    Serial.println("|HELLO - Used to verify communication to arduino");
    Serial.println("|RELAY # ON - Used to turn relay on (Valid Relay int 1 through 32)");
    Serial.println("|RELAY # OFF - Used to turn relay off (Valid Relay int 1 through 32)");
    Serial.println("|RELAY ALL OFF - Used to turn all relays off");
    Serial.println("|RELAY ALL ON - Used to turn all relays on");
    Serial.println("|SERVO ON - Will move the servo away from the reed switch");
    Serial.println("|SERVO OFF - Will move the servo towards the reed switch");
    return 1;
  }
  if (strncmp("|RELAY ", buffer, 6) == 0)
  {
    //Check to see if it is an ALL command
    if (strstr(buffer, "ALL") != NULL)
    {
      //See if the ALL command is ON or OFF.
      if (strstr(buffer, "ON") != NULL)
      {
        //Serial.println("Received All ON");
        for (int i = 0; i < 16; i++)
        {
          tca9555_1.digitalWrite(i, HIGH);
        }
        return 1;
      }
      if (strstr(buffer, "OFF") != NULL)
      {
        //Serial.println("Received All OFF");
        for (int i = 0; i < 16; i++)
        {
          tca9555_1.digitalWrite(i, LOW);
        }
        return 1;
      }
    }
    //Check to see if command is a specified relay
    int value = -1;
    char state[5];
    char str[20];
    sscanf(buffer, "%s %d %s", str, &value, state); //Scans the buffer, puts first part into str, second part into value, and third part into state.
    if (strcmp("|RELAY", str) == 0)
    {
      //Serial.println(str);
      if ((0 < value) && (value < 100))
      {
        //Serial.println(value);
        if ((strncmp(state, "ON", 2) == 0) || (strncmp(state, "OFF", 3) == 0))
        {
          //Serial.println(state);
          toggleRelay(value, state);
          return 1;
        }
      }
    }
  }
  if (strcmp("|SERVO ON", buffer) == 0)
  {
    myservo.write(8); // sets the servo 0.34" position
    delay(250);
    return 1;
  }
  if (strcmp("|SERVO OFF", buffer) == 0)
  {
    myservo.write(37); // sets the servo 0.24" position
    delay(250);
    return 1;
  }
  //Serial.println(buffer);
  return 0;
}
//------------------------------------------------------------

void toggleRelay(int _relay, char _state[])
{
  bool state;
  if (strstr(_state, "ON") != NULL)
  {
    state = HIGH;
  }
  else
  {
    state = LOW;
  }
  if (_relay <= 16)
  {
    tca9555_1.digitalWrite(_relay - 1, state);
  }
}