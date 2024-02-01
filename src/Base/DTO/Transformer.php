<?php

namespace App\Base\DTO;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;

class Transformer
{
    protected \Doctrine\ORM\EntityManagerInterface $em;
    protected \Symfony\Component\PropertyAccess\PropertyAccessorInterface $propertyAccessor;
    protected \Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface $propertyInfoExtractor;

    /**
     * Transformer constructor.
     */
    public function __construct(
        EntityManagerInterface $em,
        PropertyAccessorInterface $propertyAccessor,
        PropertyInfoExtractorInterface $propertyInfoExtractor
    ) {
        $this->em = $em;
        $this->propertyAccessor = $propertyAccessor;
        $this->propertyInfoExtractor = $propertyInfoExtractor;
    }

    /**
     * @param $entity
     * @param array $options
     * @return mixed
     */
    public function DTOToObject(DTOInterface $dto, $entity, $options = [])
    {
        $properties = isset($options['properties']) ? $options['properties'] : $this->getProperties($dto);
        if (is_array($properties)) {
            foreach ($properties as $property) {
                $read = $this->propertyAccessor->isReadable($dto, $property);
                $write = $this->propertyAccessor->isWritable($entity, $property);
                if ($read && $write) {
                    $value = $this->propertyAccessor->getValue($dto, $property);
                    $this->propertyAccessor->setValue($entity, $property, $value);
                }
            }
        }

        return $entity;
    }

    /**
     * @param $object
     * @return null|string[]
     */
    protected function getProperties($object)
    {
        return $this->propertyInfoExtractor->getProperties(get_class($object));
    }
}
