<?php

/*
 * This file is part of the Manhattan Console Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\Bundle\ConsoleBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class ObjectToIdTransformer implements DataTransformerInterface
{
     /**
     * @var ObjectManager
     */
    private $om;
    private $objectClass;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om, $objectClass = null)
    {
        $this->om = $om;
        $this->objectClass = $objectClass;
    }

    /**
     * Transforms an object to an id.
     *
     * @param  Object|null $object
     * @return mixed
     */
    public function transform($object)
    {
        if (null === $object) {

            return '';
        }

        return $object->getId();
    }

    /**
     * Transforms an id to an object.
     *
     * @param  mixed $id
     *
     * @return Object|null
     *
     * @throws TransformationFailedException if object is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $object = $this->om
            ->getRepository($this->objectClass)
            ->find($id)
        ;

        if (null === $object) {

            throw new TransformationFailedException(sprintf(
                'An instance of "%s" with id "%s" does not exist!',
                $this->objectClass,
                $id
            ));
        }

        return $object;
    }

    public function getObjectClass()
    {
        return $this->objectClass;
    }

    public function setObjectClass($class)
    {
        $this->objectClass = $class;
    }
}
