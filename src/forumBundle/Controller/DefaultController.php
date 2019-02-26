<?php

namespace forumBundle\Controller;

use forumBundle\Entity\Likes;
use UserBundle\Entity\User;
use forumBundle\Entity\Commentaire;
use forumBundle\Entity\Publication;
use forumBundle\Form\InscriptionType;
use forumBundle\Form\PublicationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class DefaultController extends Controller
{

    public function forumAction(Request $request)
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class,$publication);
        $form->handleRequest($request);
        $user = $this->getUser();
        $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->findAll();
        if($form->isSubmitted() && $form->isValid()){
            $publication->setDatePublication(new \DateTime("now"));
            $publication->setIdUser($user);
            $publication->setNbLikes(0);
            $em=$this->getDoctrine()->getManager();
            $em->persist($publication);
            $em->flush();
        }
        $pub = $this->getDoctrine()->getRepository(Publication::class)->findAll();
        return $this->render('@forum/Default/index.html.twig',array('form'=>$form->createView(),'pub'=>$pub,'commentaire'=>$commentaire));

    }
    public function indexAction()
    {

        return $this->render('base.html.twig');

    }
    public function adminAction()
    {
        return $this->render('admin.html.twig');

    }
    public function homeAction()
    {
        return $this->render('@forum/Default/home.html.twig');

    }
    public function LoginAction(Request $request)
    {
        /** @var $session Session */
        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        return $this->render('@forum/Default/Login.html.twig',array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
        ));

    }
    public function InscriptionAction(Request $request){
        $user = new User();
        $form = $this->createForm(InscriptionType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

                $user->addRole('ROLE_TECHNICIEN');
                $user->setEnabled(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        return $this->render('@forum/Default/Inscription.html.twig',array('form'=>$form->createView()));
    }
    public function ajouterCommentaireAction($id,Request $request){
        $commentaire = new Commentaire();
        $publication = $this->getDoctrine()->getRepository(Publication::class)->find($id);
        $contenu = $request->get('commentaire');
        $em = $this->getDoctrine()->getManager();
        $commentaire->setContenu($contenu);
        $commentaire->setDate(new \DateTime("now"));
        $commentaire->setIdPublication($publication);
        $em->persist($commentaire);
        $em->flush();
    }
    public function addLikeAction($id)
    {
        $publication = $this->getDoctrine()->getRepository(Publication::class)->find($id);

        $like = new Likes();
        $like->setIdPublication($publication);
        $like->setIdUser($this->getUser());
        $publication->setNbLikes($publication->getNblikes() + 1);
        $em = $this->getDoctrine()->getManager();

        $em->persist($like);
        $em->persist($publication);
        $em->flush();
        return new Response("done");

    }
    public function deletePublicationAction($id){
        $publication = $this->getDoctrine()->getRepository(Publication::class)->find($id);
        $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('idPublication'=>$publication));
        $Like = $this->getDoctrine()->getRepository(Likes::class)->findBy(array('idPublication'=>$publication));
        $em = $this->getDoctrine()->getManager();
        foreach($commentaire as $a){
            $em->remove($a);
            $em->flush();
        }
        foreach($Like as $x){
            $em->remove($x);
            $em->flush();
        }


        $em->remove($publication);
        $em->flush();
        return $this->redirectToRoute('forum_forum');
    }
    public function updateAction($id,Request $request)
    {
        $publication = $this->getDoctrine()->getRepository(Publication::class)->find($id);
        $form = $this->createForm(PublicationType::class,$publication);
        $form->handleRequest($request);
        $user = $this->getUser();
        $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->findAll();
        if($form->isSubmitted() && $form->isValid()){
            $publication->setDatePublication(new \DateTime("now"));
            $publication->setIdUser($user);
            $publication->setNbLikes(0);
            $em=$this->getDoctrine()->getManager();
            $em->persist($publication);
            $em->flush();
            return $this->redirectToRoute('forum_forum');
        }
        $pub = $this->getDoctrine()->getRepository(Publication::class)->findAll();
        return $this->render('@forum/Default/index.html.twig',array('form'=>$form->createView(),'pub'=>$pub,'commentaire'=>$commentaire));

    }
    public function listeCommentaireAction($id){
        $publication = $this->getDoctrine()->getRepository(Publication::class)->find($id);
        $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('idPublication'=>$publication));

        return $this->render('@forum/Default/commentaire.html.twig',array('commentaire'=>$commentaire));

    }
}
