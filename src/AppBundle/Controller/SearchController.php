<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response ;
use FOS\RestBundle\Controller\Annotations\View;



class SearchController extends Controller
{


    /**
     * @View(serializerGroups={"list"})
     */
    public function searchAction(Request $request){
        $user=null;
        $distance=null;
        if($request->request->has('user')&& $request->request->has('distance')){
            $user=$this->getDoctrine()->getRepository('UserBundle:User')->find($request->request->get('user'));
            $distance=$request->request->get('distance');
        }

        $tags=$request->get('tags');

        if(is_object($user)&& !is_null($distance)){

            $search_tag=explode(" ", $tags);
            $products=$this->getDoctrine()->getRepository('AppBundle:Product')->getNearProducts($user,$distance);

            $similar_product=array();
                foreach($products as $p){

                    $name=$p->getName();
                    $description=$p->getDescription();
                    $t=explode(" ", $name);
                    $tt=explode(" ",$description) ;
                    $ttt=array_merge($t,$tt);
                    foreach($ttt as $tag){
                        if(in_array($tag,$search_tag) and (strlen($tag)>4))
                        {
                            array_push($similar_product,$p);
                            break;
                        }
                    }


                }
                return $similar_product ;
        }

        else{

            $search_tag=explode(" ", $tags);

            $products=$this->getDoctrine()->getRepository('AppBundle:Product')->findBy(array('activated'=>1,'sold_out'=>0));
            $similar_product=array();
            foreach($products as $p){

                    $name=$p->getName();
                    $description=$p->getDescription();
                    $t=explode(" ", $name);
                    $tt=explode(" ",$description) ;
                    $ttt=array_merge($t,$tt);

                    foreach($ttt as $tag){
                        if(in_array($tag,$search_tag) and (strlen($tag)>4))
                        {
                            array_push($similar_product,$p);
                            break;
                        }
                    }


            }
            return $similar_product ;
        }


    }

    /**
     * @View(serializerGroups={"list"})
     */
    public function getSimilarProductAction($id){
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        $product_name=$product->getName();
        $tags=explode(" ", $product_name);

        $products=$this->getDoctrine()->getRepository('AppBundle:Product')->findBy(array('activated'=>1,'sold_out'=>0));
        $similar_product=array();
        foreach($products as $p){
            if($p->getId()!=$id){
                $name=$p->getName();
                $t=explode(" ", $name);
                foreach($t as $tag){
                    if(in_array($tag,$tags) and (strlen($tag)>4))
                    {
                        array_push($similar_product,$p);
                        break;
                    }
                }
            }

        }
        return $similar_product ;

    }


}
