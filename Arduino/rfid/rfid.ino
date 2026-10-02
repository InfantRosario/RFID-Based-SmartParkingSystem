#include <WiFi.h>
#include <HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>

// Wi-Fi
const char* ssid = ""; //Your Network name or Wifi Name
const char* password = ""; //Your Wifi Password

// PHP URL
const char* serverBaseURL = "http://10.178.9.117/php/rfidinsert.php";

// RC522 pins
#define SS_PIN 21
#define RST_PIN 22

MFRC522 mfrc522(SS_PIN, RST_PIN);

void setup() {
  Serial.begin(115200);
  delay(100); // small delay for serial stability

  SPI.begin(); // Initialize SPI
  mfrc522.PCD_Init(); // Initialize RC522
  delay(100); // give some time for reader to wake up

  // Connect Wi-Fi **after initializing RC522**
  Serial.println("Connecting to Wi-Fi...");
  WiFi.begin(ssid, password);
  while(WiFi.status() != WL_CONNECTED){
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConnected to Wi-Fi");
  Serial.println("Place your RFID card near the reader...");
}

void loop() {
  // Check if a new card is present
  if(!mfrc522.PICC_IsNewCardPresent()){
    delay(50);
    return;
  }

  // Select one of the cards
  if(!mfrc522.PICC_ReadCardSerial()){
    delay(50);
    return;
  }

  // Read UID
  String uidStr = "";
  for(byte i = 0; i < mfrc522.uid.size; i++){
    uidStr += String(mfrc522.uid.uidByte[i], HEX);
  }
  uidStr.toUpperCase();
  Serial.println("RFID UID scanned: " + uidStr);

  // Send UID to PHP
  if(WiFi.status() == WL_CONNECTED){
    HTTPClient http;
    String serverURL = String(serverBaseURL) + "?rfid=" + uidStr;
    http.begin(serverURL);

    int httpResponseCode = http.GET();
    if(httpResponseCode > 0){
      String response = http.getString();
      Serial.println("Server response: " + response);
    } else {
      Serial.println("Error sending GET");
    }
    http.end();
  } else {
    Serial.println("Wi-Fi not connected");
  }

  delay(1000); // 1 second delay before next scan
}

