<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use AppBundle\Entity\Media ;
class ProductController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }

    public function getProductsAction(){
        $user=$this->get('session')->get('user');
        $products=$this->getDoctrine()->getRepository('AppBundle:Product')->findAll();
        return $this->render('AdminBundle:Default:products.html.twig',array("products"=>$products,
                                                                            "user"=>$user));
    }

    public function newAction(Request $request){

        $product = new Product();
        $form = $this->createForm('AdminBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setDate(new \DateTime());
            $product->setSoldOut(0);
            $product->setActivated(1);
            $product->setLongitude($product->getSeller()->getLongitude());
            $product->setLatitude($product->getSeller()->getLatitude());
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $file=$product->media;
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $media=new Media();

            $media->setPath($this->getParameter('media_path').$fileName);

            $media->setWebPath($this->getParameter('media_web_path').'/'.$fileName);
            $media->setName($product->getName());
            $media->setProduct($product);
            $media->setActivated(1);
            $em->persist($media);
            $em->flush();
            $file->move(
                $this->getParameter('media_directory'),
                $fileName
            );
            $this->get('session')->getFlashBag()->add('info','تمت إضافة المادة الجديدة للبيع');
            return $this->redirectToRoute('show_product', array('id' => $product->getId()));
        }
        $user=$this->get('session')->get('user');
        return $this->render('AdminBundle:Default:newProduct.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
            'user'=>$user
        ));

    }
    public function showAction($id){

        $product=$this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        $comments=$this->getDoctrine()->getRepository('AppBundle:Comment')->findBy(array('product'=>$product));
        $media = new Media();
        $mediaForm = $this->createForm('AdminBundle\Form\MediaType', $media);
        $user=$this->get('session')->get('user');
        return $this->render('AdminBundle:Default:showProduct.html.twig', array(
            'product' => $product ,
            'comments' => $comments,
            'mediaForm'=>$mediaForm->createView(),
            'user'=>$user
        ));

    }
    public function deleteAction(Product $product)
    {
            $em = $this->getDoctrine()->getManager();
            $product->setActivated(0);
            $em->flush();
        $this->get('session')->getFlashBag()->add('info','تم حذف المادة بنجاح');
        return $this->redirectToRoute('get_products');
    }

    public function editAction(Request $request, Product $product)
    {
        $editForm = $this->createForm('AdminBundle\Form\ProductEditType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info','تم تحديث المادة بنجاح');
            return $this->redirectToRoute('update_product', array('id' => $product->getId()));
        }
        $user=$this->get('session')->get('user');
        return $this->render('AdminBundle:Default:editProduct.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'user'=>$user

        ));
    }



    public function addMediaAction(Request $request,$id_product){

            $product=$this->getDoctrine()->getRepository('AppBundle:Product')->find($id_product);
            $media = new Media();
            $mediaForm = $this->createForm('AdminBundle\Form\MediaType', $media);
            $mediaForm->handleRequest($request);

            if ($mediaForm->isSubmitted() && $mediaForm->isValid()) {
                $file=$media->getPath();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $media->setPath($this->getParameter('media_path').$fileName);
                $media->setWebPath($this->getParameter('media_web_path').'/'.$fileName);
                $media->setName($product->getName());
                $media->setProduct($product);
                $media->setActivated(1);
                $em=$this->getDoctrine()->getManager();
                $em->persist($media);
                $em->flush();
                $file->move(
                    $this->getParameter('media_directory'),
                    $fileName
                );
            }
        return $this->redirectToRoute('show_product', array('id' => $id_product));

    }

    public function activateAction(Product $product){

        $product->setActivated(1);
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        $this->get('session')->getFlashBag()->add('info','تم إعادة نشر المادة بنجاح');
        return $this->redirectToRoute('get_products');
    }

}
