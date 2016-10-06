<?php
/**
 * Created by PhpStorm.
 * User: AS
 * Date: 26/09/2016
 * Time: 17:02
 */

namespace AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use UserBundle\Entity\User;



class SecurityController extends Controller
{

    public function loginAction(Request $request){

        if($request->isMethod('POST')){
            $username=$request->request->get('username');

            $user=$this->getDoctrine()->getRepository('UserBundle:User')->findOneBy(array('username'=>$username));
            if(!is_object($user)) return $this->render('AdminBundle:Default:login.html.twig',array('errors'=>'اسم المستخدم أو كلمة المرور غير صحيحة'));


        $salt=$user->getSalt();

            $user_test=new User();

            $user_test->setSalt($salt);
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user_test,$request->request->get('password'));
            $user_test->setPassword($encoded);
            if($user_test->getPassword()===$user->getPassword()){
                if($user->getRoles()[0]!="ROLE_ADMIN")
                    return $this->render('AdminBundle:Default:login.html.twig',array('errors'=>'Access Denied'));
                $this->get('session')->set('user',$user);
                $response=new RedirectResponse($this->generateUrl('get_users'));
               /* $response->headers->setCookie(new Cookie('user',$user->getId(),time() + 3600 * 24 * 7));
                $response->headers->setCookie(new Cookie('password',$encoded,time() + 3600 * 24 * 7)); */
                return $response;
            }else return $this->render('AdminBundle:Default:login.html.twig',array('errors'=>'اسم المستخدم أو كلمة المرور غير صحيحة'));

        }

        return $this->render('AdminBundle:Default:login.html.twig');


    }

    public function logoutAction(){

        $session=$this->get('session');
        $session->remove('user');
        $response=new RedirectResponse($this->generateUrl('login'));
        $response->headers->clearCookie('password');
        $response->headers->clearCookie('user');
        return $response;
    }

}