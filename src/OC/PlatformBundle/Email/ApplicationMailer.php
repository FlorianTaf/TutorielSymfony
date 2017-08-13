<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 13/08/2017
 * Time: 11:31
 */

namespace OC\PlatformBundle\Email;

use OC\PlatformBundle\Entity\Application;

class ApplicationMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewNotification(Application $application)
    {
        $message = new \Swift_Message(
            'Nouvelle candidature',
            'Vous avec reçu une nouvelle candidature, de la part de ' . $application->getEmail() . '.'
        );

        $message
            ->addTo($application->getAdvert()->getEmail())//Ici il faut forcément un attribut "email", mais on utilise "author" à la place
            ->addFrom('florian.taffaneau@laposte.net');

        $this->mailer->send($message);
    }
}