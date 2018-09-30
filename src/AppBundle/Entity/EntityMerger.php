<?php

namespace AppBundle\Entity;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Id;

class EntityMerger
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * EntityMerger constructor.
     * @param AnnotationReader $annotationReader
     */
    public function __construct(AnnotationReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param $entity
     * @param $changes
     */
    public function merge($entity, $changes): void
    {
        // Get $entity class name or false if its not a class
        $entityClassName = get_class($entity);

        if (false === $entityClassName) {
            throw new \InvalidArgumentException('entity is not a class');
        }

        // Get $changes class name or false if its not a class
        $changesClassName = get_class($changes);

        if (false === $changesClassName) {
            throw new \InvalidArgumentException('entity is not a class');
        }

        // If $changes is the same class as $entity or $changes is a subclass of $entity
        if (!is_a($changes, $entityClassName)) {
            throw new \InvalidArgumentException("Cannot merge object of class $changesClassName with object of class $entity");
        }

        $entityReflection  = new \ReflectionObject($entity);
        $changesReflection = new \ReflectionObject($changes);

        foreach ($changesReflection->getProperties() as $changedProperty) {
            $changedProperty->setAccessible(true);
            $changedPropertyValue = $changedProperty->getValue($changes);

            // Ignore properties with null value
            if (null === $changedPropertyValue) {
                continue;
            }

            if (!$entityReflection->hasProperty($changedProperty->getName())) {
                continue;
            }

            $entityProperty = $entityReflection->getProperty($changedProperty->getName());
            $annotation = $this->annotationReader->getPropertyAnnotation($entityProperty, Id::class);

            // Ignore $changes property that has Doctrine @id annotation
            if (null !== $annotation) {
                continue;
            }

            $entityProperty->setAccessible(true);
            $entityProperty->setValue($entity, $changedPropertyValue);
        }
    }
}
