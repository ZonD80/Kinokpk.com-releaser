<?php

// paying system via sms
require_once("include/bittorrent.php");
define("IN_CONTACT",true);
INIT();
$amount = (int)$_GET['amount'];
if (!$amount) $amount = (int)$_POST['LMI_PAYMENT_AMOUNT'];

			$classes = init_class_array();
			
switch ($amount) {
	case 1: { $project_id = 28832; $discount=10;  break;}
	case 3: { $project_id = 28833; $discount=30; break;}
	case 10: {$project_id = 28834; break;}
}

$mode = trim((string)$_GET['mode']);
if (!$mode) $mode = trim((string)$_POST['mode']);
if ($mode=='wm') {
	if (isset($_GET['okay'])) {
		safe_redirect($REL_SEO->make_link('myrating'),1);
		stderr($REL_LANG->say_by_key('success'),"Платеж успешно завершен",'success');
	}
	if (isset($_GET['failed'])) {
		safe_redirect($REL_SEO->make_link('donate'),3);
		stderr($REL_LANG->say_by_key('failed'),'При проведении платежа возникли ошибки. <a href="'.$REL_SEO->make_link('donate').'">Попробуйте еще раз</a>.');
	}

	if (isset($_GET['process']))
	IF($_POST['LMI_PREREQUEST']==1) {
		if (($amount<>1) && ($amount<>3) && ($amount<>10)) die('Ошибка: Неверная сумма перевода');

		// 3) Проверяем, не произошла ли подмена кошелька.
		// Cравниваем наш настоящий кошелек с тем кошельком, который передан нам Мерчантом.
		// Если кошельки не совпадают, то выводим ошибку и прерываем работу скрипта.
		if(trim($_POST['LMI_PAYEE_PURSE'])!="Z113282224168") {
			die('Ошибка: Неверный кошелек получателя');
		}
		die('YES');
	}
	else {
		if ($discount) {
			sql_query("UPDATE users SET discount=discount+$discount, modcomment=CONCAT(".sqlesc(date("Y-m-d") . "Купил $discount откупа\n").",modcomment) WHERE id=".(int)$_POST['id']);

		} else {
			sql_query("UPDATE users SET class=".$classes['vip'].", modcomment=CONCAT(".sqlesc(date("Y-m-d") . "Купил VIP\n").",modcomment) WHERE class<".$classes['vip']." AND id=".(int)$_POST['id']);
			sql_query("UPDATE users SET dis_reason='', enabled=1 WHERE id = ".(int)$_POST['id']);

		}
		die('YES');
	}
	loggedinorreturn();

	$REL_TPL->stdhead('Пожертвование через Webmoney merchant');
	$REL_TPL->begin_frame('Пожертвование через WebMoney')
	?>
<form id="pay" name="pay" method="POST"
	action="https://merchant.webmoney.ru/lmi/payment.asp">
<p>Получение привелегий на TorrentsBook.com</p>
<p>Вам необходимо заплатить <?=$amount;?> WMZ...</p>
<p><input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?=$amount;?>">
<input type="hidden" name="LMI_PAYMENT_DESC"
	value="Привелегия на TorrentsBook.com"> <input type="hidden"
	name="amount" value="<?=$amount;?>"> <input type="hidden" name="mode"
	value="wm"> <input type="hidden" name="id" value="<?=$CURUSER['id']?>">
<input type="hidden" name="result" value=""> <input type="hidden"
	name="LMI_PAYEE_PURSE" value="Z113282224168"> <input type="hidden"
	name="LMI_SIM_MODE" value="0"></p>
<p><input type="submit" value="Продолжить оплату"></p>
</form>

	<?php

	$REL_TPL->stdfoot();
	die();
}

if (!$amount) {
	$REL_TPL->stdhead('Платные услуги TorrentsBook.com');
	?>
<style type="text/css">
	#donate{width: 903px; margin: 0pt auto;}
	
	#donate_sms,#donate_wm,#donate_pp{border:1px solid #CCCCCC;height:60px;margin-bottom:8px;padding-top:15px;}
	.donate_header{padding: 10px 0pt; margin-bottom: 10px;}
	.donate_left_name{width:300px;float:left;}
	.donate_left_name h1{width:130px;float:left;}
	.donate_left_name input{padding-left:15px;float:left;}
	.donate_1,.donate_2,.donate_3{width: 200px; float: left;}
	#donate p{width: 10px; height: 10px; float: left; color: red; padding: 0px 5px 5px; margin-top: 6px;}
	.donate_1 input,.donate_2 input,.donate_3 input{padding-top: 8px; float: left;}
	#donate_pp .donate_1 .pp{float:left;}
	#donate_pp .donate_1 input{float: none; margin-left: -86px; margin-top: 11px;}
	.sertificate p{width: 100px; float: left;}

</style>
<?
	print('
	<div id="donate">
		<div class="donate_header">
			<h3>Спасибо за желание помочь развитию ресурса, так же с оплатой вы поднимаеете себе рейтинг или переходите в Статус VIP</h3>
		</div>
		<div id="donate_sms">
			<div class="donate_left_name">
				<span class="donate_image"><img src="pic/smszamok.jpg" width="60" height="48" border="0" alt="SMS"><h1>SMS</h1></span>
			</div>
			<div class="donate_1">
				<form action="'.$REL_SEO->make_link('donate').'">
					<input type="hidden" name="amount" value="10">
						<input type="image" src="pic/donate/sms10.png" name="10$" value="" class="10sms">
						<p>*</p>
	
				</form>
			</div>
			<div class="donate_2">
				<form action="'.$REL_SEO->make_link('donate').'">
					<input type="hidden" name="amount" value="3">
						<input type="image" src="pic/donate/sms5.png" name="5$" value="" class="5sms">
						<p>**</p>
				</form>
			</div>
			<div class="donate_3">
				<form action="'.$REL_SEO->make_link('donate').'">	
					<input type="hidden" name="amount" value="1">
						<input type="image" src="pic/donate/sms1.png" name="1$" value="" class="1sms">
						<p>***</p>
				</form>
	
			</div>

		</div>
		<div id="donate_wm">
			<div class="donate_left_name">
				<span class="donate_image"><img src="/pic/donate/wm.png" width="51" height="48" border="0" alt="WebMoney"><h1>WebMoney</h1></span>
			</div>
			<div class="donate_1">
				<form action="'.$REL_SEO->make_link('donate').'">
					<input type="hidden" name="mode" value="wm">
						<input type="hidden" name="amount" value="10">
							<input type="image" src="pic/donate/wm10.png" name="10$" value="" class="10wm">
						<p>*</p>
				</form>
			</div>
			<div class="donate_2">
				<form action="'.$REL_SEO->make_link('donate').'">
					<input type="hidden" name="mode" value="wm">
						<input type="hidden" name="amount" value="3">
							<input type="image" src="pic/donate/wm5.png" name="10$" value="" class="3wm">
						<p>**</p>
				</form>
			</div>
			<div class="donate_3">
				<form action="'.$REL_SEO->make_link('donate').'">
					<input type="hidden" name="mode" value="wm">
						<input type="hidden" name="amount" value="1">
							<input type="image" src="pic/donate/wm1.png" name="10$" value="" class="1wm">
						<p>***</p>
				</form>
			</div>
		</div>
			');
	?>
		<div id="donate_pp">
			<div class="donate_left_name">
				<span class="donate_image"><img src="pic/donate/pp.png" width="100" height="48" border="0" alt="PayPal"><h1>PayPal</h1></span>
			</div>
			<div class="donate_1">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="on0" value="Enter your nickname or ID"><span class="pp">VIP account, <b>$20</b></span>
						
							<input type="hidden" name="os0"	value="userid:<?=$CURUSER['id'];?>">
								<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----"> 
									<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="image" alt="PayPal - The safer, easier way to pay online!"> <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>			
			</div>
			<div class="donate_2">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="on0" value="Enter your nickname or ID">
							<span class="pp">30 points of discount, <b>$7</b></span>
							
							<input type="hidden" name="os0"	value="userid:<?=$CURUSER['id'];?>">
								<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----"> 
									<input type="image"	src="https://www.paypal.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="image" alt="PayPal - The safer, easier way to pay online!"> <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</div>
			<div class="donate_3">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="on0" value="Enter your nickname or ID">
							<span class="pp">10 points of discount, <b>$5</b></span>
							
							<input type="hidden" name="os0"	value="userid:<?=$CURUSER['id'];?>">
								<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----"> 
									<input type="image"	src="https://www.paypal.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="image" alt="PayPal - The safer, easier way to pay online!"> 
										<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</div>
		</div>
		</div>
		

			


	<?php print ('
  <div class="info">* Внимание: Цены указаны для <img src="pic/flag/russia.gif" width="15" height="10" border="0" alt="Российская Федерация"> Российской Федерации. Если Вы находитесь вне пределов РФ, то услуга в Вашей стране может быть недоступна, либо конечная цена SMS сообщения будет зависеть от налоговой ставки в Вашей стране.</div>
  <div class="info">* Attention: Prices for PayPal are a little bigger than another due high tax rates. Also, we are checking paypal payments manually, so you will receive your privilege in ONE day.</div>
');
	?>
<div class="sertificate">
	<p><a href="http://www.megastock.ru/">
		<img	src="http://www.megastock.ru/Doc/88x31_accept/blue_rus.gif"	alt="www.megastock.ru" border="0" /></a></p>
	<p><a href="https://passport.webmoney.ru/asp/certview.asp?wmid=627388830309">

	<img src="http://www.megastock.ru/doc/75x75_user/blue_rus.gif" alt="WMID:627388830309" border="0"></a></p> 
	</div>
<div class="clear"></div>
<div class="sp-wrap">
<div class="sp-head folded clickable"><font color="red">Подробнее о
процессе оплаты через систему Webmoney Transfer</font></div>
<div class="sp-body">1. Платеж проводится через систему WebMoney
Merchant в автоматическом режиме<br />
2. После завершения платежа вы сразу же получаете услугу, за которую
платите<br />
3. В случае возникновения различных проблем обращайтесь к <a	href="<?=$REL_SEO->make_link('staff')?>">администрации сайта</a> или по
контактам, указанным выше<br />
4. В случае, если по каким либо причинам вы изменяете свое решение или
желаете получить другую услугу (вместо откупа - статус или наоборот), то
необходимо связаться с нами по указанным выше реквизитам в течении не
позднее 3-х часов после совершения платежа, и деньги будут возвращены в
полном размере за вычетом 5%. Или активирована выбранная Вами другая
услуга (по вашему желанию).</div>
</div>
	<?php
	$REL_TPL->stdfoot();
	die();
}
elseif (($amount<>1) && ($amount<>3) && ($amount<>10)) stderr($REL_LANG->say_by_key('error'),'Неверная сумма пожертвования');
// поддерживаются: cp1251, koi, utf8
$encoding = 'cp1251';
// привязать sms ключ к URL
$url_restrict = false;
// ресурс активаций для ключа
$limit = 1;
// отображать панель смены языков
$lang_switcher = true;
// язык по умолчанию
$default_lang = 'ru';
// номер проекта
// see up
//var_dump($project_id);
//var_dump($amount);

if ($lang_switcher) {
	$language = isset($_GET['lng']) ? $_GET['lng'] : (isset($_COOKIE['z_lng']) ? $_COOKIE['z_lng'] : $default_lang);
} else {
	$language = $default_lang;
}
$result_code = false;
$result_message = "closed";
if (isset($_POST['code']) && preg_match('/^[a-z0-9]{4}-[a-z0-9]{4}$/', $_POST['code'])) {
	$check_url = 'http://check.smszamok.ru/check/?p='.$_POST['code'].'&id='.$project_id;
	if ($url_restrict) {
		$check_url .= "&url_restricted=".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	if ($limit > 0) {
		$check_url .= "&limit=".$limit;
	}
	$handle = fopen($check_url, "r");
	if ($handle !== FALSE) {
		$result_message = fgets($handle, 255);
		$result_code = ($result_message == "true");
		fclose($handle);
	} else {
		$result_message = "server_busy";
	}
}
if (!$result_code) {
	readfile(($result_message == "server_busy") ?
	'http://iface.smszamok.ru/client/sorry.php?lng='.$language.'&enc='.$encoding :
	'http://iface.smszamok.ru/client/'.$language.'.iface.'.$encoding.'.php?pid='.$project_id.'&message='.$result_message.'&ls='.($lang_switcher?'1':'0'));
	// халявы нема
	die();
}


if ($discount) {
	sql_query("UPDATE users SET discount=discount+$discount, modcomment=".sqlesc(date("Y-m-d") . "Купил $discount откупа\n").$CURUSER['modcomment']." WHERE id={$CURUSER['id']}");

	safe_redirect($REL_SEO->make_link('myrating'),1);
	stderr($REL_LANG->say_by_key('success'),'Спасибо, вам успешно прибавлено '.$discount.' единиц откупа, сейчас вы перейдете к странице "Мой рейтинг"','success');
} else {
	sql_query("UPDATE users SET class=".$classes['vip'].", modcomment=".sqlesc(date("Y-m-d") . "Купил VIP\n").$CURUSER['modcomment']." WHERE class<".$classes['vip']." AND id={$CURUSER['id']}");
	if ($CURUSER['dis_reason'] == 'Your rating was too low.') sql_query("UPDATE users SET dis_reason='', enabled=1 WHERE id = {$CURUSER['id']}");

	safe_redirect($REL_SEO->make_link('myrating'),1);
	stderr($REL_LANG->say_by_key('success'),'Спасибо, вам успешно установен VIP статус, сейчас вы перейдете к странице "Мой рейтинг"','success');
}
?>
