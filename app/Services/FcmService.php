<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Client;
use Illuminate\Support\Facades\Http;

class FcmService
{
    private $client;

    public function __construct()
    {
        // تحميل ملف مفاتيح الخدمة
        $this->client = new Client();
        
        //$this->client->setAuthConfig('E:\RAWAN\AlqemahProject\storage\app\fcm_service_account.json');

        $this->client->setAuthConfig(storage_path('app/fcm_service_account.json')); // المسار إلى ملف JSON لمفاتيح الخدمة
        $this->client->addScope('https://www.googleapis.com/auth/cloud-platform');
    }

    public function getAccessToken()
    {
        // الحصول على توكن وصول OAuth 2.0
        $this->client->fetchAccessTokenWithAssertion();
        return $this->client->getAccessToken()['access_token'];
    }

    public function sendNotification($deviceToken, $title, $body)
    {
        $accessToken = $this->getAccessToken();

        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ];

        $data = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ],
        ];

        $response = Http::withHeaders($headers)->post('https://fcm.googleapis.com/v1/projects/endprojectdashbordmanager/messages:send', $data);

        return $response->json();
    }
}