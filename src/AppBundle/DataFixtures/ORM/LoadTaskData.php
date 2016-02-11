<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Task;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;


class LoadTaskData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        $tasks = Array(
            0 => Array( 'name' => 'Task 0' , 'description' => 'Description Task 0', 'products' => [1,2,3]),
            1 => Array( 'name' => 'Task 1' , 'description' => 'Description Task 1', 'products' => [1,4]),
            2 => Array( 'name' => 'Task 2' , 'description' => 'Description Task 2', 'products' => [2,3]),
        );

        foreach($tasks as $task_id => $data)
        {
            $task = new Task();
            $task->setName($data['name']);
            $task->setDescription($data['description']);            
            foreach ($data['products'] as $product) {                
                $task->addProduct($this->getReference($product));
            }
            $manager->persist($task);
        }    	
    	$manager->flush();
    }
    
    public function getOrder()
    {
    	// the order in which fixtures will be loaded
    	// the lower the number, the sooner that this fixture is loaded
    	return 2;
    }
    
    /**
     * @override
     */
    public function getEnvironments()
    {
    	return array('prod','dev','test');
    }
}