<?php

namespace App\EasyAdmin\Scheduler\Helpers;

use Doctrine\ORM\EntityManagerInterface;

class EntityChangeApply
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) { }

    public function apply(array $changes): object
    {
        $repository = $this->entityManager->getRepository($changes['extra']['entityName']);
        $entity = $repository->findOneBy(['id' => $changes['extra']['entityIdentify']]);

        $this->setVariables($entity, $changes);
        return $entity;
    }

    public function setVariables(object $entity, array $changes)
    {
        foreach ($changes as $key => $value) {
            if ($key == 'extra') {
                continue ;
            }

            $getter = 'get' . ucfirst($key);
            $entityValue = $entity->$getter();
            $type = gettype($entityValue);

            if ($type != 'object') {
                $this->updateBasicValue($key, $entity, $value, $entityValue);
            }
        }
    }

    private function updateBasicValue(string $key, object $entity, $value, $entityValue)
    {
        if ($value[1] != $entityValue) {
            $setter = 'set' . ucfirst($key);
            $entity->$setter($value[1]);
        }
    }
}