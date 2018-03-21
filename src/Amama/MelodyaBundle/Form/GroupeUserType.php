<?php

namespace Amama\MelodyaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeUserType extends AbstractType {
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
        ->add('groupe')
        ->add('user',   EntityType::class, array('class' => 'AmamaMelodyaBundle:User', 'choice_label' => function ($users) { return $users->getLogin(); }, 'multiple' => true, 'label' => 'Membres'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Amama\MelodyaBundle\Entity\GroupeUser'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'amama_melodyabundle_groupeuser';
    }


}
