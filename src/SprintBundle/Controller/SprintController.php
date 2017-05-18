<?php

namespace SprintBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SprintController extends AbstractSprintController
{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * @Route("/sprint", name="sprint")
     */
    public function sprintAction()
    {
//         var_dump($this->hasSprintAccess());

       if (!$this->hasGlobalAccess()) {
           return $this->redirectToHomePage();
       } else if (!$this->hasSprintAccess()) {
           return $this->redirectToCreate();                 
           //       Vérifier si c'est un coup true ou false                   
       }
       
       $sprint = $this->readSprint();   
  
//        var_dump($sprint->getDay());
       return $this->render(
           '@SprintBundle/Resources/views/sprint.html.twig', [
               "goal" => $sprint->getGoal(),
               "description" => $sprint->getDescription(),
               "time" => $sprint->getTime(),
               "days" => $sprint->getDay(),
               $lapsed = (time() - $sprint->getTime()),
               $duration =  $sprint->getDay() * 86400,           
               "percent" => (round($lapsed / $duration * 100, 2)),
               "master_access" => $this->hasScrumMasterAccess(),

               ]);
            }
}
