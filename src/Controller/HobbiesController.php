<?php

namespace App\Controller;

#### for create forms ####
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\Hobby;

class HobbiesController extends AbstractController
{
  
    ############# Home Controller##############

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $hobbies = $this->getDoctrine()->getRepository('App:Hobby')->findAll();
        return $this->render('hobbies/index.html.twig', array('hobbies' => $hobbies));
    }


     ############# Create Controller ##############

    #[Route('/create', name: 'hobbies_create')]
    public function createAction(Request $request): Response
    {
         // Here we create an object from the class that we made

         $hobby = new Hobby;

         /* Here we will build a form using createFormBuilder and inside this function we will put our object and then we write add then we select the input type then an array to add an attribute that we want in our input field */
         
                 $form = $this->createFormBuilder($hobby)->add('name', TextType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-bottom:15px')))
         
                 ->add('category', TextType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-bottom:15px')))
         
                 ->add('description', TextareaType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-bottom:15px')))
         
                 ->add('priority', ChoiceType::class, array('choices'=>array('Low'=>'Low', 'Normal'=>'Normal', 'High'=>'High'),'attr' => array('class'=> 'form-control', 'style'=>'margin-botton:15px')))
                
                 ->add('due_date', DateTimeType::class, array('attr' => array('style'=>'margin-bottom:15px')))

                 ->add('image', TextType::class, array('attr' => array('class'=> 'form-control', 'style'=>'margin-bottom:15px')))
                
                 ->add('save', SubmitType::class, array('label'=> 'Create Hobby', 'attr' => array('class'=> 'btn-primary', 'style'=>'margin-bottom:15px')))
         
                 ->getForm();
         
                 $form->handleRequest($request);
         
                
         
         
                 /* Here we have an if statement, if we click submit and if  the form is valid we will take the values from the form and we will save them in the new variables */
         
                 if($form->isSubmitted() && $form->isValid()){
         
                     //fetching data
         
         
                     // taking the data from the inputs by the name of the inputs then getData() function
         
                     $name = $form['name']->getData();
         
                     $category = $form['category']->getData();
         
                     $description = $form['description']->getData();
         
                     $priority = $form['priority']->getData();
         
                     $due_date = $form['due_date']->getData();
         
                     $image = $form['image']->getData();
         
                     // Here we will get the current date
         
                     $now = new \DateTime('now');
         
         
         /* these functions we bring from our entities, every column have a set function and we put the value that we get from the form */
         
                     $hobby->setName($name);
         
                     $hobby->setCategory($category);
         
                     $hobby->setDescription($description);
         
                     $hobby->setPriority($priority);
         
                     $hobby->setDueDate($due_date);
         
                     $hobby->setCreateDate($now);

                     $hobby->setImage($image);
         
                     $em = $this->getDoctrine()->getManager();
         
                     $em->persist($hobby);
         
                     $em->flush();
         
                     $this->addFlash(
         
                             'notice',
         
                             'Hobby Added'
         
                             );
         
                     return $this->redirectToRoute('home');
         
                 }
         
          /* now to make the form we will add this line form->createView() and now you can see the form in create.html.twig file  */
         
                 return $this->render('hobbies/create.html.twig', array('form' => $form->createView()));
    }

    ############# Edit Controller ##############

    #[Route('/edit', name: 'hobbies_edit')]
    public function editAction(): Response
    {
        return $this->render('hobbies/edit.html.twig', [
            'controller_name' => 'HobbiesController',
        ]);
    }


    
    ############# Details Controller ##############

    #[Route('/details', name: 'hobbies_details')]
    public function detailsAction(): Response
    {
        return $this->render('hobbies/details.html.twig', [
            'controller_name' => 'HobbiesController',
        ]);
    }
}
