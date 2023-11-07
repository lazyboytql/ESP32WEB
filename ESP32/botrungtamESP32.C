#include <ArduinoJson.h>
#include <FirebaseESP32.h>
#include <HardwareSerial.h>
#include <stdio.h>
#include <WiFi.h>
#include <string.h>
#include <stdlib.h>

#define FIREBASE_HOST "esp-32-4c1fe-default-rtdb.firebaseio.com"
#define FIREBASE_AUTH "v2UaBsQo8j1Lio26tiBvnB0UebjlRgChoYexGI8v"
#define WIFI_SSID "hieu123"
#define WIFI_PASSWORD "99999999"

FirebaseData fbdo;
String path = "/";
FirebaseJson json;

const byte rxPin = 1;
const byte txPin = 2;

char receive[40] = " "; // mảng nhận dữ liệu từ UART 
char lng[20] ="    "; // kinh độ
char lat[20] ="    "; // vĩ độ
char tem[20] ="    "; // nhiệt độ
char smoke[10]="    "; // khói
char status[20]="    "; // trạng thái cảm biến lửa

int i_tem = 0;
int i_smoke =0;
int i_status = 0;

int p_receive = 0;
HardwareSerial mySerial(1);

void setup() {
  mySerial.begin(9600,SERIAL_8N1,rxPin,txPin);
  Serial.begin(9600);

  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.print("Đang kết nối");

  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(500);
  }
  Serial.println("");
  Serial.println("Đã kết nối WiFi!");
  Serial.println(WiFi.localIP());

  Firebase.begin(FIREBASE_HOST, FIREBASE_AUTH);
}

void loop() {
  while (mySerial.available()) // nhận dữ liệu từ uart, truyền vào mảng receive
  {
    receive[p_receive] = mySerial.read();
    Serial.print(receive[p_receive]);
    p_receive++;
  }
  p_receive = 0;
  if(receive[0] != ' ')
  {
  xulychuoi(receive, "lat:", lat);
  delay(10);
  xulychuoi(receive, "lng:", lng);
  delay(10);
  xulychuoi(receive, "tem:", tem);
  delay(10);
  xulychuoi(receive, "smoke:", smoke);
  delay(10);
  xulychuoi(receive, "status:", status);
  delay(10);
  }
  receive[0] = ' ';

  if (lat[0] != ' ' && lng[0] != ' '&& tem[0] != ' ' && smoke[0] != ' ' && status[0] != ' ' )
  {
    i_tem = atoi(tem);
    i_smoke = atoi(smoke);
    i_status = atoi(status);
    if(i_tem >= 70 && i_smoke == 0 && i_status == 0)
    {
      Serial.println("nhiet do tang dot bien");
      mySerial.print("temp");
    }else if(i_tem <= 70 && i_smoke > 0 && i_status == 0)
    {
      Serial.println("phat hien khoi");
      mySerial.print("smoke");
    } else if(i_tem <= 70 && i_smoke == 0 && i_status > 0)
    {
      Serial.println("phat hien dam chay");
      mySerial.print("fire");
    }else if(i_tem <= 70 && i_smoke == 0 && i_status == 0)
    {
      Serial.println("ok");
      mySerial.println("ok");
    }else
    {
      Serial.println("warning");
      mySerial.print("warning");
    } 

    Firebase.setString(fbdo, path + "/Cambien/Nhietdo", atoi(tem));
    Firebase.setString(fbdo, path + "/Cambien/Lua", atoi(status));
    Firebase.setString(fbdo, path + "/Cambien/Khoi", atoi(smoke));

    FirebaseJson json;
    Firebase.setString(fbdo, path + "/location/Longitude", lng);
    Firebase.setString(fbdo, path + "/location/Latitude", lat);

    if(Firebase.setJSON(fbdo, path + "/location", json))
    {
      Serial.println("dữ liệu đã được gửi tới fire base");
    }
    i_tem = 0;
    i_smoke = 0;
    i_status = 0;
    reset_arr(lat);
    delay(10);
    reset_arr(lng);
    delay(10);
    reset_arr(tem);
    delay(10);
    reset_arr(smoke);
    delay(10);
    reset_arr(status);
    delay(10);
  }
  delay(1000);
}

void reset_arr(char arr[]) // gán các phần tử mảng bằng khoảng trắng
{
  for (int i = 0; i < strlen(arr); i++)
  {
    arr[i] = ' ';
  }
}

// hàm tách thông số và truyền vào mảng
void xulychuoi(char arr[], String str0, char str1[]) // mảng gốc, dữ liệu cần nhận, mảng đích
{
  if (!mySerial.available()) // kiếm tra uart ngừng truyền dữ liệu
  {
    for (int i = 0; i < strlen(arr); i++) // vòng lặp chạy từ phần tử đầu tới phần tử cuối của mảng gốc
    {
      if (arr[i] == str0[0] && arr[i + 1] == str0[1] && arr[i + 2] == str0[2] && arr[i + str0.length() - 1] == str0[str0.length() - 1]) // kiếm tra mảng dữ liệu gốc giống với dữ liệu cần nhận
      {
        int j = i + str0.length(); // vị trí đầu tiên của dữ liệu cần nhận
        int count = 0;             // biến chỉ phần tử mảng đích
        while (arr[j] != ',')      // gán các phần tử từ vị trí j tới dấu ',' vào mảng đích
        {
          str1[count] = arr[j];
          count++;
          j++;
        }
      }
    }
  }
}
