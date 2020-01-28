#include "DHT.h"
#include <RTCZero.h>
#include <ArduinoLowPower.h>
#include <SigFox.h>

#define DHTPIN 2 // pin connecté
#define DHTTYPE DHT11   // DHT 11 

DHT dht(DHTPIN, DHTTYPE);

void setup() 
{
    //Serial.begin(115200); 
    //while (!Serial);
    //Serial.println("Begin");

    // print ID AND PAC
    /*
    Serial.print("ID= ");
    Serial.println(SigFox.ID());
    Serial.print("PAC= ");
    Serial.println(SigFox.PAC());
    */
    
    dht.begin();
    SigFox.begin();
    SigFox.end();
}

typedef struct __attribute__ ((packed)) sigfox_message {
    int8_t temp;
    int8_t hum;
} SigfoxMessage;

void loop() 
{
    float temp_hum_val[2] = {0};   
    float sigTemp = 0; 
    int temp = 0;
    int hum = 0;

    //get the data
    if (!dht.readTempAndHumidity(temp_hum_val)) {
        SigfoxMessage msg;
        sigTemp = SigFox.internalTemperature();
        temp = static_cast<int>(temp_hum_val[1]);
        hum = static_cast<int>(temp_hum_val[0]);

        msg.temp = temp;
        msg.hum = hum;
        SigFox.begin();
        SigFox.beginPacket();
        SigFox.write((uint8_t*)&msg,sizeof(msg));
        bool e = SigFox.endPacket();
        /*if (e) {
          Serial.println("success");
        } else {
          Serial.println("fail");
        }*/
        SigFox.end();
    } else {
       //Serial.println("Failed to get temprature and humidity value.");
    }

   delay(1000*60*15);
}
