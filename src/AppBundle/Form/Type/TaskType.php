<?php 

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\Type\ProductSelectType;


class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder->add('name', TextType::class, [
                'attr' => array('class' => 'form_input'),
                'label' => 'Name',
                'label_attr' => array('class' => 'form_label'),
                'required' => true    
                ])
            
                ->add('description', TextType::class, [
                    'attr' => array('class' => 'form_input'),
                    'label' => 'Description',
                    'label_attr' => array('class' => 'form_label'),
                    'required' => false    
                ])
                
                ->add('products', CollectionType::class, [
                    'entry_type' => ProductSelectType::class,
                    'allow_add'  => true,
                    //'allow_delete' => true,
                    'by_reference' => false,
                ])
                
                ->add('save', SubmitType::class)
                ->add('saveAndAdd', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Task']);
    }
}