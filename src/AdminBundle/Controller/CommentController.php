<?php
/**
 * Created by PhpStorm.
 * User: AS
 * Date: 26/09/2016
 * Time: 17:02
 */

namespace AdminBundle\Controller;
use AppBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use UserBundle\Entity\User;



class CommentController extends Controller
{
    public function updateAction(Request $request,Comment $comment ,$from){


        $value=$request->request->get('value');
        $activated=$request->request->get('activated');

        if(strlen($value)>0)
            $comment->setValue($value);

        if($activated!=$comment->getActivated())
            $comment->setActivated($activated);
        $em=$this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();
        if($from=='comments')
            return $this->redirectToRoute('get_comments');
        return $this->redirectToRoute('show_product',array('id' =>$comment->getProduct()->getId()));
    }

    public function activateAction(Comment $comment,$from){
        $comment->setActivated(1);
        $em=$this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();
        if($from=='comments')
            return $this->redirectToRoute('get_comments');
        return $this->redirectToRoute('show_product',array('id' =>$comment->getProduct()->getId()));
    }

    public function deleteAction(Comment $comment){
        $em=$this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();
        return $this->redirectToRoute('show_product',array('id'=>$comment->getProduct()->getId()));

    }
     public function getAllAction(){
         $comments=$this->getDoctrine()->getRepository('AppBundle:Comment')->findAll();
         $user=$this->get('session')->get('user');
         return $this->render('AdminBundle:Default:comments.html.twig',array(
             'user'=>$user,
             'comments'=>$comments
         ));
     }
}