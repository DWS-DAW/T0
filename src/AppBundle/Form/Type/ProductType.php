<?php 

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\MoneyType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder->add('name', TextType::class, [
                'attr' => array('class' => 'form_input'),
                'label' => 'Name',
                'label_attr' => array('class' => 'form_label')    
                ])
                ->add('price', MoneyType::class, [
                    'attr' => array('class' => 'form_input'),
                    'label' => 'Price',
                    'label_attr' => array('class' => 'form_label')    
                ])
                ->add('description', TextType::class,[
                    'attr' => array('class' => 'form_input'),
                    'label' => 'Description',
                    'label_attr' => array('class' => 'form_label')    
                ])
                ->add('save', SubmitType)
                ->add('saveAndAdd', SubmitType);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Product']);
    }
}