<?php

namespace App\Twig;

use App\Entity\Commentary;
use App\Entity\Publication;
use Doctrine\Persistence\Proxy;
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
        if ($object instanceof Proxy) {
            $object->__load();
            $class = \get_parent_class($object);
        }

        $stats = array_fill_keys(self::EMOJIS, 0);
        $stats = $object->getReactions()->reduce(
            function($carry, $reaction) {
                isset($carry[$reaction->getEmoji()]) ? ++$carry[$reaction->getEmoji()] : $carry;

                return $carry;
            },
            array_fill_keys(self::EMOJIS, 0)
        );

        return $this->twig->render('components/emoji.html.twig', [
            'class' => $class ?? $object::class,
            'id' => $object->getId(),
            'emojis' => $stats,
        ]);
    }
}
