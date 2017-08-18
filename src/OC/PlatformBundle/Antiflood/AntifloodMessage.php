<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 18/08/2017
 * Time: 15:40
 */

namespace OC\PlatformBundle\Antiflood;


use Doctrine\ORM\EntityManagerInterface;

class AntifloodMessage
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function isFlood($ip, $sec){
        $date = new \DateTime();
        $advertsIp = $this->em->getRepository('OCPlatformBundle:Advert')->findByIp($ip);

        foreach($advertsIp as $advert){
            $diffSec = $date->getTimestamp() - $advert->getDate()->getTimeStamp();
            if($diffSec < $sec){
                return true;
            }
        }
        return false;
    }
}