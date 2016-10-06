<?php

namespace UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\User ;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\HttpFoundation\Request ;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\Annotations\View;

class SecurityController extends Controller
{
    public function signUpAction(Request $request){

        $user = new User();
        $user->setUsername($request->request->get('username'));

        $user->setLongitude($request->request->get('longitude'));
        $user->setLatitude($request->request->get('latitude'));
        $user->setAddress($request->request->get('address'));
        $signUpType= $request->request->get('signUpType');
        $user->setSignUpType($signUpType);
        $user->setGcmRegistrationId($request->request->get('gcmRegistrationID'));
        $user->setActivated(1);
        switch ($signUpType){
            case 'facebook' :
                $user->setFacebookID($request->request->get('facebookID'));
                $user->setFacebookProfile($request->request->get('facebookProfile'));
                break;
            case 'google' :
                $user->setGoogleID($request->request->get('googleID'));
                $user->setGoogleProfile($request->request->get('googleProfile'));
                break;
            case 'twitter' :
                $user->setTwitterID($request->request->get('twitterID'));
                $user->setGoogleProfile($request->request->get('googleProfile'));
                break;
            default :
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user,$request->request->get('password'));
                $user->setPassword($encoded);
                $user->setPhoneNumber($request->request->get('phoneNumber'));
                break;
        }

        $serializer = $this->container->get('serializer');

        $validator=$this->get('validator');
        $errors=$validator->validate($user);

        if(count($errors) <= 0 ){

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $serializedEntity = $serializer->serialize(array("id"=>$user->getId()), 'json');
            return $response= new Response($serializedEntity, 201, array('Content-Type' => 'application/json'));

        }else
        {
            $serializedEntity = $serializer->serialize($errors, 'json');
            return $response= new Response($serializedEntity, 400, array('Content-Type' => 'application/json'));

        }


    }
    /**
     * @View(serializerGroups={"me"})
     */
    public function signInAction(Request $request){

        $username=$request->request->get('username');
        $user=$this->getDoctrine()->getRepository('UserBundle:User')->findOneBy(array('username'=>$username));
        if(is_object($user))
            return $user;
        else
            throw $this->createNotFoundException('User not found for '.$request->request->get('username'));

    }

    public function infoAction($id){
         $user=$this->getDoctrine()->getRepository('UserBundle:User')->find($id);
        if($user)
        return array("salt"=>$user->getSalt());
        else throw $this->createNotFoundException('User not found for '.$id);
    }


}
