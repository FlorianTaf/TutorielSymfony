<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 18/08/2017
 * Time: 12:30
 */

namespace OC\PlatformBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class Antiflood
 * @package OC\PlatformBundle\Validator
 * @Annotation
 */
class Antiflood extends Constraint
{
    public $message = "Vous avez déjà posté un message il y a moins de 30 secondes, merci d'attendre un peu.";

    public function validatedBy()
    {
        return 'oc_platform_antiflood'; //Ici on fait appel à l'alias du service
    }
}