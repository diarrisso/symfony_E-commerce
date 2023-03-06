<?php
namespace App\Service;

use DateTimeImmutable;
use Monolog\Handler\IFTTTHandler;

class JWTService 
{
 /**
  * 
  */
  public function generate($header, $payload, $seceret, $isvalidity = 10800)
   {
     if($isvalidity <= 0 ) {
        return "";
     }

     $now = new DateTimeImmutable();
     $expire = $now->getTimestamp() + $isvalidity;

     $payload['iat'] = $now->getTimestamp();
     $payload['exp'] = $expire;

      // encoder les variables 
     $base_64Header =  base64_encode(json_encode($header));
     $base_64Pyload =  base64_encode(json_encode($payload));
     // nettoie les valeurs '
     $base_64Header = str_replace(['+', '/', '='], ['-', '_', '' ], $base_64Header);
     $base_64Pyload = str_replace(['+', '/', '='], ['-', '_', '' ], $base_64Pyload);

     // generee la signature
     $seceret = base64_encode($seceret);
     $signature = hash_hmac('sha256', $base_64Header. '.' .$base_64Pyload, $seceret, true);
     
     $base64_signature = base64_encode($signature);

     $base64_signature = str_replace(['+', '/', '='], ['-', '_', '' ], $base64_signature);

     // on crere le token 
     $JWT = $base_64Header . '.' . $base_64Pyload . '.'  .$base64_signature;


      return $JWT;
  }


  public function isValid($token)
  {

    return preg_match('/^[a-zq-z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/', $token) == 1;

  }

   // on recupere le payload 
    public function getPayload( $token)
   {
        $array = explode('.', $token);

        $payload = json_decode(base64_decode($array[1]), true);


        return $payload;
    }



    // on recupere le header 
    public function getHeader( $token)
   {
        $array = explode('.', $token);

        $header = json_decode(base64_decode($array[0]), true);


        return $header;
    }


    // on verifier le token a expiré 

      public function isExpire($token)
    {
      $payload = $this->getPayload($token);

      $now = new DateTimeImmutable();
    
      return $payload['exp'] >$now->getTimestamp();
    }

    // on verifie si l signture est bonne
    public function check($token, $seceret)
    {
        // on recuperer header et payload
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

    }
}