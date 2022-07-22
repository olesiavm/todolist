<?php
namespace App;

use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    const URL_SHOW = 'http://192.168.50.5/show-tasks';
    const URL_CREATE = "http://192.168.50.5/create-task";

    public function testShowTask()
    {
        $curl = curl_init(self::URL_SHOW);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_exec($curl);

        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $this->assertEquals(200, $httpcode);
    }

    public function testCreateTask()
    {
        $expectedResponse = 'Заполните все поля';
        $data = ['name' => '', 'email' => '', 'description' => '', 'createTask' => 'Сохранить'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::URL_CREATE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

        $html = curl_exec($curl);

        $htmlDoc = new \DOMDocument();
        $htmlDoc->loadHTML($html);
        $searchNodes = $htmlDoc->getElementsByTagName("span");
        $responseMessage = $searchNodes->item(0)->nodeValue;

        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $this->assertEquals(200, $httpcode);
        $this->assertEquals($expectedResponse, trim($responseMessage));
    }
}

