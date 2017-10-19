<?php
require('condb.php');
require_once '../vendor/autoload.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

$logger = new Logger('LineBot');
$logger->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('/ts/l7Ae8dUAyugnozVRofWcumNGm1oF0b7/ekWB595KpXPyklTUv0F6ckzi20FZhI/77xry/Avhpef/uT8cZ8DH+NrEk6sdBW6G9msPlz3p/pHfKJmE7jjTHE3zdwW34+3gl6+o0Vtx0yq1Zo1b+wdB04t89/1O/w1cDnyilFU=');
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '41826fc188564eba1cdc3f63f590b982']);
$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
try {
	$events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);
} catch(\LINE\LINEBot\Exception\InvalidSignatureException $e) {
	error_log('parseEventRequest failed. InvalidSignatureException => '.var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownEventTypeException $e) {
	error_log('parseEventRequest failed. UnknownEventTypeException => '.var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownMessageTypeException $e) {
	error_log('parseEventRequest failed. UnknownMessageTypeException => '.var_export($e, true));
} catch(\LINE\LINEBot\Exception\InvalidEventRequestException $e) {
	error_log('parseEventRequest failed. InvalidEventRequestException => '.var_export($e, true));
}
foreach ($events as $event) {
	$reply_token = $event->getReplyToken();
	// Postback Event
	if (($event instanceof \LINE\LINEBot\Event\PostbackEvent)) {
		$logger->info('Postback message has come');
		continue;
	}
	// Location Event
	if  ($event instanceof LINE\LINEBot\Event\MessageEvent\LocationMessage) {
		$logger->info("location -> ".$event->getLatitude().",".$event->getLongitude());
		continue;
	}
	// Message Event = TextMessage
	if (($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
		$messageText=strtolower(trim($event->getText()));
		switch ($messageText) {
		case "โปรไฟล์"	 :
			$response = $bot->getProfile($event->getUserId());
                    if ($response->isSucceeded()) {
                        $profile = $response->getJSONDecodedBody();
                        $msg = 'ชื่อ:' . $profile['displayName'] .' แคปชั่น:' . $profile['statusMessage'];
                        $bot->replyText($reply_token, $msg);
                    }
			break;
			case "อัพเดทข่าวสาร"	 :
					$img_url = "https://iscobot.000webhostapp.com/img/photo.jpg";
					$textMessageBuilder = new LINE\LINEBot\MessageBuilder\ImageMessageBuilder($img_url, $img_url);
					$sql = 'SELECT * FROM debit';
					$result = $conn->query($sql);
					while($row = $result->fetch_assoc()){
						$response = $bot->pushMessage($row["user_id"], $textMessageBuilder);
					}
				break;
			case "เช็คยอดเงิน"	 :
				$response = $bot->getProfile($event->getUserId());
	                    if ($response->isSucceeded()) {
													$sql = 'SELECT * FROM debit WHERE user_id = "'.$event->getUserId().'"';
													$result = $conn->query($sql);
													$row = $result->fetch_assoc();
													if($row > 0 ){
														$msg = "ยอดเงินคงเหลือ : ".$row["money"]."บาท";
														$bot->replyText($reply_token, $msg);
													}else{
														$actions = array (
															// general message action
															New \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder("สมัครสมาชิก", "สมัครสมาชิก"),
															// URL type action
															New \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("สมัครสมาชิก", "https://iscobot.000webhostapp.com/web/register.php"),
															// The following two are interactive actions
															New \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("next page", "page=3"),
															New \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("Previous", "page=1")
														);
														$img_url = "https://iscobot.000webhostapp.com/img/photo.jpg";
														$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("สมัครสมาชิก", "หน้าจอสมัครสมาชิก", $img_url, $actions);
														$outputText = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("this message to use the phone to look to the Oh", $button);
													}
	                    }
				break;
		case "สมัครสมาชิก" :
		$response = $bot->getProfile($event->getUserId());
									if ($response->isSucceeded()) {
											$profile = $response->getJSONDecodedBody();
		$actions = array(
	 new \LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder('https://iscobot.000webhostapp.com/web/register.php?id='.$event->getUserId().'&lname='.$profile['displayName'], new \LINE\LINEBot\ImagemapActionBuilder\AreaBuilder(80, 80, 400, 400)),
	 new \LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder('https://www.youtube.com/', new \LINE\LINEBot\ImagemapActionBuilder\AreaBuilder(580, 80, 400, 400)),
	 new \LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder('https://www.facebook.com/', new \LINE\LINEBot\ImagemapActionBuilder\AreaBuilder(80, 580, 400, 400)),
	 new \LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder('เช็คยอดเงิน', new \LINE\LINEBot\ImagemapActionBuilder\AreaBuilder(580, 580, 400, 400)),
);
$Imap = new \LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder("https://iscobot.000webhostapp.com/img/show", "imagemap", new \LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder(1040, 1040), $actions);
$bot->replyMessage($reply_token, $Imap);
}
				break;
		case "location" :
			$outputText = new \LINE\LINEBot\MessageBuilder\LocationMessageBuilder("Eiffel Tower", "Champ de Mars, 5 Avenue Anatole France, 75007 Paris, France", 48.858328, 2.294750);
			break;
		case "เมนู" :
			$actions = array (
				// general message action
				New \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder("เช็คยอดเงิน", "เช็คยอดเงิน"),
				// URL type action
				New \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("Google", "http://www.google.com"),
				// The following two are interactive actions
				New \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("next page", "page=3"),
				New \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("Previous", "page=1")
			);
			$img_url = "https://iscobot.000webhostapp.com/img/photo.jpg";
			$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("button text", "description", $img_url, $actions);
			$outputText = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("this message to use the phone to look to the Oh", $button);
			break;
		case "carousel" :
			$columns = array();
			$img_url = "https://cdn.shopify.com/s/files/1/0379/7669/products/sampleset2_1024x1024.JPG?v=1458740363";
			for($i=0;$i<5;$i++) {
				$actions = array (
					new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("Add to Card","action=carousel&button=".$i),
					new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("View","http://www.google.com")
				);
				$column = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("Title", "description", $img_url , $actions);
				$columns[] = $column;
			}
			$carousel = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder($columns);
			$outputText = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("Carousel Demo", $carousel);
			break;
		case "image" :
			$img_url = "https://cdn.shopify.com/s/files/1/0379/7669/products/sampleset2_1024x1024.JPG?v=1458740363";
			$outputText = new LINE\LINEBot\MessageBuilder\ImageMessageBuilder($img_url, $img_url);
			break;
		case "confirm" :
			$response = $bot->getProfile($event->getUserId());
			if ($response->isSucceeded()) {
					$actions = array (
						New \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder("yes", "ans=y"),
						New \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("no", "ans=N")
					);
					$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder("คุณแน่ใจ ?", $actions);
					$outputText = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("this message to use the phone to look to the Oh", $button);
			}
			break;
			case "map" :
			$actions = array(
		 new \LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder('https://www.google.com/', new \LINE\LINEBot\ImagemapActionBuilder\AreaBuilder(80, 80, 400, 400)),
		 new \LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder('https://www.youtube.com/', new \LINE\LINEBot\ImagemapActionBuilder\AreaBuilder(580, 80, 400, 400)),
		 new \LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder('https://www.facebook.com/', new \LINE\LINEBot\ImagemapActionBuilder\AreaBuilder(80, 580, 400, 400)),
		 new \LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder('เช็คยอดเงิน', new \LINE\LINEBot\ImagemapActionBuilder\AreaBuilder(580, 580, 400, 400)),
 );
 $Imap = new \LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder("https://iscobot.000webhostapp.com/img/show", "imagemap", new \LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder(1040, 1040), $actions);
 $bot->replyMessage($reply_token, $Imap);
				break;
		default :
			$outputText = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("พิมพ์อะไรไม่รู้เรื่องนะจ๊ะ");
			break;
		}
		$response = $bot->replyMessage($event->getReplyToken(), $outputText);
	}
}

?>
