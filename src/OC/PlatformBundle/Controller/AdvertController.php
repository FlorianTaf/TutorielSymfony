<?php

//src/OC/PlateformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller; //Ne pas oublier ce use !!!
use Symfony\Component\HttpFoundation\Request; //Pour récupérer un objet Request
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use OC\PlatformBundle\Entity\Advert; //Ne pas oublier ce use pour pouvoir utiliser notre entité Advert

class AdvertController extends Controller {

    public function indexAction($page) {
        //On ne sait pas combien de pages il y a, mais une page doit être supérieur ou égale à 1
        if ($page < 1) {
            //On déclenche une exception NotFoundHttpException, qui affichera une page d'erreur 404 (qu'on pourra personnaliser)
            throw new NotFoundHttpException("Page '" . $page . "' inexistante");
        }
        
        $listAdverts = array(
            array(
                'title' => 'Recherche développpeur Symfony',
                'id' => 1,
                'author' => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
                'date' => new \Datetime()),
            array(
                'title' => 'Mission de webmaster',
                'id' => 2,
                'author' => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date' => new \Datetime()),
            array(
                'title' => 'Offre de stage webdesigner',
                'id' => 3,
                'author' => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                'date' => new \Datetime())
        );
        //On récupèrera ici, plus tard, la liste des annonces, puis on la passera au template
        //On retourne pour l'instant juste le template
        //return $this->render('OCPlatformBundle:Advert:index.html.twig', array('listAdverts' => array()));
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts));
    }

    public function viewAction($id) {
        //On récupère le repository
        //$repository = $this->getDoctrine()->getManager()->getRepository('OCPlatformBundle:Advert');
        
        //On récupère l'entite correspondante à l'id $id
        //$advert = $repository->find($id); 
        
        //Ces 2 méthodes sont les mêmes
        $advert = $this->getDoctrine()->getManager()->find('OCPlatformBundle:Advert', $id);
        $advert2 = $this->getDoctrine()->getManager()->find('OC\PlatformBundle\Entity\Advert', $id);
        
        //$advert est donc une instance de OC\PlatformBundle\Entity\Advert
        //Si c'est null, l'id $id n'existe pas
        if($advert === null){
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }
        
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array('advert' => $advert));
    }

    public function addAction(Request $request) {
        //Création de l'entité
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony');
        $advert->setAuthor('Alexandre');
        $advert->setContent('Nous recherchons un développeur Symfony débutant sur Lyon. Blabla...');
        //On peut ne pas définir ni la date ni la publication, car ces attributs sont définit automatiquement dans le constructeur
        
        //On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();
        
        //Étape 1 : On "persiste" l'entité
        $em->persist($advert);
        
        //Étape 2 : On "flush" tout ce qui a été persisté avant
        $em->flush();
        
        //Si la requête est en post, c'est que le formulaire a été soumis
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée');
            //Puis on redirige vers la visualisation de cette annonce
            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }

        //Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('OCPlatformBundle:Advert:add.html.twig', array('advert' => $advert));
    }

    public function editAction($id, Request $request) {
        //Ici, on récupèrera l'annonce correspondant à l'id
        //Même mécanisme que pour l'ajout
        if ($request->isMethod('POST')) {
            $session = $request->getSession();
            $session->getSession()->getFlashBag()->add('notice', 'L\'annonce n°' . $id . ' sera modifiée quand on maîtrisera la BDD.');
            return $this->redirectToRoute('oc_platform_home');
        }
        
        $advert = array(
            'title' => 'Recherche développeur Symfony',
            'id' => $id,
            'author' => 'Alexandre',
            'content' => 'Nous rechercons un développeur Symfony débutant sur Lyon. Blabla...',
            'date' => new \Datetime()
        );
        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'advert' => $advert
        ));
    }

    public function deleteAction($id, Request $request) {
        //Ici on récupèrera l'annonce correspondant à l'id
        //Ici on gèrera la suppression de l'annonce en question
        $session = $request->getSession();
        $session->getFlashBag()->add('notice', 'L\'annonce n°' . $id . ' sera supprimée quand on maîtrisera la BDD.');
        
        //return $this->render('OCPlatformBundle:Advert:delete.html.twig');
        return $this->redirectToRoute('oc_platform_home');
    }

    public function menuAction() {
        //Pour le moment on fixe en dur la liste, mais par la suite on la récupèrera de la BDD

        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
                    //Tout l'intérêt est ici: le contrôleur passe les variables nécessaires au template
                    'listAdverts' => $listAdverts
        ));
    }
}
