<?php

namespace BarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    /**
     * @Route("/bar/{id}/{nic}",
     *        name="bar", 
     *        requirements={
     *        "id": "[0-9]{2}",
     *        "nic": "[0-9]{4,6}"
     *        })
     */
    public function indexAction(Request $request)
    {
        $url = $this->generateURL("baz");
            
     
        return $this->redirectToRoute("baz");
       
//         chemin dans le bundle(BarBundle/Resources/Views)
//         pas bonne pratique
//         return $this->render ('barBundle:default:index.html.twing');
        
//         chemin dans app (app/resources/views)    
//         return $this->render('Base.html.twig');

//      nouvelle pratique
//         return $this->render(
//             '@BarBundle/Resources/views/Default/index.html.twig' , [
//                 "hello" => "hello Lyon"
//             ]
//        );
    }
    /**
     * @Route("/baz", name="baz")
     *      
     */
    public function baz ()
    {
       die("oui oui");
        return $this->render(
            '@BarBundle/Resources/views/Default/index.html.twig' , [
                "hello" => "hello Lyon"
            ]
        );
    }
}
