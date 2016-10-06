<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\AvatarType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use AppBundle\Entity\Avatar ;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }

    public function getUsersAction(){

        $user=$this->get('session')->get('user');
        $users=$this->getDoctrine()->getRepository('UserBundle:User')->findAll();
        return $this->render('AdminBundle:Default:users.html.twig',array("users"=>$users,"user"=>$user));
    }

    public function newAction(Request $request){
        $user = new User();
        $form = $this->createForm('AdminBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setActivated(1);
            $encoder = $this->container->get('security.password_encoder');
            $plain_password=$user->getPassword();
            $encoded = $encoder->encodePassword($user,$plain_password);
            $user->setPassword($encoded);
            $user->setSignUpType('form');

            $em = $this->getDoctrine()->getManager();

            if(!is_null($user->img)){
                $file=$user->img;
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                $avatar=new Avatar();
                $avatar->setPath($this->getParameter('media_path').$fileName);
                $avatar->setWebPath($this->getParameter('media_web_path').'/'.$fileName);

                $em->persist($avatar);
                $em->flush();
                $file->move(
                    $this->getParameter('media_directory'),
                    $fileName
                );
                $user->setAvatar($avatar);
            }

            $em->persist($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info','تم إضافة مستعمل جديد');
            return $this->redirectToRoute('show_user', array('id' => $user->getId()));
        }


        $admin=$this->get('session')->get('user');

        return $this->render('AdminBundle:Default:newUser.html.twig',array(
            'user'=>$user,
            'form'=>$form->createView(),
            'admin'=>$admin
        ));
    }
    public function showAction($id){
        $user=$this->getDoctrine()->getRepository('UserBundle:User')->find($id);
        $floor_voting=floor($user->getVote());
        if($user->getVote()<$floor_voting){
            $stars=$floor_voting-1;
            $half_stars=1;
        }
        else{
            $stars=$floor_voting;
            $half_stars=0;
        }

        $admin=$this->get('session')->get('user');

        $products=$products=$this->getDoctrine()->getRepository('AppBundle:Product')->findBy(array("seller"=>$user));
        $offers=$this->getDoctrine()->getRepository('AppBundle:Offer')->getUserOffersForAdmin($user->getId());
        $requests=$this->getDoctrine()->getRepository('AppBundle:Offer')->findBy(array("buyer"=>$user));
        return $this->render('AdminBundle:Default:showUser.html.twig', array(
            "user"=>$admin,
            'user_s' => $user ,
            'products' => $products,
            'offers' => $offers,
            'requests'=>$requests,
            'latitude'=>floatval($user->getLatitude()),
            'longitude'=>floatval($user->getLongitude()),
            'stars'=>$stars,
            'half'=>$half_stars,
            'vide'=>5-($stars+$half_stars),

        ));

    }
    public function deleteAction(User $user)
    {
            $em = $this->getDoctrine()->getManager();
            $offers=$this->getDoctrine()->getRepository('AppBundle:Offer')->findBy(array('buyer'=>$user));
            foreach($offers as $offer){
                $offer->setActivated(0);
            }
            $products=$em->getRepository('AppBundle:Product')->findBy(array('seller'=>$user));
            foreach($products as $product){
                $product->setActivated(0);
            }

            $user->setActivated(0);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info','تم حذف المستعمل بنجاح');
            return $this->redirectToRoute('get_users');
    }

    public function editAction(Request $request, User $user)
    {


        $form = $this->createForm('AdminBundle\Form\UserType', $user);
        $form->handleRequest($request);
        if(!$form->isSubmitted()){
            $session=$this->get('session');
            $session->set('old_password',$user->getPassword());
        }

        if ($form->isSubmitted() ) {

            $em = $this->getDoctrine()->getManager();
            if(!is_null($user->getPassword())){
                $plain_password=$user->getPassword();
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user,$plain_password);
                $user->setPassword($encoded);
            }else{
                $session=$this->get('session');
                $user->setPassword($session->get('old_password'));
            }

            if(!is_null($user->img)){
                $file=$user->img;
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                $avatar=$user->getAvatar();
                if(is_null($avatar))
                    $avatar=new Avatar();

                $avatar->setPath($this->getParameter('media_path').$fileName);
                $avatar->setWebPath($this->getParameter('media_web_path').'/'.$fileName);

                $em->persist($avatar);

                $file->move(
                    $this->getParameter('media_directory'),
                    $fileName
                );
                $user->setAvatar($avatar);
            }
            $em->persist($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info','تم تحديث المستعمل بنجاح');
            return $this->redirectToRoute('update_user', array('id' => $user->getId()));
        }

        $admin=$this->get('session')->get('user');
        return $this->render('AdminBundle:Default:editUser.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
            'latitude'=>floatval($user->getLatitude()),
            'longitude'=>floatval($user->getLongitude()),
            'admin'=>$admin
        ));
    }



    public function externalProfileAction($id,$type)
    {
        $user=$this->getDoctrine()->getRepository('UserBundle:User')->find($id);
        if($type==='facebook')
             return $this->redirect($user->getFacebookProfile());
        else if ($type==='google')
            return $this->redirect($user->getGoogleProfile());
        else
            return $this->redirect($user->getTwitterProfile());

    }

    public function activationAction(User $user){
        $user->setActivated(1);
        $em=$this->getDoctrine()->getManager();
        $em->flush();

        $this->get('session')->getFlashBag()->add('info','تم إعادة تفعيل حساب المستعمل بنجاح');
        return $this->redirectToRoute('get_users');

    }

}
