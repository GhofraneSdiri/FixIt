<?php

namespace ServicesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReclamationController extends Controller
{
    public function ajoutAction()
    {
        return $this->render('@Services/Reclamation/ajout.html.twig', array(
        ));
    }

    public function listAction()
    {
        return $this->render('ServicesBundle:Reclamation:list.html.twig', array(
            // ...
        ));
    }

}
