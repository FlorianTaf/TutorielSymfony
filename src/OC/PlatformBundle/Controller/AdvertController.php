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
use OC\PlatformBundle\Repository;

class AdvertController extends Controller
{

    public function indexAction($page)
    {
        //On ne sait pas combien de pages il y a, mais une page doit être supérieur ou égale à 1
        if ($page < 1) {
            //On déclenche une exception NotFoundHttpException, qui affichera une page d'erreur 404 (qu'on pourra personnaliser)
            throw new NotFoundHttpException("Page '" . $page . "' inexistante");
        }

        $listAdverts = array(
            array(
                'title' => 'Recherche développpeur Symfony',
                'id' => 5,
                'author' => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
                'date' => new \Datetime()),
            array(
                'title' => 'Mission de webmaster',
                'id' => 6,
                'author' => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date' => new \Datetime()),
            array(
                'title' => 'Offre de stage webdesigner',
                'id' => 7,
                'author' => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                'date' => new \Datetime())
        );
        //On récupèrera ici, plus tard, la liste des annonces, puis on la passera au template
        //On retourne pour l'instant juste le template
        //return $this->render('OCPlatformBundle:Advert:index.html.twig', array('listAdverts' => array()));
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts));
    }

    public function viewAction($id)
    {
        //Equivalent des 2 méthodes juste en-dessous
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        //Ces 2 méthodes sont les mêmes
        //$advert = $this->getDoctrine()->getManager()->find('OCPlatformBundle:Advert', $id);
        //$advert2 = $this->getDoctrine()->getManager()->find('OC\PlatformBundle\Entity\Advert', $id);
        //$advert est donc une instance de OC\PlatformBundle\Entity\Advert
        //Si c'est null, l'id $id n'existe pas
        if ($advert === null) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        //On récupère la liste des candidatures de cette annonce
        $listApplications = $em
            ->getRepository('OCPlatformBundle:Application')
            ->findBy(array('advert' => $advert), array(), 1, 0);


        //On récupère la liste des AdvertSkill
        $listAdvertSkills = $em
            ->getRepository('OCPlatformBundle:AdvertSkill')
            ->findBy(array('advert' => $advert), array(), 3, 0);

        //On renvoie avec l'annonce et les candidatures liées à cette annonce
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvertSkills));
    }

    public function addAction(Request $request)
    {
        //Création de l'entité Advert
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony');
        $advert->setAuthor('Alexandre');
        $advert->setContent('Nous recherchons un développeur Symfony débutant sur Lyon. Blabla...');
        //On peut ne pas définir ni la date ni la publication, car ces attributs sont définit automatiquement dans le constructeur
        //Création de l'entité Image
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        // https://www.hostingpics.net/viewer.php?id=912018Pomme.jpg
        // http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg
        $image->setAlt('Job de rêve');

        //On lie l'image à l'annonce
        $advert->setImage($image);

        //Création d'une 1ère candidature
        $application1 = new Application();
        $application1->setAuthor('Marine');
        $application1->setContent("J'ai toutes les qualités requises.");
        //Création d'une 2ème candidature
        $application2 = new Application();
        $application2->setAuthor('Pierre');
        $application2->setContent("Je suis très motivé.");

        //On lie les candidatures à l'annonce
        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        //On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        //On récupère toutes les compétences possibles
        $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();

        //Pour chaque compétence
        foreach ($listSkills as $skill) {
            //On crée une nouvelle "relation entre 1 annonce et 1 compétence"
            $advertSkill = new AdvertSkill();
            //On la lie à l'annonce, qui est ici toujours la même
            $advertSkill->setAdvert($advert);
            //On la lie à la compétence, qui change ici dans la boucle foreach
            $advertSkill->setSkill($skill);
            //Arbitrairement, on dit que chaque compétence est requie au niveau "Expert"
            $advertSkill->setLevel('Expert');
            //On persiste cette entité de relation
            $em->persist($advertSkill);
        }

        //Étape 1 : On "persiste" l'entité
        $em->persist($advert);

        //Pour cette relation, pas de cascade lorsqu'on persiste Advert, car la relation est définie
        //dans l'entité Application et non Advert. On doit donc tout persister à la main ici
        $em->persist($application1);
        $em->persist($application2);

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

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if ($advert === null) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        //La méthode findAll retourne toutes les catégories de la BDD
        $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();

        //On boucle sur les catégories pour les lier à l'annonce
        foreach ($listCategories as $category) {
            $advert->addCategory($category);
        }

        //Pour persister le changement dans le relation, on doit persisté l'entité propriétaire, donc Advert ici
        //Mais inutile de persister ici car on l'a récupéré depuis Doctrine
        $em->flush();

        if ($request->isMethod('POST')) {
            $session = $request->getSession();
            $session->getSession()->getFlashBag()->add('notice', 'L\'annonce n°' . $id . ' sera modifiée quand on maîtrisera la BDD.');
            return $this->redirectToRoute('oc_platform_view');
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

        // On boucle sur les catégories de l'annonce pour les supprimer
        foreach ($advert->getCategories() as $category) {
            $advert->removeCategory($category);
        }

        // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
        // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine
        // On déclenche la modification
        $em->flush();

        //Ici on gèrera la suppression de l'annonce en question
        $session = $request->getSession();
        $session->getFlashBag()->add('notice', 'L\'annonce n°' . $id . ' sera supprimée quand on maîtrisera la BDD.');

        //return $this->render('OCPlatformBundle:Advert:delete.html.twig');
        //return $this->redirectToRoute('oc_platform_home');
        return $this->redirectToRoute('oc_platform_view', array('id' => $id));
    }

    public function menuAction()
    {
        //Pour le moment on fixe en dur la liste, mais par la suite on la récupèrera de la BDD

        $listAdverts = array(
            array('id' => 5, 'title' => 'Recherche développeur Symfony'),
            array('id' => 6, 'title' => 'Mission de webmaster'),
            array('id' => 7, 'title' => 'Offre de stage webdesigner')
        );

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
            //Tout l'intérêt est ici: le contrôleur passe les variables nécessaires au template
            'listAdverts' => $listAdverts
        ));
    }

    public function editImageAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        //On récupère l'annonce
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        //Si l'image est nulle, on renvoie à la page de vue de l'annonce (ça nous évite l'erreur "call funcion on null")
        if ($advert->getImage() === null) {
            return $this->redirectToRoute('oc_platform_view', array('id' => $id));
        }

        //On modifie l'URL de l'image
        $advert->getImage()->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        //$advert->getImage()->setUrl('https://www.hostingpics.net/viewer.php?id=190296Pomme.jpg');
        $advert->getImage()->setAlt('Miam miam miam');
        //On n'a pas besoin de persister ni l'annonce ni l'image car ces entitées
        //sont automatiquement persistées car on les a récupérées de Doctrine lui-même
        //On déclenche la modification
        $em->flush();

        return $this->redirectToRoute('oc_platform_view', array('id' => $id));
    }

}
