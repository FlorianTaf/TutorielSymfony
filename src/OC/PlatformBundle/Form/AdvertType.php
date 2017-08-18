<?php

namespace OC\PlatformBundle\Form;

use OC\PlatformBundle\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('content', TextareaType::class)
            ->add('email', TextType::class)
            //On ajoute une fonction qui va écouter un évènement

            ->add('image', ImageType::class)
            ->add('categories', EntityType::class, array(
                'class' => 'OCPlatformBundle:Category',
                'choice_label' => 'name',
                'multiple' => true
            ))
            ->add('save', SubmitType::class)
            ->addEventListener(
                FormEvents::PRE_SET_DATA, //1er argument : L'évènement qui nous intéresse
                function (FormEvent $event) { //2ème argument : La fonction à exécuter lorsque l'évènement est déclenché
                    //On récupère notre objet Advert sous-jacent
                    $advert = $event->getData();

                    if (null === $advert) {
                        return; //On sort de la fonction sans rien faire lorsque $advert vaut null
                    }

                    //Si l'annonce n'est pas publiée, ou si elle n'existe pas encore en base (id est null)
                    if (!$advert->getPublished() || $advert->getId() === null) {
                        //Alors on ajoute le champ published
                        $event->getForm()->add('published', CheckboxType::class, array('required' => false));
                    } else {
                        //Sinon, on le supprime
                        $event->getForm()->remove('published');
                    }
                }
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\PlatformBundle\Entity\Advert'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'oc_platformbundle_advert';
    }


}
