<?php
// กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
header('Content-Type: text/html; charset=utf-8');

// include composer autoload
require_once 'vendor/autoload.php';

// การตั้งเกี่ยวกับ bot
require_once 'Api_Setting.php';

// กรณีมีการเชื่อมต่อกับฐานข้อมูล
//require_once("dbconnect.php");

///////////// ส่วนของการเรียกใช้งาน class ผ่าน namespace
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;

// เชื่อมต่อกับ LINE Messaging API
$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));


// คำสั่งรอรับการส่งค่ามาของ LINE Messaging API
$content = file_get_contents('php://input');

if(!empty($content)){
  $fp = fopen('results.json', 'w');
  fwrite($fp, $content);
  fclose($fp);
}

$json_url = "https://pnall.co.th/apps/line/pm2019/Line/results.json";
$json = file_get_contents($json_url);

$data = json_decode($json);

// echo '<pre>'.print_r($data, true).'</pre>';

// echo 'NiSKx:'.'<pre>'.print_r($data->events[0]->source->userId, true).'</pre>';

$userId = $data->events[0]->source->userId;

// แปลงข้อความรูปแบบ JSON  ให้อยู่ในโครงสร้างตัวแปร array
$events = json_decode($content, true);
if(!is_null($events)){
     // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
    $replyToken = $events['events'][0]['replyToken'];
    $typeMessage = $events['events'][0]['message']['type'];
    $userMessage = $events['events'][0]['message']['text'];

    

}

// บัคๆอยู่เดี้ยวมาแก้  --------------------- เอาไว้ทดสอบ DB เล่นๆ ถ้าไม่ลืม แก้ ก็ลบออกด้วย เดี้ยวลืม


// บัคๆอยู่เดี้ยวมาแก้  --------------------- เอาไว้ทดสอบ DB เล่นๆ ถ้าไม่ลืม แก้ ก็ลบออกด้วย เดี้ยวลืม


// บัคๆอยู่เดี้ยวมาแก้            


if(!is_null($events)){
    // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
    $replyToken = $events['events'][0]['replyToken'];
    $userID = $events['events'][0]['source']['userId'];
    $sourceType = $events['events'][0]['source']['type'];
    $is_postback = NULL;
    $is_message = NULL;
    if(isset($events['events'][0]) && array_key_exists('message',$events['events'][0])){
        $is_message = true;
        $typeMessage = $events['events'][0]['message']['type'];
        $userMessage = $events['events'][0]['message']['text'];     
        $idMessage = $events['events'][0]['message']['id']; 
    }
    if(isset($events['events'][0]) && array_key_exists('postback',$events['events'][0])){
        $is_postback = true;
        $dataPostback = NULL;
        parse_str($events['events'][0]['postback']['data'],$dataPostback);;
        $paramPostback = NULL;
        if(array_key_exists('params',$events['events'][0]['postback'])){
            if(array_key_exists('date',$events['events'][0]['postback']['params'])){
                $paramPostback = $events['events'][0]['postback']['params']['date'];
            }
            if(array_key_exists('time',$events['events'][0]['postback']['params'])){
                $paramPostback = $events['events'][0]['postback']['params']['time'];
            }
            if(array_key_exists('datetime',$events['events'][0]['postback']['params'])){
                $paramPostback = $events['events'][0]['postback']['params']['datetime'];
            }                       
        }
    }   
    if(!is_null($is_postback)){
        $textReplyMessage = "ข้อความจาก Postback Event Data = ";
        if(is_array($dataPostback)){
            $textReplyMessage.= json_encode($dataPostback);
        }
        if(!is_null($paramPostback)){
            $textReplyMessage.= " \r\nParams = ".$paramPostback;
        }
        $replyData = new TextMessageBuilder($textReplyMessage);     
    }
    if(!is_null($is_message)){
        switch ($typeMessage){
            case 'text':
                $userMessage = strtolower($userMessage); // แปลงเป็นตัวเล็ก สำหรับทดสอบ
                switch ($userMessage) {
                    case "p":
                    // เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
                    $response = $bot->getProfile($userID);
                    if ($response->isSucceeded()) {
                        // ดึงค่ามาแบบเป็น JSON String โดยใช้คำสั่ง getRawBody() กรณีเป้นข้อความ text
                        $textReplyMessage = $response->getRawBody(); // return string            
                        $replyData = new TextMessageBuilder($textReplyMessage);         
                        break;              
                    }
                    // กรณีไม่สามารถดึงข้อมูลได้ ให้แสดงสถานะ และข้อมูลแจ้ง ถ้าไม่ต้องการแจ้งก็ปิดส่วนนี้ไปก็ได้
                    $failMessage = json_encode($response->getHTTPStatus() . ' ' . $response->getRawBody());
                    $replyData = new TextMessageBuilder($failMessage);
                    break;    
                    
                    //case study 




                    case "check-in" :

                    // เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
                    $response = $bot->getProfile($userID);
                    if ($response->isSucceeded()) {
                        // ดึงค่าโดยแปลจาก JSON String .ให้อยู่ใรูปแบบโครงสร้าง ตัวแปร array 
                        $userData = $response->getJSONDecodedBody(); // return array     
                        // $userData['userId']
                        // $userData['displayName']
                        // $userData['pictureUrl']
                        // $userData['statusMessage']
                      
                    $replyData = new TemplateMessageBuilder('Confirm Template',
                        new ConfirmTemplateBuilder(
                                'ลงทะเบียนแล้วหรือยัง?',
                                
                               array(
                                     new MessageTemplateActionBuilder(
                                       'Yes',
                                       'ยืนยัน'

                                       
                                   ),
                                    new MessageTemplateActionBuilder(
                                       'ยกเลิก',
                                        'NO'
                                   )
                                )
                                
                        )
                        
                    );
                }
               break;
                        
            case "ยืนยัน":
          
            // เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
            $response = $bot->getProfile($userID);
            if ($response->isSucceeded()) {
                // ดึงค่าโดยแปลจาก JSON String .ให้อยู่ใรูปแบบโครงสร้าง ตัวแปร array 
                $userData = $response->getJSONDecodedBody(); // return array     
                // $userData['userId']
                // $userData['displayName']
                // $userData['pictureUrl']
                // $userData['statusMessage']
                $textReplyMessage = 'สวัสดีครับ คุณ '.$userData['displayName'] .'   กรุณาถ่ายรูป และ ส่งเข้ามา ยืนยันสถานที่ด้วยครับ';             
                $replyData = new TextMessageBuilder($textReplyMessage);         
                break;              
            }

                    //module หลัก TEST 

           
                    case "stulog":
                    $serverName = "localhost";
                    $userName = "root";
                    $userPassword = "P@ssw0rd";
                    $dbName = "PM2019";
    
                   $conn = mysqli_connect($serverName,$userName,$userPassword,$dbName);
    
                   $sql = "SELECT * FROM user WHERE user_id=1" ;
    
                   $query = mysqli_query($conn,$sql);
                   $res = mysqli_fetch_array($query);
                   $textReplyMessage = "";
                   if($query){
                    $textReplyMessage = "Username:".$res['user_username']."\nUser Date:".$res['user_datetime'];
                  }else{
                    $textReplyMessage = "เชื่อมต่อไม่สำเร็จ";
                  }
                   $replyData = new TextMessageBuilder($textReplyMessage);
                   break;
                
                   case 'register' :

                   $serverName = "localhost";
                   $userName = "root";
                   $userPassword = "P@ssw0rd";
                   $dbName = "PM2019";
   
                  $conn = mysqli_connect($serverName,$userName,$userPassword,$dbName);
   
                  $sql = "SELECT * FROM user WHERE user_id=1" ;
   
                  $query = mysqli_query($conn,$sql);
                  $res = mysqli_fetch_array($query);
                  $textReplyMessage = "";
                  if($query){
                   // $textReplyMessage = "Username:".$res['user_username']."\nUser Date:".$res['user_datetime'];
                   $textReplyMessage = "สิ่งที่ User พิมพ์มา: ".$userMessage."\n";
   
                   $date = date("Y-m-d H:i:s");
                   $sql = mysqli_query($conn, "INSERT INTO user VALUES (NULL, 'username', 'password', 'lineid', 'firstname', 'lastname', 'Device', 'lat', 'logtitude', '$date', 'companycode')");
                 }else{
                   $textReplyMessage = "เชื่อมต่อไม่สำเร็จ";
                 }
                  $replyData = new TextMessageBuilder($textReplyMessage);
                  break;
   
               

                  case "!help":
                  $textReplyMessage = 'admin , p ';             
                  $replyData = new TextMessageBuilder($textReplyMessage);         
                  break; 

                




//API LIFF TEST v1

                    case "admin":
                    $textReplyMessage = 'line://app/1636902643-46LVNZPG';             
                    $replyData = new TextMessageBuilder($textReplyMessage);         
                    break; 



                    case "check-out":
    // กำหนด action 4 ปุ่ม 4 ประเภท
         
                     
    $actionBuilder = array(
        new MessageTemplateActionBuilder(
            'ทดสอบTry{Catch',// ข้อความแสดงในปุ่ม
            'test'

        ),
        new UriTemplateActionBuilder(
            'ยังไม่ได้ลงทะเบียน', // ข้อความแสดงในปุ่ม
            'https://www.pnall.co.th'
        ),
        new DatetimePickerTemplateActionBuilder(
            'Datetime Picker', // ข้อความแสดงในปุ่ม
            http_build_query(array(
                'action'=>'reservation',
                'person'=>5
            )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
            'datetime', // date | time | datetime รูปแบบข้อมูลที่จะส่ง ในที่นี้ใช้ datatime
            substr_replace(date("Y-m-d H:i"),'T',10,1), // วันที่ เวลา ค่าเริ่มต้นที่ถูกเลือก
            substr_replace(date("Y-m-d H:i",strtotime("+5 day")),'T',10,1), //วันที่ เวลา มากสุดที่เลือกได้
            substr_replace(date("Y-m-d H:i"),'T',10,1) //วันที่ เวลา น้อยสุดที่เลือกได้
        ),      
        new PostbackTemplateActionBuilder(
            'Check-in', // ข้อความแสดงในปุ่ม
            http_build_query(array(
                'action'=>'checkinLog',
                'item'=>1
            )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
        ),   
        
        



        
    );
    $imageUrl = 'https://www.pnall.co.th/images/logo.png';
    $replyData = new TemplateMessageBuilder('Button Template',
        new ButtonTemplateBuilder(
                'Test menu checkin', // กำหนดหัวเรื่อง
                'กรุณากด Button ที่ยังคง บัคอยู่', // กำหนดรายละเอียด  
                $imageUrl, // กำหนด url รุปภาพ
                $actionBuilder  // กำหนด action object
        )
        
    );     
    
    if(!is_null($is_postback)){
        $textReplyMessage = "ข้อความจาก Postback Event Data = ";
        if(is_array($dataPostback)){
            $textReplyMessage.= json_encode($dataPostback);
        }
        if(!is_null($paramPostback)){
            $textReplyMessage.= " \r\nParams = ".$paramPostback;
        }
        $replyData = new TextMessageBuilder($textReplyMessage);     
    }

    
    break;  
                case "สวัสดี":
                    // เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
                    $response = $bot->getProfile($userID);
                    if ($response->isSucceeded()) {
                        // ดึงค่าโดยแปลจาก JSON String .ให้อยู่ใรูปแบบโครงสร้าง ตัวแปร array 
                        $userData = $response->getJSONDecodedBody(); // return array     
                        // $userData['userId']
                        // $userData['displayName']
                        // $userData['pictureUrl']
                        // $userData['statusMessage']
                        $textReplyMessage = 'สวัสดีครับ คุณ '.$userData['displayName'];             
                        $replyData = new TextMessageBuilder($textReplyMessage);         
                        break;              
                    }
                    // กรณีไม่สามารถดึงข้อมูลได้ ให้แสดงสถานะ และข้อมูลแจ้ง ถ้าไม่ต้องการแจ้งก็ปิดส่วนนี้ไปก็ได้
                    $failMessage = json_encode($response->getHTTPStatus() . ' ' . $response->getRawBody());
                    $replyData = new TextMessageBuilder($failMessage);
                    break;                                                                                                                                                                                                                                          
                default:
                    $textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
                    $replyData = new TextMessageBuilder($textReplyMessage);         
                    break;                                      
            }
            break;      
        case (preg_match('/[image|audio|video]/',$typeMessage) ? true : false) :
            $response = $bot->getMessageContent($idMessage);
            if ($response->isSucceeded()) {
                // คำสั่ง getRawBody() ในกรณีนี้ จะได้ข้อมูลส่งกลับมาเป็น binary 
                // เราสามารถเอาข้อมูลไปบันทึกเป็นไฟล์ได้
                $dataBinary = $response->getRawBody(); // return binary
                // ดึงข้อมูลประเภทของไฟล์ จาก header
                $fileType = $response->getHeader('Content-Type');    
                switch ($fileType){
                    case (preg_match('/^image/',$fileType) ? true : false):
                        list($typeFile,$ext) = explode("/",$fileType);
                        $ext = ($ext=='jpeg' || $ext=='jpg')?"jpg":$ext;
                        $fileNameSave = time().".".$ext;
                        break;
                    case (preg_match('/^audio/',$fileType) ? true : false):
                        list($typeFile,$ext) = explode("/",$fileType);
                        $fileNameSave = time().".".$ext;                        
                        break;
                    case (preg_match('/^video/',$fileType) ? true : false):
                        list($typeFile,$ext) = explode("/",$fileType);
                        $fileNameSave = time().".".$ext;                                
                        break;                                                      
                }
                $botDataFolder = 'botdata/'; // โฟลเดอร์หลักที่จะบันทึกไฟล์
                $botDataUserFolder = $botDataFolder.$userID; // มีโฟลเดอร์ด้านในเป็น userId อีกขั้น
                if(!file_exists($botDataUserFolder)) { // ตรวจสอบถ้ายังไม่มีให้สร้างโฟลเดอร์ userId
                    mkdir($botDataUserFolder, 0777, true);
                }   
                // กำหนด path ของไฟล์ที่จะบันทึก
                $fileFullSavePath = $botDataUserFolder.'/'.$fileNameSave;
                file_put_contents($fileFullSavePath,$dataBinary); // ทำการบันทึกไฟล์
                $textReplyMessage = "บันทึกไฟล์เรียบร้อยแล้ว $fileNameSave";
                $replyData = new TextMessageBuilder($textReplyMessage);
                break;

                


            }


            


            //test ระบบเฉยๆ
            $failMessage = json_encode($idMessage.' '.$response->getHTTPStatus() . ' ' . $response->getRawBody());
            $replyData = new TextMessageBuilder($failMessage);  
            break;                                                      
        default:
            $textReplyMessage = json_encode($events);
            $replyData = new TextMessageBuilder($textReplyMessage);         
            break;  
    }
}
}
//l ส่วนของคำสั่งตอบกลับข้อความ
$response = $bot->replyMessage($replyToken,$replyData);

if ($response->isSucceeded()) {
    echo 'Succeeded!';
    return;
}

// Failed
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
?>
