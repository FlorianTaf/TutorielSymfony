<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 13/08/2017
 * Time: 11:56
 */

namespace OC\PlatformBundle\DoctrineListener;


use Doctrine\ORM\Event\LifecycleEventArgs;
use OC\PlatformBundle\Email\ApplicationMailer;
use OC\PlatformBundle\Entity\Application;

class ApplicationCreationListener
{
    /**
     * @var ApplicationMailer
     */
    private $applicationMailer;

    public function __construct(ApplicationMailer $applicationMailer)
    {
        $this->applicationMailer = $applicationMailer;
    }

    public function postPersist(LifecycleEventArgs $args){
        $entity = $args->getObject();

        //On ne veut envoyer un email que pour les entités Application
        if(!$entity instanceof Application){
            return;
        }

        $this->applicationMailer->sendNewNotification($entity);
    }
}