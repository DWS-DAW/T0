<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\Type\ProductType;

class ProductController extends Controller
{

    public function listAction()
    {
        $products = $this->getDoctrine ()->getRepository( 'AppBundle:Product' )
            ->findBy([],['id' => 'ASC']);
        
            return $this->render ( 'AppBundle:Product:list.html.twig', ['products' => $products]);       
    }

    public function newAction(Request $request)
    {
        $em = $this->getDoctrine ()->getManager ();       

        $product = new Product();
        $product->setName('product1');
        $product->setPrice(33.3);
        $product->setDescription('Product description');
        
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em->persist($product);
            $em->flush();                   
            
            return $form->get('saveAndAdd')->isClicked()
                ? $this->redirectToRoute('product_new',[],301)
                : $this->redirectToRoute('product_list',[],301); 
        }

        return $this->render('AppBundle:Product:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }


}