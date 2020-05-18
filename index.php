<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Sunra\PhpSimple\HtmlDomParser;

class Crawler
{

    protected $url;
    protected $client;

    public function __construct()
    {
        $this->url = 'http://applicant-test.us-east-1.elasticbeanstalk.com/';

        $this->client = new Client([
            'cookies' => new CookieJar(),
            'headers' => [
                'Host' => 'applicant-test.us-east-1.elasticbeanstalk.com',
                'Origin' => 'http://applicant-test.us-east-1.elasticbeanstalk.com',
                'Referer' => 'http://applicant-test.us-east-1.elasticbeanstalk.com/'
            ]
        ]);
    }

    public function getAnswer()
    {
        $response = $this->client->get($this->url);
        $token = $this->getToken($response->getBody());

        $data = [
            'form_params' => [
                'token' => $this->replaceToken($token)
            ]
        ];

        $responsePost = $this->client->post($this->url, $data);

        return HtmlDomParser::str_get_html($responsePost->getBody())->getElementById('answer');
    }

    protected function getToken($stringBody)
    {
        return HtmlDomParser::str_get_html($stringBody)->getElementById('token')->attr['value'];
    }

    protected function replaceToken($token)
    {
        $new_token = '';
        $replacements = [
            "a" => "z",
            "b" => "y",
            "c" => "x",
            "d" => "w",
            "e" => "v",
            "f" => "u",
            "g" => "t",
            "h" => "s",
            "i" => "r",
            "j" => "q",
            "k" => "p",
            "l" => "o",
            "m" => "n",
            "n" => "m",
            "o" => "l",
            "p" => "k",
            "q" => "j",
            "r" => "i",
            "s" => "h",
            "t" => "g",
            "u" => "f",
            "v" => "e",
            "w" => "d",
            "x" => "c",
            "y" => "b",
            "z" => "a"
        ];

        for ($i = 0; $i < strlen($token); $i++) {
            $new_token .= (in_array($token[$i], $replacements) ? $replacements[$token[$i]] : $token[$i]);
        }

        return $new_token;
    }

}

$obj = new Crawler();
echo $obj->getAnswer();