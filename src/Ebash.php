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

	
}