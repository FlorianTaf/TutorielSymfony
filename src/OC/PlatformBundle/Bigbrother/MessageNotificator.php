<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 22/08/2017
 * Time: 23:11
 */

namespace OC\PlatformBundle\Bigbrother;


use Symfony\Component\Security\Core\User\UserInterface;

class MessageNotificator
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    //Méthode pour notifier par e-mail un administrateur
    public function notifyByEmail($message, UserInterface $user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("Nouveau message d'un utilisateur surveillé")
            ->setFrom('tafatouf@hotmail.fr')
            ->setTo('florian.taffaneau@laposte.net')
            ->setBody("Lu'tilisateur surveillé '" . $user->getUsername() . "' a posté le message suivant '" . $message . "''");

        $this->mailer->send($message);
    }
}