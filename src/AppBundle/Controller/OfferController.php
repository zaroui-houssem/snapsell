<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Offer ;
use Symfony\Component\HttpFoundation\Response ;
use FOS\RestBundle\Controller\Annotations\View;
use AppBundle\FCMService\FCMMessage;

class OfferController extends Controller
{
    /**
     * @View(serializerGroups={"listOffer"})
     */
    public function getOfferAction($id){
        $offer=$this->getDoctrine()->getRepository('AppBundle:Offer')->findOneBy(array('id'=>$id,'activated'=>1));
        if(is_object($offer))
            return $offer;
        else throw $this->createNotFoundException('Offer not found or deactivated ');

    }
    public function postOffersAction(Request $request,$id){

        $value=$request->request->get('value');
        $product=$this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        $buyer=$this->getDoctrine()->getRepository('UserBundle:User')->find($request->request->get('buyer'));
        $offer =new Offer();
        if($product->getOfferType()=="Sale")
            $offer->setValue($product->getPrice());
        else
        $offer->setValue($value);
        $offer->setDate(new \DateTime());
        $offer->setProduct($product);
        $offer->setBuyer($buyer);
        $offer->setAccept(0);
        $offer->setActivated(1);
        if(!is_object($buyer)) throw $this->createNotFoundException('Buyer not found') ;
        else if(!is_object($product)) throw $this->createNotFoundException('Product not found') ;
        else{
            if($product->getOfferType()=='sale') {
                $of = $this->getDoctrine()->getRepository('AppBundle:Offer')->findOneBy(array("product" => $product,
                    "buyer" => $buyer));

                if (!is_object($of)) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($offer);
                    $em->flush();
                    $message = new FCMMessage();
                    $message->setNotification(array("title" => "Snap Sell App",
                        "text" => "New purchase order for " . $product->getName()));

                    $message->setReceiverId($product->getSeller()->getGcmRegistrationId());
                    $this->sendMessage($message->getMessage());
                    return array("id" => $offer->getId());

                } else {
                    $response = new Response();
                    $response->setStatusCode(RESPONSE::HTTP_CONFLICT);
                    $response->setContent('already apply ');
                    return $response;
                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($offer);
            $em->flush();
            $message = new FCMMessage();
            $message->setNotification(array("title" => "Snap Sell App",
                "text" => "New purchase order for " . $product->getName()));

            $message->setReceiverId($product->getSeller()->getGcmRegistrationId());
            $this->sendMessage($message->getMessage());
            return array("id" => $offer->getId());
        }
    }

    public function deleteOfferAction($id){
            $offer=$this->getDoctrine()->getRepository('AppBundle:Offer')->find($id);
            $em= $this->getDoctrine()->getManager();
            $offer->setActivated(0);
            $em->flush();
            $res=new Response();
            $res->setStatusCode(200);
            return $res;
    }

    /**
     * @View(serializerGroups={"listOffer"})
     */
    public function getUserOffersAction($id,$slug){

        $offers=$this->getDoctrine()->getRepository('AppBundle:Offer')->getUserOffers($id,$slug);
        return $offers;

    }

    public function acceptOfferAction($id){
        $offer=$this->getDoctrine()->getRepository('AppBundle:Offer')->find($id);
        $offer->setAccept(1);
        $product=$offer->getProduct();
        $product->setSoldOut(1);
        $product->setActivated(0);
        $em=$this->getDoctrine()->getManager();
        $em->persist($offer);
        $em->persist($product);
        $em->flush();

        $message = new FCMMessage();

        $message->setNotification(array("title"=>"Snap Sell App",
                                        "text"=>" your offer has been accepted "));
        $message->setReceiverId($offer->getBuyer()->getGcmRegistrationId());
        $this->sendMessage($message->getMessage());
        $res=new Response();
        $res->setStatusCode(200);
        return $res;
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
