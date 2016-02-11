<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Product;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class LoadProductData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
    	
    	$yaml = new Parser();

        $symbonfy_base_dir = $this->container->getParameter('kernel.root_dir');
        $data_dir = $symbonfy_base_dir . '/Resources/data/';

        try {
            $value = Yaml::parse(file_get_contents($data_dir . 'tasks.yml'));
        }  catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }

    /*
    tasks:
        0: { id: 1, name: ''}
        1: { id: 1, name: ''}
    taskauthor:
        0: { id: 1, name: "Brad Taylor", isActive: true }
        1: { id: 2, name: "William O'Neil", isActive: false }
    */

        $products = Array(
            0 => Array( 'name' => 'Product0', 'price' => 0, 'description' => 'Description Product 0'),
            1 => Array( 'name' => 'Product1', 'price' => 1, 'description' => 'Description Product 1'),
            2 => Array( 'name' => 'Product2', 'price' => 2, 'description' => 'Description Product 2'),
            3 => Array( 'name' => 'Product3', 'price' => 3, 'description' => 'Description Product 3'),
            4 => Array( 'name' => 'Product4', 'price' => 4, 'description' => 'Description Product 4'),
        );

        foreach($products as $product_id => $data)
        {
            $product = new Product();            
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setPrice($data['price']);
            $manager->persist($product);
            $this->addReference($product_id,$product);
        }       
        $manager->flush();

    }
    
    public function getOrder()
    {
    	// the order in which fixtures will be loaded
    	// the lower the number, the sooner that this fixture is loaded
    	return 1;
    }
    
    /**
     * @override
     */
    public function getEnvironments()
    {
    	return array('prod','dev','test');
    }
}