<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class AbstractFixtures extends Fixture
{
    private \ReflectionClass $r;

    public function load(ObjectManager $manager): void
    {
        $r = $this->r ??= new \ReflectionClass($this->getEntityClass());

        foreach ($this->getData() as $k => $data) {
            $entity = new ($this->getEntityClass());

            foreach ($data as $property => $value) {
                $setter = 'set' . ucfirst($property);

                if (method_exists($entity, $setter)) {
                    $entity->$setter($value);
                }
            }

            $this->postInstantiate($entity);
            $this->addReference($r->getShortName() . '_' . $k, $entity);
            $manager->persist($entity);
        }

        $manager->flush();
    }

    protected function postInstantiate(object $entity): void {}

    abstract protected function getData(): iterable;

    abstract protected function getEntityClass(): string;

    public function getOne(): object
    {
        $count = count(iterator_to_array($this->getData()));

        return $this->getReference(
            \sprintf('%s_%d', $this->loadReflection()->r->getShortName(), random_int(0, $count - 1)),
            $this->getEntityClass()
        );
    }

    private function loadReflection(): self
    {
        $this->r = new \ReflectionClass($this->getEntityClass());

        return $this;
    }
}
