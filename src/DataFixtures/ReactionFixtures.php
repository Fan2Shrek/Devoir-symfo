<?php

namespace App\DataFixtures;

use App\Entity\Reaction;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

final class ReactionFixtures extends AbstractFixtures implements DependentFixtureInterface
{
    public function __construct(
        private CommentaryFixtures $commentaryFixtures,
        private PublicationFixtures $publicationFixtures,
        private UserFixtures $userFixtures,
    ) {
    }

    protected function getEntityClass(): string
    {
        return Reaction::class;
    }

    protected function getData(): iterable
    {
        yield [
            'emoji' => '👍',
            'publication' => $this->publicationFixtures->getOne(),
            'author' => $this->userFixtures->getOne(),
        ];

        yield [
            'emoji' => '👎',
            'publication' => $this->publicationFixtures->getOne(),
            'author' => $this->userFixtures->getOne(),
        ];

        yield [
            'emoji' => '😂',
            'commentary' => $this->commentaryFixtures->getOne(),
            'author' => $this->userFixtures->getOne(),
        ];

        yield [
            'emoji' => '😡',
            'commentary' => $this->commentaryFixtures->getOne(),
            'author' => $this->userFixtures->getOne(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            CommentaryFixtures::class,
            PublicationFixtures::class,
            UserFixtures::class,
        ];
    }
}
