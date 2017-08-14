<?php

//src/OC/PlateformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\OCPlatformBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller; //Ne pas oublier ce use !!!
use Symfony\Component\HttpFoundation\Request; //Pour récupérer un objet Request
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use OC\PlatformBundle\Entity\Advert; //Ne pas oublier ce use pour pouvoir utiliser notre entité Advert
use OC\PlatformBundle\Entity\Image; //Ne pas oublier ce use pour pouvoir utiliser notre entité Image
use OC\PlatformBundle\Entity\Application; //Ne pas oublier ce use pour pouvoir utiliser notre entité Application
use OC\PlatformBundle\Entity\AdvertSkill; //Ne pas oublier ce use pour pouvoir utiliser notre entité AdvertSkill
use OC\PlatformBundle\Repository\AdvertRepository;

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

    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        //On ne sait toujours pas gérer le formulaire, cela viendra à la prochaine partie

        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée');
            //Puis on redirige vers la visualisation de cette annonce
            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }

        return $this->render('OCPlatformBundle:Advert:add.html.twig');
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if ($advert === null) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        if ($request->isMethod('POST')) {
            $session = $request->getSession();
            $session->getSession()->getFlashBag()->add('notice', 'L\'annonce n°' . $id . ' sera modifiée quand on maîtrisera la BDD.');
            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }

        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'advert' => $advert
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

        foreach($advert->getCategories() as $category){
            $advert->removeCategory($category);
        }

        $em->flush();

        $session = $request->getSession();
        $session->getSession()->getFlashBag()->add('notice', 'Les catégories liées à l\'annonce ont bien été supprimées');

        return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
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
}
