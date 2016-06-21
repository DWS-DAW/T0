<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\Product;
use AppBundle\Form\Type\TaskType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TaskController extends Controller
{

    public function listAction()
    {
        $tasks = $this->getDoctrine ()->getRepository( 'AppBundle:Task' )
            ->findBy([],['id' => 'ASC']);
        
            return $this->render ( 'AppBundle:Task:list.html.twig', ['tasks' => $tasks]);
    }   


    public function newAction(Request $request)
    {
        $em = $this->getDoctrine ()->getManager ();       
        
        $task = new Task();
        $task->setName('Task name');

        // dummy code - this is here just so that the Task has some tags
        // otherwise, this isn't an interesting example
        $product1 = new Product();
        $product1->setName('prodct1');
        $product1->setPrice('11.11');
        $task->getProducts()->add($product1);
        $product2 = new Product();
        $product2->setName('product2');
        $product2->setPrice('22.22');
        $task->getProducts()->add($product2);
        

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($task);
            $em->flush();                   
            
            return $form->get('saveAndAdd')->isClicked()
                ? $this->redirectToRoute('task_new',[],301)
                : $this->redirectToRoute('task_list',[],301); 
        }

        return $this->render('AppBundle:Task:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
    
        $task = $em->getRepository('AppBundle:Task')->find($id);
        
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($task);
            $em->flush();                   
            
            return $form->get('saveAndAdd')->isClicked()
                ? $this->redirectToRoute('task_new',[],301)
                : $this->redirectToRoute('task_list',[],301); 
        }

        return $this->render('AppBundle:Task:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}