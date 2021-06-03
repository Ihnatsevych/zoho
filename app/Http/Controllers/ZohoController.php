<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZohoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function RefreshToken(){
        $data = [
            'code' => '1000.a508dde7e09b26c7d18ee971ab971827.2777bb0e2ceb964c10f21e25f81f4864',
            'redirect_uri' => 'http://example.com/callbackurl',
            'client_id' => '1000.MHNGMNC3LM2998OGYWUYTX4Z4DOOMD',
            'client_secret' => 'a24050a037c785aa92d3be483f0a61f7cba212913e',
            'grant_type' => 'authorization_code'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));

        $result = curl_exec($ch);
        $result = json_decode($result);

        return $result->{"refresh_token"};
    }

    public function AccessToken()
    {
        $data = [
            'refresh_token' => '1000.8acccc3da4adc37e74df02c85139763c.d27de2b1d4f8047bd61ec104670e1189',
            'client_id' => '1000.MHNGMNC3LM2998OGYWUYTX4Z4DOOMD',
            'client_secret' => 'a24050a037c785aa92d3be483f0a61f7cba212913e',
            'grant_type' => 'refresh_token'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));

        $result = curl_exec($ch);
        $result = json_decode($result);
        return $result->{"access_token"};
    }
    public function InsertRecord(){
        $access_token = $this->AccessToken();

        $data = "{\"data\":[{\"Deal_Name\": \"Test_Deal\",\"Stage\": \"Identify Decision Makers\"}],\"trigger\":[\"approval\",\"workflow\"]}";


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.zohoapis.com/crm/v2/Deals");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Zoho-oauthtoken '.$access_token,
            'Content-Type: application/x-www-form-urlencoded'
        ));

        $deal = curl_exec($ch);
        $deal = json_decode($deal);

        $what_id = $deal->{"data"}[0]->{"details"}->{"id"};

        $data = "{\"data\":[{\"Subject\": \"Test_Task\",\"What_Id\": \"$what_id\",\"se_module\": \"Deals\"}]}";


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.zohoapis.com/crm/v2/Tasks");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Zoho-oauthtoken '.$access_token,
            'Content-Type: application/x-www-form-urlencoded'
        ));
        $task = curl_exec($ch);
        $task = json_decode($task);

    }
}
