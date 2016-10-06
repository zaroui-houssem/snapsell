<?php
/**
 * Created by PhpStorm.
 * User: AS
 * Date: 26/09/2016
 * Time: 10:19
 */

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response ;
use FOS\RestBundle\Controller\Annotations\View;
use AdminBundle\Entity\Message;
use UserBundle\Entity\User;
use AppBundle\FCMService\FCMMessage;

class MessageController extends Controller
{

    public function sendAction(User $user,Request $request){
        $message=new Message();
        $message->setDate(new \DateTime());
        $message->setMessage($request->request->get('message'));
        $message->setSubject($request->request->get('subject'));
        $message->setSender($user);
        $message->setMsgRead(0);
        $em=$this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();
    }




}