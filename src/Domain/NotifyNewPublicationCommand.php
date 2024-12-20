<?php

declare(strict_types=1);

namespace App\Domain;

use App\Entity\Publication;

final readonly class NotifyNewPublicationCommand
{
    public function __construct(
        public Publication $publication,
    ) {
    }
}
