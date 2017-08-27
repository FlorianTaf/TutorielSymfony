<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 26/08/2017
 * Time: 18:40
 */

namespace OC\PlatformBundle\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;

class JsonParamConverter implements ParamConverterInterface
{
    function supports(ParamConverter $configuration)
    {
        // Si le nom de l'argument du contrôleur n'est pas "json", on n'applique pas le convertisseur
        if ('json' !== $configuration->getName()){
            return false;
        }
        return true;
    }

    function apply(Request $request, ParamConverter $configuration)
    {
        // On récupère la valeur actuelle de l'attribut
        $json = $request->attributes->get('json');

        // On effectue notre action: le décoder
        $json = json_decode($json, true);

        // On met à jour la nouvelle valeur de l'attribut
        $request->attributes->set('json', $json);
    }
}