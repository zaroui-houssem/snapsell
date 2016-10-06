<?php
namespace AppBundle\FCMService ;
use AppBundle\FCMService\FCMMessage ;

class FCMService {

    private $API_ACCESS_KEY;

    public function  __construct()
    {
        $this->API_ACCESS_KEY='AIzaSyDn466FZ4JoEW4Hxo6Fm3gWdjSZzXboJ4M' ;
    }

    public function send(FCMMessage $message){
        $headers = array
        (
            'Authorization: key=' . $this->API_ACCESS_KEY,
            'Content-Type: application/json'
        );
        $data=array(
            "notification"=>array(
                "title"=>"test notification",
                "text"=>"text notification"
            ),
            "to"=>"dEIt_rBlFS0:APA91bFB0DEVJzkznABULNe5ulljEoftdQKTkp5JLEkbFYa5DcjYLzBtpc8O-fHe7WWYxklGAT_j7gdEJpXY1q-GCq0A5kbmHY_zYq7Mwc9wilSYjVWrZzLhJ_aRnIbcAowddhjVladU"
        );


        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($data) );
        $result = curl_exec($ch );
        curl_close( $ch );

    }
}