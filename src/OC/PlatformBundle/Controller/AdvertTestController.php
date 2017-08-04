<?php

//src/OC/PlateformBundle/Controller/AdvertTestController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
//Ne pas oublier ce use !!!
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdvertTestController extends Controller
{
    public function indexAction()
    {
        $content = $this->get('templating')->render('OCPlatformBundle:Advert:indextest.html.twig', array('pseudo' => 'Altoros'));
        return new Response($content);
        //return new Response("Notre propre Hello World!");
    }
}