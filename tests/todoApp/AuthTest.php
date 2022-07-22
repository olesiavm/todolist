<?php
namespace App;

use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    private const URL = 'http://192.168.50.5/authentication';

    /**
     * @dataProvider getDataProviderForAuthForm
     */
    public function testAuth($expectedResponse, $data)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::URL);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

        $json = curl_exec($curl);
        $list = json_decode($json);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $this->assertEquals(200, $httpcode);
        $this->assertEquals($expectedResponse, $list->error);
    }

    public function getDataProviderForAuthForm() {
        return [
            [
                'Запоните все поля!',
                ['login' => '', 'password' => ''],
            ],
            [
                'Запоните все поля!',
                ['login' => 'sdfsdf', 'password' => ''],
            ],
            [
                'Вы можете использовать только буквы и цифры. Число символов должно быть не меньше 3 и не больше 20',
                ['login' => '%a', 'password' => '*()1234567'],
            ],
            [
                'Пользователь с таким логином не зарегистрирован!',
                ['login' => 'ivan', 'password' => '64646456'],
            ],
            [
                'Не верно введен логин или пароль!',
                ['login' => 'admin', 'password' => '1234567'],
            ]
        ];
    }
}

