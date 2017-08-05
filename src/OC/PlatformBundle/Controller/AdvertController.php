<?php

//src/OC/PlateformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
//Ne pas oublier ce use !!!
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdvertController extends Controller {

    public function indexAction() {
        
        $content = $this->get('templating')->render('OCPlatformBundle:Advert:index.html.twig', array('pseudo' => 'Altoros', 'advert_id' => 5));
        /*
        $url = $this->get('router')->generate('oc_platform_view', //1er argument le nom de la route
                array('id' => 5), UrlGeneratorInterface::ABSOLUTE_URL); //2ème argument les valeurs des paramètres
        $url = $this->generateUrl('oc_platform_home', array(), UrlGeneratorInterface::ABSOLUTE_URL);
         */
        return new Response($content);
        //return new Response("Notre propre Hello World!");
        //return new Response("L'URL de l'annonce d'id 5 est : <strong>" . $url . "</strong>");
    }

    public function viewAction($id) {
        //$id vaut 5 si l'URL est /platform/advert/5
        //Ici, on récupèrera depuis la BDD l'annonce correspondant à l'id $id
        //Puis on passera l'annonce à la vue pour qu'elle puisse l'afficher
        return new Response("Affichage de l'annonce d'id : " . $id);
    }

    public function viewSlugAction($slug, $year, $_format) {
        return new Response("On pourrait afficher l'annonce correspondant au slug '" . $slug . "', créée en "
                . "'" . $year . "' et au format '" . $_format . "'");
    }
}
