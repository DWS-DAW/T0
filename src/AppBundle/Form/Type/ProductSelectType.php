<?php 

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Form\DataTransformer\ProductToNumberTransformer;
use AppBundle\Form\Type\ProductType;



class ProductSelectType extends AbstractType
{

    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
        
        // Build our choices array from the database
        $products = $manager->getRepository('AppBundle:Product')->findAll();        
        foreach ($products as $product)
        {
            // choices[label] = key            
            $this->choices[$product->getName() . "[". $product->getPrice() ."]" ] = $product->getId();
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ProductToNumberTransformer($this->manager);
        $builder->addModelTransformer($transformer);
        
        //$builder        
          //  ->add('product', 'EntityType::class', ['class'=>'AppBundle:product', 'property'=>'name']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                "choices" => $this->choices,
                /*"choices" => array(
                    'm' => 'Male',
                    'f' => 'Female',
                ),
                */
                "data_class" => 'AppBundle\Entity\Product'
                ]);
    }


    public function getParent()
    {
        return ChoiceType::class;
    }
}