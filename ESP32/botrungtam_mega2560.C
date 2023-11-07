#include <SPI.h> // thư viện spi
#include <nRF24L01.h> // thư viện RF
#include <RF24.h> 
#include <string.h> // thư viện hỗ trợ các thao tác với string

String myphone = "0858773705"; // số điện thoại thiết bị gọi tới

String msg = ""; // chuỗi nhận tín hiệu cảnh báo từ esp thông qua uart2

RF24 radio(7, 8); // CE, CSN
const byte diachi[6] = "12345"; //địa chỉ của module RF

void setup() 
{
  Serial.begin(9600);// baud rate uart
  Serial2.begin(9600);
  Serial3.begin(9600);
  while(!radio.begin()) // kiếm tra xem module có phản hồi hay không
  {
    Serial.println("Module không khởi động được...!!");
  } 
  delay(3000);
  test_sim800_module();// các lệnh kiểm tra và setup cho module sim
  delay(3000);
  radio.openWritingPipe(diachi); // mở kênh truyền
  radio.openReadingPipe(1,diachi); // mở 1 kênh nhận
  radio.setPALevel(RF24_PA_LOW); // cài đặt bộ khuếch đại công suất mức LOW
  radio.setChannel(90); // 125 kênh từ 0-124; TX và RX phải cùng kênh                
  radio.setDataRate(RF24_1MBPS); //Tốc độ truyền dữ liệu trong không khí                          
}
void loop() 
{

  radio.stopListening(); // cài đặt module là TX
  const char text[] = "slave1";
  radio.write(&text, sizeof(text)); // gửi yêu cầu đi, bộ cảm biến cài đặt sẵn nhận chuỗi này sẽ phản hồi
  Serial.println("da gui tin hieu toi slave 1");
  radio.startListening(); // bắt đầu nhận dữ liệu bộ cảm biến gửi
  int count = 0;  
  int timeout = 0;
  while(count <= 4 && timeout < 31 ) // nhận đủ 5 lần hoặc quá 60s sẽ ngưng nhận dữ liệu
  {
    if (radio.available()) 
    {
      char nhan[32] = "";
      radio.read(&nhan, sizeof(nhan)); // nhận dữ liệu từ bộ cảm biến (tối đa 32 kí tự)
      Serial2.println(nhan); // truyền dữ liệu nhận được ra uart2
      Serial.println(nhan); 
      count++;
    }
    if(Serial2.available()) // có dữ liệu từ esp32 gửi qua uart2
    {
      String msg = Serial2.readString(); // đọc dữ liệu từ uart2
      if(strcmp(msg.c_str(),"warning") == 0) // nếu dữ liệu nhận được là "warning" 
        {
          Serial.println("warning");
          delay(500); 
          Call();
          delay(5000);
          send_SMS("warning");
          delay(5000);
          msg = " ";
          break;
        }
        if(strcmp(msg.c_str(),"fire") == 0) // nếu dữ liệu nhận được là "fire" 
        {
          Serial.println("phat hien dam chay");
          delay(500);
          Call();
          delay(2000);
          send_SMS("phat hien dam chay");
          delay(5000);
          msg = " ";
          break;
        }
        if(strcmp(msg.c_str(),"smoke") == 0) // nếu dữ liệu nhận được là "smoke"
        {
          Serial.println("phat hien khoi");
          delay(500);
          Call();
          delay(2000);
          send_SMS("phat hien khoi");
          delay(5000);
          msg = " ";
          break;
        }
        if(strcmp(msg.c_str(),"temp") == 0) // nếu dữ liệu nhận được là "smoke"
        {
          Serial.println("nhiet do tang dot bien");
          delay(500);
          Call();
          delay(5000);
          send_SMS("nhiet do tang dot bien");
          delay(5000);
          msg = " ";
          break;
        }
        if(strcmp(msg.c_str(),"ok") == 0) // nếu dữ liệu nhận được là "ok"
        {
          Serial.println("ok");
          delay(500);
          msg = " ";
        } 
      }
      timeout++;
      delay(1000);
    }
  count = 0;
  timeout = 0;
  delay(5000);
}

void test_sim800_module() // hàm test module sim
{
  Serial3.println("AT");  // kiểm tra kết nối
  updateSerial();
  Serial3.println();
  Serial3.println("AT+CSQ"); // kiểm tra chất lượng đường truyền
  updateSerial();
  Serial3.println("AT+CCID"); // đọc imei của sim
  updateSerial();
  Serial3.println("AT+CREG?"); // kiểm tra đã đăng ký mạng chưa
  updateSerial();
  Serial3.println("ATI"); // nhận tên module
  updateSerial();
  Serial3.println("AT+CBC"); // trả về trạng thái pin,Số thứ hai là % pin, số thứ ba là điện áp thực tế tính bằng mV 
  updateSerial();
  Serial3.println("AT+CMGF=1"); // chọn định dạng tin nhắn sms dạng văn bản
  updateSerial();
  
}

void updateSerial() // ghi dữ liệu nhận được từ uart3 ra uart và ngược lại
{
  delay(500);
  while (Serial.available())
  {
    Serial3.write(Serial.read());
  }
  while (Serial3.available())
  {
    Serial.write(Serial3.read());
  }
}

void Call() // hàm gọi điện      
{
  Serial3.println("ATD" + myphone + ";");       // gọi điện 
  delay(15000);                                // Sau 15s
  Serial3.println("ATH");                       // Ngat cuoc goi
  delay(2000);
}

void send_SMS(String arr)  // hàm gửi tin nhắn
{
  
  Serial3.println("AT+CMGS=\"" + myphone + "\"");;//gửi sms đến số điện thoại được chỉ định
  updateSerial();
  for(int i = 0; i< arr.length() ;i++)
  {
  Serial3.print(arr[i]); //nội dung tin nhắn
  }
  updateSerial();
  Serial.println();
  Serial3.write(26); // kết thúc tin nhắn bằng ctrl + Z hoặc 26
}
 

