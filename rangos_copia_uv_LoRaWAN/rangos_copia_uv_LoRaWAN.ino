/*******************************************************************************
 * Copyright (c) 2015 Thomas Telkamp and Matthijs Kooijman
 * Copyright (c) 2018 Terry Moore, MCCI
 *
 * Permission is hereby granted, free of charge, to anyone
 * obtaining a copy of this document and accompanying files,
 * to do whatever they want with them without any restriction,
 * including, but not limited to, copying, modification and redistribution.
 * NO WARRANTY OF ANY KIND IS PROVIDED.
 *
 * This example sends a valid LoRaWAN packet with payload "Hello,
 * world!", using frequency and encryption settings matching those of
 * the The Things Network.
 *
 * This uses OTAA (Over-the-air activation), where where a DevEUI and
 * application key is configured, which are used in an over-the-air
 * activation procedure where a DevAddr and session keys are
 * assigned/generated for use with all further communication.
 *
 * Note: LoRaWAN per sub-band duty-cycle limitation is enforced (1% in
 * g1, 0.1% in g2), but not the TTN fair usage policy (which is probably
 * violated by this sketch when left running for longer)!

 * To use this sketch, first register your application and device with
 * the things network, to set or generate an AppEUI, DevEUI and AppKey.
 * Multiple devices can use the same AppEUI, but each device has its own
 * DevEUI and AppKey.
 *
 * Do not forget to define the radio type correctly in
 * arduino-lmic/project_config/lmic_project_config.h or from your BOARDS.txt.
 *
 *******************************************************************************/

#include <lmic.h>
#include <hal/hal.h>
#include <SPI.h>
#include <heltec.h>
#include <Wire.h> 
//#include <DHT.h>
#include "Adafruit_VEML6070.h"
#include <TimeLib.h>
//#define DHTPIN 13
//#define DHTTYPE DHT11 // Tipo de sensor DHT11
//DHT dht(DHTPIN, DHTTYPE);
Adafruit_VEML6070 uv = Adafruit_VEML6070();
#ifdef COMPILE_REGRESSION_TEST
# define FILLMEIN 0
#else
# warning "You must replace the values marked FILLMEIN with real values from the TTN control panel!"
# define FILLMEIN (#dont edit this, edit the lines that use FILLMEIN)
#endif


// This EUI must be in little-endian format, so least-significant-byte
// first. When copying an EUI from ttnctl output, this means to reverse
// the bytes. For TTN issued EUIs the last bytes should be 0xD5, 0xB3,
// 0x70.
static const u1_t PROGMEM APPEUI[8]={ 0xe7,0xd4, 0x02,0xd0,0x7e,0xd5,0xb3,0x70 };
void os_getArtEui (u1_t* buf) { memcpy_P(buf, APPEUI, 8);}

// This should also be in little endian format, see above.
static const u1_t PROGMEM DEVEUI[8]={ 0x32,0x31, 0x45,0x52,0x42,0x4d,0x4f,0x4e };
void os_getDevEui (u1_t* buf) { memcpy_P(buf, DEVEUI, 8);}

// This key should be in big endian format (or, since it is not really a
// number but a block of memory, endianness does not really apply). In
// practice, a key taken from ttnctl can be copied as-is.
static const u1_t PROGMEM APPKEY[16] = { 0x63,0xae,0x0b,0x8b,0x8f,0x5c,0xc6,0x37,0xe6,0x45,0x93,0x9e,0xc5,0x2c,0x12,0x51 };
void os_getDevKey (u1_t* buf) {  memcpy_P(buf, APPKEY, 16);}

static osjob_t sendjob;


int uvlevel = 0;
int uvIndex=0;
const unsigned TX_INTERVAL = 300;

// Mapeo de pins
const lmic_pinmap lmic_pins = {
  .nss = 18,
  .rxtx = LMIC_UNUSED_PIN,
  .rst = 14,
  .dio = {26, 33, 32}
};

void setup() {
    
    Serial.begin(9600);
    uv.begin(VEML6070_1_T); // pass in the integration time constant
    delay(1000);
    Serial.println(F("Starting"));
    // LMIC init
    os_init();
    // Reset the MAC state. Session and pending data transfers will be discarded.
    LMIC_reset();
    // Start job (sending automatically starts OTAA too)
    do_send(&sendjob);
    //delay(1000);
}




void printHex2(unsigned v) {
    v &= 0xff;
    if (v < 16)
        Serial.print('0');
    Serial.print(v, HEX);
}

void do_send(osjob_t* j){
    // Check if there is not a current TX/RX job running
    if (LMIC.opmode & OP_TXRXPEND) {
        Serial.println(F("OP_TXRXPEND, not sending"));
    } else {
        // Prepare upstream data transmission at the next possible time.
       
        uvlevel = uv.readUV();        
  if(uvlevel>=0 && uvlevel<=187 )
  {
    uvIndex=0;
  } else 
   if(uvlevel>187 && uvlevel<=373)
  {    
    uvIndex=1;
  } else 
   if(uvlevel>373 && uvlevel<=560)
  {
    uvIndex=2;
  } else
   if(uvlevel>560 && uvlevel<=747)
  {
    uvIndex=3;
  } else if(uvlevel>747 && uvlevel<=934)
  {
    uvIndex=4;
  } else if(uvlevel>934 && uvlevel<=1120)
  {
    uvIndex=5;
  } else if(uvlevel>1120 && uvlevel<=1307)
  {
    uvIndex=6;
  } else if(uvlevel>1307 && uvlevel<=1494)
  {
    uvIndex=7;
  } else if(uvlevel>1494 && uvlevel<=1681)
  {
    uvIndex=8;
  } else if(uvlevel>1681 && uvlevel<=1868)
  {
    uvIndex=9;
  } else if(uvlevel>1868 && uvlevel<=2055)
  {
    uvIndex=10;
  } else if(uvlevel>2055 && uvlevel<=2242)
  {
    uvIndex=11;
  }
  else if(uvlevel>2242 && uvlevel<=2429)
  {
    uvIndex=12;
  }
  else if(uvlevel>2429 && uvlevel<=2616)
  {
    uvIndex=13;
  } 
  else if(uvlevel>=2803)
  {
    uvIndex=14;
  }

        delay(5000);
        String strData="UV Level="+String(uvIndex);
        Serial.print("UV INDEX="+uvIndex);
        if (isnan(uvlevel))
        {
          Serial.println("Failed to read from UV sensor!");
        }else{           
              Serial.print(strData);   
              Serial.println();           
        } 
        char charMensaje[12];
        Serial.print(charMensaje);
        strData.toCharArray(charMensaje, strData.length()+1);              
        LMIC_setTxData2(1,(uint8_t *)charMensaje, sizeof(charMensaje)-1, 0);
        Serial.println(F("Packet queued"));
    }
    // Next TX is scheduled after TX_COMPLETE event.
}


void onEvent (ev_t ev) {
  
    Serial.print(os_getTime());
    Serial.print(": ");
    switch(ev) {
        case EV_SCAN_TIMEOUT:
            Serial.println(F("EV_SCAN_TIMEOUT"));
            break;
        case EV_BEACON_FOUND:
            Serial.println(F("EV_BEACON_FOUND"));
            break;
        case EV_BEACON_MISSED:
            Serial.println(F("EV_BEACON_MISSED"));
            break;
        case EV_BEACON_TRACKED:
            Serial.println(F("EV_BEACON_TRACKED"));
            break;
        case EV_JOINING:
            Serial.println(F("EV_JOINING"));
            break;
        case EV_JOINED:
            Serial.println(F("EV_JOINED"));
            {
              u4_t netid = 0;
              devaddr_t devaddr = 0;
              u1_t nwkKey[16];
              u1_t artKey[16];
              LMIC_getSessionKeys(&netid, &devaddr, nwkKey, artKey);
              Serial.print("netid: ");
              Serial.println(netid, DEC);
              Serial.print("devaddr: ");
              Serial.println(devaddr, HEX);
              Serial.print("AppSKey: ");
              for (size_t i=0; i<sizeof(artKey); ++i) {
                if (i != 0)
                  Serial.print("-");
                printHex2(artKey[i]);
              }
              Serial.println("");
              Serial.print("NwkSKey: ");
              for (size_t i=0; i<sizeof(nwkKey); ++i) {
                      if (i != 0)
                              Serial.print("-");
                      printHex2(nwkKey[i]);
              }
              Serial.println();
            }
            LMIC_setLinkCheckMode(0);
            break;
        case EV_JOIN_FAILED:
            Serial.println(F("EV_JOIN_FAILED"));
            break;
        case EV_REJOIN_FAILED:
            Serial.println(F("EV_REJOIN_FAILED"));
            break;
        case EV_TXCOMPLETE:
            Serial.println(F("EV_TXCOMPLETE (includes waiting for RX windows)"));
            if (LMIC.txrxFlags & TXRX_ACK)
              Serial.println(F("Received ack"));
            if (LMIC.dataLen) {
              Serial.print(F("Received "));
              Serial.print(LMIC.dataLen);
              Serial.println(F(" bytes of payload"));
            }
            // Schedule next transmission
            os_setTimedCallback(&sendjob, os_getTime()+sec2osticks(TX_INTERVAL), do_send);
            break;
        case EV_LOST_TSYNC:
            Serial.println(F("EV_LOST_TSYNC"));
            break;
        case EV_RESET:
            Serial.println(F("EV_RESET"));
            break;
        case EV_RXCOMPLETE:
            // data received in ping slot
            Serial.println(F("EV_RXCOMPLETE"));
            break;
        case EV_LINK_DEAD:
            Serial.println(F("EV_LINK_DEAD"));
            break;
        case EV_LINK_ALIVE:
            Serial.println(F("EV_LINK_ALIVE"));
            break;
        
        case EV_TXSTART:
            Serial.println(F("EV_TXSTART"));
            
            break;
        case EV_TXCANCELED:
            Serial.println(F("EV_TXCANCELED"));
            break;
        case EV_RXSTART:
            /* do not print anything -- it wrecks timing */
            break;
        case EV_JOIN_TXCOMPLETE:
            Serial.println(F("EV_JOIN_TXCOMPLETE: no JoinAccept"));
            break;

        default:
            Serial.print(F("Unknown event: "));
            Serial.println((unsigned) ev);
            break;
    }
}



void loop() {    
   os_runloop_once();
}
