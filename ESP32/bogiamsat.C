#include <stdlib.h> 
#include<string.h>
#include <SPI.h>
#include <nRF24L01.h>
#include <RF24.h>
#include <TinyGPS++.h>
#include <SoftwareSerial.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#define ONE_WIRE_BUS 4

bool _system = 0;
float t;
int smoke_ = 0;

char kinhdo[50];
char vido[50];
char nhietdo[50];
char khoi[50];
char flame[50];
char smoke[1];

OneWire oneWire(ONE_WIRE_BUS); // thiết lập chân 1=Wire
DallasTemperature sensors(&oneWire); // thiết lập chân đọc nhiệt độ

RF24 radio(7, 8); // chân giáo tiếp RF
const byte diachi[6] = "12345"; // địa chỉ kết nối RF

static const int RXPin = 3, TXPin = 2;
static const uint32_t GPSBaud = 9600;

TinyGPSPlus gps;
SoftwareSerial gps_(RXPin, TXPin); 

void setup() {
  pinMode(A0, INPUT);
  pinMode(A1, INPUT);
  pinMode(A2, INPUT);
  pinMode(A3, INPUT);
  pinMode(A4, INPUT);
  pinMode(A5, INPUT);

  Serial.begin(9600);

  gps_.begin(GPSBaud); 
  sensors.begin();
  if (!radio.begin()) {
    Serial.println("Module không khởi động được...!!");
    while (1) {}
  }
  radio.openReadingPipe(1, diachi); // thiết lập địa chỉ nhận
  radio.openWritingPipe(diachi); // Thiết lập địa chỉ gửi
  radio.setPALevel(RF24_PA_LOW);// thiết lập bộ khuyếch đại công suất
  radio.setChannel(90); // thiết lập kênh truyền
  radio.setDataRate(RF24_1MBPS); //  tốc độ truyền dữ liệu trong không khí
  radio.startListening();
}

void loop() {

  char receive[32] = "";
  if(radio.available())
  {
      radio.read(&receive, sizeof(receive)); // Đọc yêu cầu gửi dữ liệu
      if(strcmp(receive, "slave1") == 0) // so sánh chuỗi
      {
        Serial.println("Bắt đầu truyền");
        _system = 1;
      }
  }
  
  if(_system == 1)
  {
    while (gps_.available() > 0)  // có dữ liệu gps gửi tới
    {
      if (gps.encode(gps_.read())) // kiểm tra
      {    
        readgps(); // gọi hàm đọc gps
        readtem(); // gọi hàm đọc nhiệt độ
        readflame(); // gọi hàm đọc trạng thái lửa
        readsmoke(); // Gọi hàm đọc nồng độ khói
        radio.stopListening(); // cài đặt module là TX
        
        //Gửi vĩ độ
        int i = 0;
        char note1[20] = "lat:";
        craft(vido,note1); // ghép chuỗi
        note1[strlen(note1)] = ','; // thêm ký tự kết thúc chuỗi
        Serial.println(note1);
        if (!radio.write(&note1,sizeof(note1))) // gửi vĩ độ và kiểm tra trạng thái gửi
        {
          Serial.println("lỗi truyền");
          _system = 0;
          radio.startListening();
          break;
        }
        delay(500);
        i++;

        // Gửi kinh độ
        char note2[32] = "lng:";
        craft(kinhdo,note2); // ghép chuỗi
        note2[strlen(note2)] = ','; // thêm kí tự kết thúc chuỗi
        Serial.println(note2);
        if(!radio.write(&note2,sizeof(note2))) // gửi kinh độ và kiểm tra trạng thái gửi
        {
          Serial.println("lỗi truyền");
          _system = 0;
          radio.startListening();
          break;
        }
        delay(500);
        i++;

        // Gửi nhiệt độ
        char note3[32] = "tem:";
        craft(nhietdo,note3); // ghép chuỗi
        note3[strlen(note3)] = ','; // thêm lý tự kết thúc chuỗi
        Serial.println(note3); // in chuỗi
        if(!radio.write(&note3,sizeof(note3))){ // // gửi nhiệt độ và kiểm tra trạng thái gửi
          Serial.println("lỗi truyền");
          _system = 0;
          radio.startListening();
          break;
        }
        delay(500);
        i++;

        //Gửi trạng thái khói
        char note4[32] = "smoke:";
        craft(khoi,note4); // ghép chuỗi
        note4[strlen(note4)] = ','; // thêm ký tự kết thúc chuỗi
        Serial.println(note4);
        if(!radio.write(&note4,sizeof(note4))) // gửi trạng thái cảm biến khói và kiểm tra trạng thái gửi
        {
          Serial.println("lỗi truyền");
          _system = 0;
          radio.startListening();
          break;
        }
        delay(500);
        i++;

        //Gửi trạng thái lửa
        char note5[32] = "status:";
        craft(flame,note5); // ghép chuỗi
        note5[strlen(note5)] = ','; // thêm ký tự kết thúc chuỗi
        Serial.println(note5);
        if(!radio.write(&note5,sizeof(note5))) // gửi trạng thái cảm biến lửa và kiểm tra trạng thái gửi
        {
          Serial.println("lỗi truyền");
          _system = 0;
          radio.startListening();
          break;
        }
        delay(500);
        i++;
        radio.startListening();
        _system = 0;
        if(i == 5)  break; // thoát khỏi ngay sau khi gửi xong 1 đợt dữ liệu
      } 
    }
  }
}


// đọc gps
void readgps() 
{
  float lat_ = gps.location.lat(); // Truy xuất vĩ độ
  float lng_ = gps.location.lng(); // truy xuất kinh độ
  dtostrf(lat_, 8, 6, vido); // chuyển đổi vĩ độ(float) sang kiểu char
  dtostrf(lng_, 8, 6, kinhdo); // chuyển đổi kinh độ(float) sang kiểu char
  delay(500);
}

// Đọc nhiệt độ
void readtem() 
{
  sensors.requestTemperatures();// gửi yêu cầu đọc nhiệt độ 
  t = sensors.getTempCByIndex(0);// lấy gia trị nhiệt độ
  dtostrf(t, 5, 2, nhietdo); // chuyển đổi giá trị nhiệt độ kiểu int sang char
  delay(500);
}

// Đọc trạng thái cảm biến khói
void readsmoke()
{
  smoke_ = analogRead(A5);
  if(smoke_ >= 400)
  {
  khoi[0] = '1';
  } else { 
    khoi[0] = '0'; }
}

// ghép 2 chuỗi kiểu char
void  craft(char data1[], char data2[]) 
{
  int x = strlen(data2);
  for(int i = 0; i < strlen(data1); i++ ) // vòng lặp nối từng kí tự trong mảng.
  {
    data2[i + x] = data1[i];
  }
}

 // Đọc trạng thái lửa
void readflame(){
  if(analogRead(A0) < 300){
    flame[0] = '1';
  } else flame[0] = '0';

  if(analogRead(A1) < 300){
    flame[1] = '1';
  } else flame[1] = '0';

  if(analogRead(A2) < 300){
    flame[2] = '1';
  } else flame[2] = '0';

  if(analogRead(A3) < 300){
    flame[3] = '1';
  } else flame[3] = '0';

  if(analogRead(A4) < 300){
    flame[4] = '1';
  } else flame[4] = '0';

}

