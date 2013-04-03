<?php
namespace HackThursday\Handler;

use HackThursday\Entity\FacebookUser;

class FacebookHandler
{
    private $cookiePath;
    private $user;

    public function __construct($cookiePath)
    {
        $this->cookiePath = $cookiePath;
    }

    public function login($username, $password)
    {
        $this->user = new FacebookUser($username, $password);
        
        $this->retrieveCookiesAndIdentifiers();
       
        $this->facebookLogin();
    }

    public function request($url)
    {
        return $this->facebookRequest($url);
    }

    private function retrieveCookiesAndIdentifiers()
    {
        //credits to Sony AK Knowledge Center - www.sony-ak.com
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://www.facebook.com");
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookiePath);
        curl_setopt(
            $curl,
            CURLOPT_USERAGENT,
            "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.2) 
            Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)"
        );
        $curlData = curl_exec($curl);
        curl_close($curl);

        $this->identifiers = $this->retrieveIdentifiers($curlData);
    } 

    private function retrieveIdentifiers($curlData)
    {
        //credits to Sony AK Knowledge Center - www.sony-ak.com
        $charsetTest = substr($curlData, strpos($curlData, "name=\"charset_test\""));
        $charsetTest = substr($charsetTest, strpos($charsetTest, "value=") + 7);
        $charsetTest = substr($charsetTest, 0, strpos($charsetTest, "\""));

        $locale = substr($curlData, strpos($curlData, "name=\"locale\""));
        $locale = substr($locale, strpos($locale, "value=" ) + 7);
        $locale = substr($locale, 0, strpos($locale, "\""));

        $lsd = substr($curlData, strpos($curlData, "name=\"locale\""));
        $lsd = substr($lsd, strpos($lsd, "value=") + 7);
        $lsd = substr( $lsd, 0, strpos($lsd, "\""));

        return array($charsetTest, $locale, $lsd);
    }

    private function facebookLogin()
    {
        return $this->facebookRequest(
            "https://login.facebook.com/login.php?login_attempt=1"
        );
    }

    private function facebookRequest($url)
    {
        //credits to Sony AK Knowledge Center - www.sony-ak.com
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        list($charsetTest, $locale, $lsd) = $this->identifiers;

        curl_setopt(
            $curl,
            CURLOPT_POSTFIELDS, 
            "charset_test=" . $charsetTest . "&locale=" . $locale .
            "&non_com_login=&email=" . $this->user->getUsername() .
            "&pass=" . $this->user->getPassword() .
            "&charset_test=" . $charsetTest . "&lsd=" . $lsd
        );

        curl_setopt($curl, CURLOPT_ENCODING, "" );
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookiePath);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookiePath);
        curl_setopt(
            $curl,
            CURLOPT_USERAGENT, 
            "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.2) 
            Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)"
        );
        $curlData = curl_exec ( $curl );

        return $curlData;   
    }

}