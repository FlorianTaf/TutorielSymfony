<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 14/08/2017
 * Time: 23:54
 */

namespace OC\PlatformBundle\Purger;

use Symfony\Component\Validator\Constraints\DateTime;

class PurgerAdvert
{
    private $em;

    /**
     * PurgerAdvert constructor.
     * @param \Doctrine\ORM\EntityManager $em EntityManager nécessaire pour faire notre requête SQL
     * @param $days Paramètres qui vient du contrôleur (qui vient avant ça de l'URL
     */
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function purgeAdvertsDate($days)
    {
        //On récupère le repository Advert sur lequel on va faire notre requête pour récupérer les annonces n'ayant pas été mises à jour depuis "$days"jours
        $advertsDate = $this->em->getRepository('OCPlatformBundle:Advert')->getAdvertsForPurge($days);
        $nbAdvertsDateDelete = 0;


        foreach ($advertsDate as $advertDate) {
            //On va récupérer tous les skillsAdvert liés à notre Advert pour les supprimer
            $advertSkills = $this->em->getRepository('OCPlatformBundle:AdvertSkill')->findBy(array('advert' => $advertDate));
            foreach ($advertSkills as $advertSkill) {
                $this->em->remove($advertSkill);
            }
            $this->em->remove($advertDate);
            $nbAdvertsDateDelete++;
        }
        //Pas besoin du persist, car on récupère les entités directement de l'ORM
        $this->em->flush();
        //On retourne le nombre d'annonces supprimées (pas obligatoire mais c'est pour l'afficher en message flash dans la vue)
        return $nbAdvertsDateDelete;
    }
}