<?php

//src/OC/PlateformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
//Ne pas oublier ce use !!!
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdvertController extends Controller {

    public function indexAction() {
        $content = $this->get('templating')->render('OCPlatformBundle:Advert:index.html.twig', array('pseudo' => 'Altoros'));
        return new Response($content);
        //return new Response("Notre propre Hello World!");
    }

    public function bonjourAction() {
        $content = $this->get('templating')->render('OCPlatformBundle:Advert:indextest2.html.twig', array('pseudo' => 'Altoros'));
        return new Response($content);
        //return new Response("Notre propre Hello World!"); 
    }

}
