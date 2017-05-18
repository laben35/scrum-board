<?php

namespace SprintBundle\Controller;


use SprintBundle\Entity\Sprint;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class DeleteController extends AbstractSprintController
{
    
    public function __construct()
    {
        parent::__construct();
    }


   /**
    * @Route("/delete", name="delete")
    */
   public function deleteAction(Request $request)
   {
        if (!$this->hasGlobalAccess()) {
            return $this->redirectToHomePage();
        } else if (!$this->hasSprintAccess()
                || !$this->hasSprintAccess()) {
            return $this->redirectToSprint();
           }
//            r�cup�rer tous les users qui travaillent sur le sprint
//            ==> Enlever les r�f�rences
           $users = $this
           ->getDoctrine()
           ->getManager()
           ->getRepository(\AuthBundle\Entity\User::class)
           ->findBy([
               "sprint" =>$this->getSprintAccess()
           ]);
//            nettoyer la colonne sprint des utilisateurs
//            flush: on vide le cache et �a va vers la base de donn�e
           foreach ($users as $user) {
               $user->setSprint(null);
               $this->getDoctrine()->getManager()->flush();
           }
//            r�cup�rer le sprint
           $sprint = $this->readSprint();
           $sprint->getUser()->setSprint(null);
           $this->getDoctrine()->getManager()->flush();
//            Remove le sprint
           $this->getDoctrine()->getManager()->remove($sprint);
           $this->getDoctrine()->getManager()->flush();
//             il faut r�voquer les droits!!!          
           $this->session->remove("sprint");
           $this->session->remove("master");
           return $this->redirectToCreate();
          die("delete controller");
        
   }
}