#include <WiFi.h>
#include <OneWire.h>
#include <DallasTemperature.h>

// Data pin yang terhubung ke sensor DS18B20
#define ONE_WIRE_BUS_1 15  // Pin untuk sensor pertama
#define ONE_WIRE_BUS_2 18  // Pin untuk sensor kedua

OneWire oneWire1(ONE_WIRE_BUS_1);
OneWire oneWire2(ONE_WIRE_BUS_2);

DallasTemperature sensors1(&oneWire1);
DallasTemperature sensors2(&oneWire2);

const char* ssid = "Wifi";
const char* password = "2929292929";
const char* host = "192.168.248.61";

void setup() {
    Serial.begin(115200);
    Serial.println("DS18B20 Output!");
    sensors1.begin(); // Start the first DS18B20 sensor
    sensors2.begin(); // Start the second DS18B20 sensor

    Serial.println("\nConnecting to " + String(ssid));
    WiFi.begin(ssid, password);

    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }

    Serial.println("\nWiFi connected");
    Serial.print("IP address: ");
    Serial.println(WiFi.localIP());
}

void loop() {
    // Request temperature readings from both sensors
    sensors1.requestTemperatures(); 
    sensors2.requestTemperatures(); 
    
    float suhuChiller1 = sensors1.getTempCByIndex(0); // Reading temperature from sensor 1 in Celsius
    float suhuChiller2 = sensors2.getTempCByIndex(0); // Reading temperature from sensor 2 in Celsius
    
    // Check if reading from sensor 1 is successful
    if (suhuChiller1 == DEVICE_DISCONNECTED_C) {
        Serial.println("Failed to read from Chiller 1 sensor");
    } else {
        Serial.print("Suhu chiller 1 : ");
        Serial.print(suhuChiller1);
        Serial.println(" *C");

        // If reading from sensor 2 is successful, proceed
        if (suhuChiller2 != DEVICE_DISCONNECTED_C) {
            Serial.print("Suhu chiller 2 : ");
            Serial.print(suhuChiller2);
            Serial.println(" *C");
        } else {
            Serial.println("Failed to read from Chiller 2 sensor");
        }

        Serial.print("Connecting to ");
        Serial.println(host);

        WiFiClient client;
        const int httpPort = 80; // Assuming you are using standard HTTP port
        if (!client.connect(host, httpPort)) {
            Serial.println("Connection failed");
            return;
        }

        // Construct the URL for the GET request
        String url = "/example/connect.php?suhuchiller1=" + String(suhuChiller1) + "&suhuchiller2=" + String(suhuChiller2);

        // Send the request
        client.print(String("GET ") + url + " HTTP/1.1\r\n" +
                     "Host: " + host + "\r\n" + 
                     "Connection: close\r\n\r\n");

        unsigned long timeout = millis();
        while (client.available() == 0) {
            if (millis() - timeout > 5000) { // Client Timeout after 5 seconds
                Serial.println(">>> Client Timeout !");
                client.stop();
                return;
            }
        }

        // Read and print the response from the server
        while(client.available()) {
            String line = client.readStringUntil('\r');
            Serial.print(line);
        }

        Serial.println("\nClosing connection");
    }
    delay(3000); // Wait for 3 seconds before the next send
}
