<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use AuthBundle\Entity\User;


abstract class AbstractAppController extends Controller
{
    
    protected $session;
    
    public function __construct()
    {
        $this->session = new Session();
    }    
                
    protected function redirectToHomePage()
    {
        return $this->redirectToRoute("homepage");
    }
    
    protected function hasGlobalAccess() :bool
    {        
        return (bool) $this->getGlobalAccess();        
    }
    
    protected function setGlobalAccess(User $user)
    {
        $this->session->set("id", $user->getId());
    }
    
    protected function getGlobalAccess()
    {
        return  $this->session->get("id");
    }
}
