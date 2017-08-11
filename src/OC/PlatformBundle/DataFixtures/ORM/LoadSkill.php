<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Skill;

/**
 * Description of LoadSkill
 *
 * @author Florian
 */
class LoadSkill implements FixtureInterface {

    public function load(ObjectManager $manager) {
        //Liste des noms de compétences à ajouter
        $names = array('PHP', 'Symfony', 'C++', 'Java', 'Photoshop', 'Blender', 'Bloc-notes');
        
        foreach($names as $name){
            //On crée la compétence
            $skill = new Skill();
            $skill->setName($name);
            
            //On la persiste
            $manager->persist($skill);
        }
        
        //On déclenche l'enregistrement de toutes les catégories
        $manager->flush();
    }

}
