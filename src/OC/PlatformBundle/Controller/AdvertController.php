<?php

//src/OC/PlateformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Event\MessagePostEvent;
use OC\PlatformBundle\Event\PlatformEvents;
use OC\PlatformBundle\Form\AdvertType;
use OC\PlatformBundle\Form\AdvertEditType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller; //Ne pas oublier ce use !!!

use Symfony\Component\HttpFoundation\Request; //Pour récupérer un objet Request
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use OC\PlatformBundle\Entity\Advert; //Ne pas oublier ce use pour pouvoir utiliser notre entité Advert

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security; //Pour utiliser les annotations @Security

//Je sais plus si ces "use" me servent
use OC\PlatformBundle\OCPlatformBundle;
use OC\PlatformBundle\Entity\Image; //Ne pas oublier ce use pour pouvoir utiliser notre entité Image
use OC\PlatformBundle\Entity\Application; //Ne pas oublier ce use pour pouvoir utiliser notre entité Application
use OC\PlatformBundle\Entity\AdvertSkill; //Ne pas oublier ce use pour pouvoir utiliser notre entité AdvertSkill
use OC\PlatformBundle\Repository\AdvertRepository;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class AdvertController extends Controller
{

    public function indexAction($page)
    {
        //On ne sait pas combien de pages il y a, mais une page doit être supérieur ou égale à 1
        if ($page < 1) {
            //On déclenche une exception NotFoundHttpException, qui affichera une page d'erreur 404 (qu'on pourra personnaliser)
            throw $this->createNotFoundException("Page '" . $page . "' inexistante");
        }

        $em = $this->getDoctrine()->getManager();
        $findAdverts = $em->getRepository('OCPlatformBundle:Advert')->getAdverts();


        //J'ai utilisé l'annotation ici pour avoir accès à l'autocomplétion quand j'utilise ma variable $paginator
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $listAdverts = $paginator->paginate(
            $findAdverts,
            $page,
            3);

        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('username' => 'Florian'));
        $user->setRoles(array('ROLE_AUTEUR'));
        $userManager->updateUser($user);

        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts));
    }

    public function viewAction($id)
    {
        //Equivalent des 2 méthodes juste en-dessous
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if ($advert === null) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        //On récupère la liste des candidatures de cette annonce

        $listApplications = $em
            ->getRepository('OCPlatformBundle:Application')
            ->findBy(array('advert' => $advert));

        $date = new \DateTime();
        $date = $date->getTimestamp();
        var_dump($date);

        //On récupère la liste des AdvertSkill
        $listAdvertSkills = $em
            ->getRepository('OCPlatformBundle:AdvertSkill')
            ->getSkillsByAdvert($id);


        //On renvoie avec l'annonce et les candidatures liées à cette annonce
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvertSkills));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function addAction(Request $request)
    {
        //On crée notre objet Advert
        $advert = new Advert();

        //On crée le FormBuilder gr$ace au service form factory
        //$form = $this->get('form.factory')->create(AdvertType::class, $advert);
        $form = $this->createForm(AdvertType::class, $advert);

        //Pour l'instant, pas de candidatures, catégories, etc., on les gèrera plus tard

        //Si la requête est en POST
        if ($request->isMethod('POST')) {
            //On fait le lien Requête <-> Formulaire
            //À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par l'utilisateur
            $form->handleRequest($request);

            //On vérifie que les valeurs entrées sont correctes
            if ($form->isValid()) {
                //On fait cette ligne pour mettre à jour notre user (car on ne le fait pas remplir dans le formulaire)
                $advert->setUser($this->getUser());
                //On crée l'évènement avec ces 2 arguments
                $event = new MessagePostEvent($advert->getContent(), $advert->getUser());
                //On déclenche l'évènement
                $this->get('event_dispatcher')->dispatch(PlatformEvents::POST_MESSAGE, $event);
                //On récupère ce qui a été modifié par le ou les listeners, ici le message
                $advert->setContent($event->getMessage());

                //On enregistre notre objet $advert dans la BDD, par exemple
                $em = $this->getDoctrine()->getManager();
                $advert->setIp($request->getClientIp());
                $advert->setUser($advert->getUser());
                //On définit la date de base à la date actuelle
                $advert->setDate(new \DateTime());
                $em->persist($advert);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée');

                //On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
            }
        }

        $ip2 = $request->getClientIp();
        return $this->render('OCPlatformBundle:Advert:add.html.twig', array('form' => $form->createView()));
    }

    public function editAction($id, Request $request)
    {
        $advert = $this->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert')
            ->find($id);

        if ($advert === null) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        $form = $this->createForm(AdvertEditType::class, $advert);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $advert->setUpdatedAt(new \DateTime());
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'L\'annonce n°' . $id . ' a bien été modifiée.');
                return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
            }
        }

        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'advert' => $advert,
            'form' => $form->createView()
        ));
    }

    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        //On crée un formulaire vide, qui ne contiendra que le champ CSRF
        //Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($advert);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");
            return $this->redirectToRoute('oc_platform_home');
        }

        return $this->render('OCPlatformBundle:Advert:delete.html.twig', array('advert' => $advert,
            'form' => $form->createView()));
    }

    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listAdverts = $em->getRepository('OCPlatformBundle:Advert')->findBy(
            array(),
            array('date' => 'desc'),
            3,
            0);

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
            //Tout l'intérêt est ici: le contrôleur passe les variables nécessaires au template
            'listAdverts' => $listAdverts
        ));
    }

    /**
     * @param int $days Paramètre pour la requête
     * @param Request $request Pour utiliser une variable de session et afficher le nombre d'annonces supprimées
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function purgeAction($days, Request $request)
    {
        $nBAdvertsDateDelete = $this->container->get('oc_platform.purger.purgerAdvert')->purgeAdvertsDate($days);

        if ($nBAdvertsDateDelete != 0) {
            $request->getSession()->getFlashBag()->add('notice', 'Vous venez de supprimer ' . $nBAdvertsDateDelete . ' annonce(s).');
        }

        //On retourne le nombre d'annonces supprimées
        return $this->redirectToRoute('oc_platform_home');
    }
}
