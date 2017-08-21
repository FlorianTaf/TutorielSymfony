<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 21/08/2017
 * Time: 12:38
 */

namespace OC\PlatformBundle\Twig;

use OC\PlatformBundle\Antispam\OCAntispam;

class AntispamExtension extends \Twig_Extension
{
    /**
     * @var oCAntispam
     */
    private $ocAntispam;

    public function __construct(OCAntispam $ocAntispam)
    {
        $this->ocAntispam = $ocAntispam;
    }

    public function checkIsArgumentIsSpam($text)
    {
        return $this->ocAntispam->isSpam($text);
    }

    //Twig va exécuter cette méthode pour savoir quelles fonctions ajouter à notre service
    public function getFunctions(){
        return array(
            new \Twig_SimpleFunction('checkIfSpam', array($this, 'checkIfArgumentIsSpam'))
        );
    }

    //La méthode getName() identifie notre extension Twig, elle est obligatoire
    public function getName(){
        return 'OCAntispam';
    }
}