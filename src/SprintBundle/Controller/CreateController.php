<?php

namespace SprintBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use SprintBundle\Entity\Sprint;

class CreateController extends AbstractSprintController
{

   public function __construct()
   {
       parent::__construct();
   }

   /**
    * @Route("/create", name="create")
    */
   public function createAction(Request $request)
   {
//         var_dump($this->session->get("sprint"));
//         var_dump($this->hasSprintAccess());
       if (!$this->hasGlobalAccess()) {
           return $this->redirectToHomePage();
       } else if ($this->hasSprintAccess()) {
           return $this->redirectToSprint();
       } else {
           $sprint = $this->readUserSprint();
           if ($sprint) {
               $this->setSprintAccess($sprint);
               $this->setScrumMasterAccess($sprint);
               return $this->redirectToSprint();
            }
        }
       $form = $this->getCreateForm();
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
           $sprint = $this->createSprint($form);
           $this->setSprintAccess($sprint);
           $this->setScrumMasterAccess($sprint);
           return $this->redirectToSprint();
       }
//         on veut inserer un sprint
 
        
       return $this->render(
           '@SprintBundle/Resources/views/create.html.twig', [
               "form" => $form->createView(),
           ]
       );
   }

   private function getCreateForm(): Form
   {
       $builder = $this->createFormBuilder();
       $builder->add(
           "goal",
           TextType::class, [
               "label" => "Goal",
                "attr" => [
                    "class" => "col-xs-12"
                ],
               "constraints" => [
                   new Regex([
                       "pattern" => "/^[\w]{2,64}$/",
                       "message" => "Incorrect goal"
                   ]),
                   new NotBlank([
                       "message" => "Incorrect goal"
                   ])
               ]
           ]
       );
       $builder->add(
           "description",
           TextareaType::class, [
               "label" => "Description",
               "attr" => [
                   "class" => "col-xs-12"
               ],
               "constraints" => [
                   new Regex([
                       "pattern" => "/^[\w]{5,255}$/",
                       "message" => "Incorrect description"
                   ]),
                   new NotBlank([
                        "message" => "Incorrect description"
                   ])
               ]
           ]
       );
       $builder->add(
           "day",
           TextType::class, [
               "label" => "Days",
               "attr" => [
                   "class" => "col-xs-12"
               ],
               "constraints" => [
                   new Regex([
                       "pattern" => "/^[\w]{1,2}$/",
                       "message" => "Incorrect duration"
                   ]),
                   new NotBlank([
                       "message" => "Incorrect duration"
                   ])
               ]
           ]
       );
       $builder->add("create", SubmitType::class, [
           "label" => "Create sprint",
           "attr" => [
               "class" => "col-xs-12 btn btn-success blockintro"
           ],
       ]);
       return $builder->getForm();
    }

   private function createSprint(Form $form): Sprint
    {
       $user = $this->getDoctrine()
       ->getManager()
       ->getRepository(\AuthBundle\Entity\User::class)
       ->findOneBy([
           "id" => $this->getGlobalAccess()
       ]);
        
       $sprint = new Sprint;
       $sprint->setGoal($form->getData() ["goal"]);
       $sprint->setDescription($form->getData() ["description"]);
       $sprint->setDay($form->getData() ["day"]);
       $sprint->setTime(time());
        //         il faut un user qui n'est pas optionnel   
       $sprint->setUser($user);
       $this->getDoctrine()->getManager()->persist($sprint);
       $this->getDoctrine()->getManager()->flush();
       $user->setSprint($sprint);
       $this->getDoctrine()->getManager()->persist($user);
       $this->getDoctrine()->getManager()->flush();
       return $sprint;
    }  

}
