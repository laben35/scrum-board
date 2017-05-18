<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends AbstractAppController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // replace this example code with whatever you need
        return $this->render(
            '@AppBundle/Resources/views/document.html.twig',
            [
                "global_access" => $this->hasGlobalAccess()
            ]
            );
    }
}
