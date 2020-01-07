#include "DHT.h"
#include <RTCZero.h>
#include <ArduinoLowPower.h>
#include <SigFox.h>

#define DHTPIN 2 // pin connecté
#define DHTTYPE DHT11   // DHT 11 

DHT dht(DHTPIN, DHTTYPE);

void setup() 
{
    Serial.begin(115200); 
    while (!Serial);
    Serial.println("Begin");
    SigFox.begin();

    // print ID AND PAC
    /*
    Serial.print("ID= ");
    Serial.println(SigFox.ID());
    Serial.print("PAC= ");
    Serial.println(SigFox.PAC());
    */
    
    dht.begin();
}

void loop() 
{
    float temp_hum_val[2] = {0};   
    float sigTemp = 0; 
    int temp = 0;
    int hum = 0;

    //get the data
    if(!dht.readTempAndHumidity(temp_hum_val)){
        sigTemp = SigFox.internalTemperature();
        temp = static_cast<int>(temp_hum_val[1]);
        hum = static_cast<int>(temp_hum_val[0]);
        Serial.print("Humidity: "); 
        Serial.print(hum);
        Serial.print(" %\t");
        Serial.print("Temperature: "); 
        Serial.print(temp);
        Serial.print(" *C");
        Serial.print(" SigTemperature: "); 
        Serial.print(sigTemp);
        Serial.println(" *C");
        int8_t t = (int8_t)sigTemp;
        SigFox.beginPacket();
        SigFox.write(t);
        SigFox.endPacket(false);
        SigFox.end();
    }
    else{
       Serial.println("Failed to get temprature and humidity value.");
    }

   delay(1000*60*11);
}
