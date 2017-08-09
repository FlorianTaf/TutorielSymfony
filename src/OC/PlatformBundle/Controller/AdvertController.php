<?php

//src/OC/PlateformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller; //Ne pas oublier ce use !!!
use Symfony\Component\HttpFoundation\Request; //Pour récupérer un objet Request
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        //Ici on récupèrera l'annonce correspondante à l'id $id
        $advert = array(
            'title' => 'Recherche développeur Symfony',
            'id' => $id,
            'author' => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony2 début sur Lyon. Blabla...',
            'date' => new \Datetime()
        );
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array('advert' => $advert));
    }

    public function addAction(Request $request) {
        //On récupère le service
        $antispam = $this->container->get('oc_platform.antispam');
        //On part du principe que $text contient le texte d'un message quelconque
        $text = 'gkqsfsbgvjsfwbghkdfsbvhjxcfjkvbbdfhbjqdfkbxcvwnvb <sf:gv bsfdv bkxc bvhksd bfh';
        if($antispam->isSpam($text)){
            throw new \Exception('Votre message a été détecté comme spam !');
        }
        
        
        //On va ici gérer si le formulaire a été soumis ou non
        //Si la requête est en post, c'est que le formulaire a été soumis
        if ($request->isMethod('POST')) {
            //On s'occupera plus tard, ici, de la création et de la gestion du formulaire
            $request->getSession()->getFlashBag()->add('notice', 'L\'annonce sera enregistrée quand on maîtrisera la BDD');
            //Puis on redirige vers la visualisation de cette annonce
            return $this->redirectToRoute('oc_platform_home');
        }

        //Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('OCPlatformBundle:Advert:add.html.twig');
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
