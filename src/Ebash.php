<?php

namespace  Wpdew\Ebash;

class Ebash
{
    

    public function getName($name)
    {
        return 'Hi '.$name.' from Ebash Class';
    }

	public function SendTelegram($botToken, $chatId, $message)
    {
        $botToken = $botToken;
        $chatId = $chatId;
        $message = $message;

        $url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&parse_mode=html&text=".$message."";
        $ch = curl_init();
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public function SendTelegramArray($botToken, $chatId, $message)
    {
        $botToken = $botToken;
        $chatId = $chatId;
        $message = $message;
        $message = $this->array2string($message);
        $result = fopen("https://api.telegram.org/bot{$botToken}/sendMessage?chat_id={$chatId}&parse_mode=html&text={$message}","r");
        return $result;
    }

    public function SendCurl($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }


	//send sendToTelegram function
	public function sendToTelegram($tgtoken, $tgchatid, $arrTg) {
		$txt;
		foreach ($arrTg as $key => $value) {
			$txt .= "<b>" . $key . "</b> " . $value . "%0A";
		};
		$sendToTelegram = fopen("https://api.telegram.org/bot{$tgtoken}/sendMessage?chat_id={$tgchatid}&parse_mode=html&text={$txt}", "r");

	}

	//send sentdToLpCrm function
	public function sentdToLpCrm($crm_lp_token, $crm_lp_adress, $lp_office, $dataarray){

		$products_list = array(
			0 => array(
				'product_id' => $dataarray['product_id'],
				'price'      => $dataarray['product_price'],
				'count'      => $dataarray['count'],
			),
		);
		$_SERVER['SERVER_NAME'] = $_SERVER['SERVER_NAME']. dirname($_SERVER['SCRIPT_NAME']);
		$products = urlencode(serialize($products_list));
		$sender = urlencode(serialize($_SERVER));
		$data = array (
			'key'             => $crm_lp_token,
			'order_id'        => number_format(round(microtime(true) * 10), 0, '.', ''),
			'country'         => 'UA',
			'office'          => $lp_office,
			'products'        => $products,
			'bayer_name'      => $dataarray['name'],
			'phone'           => $dataarray['phone'],
			'comment'         => $dataarray['comment'],
			'payment'         => $dataarray['payment'],
			'delivery'        => $dataarray['delivery'],
			'delivery_adress' => $dataarray['delivery_adress'],
			'sender'          => $sender,
			'utm_source'      => $_SESSION['utms']['utm_source'],
			'utm_medium'      => $_SESSION['utms']['utm_medium'],
			'utm_term'        => $_SESSION['utms']['utm_term'],
			'utm_content'     => $_SESSION['utms']['utm_content'],
			'utm_campaign'    => $_SESSION['utms']['utm_campaign'],
			'additional_1'    => $dataarray['additional_1'],
			'additional_2'    => $dataarray['additional_2'],
			'additional_3'    => $dataarray['additional_3'],
			'additional_4'    => $dataarray['additional_4'] 
		);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $crm_lp_adress . '/api/addNewOrder.html');
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		$out = curl_exec($curl);
		curl_close($curl);

	}

	public function sentdEbashCrm($crm_ebash_token, $crm_ebash_adress, $ebash_office, $dataarray){

		$products_list = array(
			0 => array(
				'product_id' => $dataarray['product_id'],
				'price'      => $dataarray['product_price'],
				'count'      => $dataarray['count'],
			),
		);
		$_SERVER['SERVER_NAME'] = $_SERVER['SERVER_NAME'];
		$products = urlencode(serialize($products_list));
		$sender = urlencode(serialize($_SERVER));
		$data = array (
			'key'             => $crm_ebash_token,
			'order_id'        => number_format(round(microtime(true) * 10), 0, '.', ''),
			'country'         => 'UA',
			'office'          => $ebash_office,
			'products'        => $products,
			'bayer_name'      => $dataarray['name'],
			'phone'           => $dataarray['phone'],
			'comment'         => $dataarray['comment'],
			'payment'         => '1',
			'sender'          => $sender,
		);

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $crm_ebash_adress. 'order/inc/api.php',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $data
		  ));

		$response = curl_exec($curl);
		curl_close($curl);



	}

	//send sendEmail function
	public function sendEmail($email, $arrTg){
		$subject = "Заказ товара ";

		$message;
		$message .= "<b>Заказ товара</b><br/><hr/><br/>";
		foreach ($arrTg as $key => $value) {
			$message .= "<b>" . $key . "</b> " . $value . "<br/>";
		};
		$message .= "<hr/><br/>";
		$message .= "<b>Дата: </b> " . date("Y-m-d H:i:s") . "<br/>";
		$message .= "Разработка конфигуратора  <a href='https://t.me/WpDews'>@WpDews</a><br/>";


		$sendMail = mail($email, $subject, $message, "Content-type:text/html; charset=UTF-8\r\n");

	}

	//send sentdToSalesDrive function
	public function sentdToSalesDrive($crm_salesdrive_token, $crm_salesdrive_sources, $dataarray){
		$crm_salesdrive_token = $crm_salesdrive_token;
		$crm_salesdrive_sources = $crm_salesdrive_sources;
		$dataarray = $dataarray;

		$products = [];

		$products[0]["id"] = $dataarray['product_id']; // id товару
		$products[0]["name"] = $dataarray['product_title']; // назва товару
		$products[0]["costPerItem"] = $dataarray['product_price']; // ціна
		$products[0]["amount"] = $dataarray['count']; // кількість
		$products[0]["description"] = ""; // опис товарної позиції в заявці
		$products[0]["discount"] = ""; // знижка, задається в % або в абсолютній величині
		$products[0]["sku"] = ""; // артикул (SKU) товару

		$_salesdrive_url = $crm_salesdrive_sources;
		$_salesdrive_values = [
			"form" => $crm_salesdrive_token,
			"getResultData" => "1", // Отримувати дані створеної заявки (0 - не отримувати, 1 - отримувати)
			"products"=>$products, //Товари/Послуги
			"comment"=>$dataarray['comment'], // Коментар
			"fName"=>$dataarray['name'], // Ім'я
			"lName"=>"", // Прізвище
			"mName"=>"", // По батькові
			"phone"=>$dataarray['phone'], // Телефон
			"email"=>"", // E-mail
			"con_comment"=>$dataarray['comment'], // Коментар
			"shipping_method"=>"", // Спосіб доставки
			"payment_method"=>"", // Спосіб оплати
			"shipping_address"=>"", // Адреса доставки
			"novaposhta"=> [
				"ServiceType" => "", // можливі значення: DoorsDoors, DoorsWarehouse, WarehouseWarehouse, WarehouseDoors
				"payer" => "", // можливі значення: "sender", "recipient"
				"area" => "", // область російською або українською мовою, або Ref області в системі Нової пошти
				"region" => "", // район російською або українською мовою (використовується тільки якщо cityNameFormat=settlement)
				"city" => "", // назва міста російською або українською мовою, або Ref міста в системі Нової пошти
				"cityNameFormat" => "", // можливі значення: full (за замовчуванням), short, settlement (населений пункт із нової адресної системи: ref або назва)
				"WarehouseNumber" => "", // відділення Нової Пошти в одному з форматів: номер, опис, Ref
				"Street" => "", // назва і тип вулиці, або Ref вулиці в системі Нової пошти
				"BuildingNumber" => "", // номер будинку
				"Flat" => "", // номер квартири
			],
			"ukrposhta"=> [
				"ServiceType" => "", // можливі значення: DoorsDoors, DoorsWarehouse, WarehouseWarehouse, WarehouseDoors
				"payer" => "", // можливі значення: "sender", "recipient"
				"type" => "", // можливі значення: express, standard
				"city" => "", // місто російською або українською мовою, або CITY_ID Укрпошти
				"WarehouseNumber" => "", // номер відділення Укрпошти
				"Street" => "", // STREET_ID Укрпошти
				"BuildingNumber" => "", // номер будинку
				"Flat" => "" // номер квартири
			],
			"sajt"=>"", // Сайт
			"organizationId"=>"", // id організації
			"shipping_costs"=>"", // Витрати на доставку
			"stockId"=>"", // id складу
			"prodex24source_full"=>isset($_COOKIE["prodex24source_full"])?$_COOKIE["prodex24source_full"]:"",
			"prodex24source"=>isset($_COOKIE["prodex24source"])?$_COOKIE["prodex24source"]:"",
			"prodex24medium"=>isset($_COOKIE["prodex24medium"])?$_COOKIE["prodex24medium"]:"",
			"prodex24campaign"=>isset($_COOKIE["prodex24campaign"])?$_COOKIE["prodex24campaign"]:"",
			"prodex24content"=>isset($_COOKIE["prodex24content"])?$_COOKIE["prodex24content"]:"",
			"prodex24term"=>isset($_COOKIE["prodex24term"])?$_COOKIE["prodex24term"]:"",
			"prodex24page"=>isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:"",
		];

		$_salesdrive_ch = curl_init();
		curl_setopt($_salesdrive_ch, CURLOPT_URL, $_salesdrive_url);
		curl_setopt($_salesdrive_ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($_salesdrive_ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($_salesdrive_ch, CURLOPT_SAFE_UPLOAD, true);
		curl_setopt($_salesdrive_ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($_salesdrive_ch, CURLOPT_POST, 1);
		curl_setopt($_salesdrive_ch, CURLOPT_POSTFIELDS, json_encode($_salesdrive_values));
		curl_setopt($_salesdrive_ch, CURLOPT_TIMEOUT, 15);

		$_salesdrive_res = curl_exec($_salesdrive_ch);

	}

	//send sentdToKeyCrm function
	public function sentdToKeyCrm($crm_key_token, $crm_key_sources, $dataarray){
		$crm_key_token = $crm_key_token;
		$crm_key_sources = $crm_key_sources;
		$dataarray = $dataarray;

			$data = [
				"source_id" => $crm_key_sources, // в какой источник в KeyCRM добавлять заказы
				"buyer" => [
					"full_name"=> $dataarray['name'], // ФИО покупателя
					"email"=> $dataarray['email'], // email покупателя
					"phone"=> $dataarray['phone'] // номер телефона покупателя
				],
				"shipping" => [
					"shipping_address_city"=> $_POST['address_city'], // город покупателя
					"shipping_receive_point"=> $_POST['address_street'], // улица, номер дома или отделение Новой Почты
					"shipping_address_country"=> $_POST['address_country'], // страна
					"shipping_address_region"=> $_POST['address_region'], // область/штат/регион
					"shipping_address_zip"=> $_POST['address_zip'] // индекс
				],
				"products"=> [
					[
						"price"=> $dataarray['product_price'], // цена продажи
						"quantity"=> $dataarray['count'], // количество проданного товара
						"name"=> $dataarray['product_title'], // название товара
						"picture"=> $_POST['product_url'], // картинка товара
						"comment"=> $dataarray['comment'],
						"properties"=>[
						[
							"name"=> $dataarray['properties_name'],
							"value"=> $dataarray['properties_value']
						]
						]
					]
				]
			];

			//  "упаковываем данные"
			$data_string = json_encode($data);

			// Ваш уникальный API ключ KeyCRM
			$token = $crm_key_token;

			// отправляем на сервер
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://openapi.keycrm.app/v1/order");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					"Content-type: application/json",
					"Accept: application/json",
					"Cache-Control: no-cache",
					"Pragma: no-cache",
					'Authorization:  Bearer ' . $token)
			);
			$result = curl_exec($ch);
			curl_close($ch);

	}

	function getCaptcha($SectretKey){
		$SiteKey = "6LfoAYkjAAAAAJGXXB8wSco-P8RSFZNN8cT7xHfY";
		$Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$SiteKey}&response={$SectretKey}");
		$Return = json_decode($Response);
		return $Return;
	}

	
}