<?php

namespace App\Twig;

use App\Entity\Commentary;
use App\Entity\Publication;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

final class AppRuntime implements RuntimeExtensionInterface
{
    public const EMOJIS = [
        '👍',
        '👎',
        '😂',
        '😡',
    ];

    public function __construct(private Environment $twig) {
    }

    public function emoji(Commentary|Publication $object): string
    {
        $stats = array_fill_keys(self::EMOJIS, 0);
        $stats = $object->getReactions()->reduce(
            function($carry, $reaction) {
                isset($carry[$reaction->getEmoji()]) ? ++$carry[$reaction->getEmoji()] : $carry;

                return $carry;
            },
            array_fill_keys(self::EMOJIS, 0)
        );

        return $this->twig->render('components/emoji.html.twig', [
            'class' => $object::class,
            'id' => $object->getId(),
            'emojis' => $stats,
        ]);
    }
}
