<?php

$email_smtp = 'smtp.yandex.com'; //адрес смтп сервера
$email_port = 465; //порт от смтп сервера
$email_login = 'carinformerbot@yandex.com'; //логин от почты
$email_password = 'bot2020'; //пароль от почты

$name_bot = 'carinformerbot'; //имя бота (в письме)
$manager = 'carinformerbot@yandex.com'; //почта менеджера которому дублируется заказ
$tema = $_POST['name'].', на заметку...'; //это тема письма
$bodyy = '<b>'.$_POST['name'].'</b>, <br> Ваш телефон: '.$_POST['phone'].'<br>Ваш заказ в прикрепленном файле.<br><br>Ожидайте, наш менеджер скоро свяжется с вами!'; //текст письма

$tm = time();

if (isset($_POST['submit'])) {

	require 'mail/PHPMailerAutoload.php';
	 
	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail->isSMTP();
	$mail->Host = $email_smtp;
	$mail->SMTPAuth = true;
	$mail->Username = $email_login;
	$mail->Password = $email_password;
	$mail->SMTPSecure = 'ssl';
	$mail->Port = $email_port;
	$mail->setLanguage('ru');
	$mail->IsHTML(true);
	$mail->setFrom($email_login, $name_bot);

	$mail->addAddress($_POST['email'], $_POST['name']); 
	$mail->addBCC($manager);             
	 
	 $ffl = explode('base64,', $_POST['pdf']);
	 $ttm = time();
	 
	 file_put_contents('pdf/'.md5($ttm.$_POST['email'].'-'.$_POST['name']).'.pdf', base64_decode($ffl[1]));
	 
	$mail->addAttachment('pdf/'.md5($ttm.$_POST['email'].'-'.$_POST['name']).'.pdf');     

	$mail->isHTML(true);
	 
	$mail->Subject = $tema;
	$mail->Body    = $bodyy;
	//$mail->AltBody = 'Текстовая версия письма';
	$mail->MsgHTML($mail->Body);
	
	//Отправка сообщения
	if(!$mail->send()) {
	echo json_encode(array('status'=>'failed:'.$mail->ErrorInfo));
	} else {
	echo json_encode(array('status'=>'ok'));
	}
	
}


?>