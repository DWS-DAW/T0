<?php 

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProductToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Transforms an object (product) to a string (number).
     *
     * @param  product|null $product
     * @return string
     */
    public function transform($product)
    {
        if (null === $product) {
            return '';
        }

        return $product->getId();
    }

    /**
     * Transforms a string (number) to an object (product).
     *
     * @param  string $productNumber
     * @return Product|null
     * @throws TransformationFailedException if object (product) is not found.
     */
    public function reverseTransform($productNumber)
    {
        // no issue number? It's optional, so that's ok
        if (!$productNumber) {
            return;
        }

        $product = $this->manager
            ->getRepository('AppBundle:Product')
            // query for the issue with this id
            ->find($productNumber)
        ;

        if (null === $product) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An issue with number "%s" does not exist!',
                $productNumber
            ));
        }

        return $product;
    }
}
