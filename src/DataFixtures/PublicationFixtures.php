<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Publication;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

final class PublicationFixtures extends AbstractFixtures implements DependentFixtureInterface
{
    public function __construct(
        private UserFixtures $userFixtures,
        private TagFixtures $tagFixtures,
    ) {
    }

    public function getEntityClass(): string
    {
        return Publication::class;
    }

    public function getData(): iterable
    {
        yield [
            'author' => $this->userFixtures->getOne(),
            'content' => 'Hello world!',
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'content' => 'Trop bien',
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'content' => 'Propos racistes',
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'content' => 'Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite Fornite ',
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'content' => 'Les soirées de samedi quelques fois ca me déçoit',
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'content' => 'Je suis un bot',
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'content' => "On parle pas assez de l'impact de Baudelaire sur la poésie française",
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'content' => 'Publication 8',
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'content' => "Henri IV, dit « le Grand » ou « Le Vert Galant », ou encore « Le Bon Roi Henri », né sous le nom d'Henri de Bourbon le 13 décembre 1553 à Pau et mort assassiné le 14 mai 1610 à Paris, est roi de Navarre à partir du 9 juin 1572 sous le nom d'Henri IIIa, et roi de France sous le nom d'Henri IV du 2 août 1589 jusqu'à sa mort en 1610. Il réunit ainsi les dignités de roi de France et de Navarre et est le premier roi de France de la maison capétienne de Bourbon.",
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'content' => "En vrai José de scène de ménage il est trop drôle",
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'content' => "J'ai pas compris la fin de Inception",
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'content' => "海盗",
        ];
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TagFixtures::class,
        ];
    }

    protected function postInstantiate(object $entity): void
    {
        for ($i = 0; $i < random_int(0, 3); $i++) {
            $entity->addTag($this->tagFixtures->getOne());
        }
    }
}
