<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 18/08/2017
 * Time: 12:40
 */

namespace OC\PlatformBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use OC\PlatformBundle\Antiflood\AntifloodMessage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AntifloodValidator extends ConstraintValidator
{
    private $requestStack;
    private $em;
    private $antifloodMessage;

    //Les arguments déclarés dans la définition du service arrivent au constructeur
    //On doit les enregistrer dans l'objet pour pouvoir s'en resservir dans la méthode validate()
    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, AntifloodMessage $antifloodMessage)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->antifloodMessage = $antifloodMessage;
    }

    public function validate($value, Constraint $constraint)
    {
        //Pour récupérer l'objet Request tel qu'on le connaît, il faut utiliser getCurrentRequest du service request_stack
        $request = $this->requestStack->getCurrentRequest();

        //On récupère l'IP de celui qui poste
        $ip = $request->getClientIp();

        $isFlood = $this->antifloodMessage->isFlood($ip, 30);

        if($isFlood){
            //C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message
            $this->context->addViolation($constraint->message);
        }
    }
}