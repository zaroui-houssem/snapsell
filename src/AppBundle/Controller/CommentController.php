<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\View;

class CommentController extends Controller
{
    public function postProductsCommentsAction($id,Request $request){
        $user=$this->getDoctrine()->getRepository('UserBundle:User')->find($request->request->get('user'));
        $product=$this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        $em=$this->getDoctrine()->getManager();
        if(!is_object($user)) throw $this->createNotFoundException('User not found');
        else if (!is_object($product)) throw $this->createNotFoundException('Product not found');
            else {
                $comment=new Comment();
                $comment->setProduct($product);
                $comment->setUser($user);
                $comment->setDate(new \DateTime());
                $comment->setValue($request->request->get('value'));
                $comment->setActivated(1);
                $em->persist($comment);
                $em->flush();
               return array("id"=>$comment->getId());
            }
    }

    public function putCommentAction(Request $request,$id){

        $comment=$this->getDoctrine()->getRepository("AppBundle:Comment")->find($id);
        $em = $this->getDoctrine()->getManager();

        if(!is_object($comment))
            throw $this->createNotFoundException('Comment not found');

        else {
            foreach($request->request as $key=>$value){
                $setter='set'.ucfirst($key);
                $comment->$setter($value);
            }
            $comment->setDate(new \DateTime());
            $em->flush();
        }
        $res=new Response();
        $res->setStatusCode(200);
        return $res;
    }

    public function deleteCommentAction($id){
        $comment=$this->getDoctrine()->getRepository("AppBundle:Comment")->find($id);
        $comment->setActivated(0);
        $this->getDoctrine()->getManager()->flush();
        $res=new Response();
        $res->setStatusCode(200);
        return $res;
    }
    /**
     * @View(serializerGroups={"comment"})
     */

    public function getCommentAction($id){
        $comment = $this->getDoctrine()->getRepository('AppBundle:Comment')->findOneBy(array('id'=>$id,
                                                                                              'activated'=>1));
        if(is_object($comment))
            return $comment;
        else throw $this->createNotFoundException('Comment not found ');
    }

}