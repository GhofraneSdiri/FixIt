<?php

namespace ServicesBundle\Controller;

use ServicesBundle\Entity\Categorie;
use ServicesBundle\Entity\Service;
use ServicesBundle\Form\ServiceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    public function ajoutAction(Request $request)
    {
        $em  = $this->getDoctrine()->getManager();
        $service = new Service();

        $form = $this->createForm(ServiceType::class,$service);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()){
            $em->persist($service);
            $em->flush();
            return $this->redirectToRoute('list_client');
        }
        return $this->render('@Services/Service/ajout.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $services = $em->getRepository(Service::class)->findAll();

        return $this->render('@Services/Service/list.html.twig', array(
            'services' =>$services
        ));
    }
    public function listClientAction()
    {
        $em = $this->getDoctrine()->getManager();
        $services = $em->getRepository(Service::class)->findAll();
        $categories = $em->getRepository(Categorie::class)->findAll();
        return $this->render('@Services/Service/listClient.html.twig', array(
            'services' =>$services,
            'categories' => $categories
        ));
    }
    public function  getServiceAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $service = $em->getRepository(Service::class)->find($id);

        return $this->render('@Services/Service/read.twig',array(
            'service'=> $service
        ));
    }

    public function updateAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $service = $em->getRepository(Service::class)->find($id);
        $form = $this->createForm(ServiceType::class,$service);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()){
            $em->persist($service);
            $em->flush();
            return $this->redirectToRoute('list');
        }

        return $this->render('@Services/Service/ajout.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $service = $em->getRepository(Service::class)->find($id);
        $em->remove($service);
        $em->flush();
        return $this->redirectToRoute('list');
    }

    public function  rechercheBynameAction($name)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT s FROM ServicesBundle:Service s WHERE s.nom LIKE '%".$name."%'");
       // $services=$em->getRepository(Service::class)->findBy(array('nom'=>$name));
        $services = $query->getResult();
        //to do ajax
        /*$response = new Response(json_encode(array('services' => $services)));
        $response->headers->set('Content-Type', 'application/json');*/
        return $this->render('@Services/Service/list.html.twig',array(
            'services' => $services
        ));
    }
    public function  rechercheByCategorieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->get('categorie') == 0)
            return $this->redirectToRoute('list_client');
        $services=$em->getRepository(Service::class)->findBy(array('categorie'=>$request->get('categorie')));
        $categories = $em->getRepository(Categorie::class)->findAll();
        return $this->render('@Services/Service/listClient.html.twig',array(
            'services' => $services,
            'categories' => $categories
        ));
    }
}
