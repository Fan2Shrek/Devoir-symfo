<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Commentary;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

final class CommentaryFixtures extends AbstractFixtures implements DependentFixtureInterface
{
    public function __construct(
        private PublicationFixtures $publicationFixtures,
        private UserFixtures $userFixtures,
    ) {
    }

    protected function getEntityClass(): string
    {
        return Commentary::class;
    }

    protected function getData(): iterable
    {
        yield [
            'author' => $this->userFixtures->getOne(),
            'publication' => $this->publicationFixtures->getOne(),
            'content' => 'Je suis un commentaire',
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'publication' => $this->publicationFixtures->getOne(),
            'content' => 'First',
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'publication' => $this->publicationFixtures->getOne(),
            'content' => 'Oui',
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'publication' => $this->publicationFixtures->getOne(),
            'content' => 'AAAAAAAAAAAAAAAAAAAAAAAAAA',
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'publication' => $this->publicationFixtures->getOne(),
            'content' => 'Mais du coup qui vie dans une ananas dans la mer ?',
        ];

        yield [
            'author' => $this->userFixtures->getOne(),
            'publication' => $this->publicationFixtures->getOne(),
            'content' => "Alexandre le Grand (en grec ancien : Ἀλέξανδρος ὁ Μέγας / Aléxandros ho Mégas ou Μέγας Ἀλέξανδρος / Mégas Aléxandros) ou Alexandre III (Ἀλέξανδρος Γ' / Aléxandros III), né le 21 juillet 356 av. J.-C. à Pella et mort le 11 juin 323 av. J.-C. à Babylone, est un roi de Macédoine et l'un des personnages les plus célèbres de l'Antiquité. Fils de Philippe II, élève d'Aristote et roi de Macédoine à partir de 336 av. J.-C., il devient l'un des plus grands conquérants de l'histoire en prenant possession de l'immense Empire perse et en s'avançant jusqu'aux rives de l'Indus.",
        ];
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PublicationFixtures::class,
        ];
    }
}
