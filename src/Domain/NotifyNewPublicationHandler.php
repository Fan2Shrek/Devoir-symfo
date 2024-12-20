<?php

declare(strict_types=1);

namespace App\Domain;

use App\Repository\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final class NotifyNewPublicationHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private MailerInterface $mailer,
    ) {
    }

    public function __invoke(NotifyNewPublicationCommand $command)
    {
        $email = new Email();
        $email->from('no-reply@gmail.com');
        $email->subject('New publication');
        $email->html("A new publication has been added to the website!" . $command->publication->getAuthor()->getUsername());

        foreach ($this->userRepository->findAll() as $user) {

            if ($user->getId() === $command->publication->getAuthor()->getId()) {
                continue;
            }

            $email->to($user->getEmail());
            $this->mailer->send($email);
        }
    }
}
