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
require 'LINETypeMessage.php';

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

$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));

 // คำสั่งรอรับการส่งค่ามาของ LINE Messaging API
 $content = file_get_contents('php://input');
 file_put_contents('log.txt', file_get_contents('php://input') . PHP_EOL, FILE_APPEND);

 // กำหนดค่า signature สำหรับตรวจสอบข้อมูลที่ส่งมาว่าเป็นข้อมูลจาก LINE
 $hash = hash_hmac('sha256', $content, LINE_MESSAGE_CHANNEL_SECRET, true);
 $signature = base64_encode($hash);

 // แปลงค่าข้อมูลที่ได้รับจาก LINE เป็น array ของ Event Object
 $events = $bot->parseEventRequest($content, $signature);
 $eventObj = $events[0]; // Event Object ของ array แรก

 // ดึงค่าประเภทของ Event มาไว้ในตัวแปร มีทั้งหมด 7 event
 $eventType = $eventObj->getType();

 // สร้างตัวแปร ไว้เก็บ sourceId ของแต่ละประเภท
 $userId = NULL;
 $groupId = NULL;
 $roomId = NULL;
 // สร้างตัวแปร replyToken สำหรับกรณีใช้ตอบกลับข้อความ
 $replyToken = NULL;
 // สร้างตัวแปร ไว้เก็บค่าว่าเป้น Event ประเภทไหน
 $eventMessage = NULL;
 $eventPostback = NULL;
 $eventJoin = NULL;
 $eventLeave = NULL;
 $eventFollow = NULL;
 $eventUnfollow = NULL;
 $eventBeacon = NULL;

 //ตัวแปรเก็บ Beacon
 $beaconuserId = NULL;
 $beaconhw = NULL;
 $beacondm = NULL;

 // เงื่อนไขการกำหนดประเภท Event
 switch($eventType){
     case 'message': $eventMessage = true; break;
     case 'postback': $eventPostback = true; break;
     case 'join': $eventJoin = true; break;
     case 'leave': $eventLeave = true; break;
     case 'follow': $eventFollow = true; break;
     case 'unfollow': $eventUnfollow = true; break;
     case 'beacon': $eventBeacon = true; break;
 }
// ------------------ greeting message --------------//


if(!is_null($eventFollow)){
    $userId = $eventObj->getUserId();
    $response = $bot->getProfile($userId);
    $userData = $response->getJSONDecodedBody(); // return array

    $textReplyMessage = 'hi' ;
    $replyData = new TextMessageBuilder($textReplyMessage);


}
// ------------------ greeting message --------------//
 //LINE BEACON --
//if(!is_null($eventBeacon)){
 //$typeBeacon = $eventObj->getBeaconType();
 //if($typeBeacon=='enter'){
//     $beaonhwid = $eventObj->getHwid(); // เก็บค่าข้อความที่ผู้ใช้พิมพ์
//     $textReplyMessage = 'hi'.$beaonhwid ;
//     $replyData = new TextMessageBuilder($textReplyMessage);

 //}
//----------------------------------------------------------//
if(!is_null($eventBeacon)){
  $beaconhw = $eventObj->getHwId();

  if($beaconhw == "01265d5c2a"){
  $textReplyMessage = "hw ID ของคุณ เครื่องที่ 1  ".$beaconhw;
  $replyData = new TextMessageBuilder($textReplyMessage);

}else if($beaconhw == "01265fcfaf"){
  $textReplyMessage = 'THIS IS number เครื่องสอง '.$beaconhw;
  $replyData = new TextMessageBuilder($textReplyMessage);
}else{
  $textReplyMessage = 'err';
  $replyData = new TextMessageBuilder($textReplyMessage);
}
}







 // สร้างตัวแปรเก็บค่า groupId กรณีเป็น Event ที่เกิดขึ้นใน GROUP
 if($eventObj->isGroupEvent()){
     $groupId = $eventObj->getGroupId();
 }
 // สร้างตัวแปรเก็บค่า roomId กรณีเป็น Event ที่เกิดขึ้นใน ROOM
 if($eventObj->isRoomEvent()){
     $roomId = $eventObj->getRoomId();
 }
 // ดึงค่า replyToken มาไว้ใช้งาน ทุกๆ Event ที่ไม่ใช่ Leave และ Unfollow Event
 if(is_null($eventLeave) && is_null($eventUnfollow)){
     $replyToken = $eventObj->getReplyToken();
 }
 // ดึงค่า userId มาไว้ใช้งาน ทุกๆ Event ที่ไม่ใช่ Leave Event
 if(is_null($eventLeave)){
     $userId = $eventObj->getUserId();
 }
 // ตรวจสอบถ้าเป็น Join Event ให้ bot ส่งข้อความใน GROUP ว่าเข้าร่วม GROUP แล้ว
 if(!is_null($eventJoin)){
     $textReplyMessage = "ขอเข้ากลุ่มด้วยน่ะ GROUP ID:: ".$groupId;
     $replyData = new TextMessageBuilder($textReplyMessage);
 }
 // ตรวจสอบถ้าเป็น Leave Event เมื่อ bot ออกจากกลุ่ม





 // ตรวจสอบถ้าเป้น Message Event และกำหนดค่าตัวแปรต่างๆ
 if(!is_null($eventMessage)){
     // สร้างตัวแปรเก็ยค่าประเภทของ Message จากทั้งหมด 8 ประเภท
     $typeMessage = $eventObj->getMessageType();
     //  text | image | sticker | location | audio | video | imagemap | template
     // ถ้าเป็นข้อความ
     if($typeMessage=='text'){
         $userMessage = $eventObj->getText(); // เก็บค่าข้อความที่ผู้ใช้พิมพ์
     }
     // ถ้าเป็น sticker
     if($typeMessage=='sticker'){
         $packageId = $eventObj->getPackageId();
         $stickerId = $eventObj->getStickerId();
     }
     // ถ้าเป็น location
     if($typeMessage=='location'){
         $locationTitle = $eventObj->getTitle();
         $locationAddress = $eventObj->getAddress();
         $locationLatitude = $eventObj->getLatitude();
         $locationLongitude = $eventObj->getLongitude();
     }
     // เก็บค่า id ของข้อความ
     $idMessage = $eventObj->getMessageId();
 }

 // ส่วนของการทำงาน
 if(!is_null($events)){
     // ถ้าเป็น Postback Event
     if(!is_null($eventPostback)){
         $dataPostback = NULL;
         $paramPostback = NULL;
         // แปลงข้อมูลจาก Postback Data เป็น array
         parse_str($eventObj->getPostbackData(),$dataPostback);
         // ดึงค่า params กรณีมีค่า params
         $paramPostback = $eventObj->getPostbackParams();
         // ทดสอบแสดงข้อความที่เกิดจาก Postaback Event
         $textReplyMessage = "ข้อความจาก Postback Event Data = ";
         $textReplyMessage.= json_encode($dataPostback);
         $textReplyMessage.= json_encode($paramPostback);
         $replyData = new TextMessageBuilder($textReplyMessage);
     }
     // ถ้าเป้น Message Event
     if(!is_null($eventMessage)){
         switch ($typeMessage){ // กำหนดเงื่อนไขการทำงานจาก ประเภทของ message
             case 'text':  // ถ้าเป็นข้อความ
                 switch ($userMessage) {
                    case "help":

                    $textReplyMessage = '-add' . "\xA"  . '-show';
                    $replyData = new TextMessageBuilder($textReplyMessage);
                    break;



                    case "-add":

                                   $textReplyMessage = '####BOT NOK ####' . "\xA"  . 'เพื่มข้อมูลง่ายๆ พิมพ์ add และตามด้วย -เลขโน๊ต -หัวข้อ -เนื้อหา -วันที่ -เวลา -สถานที่  '. "\xA"  . 'เช่นตัวอย่าง: '."\xA".'add,1,ชั้น17ไม่เรียบร้อย,PMเสร็จแต่ไม่ได้เก็บของกลับมาเช่นปริ้นเตอร์,19960101,09:30,BA';
                                   $replyData = new TextMessageBuilder($textReplyMessage);
                    break;


                     case "t_b":
                         // กำหนด action 4 ปุ่ม 4 ประเภท
                         $actionBuilder = array(
                             new MessageTemplateActionBuilder(
                                 'Message Template',// ข้อความแสดงในปุ่ม
                                 'This is Text' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                             ),
                             new UriTemplateActionBuilder(
                                 'Uri Template', // ข้อความแสดงในปุ่ม
                                 'https://www.ninenik.com'
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
                                 'Postback', // ข้อความแสดงในปุ่ม
                                 http_build_query(array(
                                     'action'=>'buy',
                                     'item'=>100
                                 )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
     //                          'Postback Text'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                             ),
                         );
                         $imageUrl = 'https://www.mywebsite.com/imgsrc/photos/w/simpleflower';
                         $replyData = new TemplateMessageBuilder('Button Template',
                             new ButtonTemplateBuilder(
                                     'button template builder', // กำหนดหัวเรื่อง
                                     'Please select', // กำหนดรายละเอียด
                                     $imageUrl, // กำหนด url รุปภาพ
                                     $actionBuilder  // กำหนด action object
                             )
                         );
                         break;
                     case "p":
                             if(!is_null($groupId) || !is_null($roomId)){
                                 if($eventObj->isGroupEvent()){
                                     $response = $bot->getGroupMemberProfile($groupId, $userId);
                                 }
                                 if($eventObj->isRoomEvent()){
                                     $response = $bot->getRoomMemberProfile($roomId, $userId);
                                 }
                             }else{
                                 $response = $bot->getProfile($userId);
                             }
                             if ($response->isSucceeded()) {
                                 $userData = $response->getJSONDecodedBody(); // return array
                                 // $userData['userId']
                                 // $userData['displayName']
                                 // $userData['pictureUrl']
                                 // $userData['statusMessage']
                                 $textReplyMessage = 'สวัสดีครับ คุณ '.$userData['displayName'];
                             }else{
                                 $textReplyMessage = 'สวัสดีครับ คุณคือใคร';
                             }
                             $replyData = new TextMessageBuilder($textReplyMessage);
                         break;
                     case "l": // เงื่อนไขทดสอบถ้ามีใครพิมพ์ L ใน GROUP / ROOM แล้วให้ bot ออกจาก GROUP / ROOM
                             $sourceId = $eventObj->getEventSourceId();
                             if($eventObj->isGroupEvent()){
                                 $bot->leaveGroup($sourceId);
                             }
                             if($eventObj->isRoomEvent()){
                                 $bot->leaveRoom($sourceId);
                             }
                             $textReplyMessage = 'เชิญ bot ออกจาก Group / Room';
                             $replyData = new TextMessageBuilder($textReplyMessage);
                         break;

                 }
                 break;
             default:
                 // กรณีทดสอบเงื่อนไขอื่นๆ ผู้ใช้ไม่ได้ส่งเป็นข้อความ
                 $textReplyMessage = 'สวัสดีครับ คุณ '.$typeMessage;
                 $replyData = new TextMessageBuilder($textReplyMessage);
                 break;
         }
     }
 }
 $response = $bot->replyMessage($replyToken,$replyData);
 if ($response->isSucceeded()) {
     echo 'Succeeded!';
     return;
 }
 // Failed
 echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
 ?>
