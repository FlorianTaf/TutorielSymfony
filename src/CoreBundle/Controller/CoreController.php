<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; //Pour récupérer l'objet Request

class CoreController extends Controller
{
    public function indexAction()
    {
        return $this->render('CoreBundle::layout.html.twig');
    }
    
    public function platformAccueilAction(){
        //On renvoie sur la page d'accueil de la plateforme d'annonces
        return $this->render('OCPlatformBundle::layout.html.twig');
    }
    
    public function contactAction(Request $request){
        $session = $request->getSession();
        $session->getFlashBag()->add('notice', 'La page de contact n\'est pas encore disponible, merci de revenir plus tard');
        
        return $this->redirectToRoute('core_homepage');
    }
}
