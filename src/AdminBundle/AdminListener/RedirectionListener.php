<?php

namespace AdminBundle\AdminListener ;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent ;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;


class RedirectionListener {

    public function __construct(Router $router, Session $session){

        $this->session=$session;
        $this->router=$router;

    }
    public function onkernelRequest(GetResponseEvent $event){
        $request =$event->getRequest();
        $route=$request->attributes->get('_route') ;
        $tab=array('admin_homepage','mail_box','sent_messages','read_mail','compose_mail','delete_mail',
            'delete_all_mail','delete_all_send_mail','get_products','new_product','show_product','update_product',
            'delete_product','delete_media','add_media','delete_all_products','get_users',
            'show_user','new_user','update_user','delete_user','delete_all_users','redirect');

        if(in_array($route,$tab)) {
            if(!$this->session->has('user'))
                $event->setResponse(new RedirectResponse($this->router->generate('login')));
        }

    }

}