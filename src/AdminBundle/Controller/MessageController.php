<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Draft;
use AdminBundle\Entity\Message;
use AdminBundle\Form\AvatarType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use AppBundle\Entity\Avatar ;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\FCMService\FCMMessage;

class MessageController extends Controller
{
    public function mailBoxAction(Request $request)
    {

        $user=$this->get('session')->get('user');
        $messages=$this->getDoctrine()->getRepository('AdminBundle:Message')->findBy(array('receiver'=>null));

        $messages_no_read=$this->getDoctrine()->getRepository('AdminBundle:Message')->findBy(array('msg_read'=>0));
        $nb_msg_read=count($messages_no_read);

        return $this->render('AdminBundle:Default:MailBox/mailbox.html.twig',array(
            'messages'=>$messages,
            'nb_msg_no_read'=>$nb_msg_read,
            'user'=>$user
        ));
    }

    public function composeAction(Request $request)
    {
        $message=new Message();
        $form = $this->createForm('AdminBundle\Form\MessageType', $message);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $sender=$this->get('session')->get('user');
            $sender=$this->getDoctrine()->getRepository('UserBundle:User')->find($sender->getId());
            $message->setSender($sender);
            $message->setDate(new \DateTime());
            $message->setMsgRead(1);

            $em->persist($message);
            $em->flush();

            $response=new RedirectResponse($this->generateUrl('sent_messages'));
            return $response;

        }
        $user=$this->get('session')->get('user');
        $messages_no_read=$this->getDoctrine()->getRepository('AdminBundle:Message')->findBy(array('msg_read'=>0));
        $nb_msg_read=count($messages_no_read);
        return $this->render('AdminBundle:Default:MailBox/compose.html.twig',array(
            'nb_msg_no_read'=>$nb_msg_read,
            'form'=>$form->createView(),
            'user'=>$user
        ));
    }

    public function readMailAction(Message $message)
    {
        $user=$this->get('session')->get('user');
        $message->setMsgRead(1);
        $em=$this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();
        $messages_no_read=$this->getDoctrine()->getRepository('AdminBundle:Message')->findBy(array('msg_read'=>0));
        $nb_msg_read=count($messages_no_read);

        return $this->render('AdminBundle:Default:MailBox/readmail.html.twig',array('message'=>$message,
                                                                                    'nb_msg_no_read'=>$nb_msg_read,
                                                                                    'user'=>$user));
    }

    public function messagesSentAction(Request $request){

        $user=$this->get('session')->get('user');
        $messages_no_read=$this->getDoctrine()->getRepository('AdminBundle:Message')->findBy(array('msg_read'=>0));
        $nb_msg_read=count($messages_no_read);
        $messages=$this->getDoctrine()->getRepository('AdminBundle:Message')->findBy(array('sender'=>$user));

        return $this->render('AdminBundle:Default:MailBox/messagesSent.html.twig',array(
            'messages'=>$messages,
            'nb_msg_no_read'=>$nb_msg_read,
            'user'=>$user
        ));
    }


    public function deleteMailAction(Message $message){

        $sender=$message->getSender();
        $em=$this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();
        $user=$this->get('session')->get('user');

        if($sender->getId()==$user->getId())
            return $response = new RedirectResponse($this->generateUrl('sent_messages'));
        else
        return $response = new RedirectResponse($this->generateUrl('mail_box'));

    }


    public function replyAction(Message $msg,Request $request){

        $u=$this->get('session')->get('user');
        $user=$this->getDoctrine()->getRepository('UserBundle:User')->find($u->getId());
        $message= new Message();
        $message->setSender($user);
        $message->setReceiver($msg->getSender());
        $reply=" رد :   ".$msg->getSubject();
        $message->setSubject($reply);
        $form = $this->createForm('AdminBundle\Form\MessageType', $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $message->setDate(new \DateTime());
            $message->setMsgRead(1);

            $em->persist($message);
            $em->flush();

            $receiver=$message->getReceiver();
            $fcm_message = new FCMMessage();

            $fcm_message->setNotification(array("title"=>"Reply from Snap Sell App Admin",
                "text"=>array(
                    "subject"=>$message->getSubject(),
                    "message"=>$message->getMessage()
                )));
            $fcm_message->setReceiverId($receiver->getGcmRegistrationId());
            $this->sendMessage($fcm_message->getMessage());

            $response=new RedirectResponse($this->generateUrl('sent_messages'));
            return $response;

        }

        $user=$this->get('session')->get('user');
        $messages_no_read=$this->getDoctrine()->getRepository('AdminBundle:Message')->findBy(array('msg_read'=>0));
        $nb_msg_read=count($messages_no_read);
        return $this->render('AdminBundle:Default:MailBox/compose.html.twig',array(
            'nb_msg_no_read'=>$nb_msg_read,
            'form'=>$form->createView(),
            'user'=>$user
        ));
    }

    private function sendMessage($message){
        $API_ACCESS_KEY=$this->getParameter('API_KEY');
        $headers = array
        (
            'Authorization: key=' . $API_ACCESS_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, $message );
        $result = curl_exec($ch );
        curl_close( $ch );
    }


}
