<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Tag;

final class TagFixtures extends AbstractFixtures
{
    protected function getEntityClass(): string
    {
        return Tag::class;
    }

    protected function getData(): iterable
    {
        yield [
            'label' => 'Drole',
        ];

        yield [
            'label' => 'Absurde',
        ];

        yield [
            'label' => 'Gaming',
        ];

        yield [
            'label' => 'Pffff on souffle',
        ];
    }
}
