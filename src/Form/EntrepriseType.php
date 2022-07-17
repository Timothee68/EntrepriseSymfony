<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // on rajoute le type de chaque champ input ( TextType::class => type="text") et PENSEZ A IMPORTER les class pour les uses 
            ->add('raisonSocial',TextType::class, [
                //il existe des options supplémentaires comme attribue qui ajoute une class par ex ( aller voir doc pour d'autres !!)
                "attr" => ['class' => "form-control"],
            ])
            ->add('ville',TextType::class, [
                "attr" => ['class' => "form-control"]
                ])
            ->add('cp',TextType::class, [
                "attr" => ['class' => "form-control"]
                ])
            ->add('adresse',TextType::class, [
                "attr" => ['class' => "form-control"]
                ])
            //il existe des options supplémentaires comme WIDGET ( aller voir doc pour d'autres !!)
            ->add('dateCreation',DateType::class, [
                'widget' => 'single_text',
                "attr" => ['class' => "form-control"]
            ])
            ->add('siret',TextType::class, [
                "attr" => ['class' => "form-control"]
                ])
            //on doit rajouter le bouton submit nous même 
            ->add('submit',SubmitType::class, [
                "attr" => ['class' => "form-control bg-primary"]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
