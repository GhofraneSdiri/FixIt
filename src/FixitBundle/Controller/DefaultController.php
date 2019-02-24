<?php

namespace FixitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function contentAction()
    {
        return $this->render('@Fixit/Default/index.html.twig');
    }
    public function acceuilAction()
    {
        return $this->render('base.html.twig');
    }
    public function adminAction()
    {
        return $this->render('admin.html.twig');
    }
    public function contactAction()
    {
        return $this->render('@Fixit/contact.html.twig');
    }
    public function disponibiliteAction()
    {
        return $this->render('@Fixit/disponibilite.html.twig');
    }
    public function serviceAction()
    {
        return $this->render('@Fixit/service.html.twig');
    }
    public function blogAction()
    {
        return $this->render('@Fixit/blog.html.twig');
    }
    public function venteAction()
    {
        return $this->render('@Fixit/vente.html.twig');
    }

}
