<?php
/**
 * Created by PhpStorm.
 * User: AS
 * Date: 26/09/2016
 * Time: 17:02
 */

namespace AdminBundle\Controller;
use AppBundle\Entity\Media;
use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use AppBundle\Entity\Comment;



class MediaController extends Controller
{

    public function deleteAction(Media $media,$from){
        $em=$this->getDoctrine()->getManager();
        $media->setActivated(0);
        $em->flush();
        if($from=='show_product')
          return  $this->redirectToRoute('show_product',array('id'=>$media->getProduct()->getId()));
         return $this->redirectToRoute('get_media');

    }
     public function getAllAction(){
         $media=$this->getDoctrine()->getRepository('AppBundle:Media')->findAll();
         $user=$this->get('session')->get('user');
         return $this->render('AdminBundle:Default:media.html.twig',array(
             'user'=>$user,
             'media_list'=>$media
         ));
     }
    public function activateAction(Media $media,$from){
        $media->setActivated(1);
        $em=$this->getDoctrine()->getManager();
        $em->persist($media);
        $em->flush();
        if($from=='show_product')
            return  $this->redirectToRoute('show_product',array('id'=>$media->getProduct()->getId()));
        return $this->redirectToRoute('get_media');

    }
}