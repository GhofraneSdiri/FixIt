<?php

namespace DisponibiliteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DisponibiliteBundle:Default:index.html.twig');
    }
}
