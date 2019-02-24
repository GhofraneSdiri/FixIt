<?php

namespace ServicesBundle\Controller;

use ServicesBundle\Entity\Categorie;
use ServicesBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class CategorieController extends Controller
{
    public function ajoutAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

      //  $user = $em->getRepository(User::class)->find(1);
        if ($form->isValid()&&$form->isSubmitted()) {
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('list_categorie');
        }
        return $this->render('@Services/Categorie/ajout.html.twig', array(
            'form' => $form->createView(),

        ));
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Categorie::class)->findAll();
        //$user = $em->getRepository(User::class)->find(1);
        return $this->render('@Services/Categorie/list.html.twig', array(
            'categories' => $categories,
           //'user' => $user
        ));
    }

    public function upadateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository(Categorie::class)->find($id);
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isValid()&&$form->isSubmitted()) {
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('list_categorie');
        }
        return $this->render('@Services/Categorie/ajout.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository(Categorie::class)->find($id);
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('list_categorie');
    }

}
