<?php

namespace UserBundle\Controller;

use AppBundle\Entity\Avatar;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use UserBundle\Entity\User ;
use AppBundle\Entity\Vote ;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\HttpFoundation\Request ;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\Annotations\View;

class UserController extends Controller
{
    /**
     * @View(serializerGroups={"Default"})
     */

    public function getAction($id){

    $user=$this->getDoctrine()->getRepository("UserBundle:User")->find($id);
    if(is_object($user)){

        return $user;
    }
    else{
        $serializer = $this->container->get('serializer');
        $rep = $serializer->serialize(array("error"=>"user not found"), 'json');
        return $response= new Response($rep,404, array('Content-Type' => 'application/json'));
    }

    }

    /**
     * @View(serializerGroups={"me"})
     */
    public function getMeAction($id){
        $user=$this->getDoctrine()->getRepository("UserBundle:User")->find($id);
        if(is_object($user)){

            return $user;
        }
        else{
            $serializer = $this->container->get('serializer');
            $rep = $serializer->serialize(array("error"=>"user not found"), 'json');
            return $response= new Response($rep,404, array('Content-Type' => 'application/json'));
        }

    }


    public function updateAction(Request $request,$id)
    {

        $user=$this->getDoctrine()->getRepository("UserBundle:User")->find($id);
        $em = $this->getDoctrine()->getManager();

        if(is_object($user)){
           foreach($request->request as $key=>$value){
               if($key=="phone_number")
                   $user->setPhoneNumber($value);
               else{
                   $setter='set'.ucfirst($key);
                   $user->$setter($value);
               }

           }

            $em->flush();
        }
        else {
            $serializer = $this->container->get('serializer');
            $rep = $serializer->serialize(array("error"=>"user not found"), 'json');
            return $response= new Response($rep,404, array('Content-Type' => 'application/json'));
        }

        return new Response("ok",200,array('Content-Type' => 'application/json'));
    }
    public function deleteAction($id)
    {

        $user=$this->getDoctrine()->getRepository("UserBundle:User")->find($id);
        $em=$this->getDoctrine()->getManager();
        if(is_object($user)){
            $products=$this->getDoctrine()->getRepository('AppBundle:Product')->findBy(array("seller"=>$user));
            foreach($products as $product){
                $offers=$this->getDoctrine()->getRepository('AppBundle:Offer')->findBy(array("product"=>$product));
                    foreach($offers as $offer)
                    {
                        $offer->setActivated(0);
                    }
                $product->setActivated(0);
            }

            $user->setActivated(0);
            $em->flush();
            return $response= new Response("ok",200, array('Content-Type' => 'application/json'));
        }
        else  {
            $serializer = $this->container->get('serializer');
            $rep = $serializer->serialize(array("error"=>"user not found"), 'json');
            return $response= new Response($rep,404, array('Content-Type' => 'application/json'));
        }

    }

    public function changeAvatarAction(Request $request,$id){
        $file=$request->files->get('avatar');
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $user=$this->getDoctrine()->getRepository('UserBundle:User')->find($id);

        if(!is_null($user->getAvatar())){
            $avatar=$user->getAvatar();

            $em=$this->getDoctrine()->getManager();

            $avatar->setPath($this->getParameter('media_path').$fileName);
            $avatar->setWebPath($this->getParameter('media_web_path').'/'.$fileName);
            $em->flush();

        }

        else {
            $avatar=new Avatar();
            $avatar->setPath($this->getParameter('media_path').$fileName);
            $avatar->setWebPath($this->getParameter('media_web_path').'/'.$fileName);
            $em=$this->getDoctrine()->getManager();
            $em->persist($avatar);
            $em->flush();

            $user->setAvatar($avatar);
            $em->persist($user);
            $em->flush();
        }

        $file->move(
            $this->getParameter('media_directory'),
            $fileName
        );
        $res=new Response();
        $res->setStatusCode(200);
        return $res;
    }

    /**
     * @View(serializerGroups={"list"})
     */
    public function getNearProductsAction($latitude,$longitude)
    {
            return $products=$this->getDoctrine()->getRepository('AppBundle:Product')->findNearProduits($latitude,$longitude);

    }
    public function postUserVoteAction($id,Request $request){
        $value=$request->request->get('vote_value');
        $em=$this->getDoctrine()->getManager();
        $seller=$em->getRepository('UserBundle:User')->find($id);
        $buyer=$em->getRepository('UserBundle:User')->find($request->request->get('buyer'));


        if(!is_object($buyer))
            throw  $this->createNotFoundException('Buyer id  not found ');
        else{
            $vote=$em->getRepository('AppBundle:Vote')->findOneBy(array("seller"=>$seller,
                "buyer"=>$buyer));
            if(!is_object($vote)){
                $new_vote=new Vote();
                $new_vote->setValue($value);
                $new_vote->setBuyer($buyer);
                $new_vote->setSeller($seller);
                $em->persist($new_vote);
                $s=($seller->getVote()*$seller->getNbVote())+$value;
                $new_vote=$s/($seller->getNbVote()+1);
                $seller->setVote($new_vote);
                $seller->setNbVote($seller->getNbVote()+1);
            }else{
                $s=($seller->getVote()*$seller->getNbVote())+$value-$vote->getValue();
                $new_vote=$s/$seller->getNbVote();
                $seller->setVote($new_vote);
                $vote->setValue($new_vote);
            }

            $em->flush();

        }
        $res=new Response();
        $res->setStatusCode(200);
        return $res;
    }


    /**
     * @View(serializerGroups={"products user list"})
     */
    public function getUserProductsAction($id){
        $user=$this->getDoctrine()->getRepository('UserBundle:User')->findOneBy(array('id'=>$id,
                                                                                        'activated'=>1));
        if(is_object($user))
        return $products=$this->getDoctrine()->getRepository('AppBundle:Product')->findBy(array("seller"=>$user));
        else throw $this->createNotFoundException('User not found or deactivated') ;
    }

    /**
     * @View(serializerGroups={"user offers list"})
     */
    public function getUserOffersAction($id){
        $user=$this->getDoctrine()->getRepository('UserBundle:User')->findOneBy(array('id'=>$id,
                                                                                      'activated'=>1));
        if(is_object($user))
        return $offers=$this->getDoctrine()->getRepository('AppBundle:Offer')->findBy(array("buyer"=>$user,
                                                                                            "activated"=>1));
        else throw $this->createNotFoundException('User not found or deactivated') ;
    }

    /**
     * @View(serializerGroups={"user offers list"})
     */
    public function getUserAcceptedOffersAction($id){
        $user=$this->getDoctrine()->getRepository('UserBundle:User')->find($id);

        return $offers=$this->getDoctrine()->getRepository('AppBundle:Offer')->findBy(array("buyer"=>$user,
                                                                                            "accept"=>1,
                                                                                            "activated"=>1));
    }
}
