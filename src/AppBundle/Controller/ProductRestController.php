<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response ;
use AppBundle\Entity\Product ;
use AppBundle\Entity\Media ;
use FOS\RestBundle\Controller\Annotations\View;
use AppBundle\Entity\Vote ;
use Symfony\Component\Validator\Constraints as Assert;

class ProductRestController extends Controller
{
    /**
     * @View(serializerGroups={"list"})
     */
    public function getProductAction($slug){
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->findOneBy(array('id'=>$slug,
                                                                                        'activated'=>1));
        if(!is_object($product)){
            throw $this->createNotFoundException('product not found or deactivated');
        }
        return $product;
    }

    public function postProductAction(Request $request){

        $product = new Product();

        $name=$request->request->get('name');
        $description=$request->request->get('description');
        $user_id=$request->request->get('user');
        $hashtags=$request->request->get('tags');
        $price=$request->request->get('price');
        $offreType=$request->request->get('offerType');


        if(is_numeric($user_id)){
            $user=$this->getDoctrine()->getRepository("UserBundle:User")->find($user_id);
            $product->setSeller($user);
            $product->setLongitude($user->getLongitude());
            $product->setLatitude($user->getLatitude());
        }
        $serializer = $this->container->get('serializer');

        $product->setName($name);
        $product->setDescription($description);

        $product->setHashtags($hashtags);
        $product->setPrice($price);
        $product->setDate(new \DateTime());
        $product->setSoldOut(0);
        $product->setOfferType($offreType);
        $product->setActivated(1);
        $validator=$this->get('validator');
        $errors=$validator->validate($product);

        if(is_null($request->files->get('media'))){
            $res=new Response();
            $res->setStatusCode(400);
            $res->setContent($serializer->serialize(array("property_path" =>"media","message"=>"media must be not null"), 'json'));
            return $res;
        }

        else if(count($errors) <= 0 ){

            $this->getDoctrine()->getManager()->persist($product);
            $this->getDoctrine()->getManager()->flush();

                $file=$request->files->get('media');
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                $media=new Media();
                $media->setName($name);

                $media->setPath($this->getParameter('media_path').$fileName);
                $media->setWebPath($this->getParameter('media_web_path').'/'.$fileName);
                $media->setProduct($product);
                $media->setActivated(1);
                $this->getDoctrine()->getManager()->persist($media);
                $this->getDoctrine()->getManager()->flush();

                $file->move(
                    $this->getParameter('media_directory'),
                    $fileName
                );



            return array("id"=>$product->getId());

        }else{
            $res=new Response();
            $res->setStatusCode(400);
            $res->setContent($serializer->serialize($errors, 'json'));
            return $res;
        }


    }

    public function putProductAction(Request $request,$slug){

        $product=$this->getDoctrine()->getRepository("AppBundle:Product")->findOneBy(array('id'=>$slug,
                                                                                        'activated'=>1));
        $em = $this->getDoctrine()->getManager();

        if(is_object($product)){
            foreach($request->request as $key=>$value){
                $setter='set'.ucfirst($key);
                $product->$setter($value);

            }
            $em->flush();
            $res=new Response();
            $res->setStatusCode(200);
            return $res;
        }
        else
            throw $this->createNotFoundException('product not found');


    }

    public function deleteProductAction($slug){
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($slug);
        $em=$this->getDoctrine()->getManager();
        if(!is_object($product)){
            throw $this->createNotFoundException('product not found ');
        }
        else{
            $offers=$this->getDoctrine()->getRepository('AppBundle:Offer')->findBy(array("product"=>$product));
            foreach($offers as $offer)
            {
                $offer->setActivated(0);
            }
            $product->setActivated(0);
            $em->flush();
        }
        $res=new Response();
        $res->setStatusCode(200);
        return $res;

    }



    public function postProductMediaAction($id,Request $request){

        $product=$this->getDoctrine()->getRepository('AppBundle:Product')->findOneBy(array('id'=>$id,
                                                                                    'activated'=>1));
        if(is_object($product)){
            $file=$request->files->get('media');
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $media=new Media();
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
        else throw $this->createNotFoundException('Product not found ');
        $res=new Response();
        $res->setStatusCode(200);
        return $res;
    }

    public function deleteMediaAction($id){
        $em=$this->getDoctrine()->getManager();
        $media=$em->getRepository('AppBundle:Media')->find($id);
        $em->remove($media);
        $em->flush();
        $res=new Response();
        $res->setStatusCode(200);
        return $res;
    }


}
