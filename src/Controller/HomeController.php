<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\NotifyNewPublicationCommand;
use App\Entity\Commentary;
use App\Entity\Publication;
use App\Entity\Reaction;
use App\Form\CommentaryType;
use App\Form\PublicationFormType;
use App\Repository\PublicationRepository;
use App\Service\ChatGPT;
use App\Twig\AppRuntime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    public function __construct(
        private PublicationRepository $publicationRepository,
        private EntityManagerInterface $entityManager,
        private HubInterface $hub,
    ) {
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'publications' => $this->publicationRepository->findBy([], ['publishedAt' => 'DESC'], 10),
        ]);
    }

    #[Route('/publish', name: 'app_publish')]
    public function publish(MessageBusInterface $bus, Request $request): Response
    {
        $form = $this->createForm(PublicationFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $publication = $form->getData();
            $publication->setAuthor($this->getUser());

            $this->entityManager->persist($publication);
            $this->entityManager->flush();

            $bus->dispatch(new NotifyNewPublicationCommand($publication));

            return $this->redirectToRoute('home');
        }

        return $this->render('home/publish.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/publish_help', name: 'app_publish_help', methods: ['POST'])]
    public function help(Request $request, ChatGPT $goat): Response
    {
        $prompt = $request->getPayload()->get('prompt');

        return new JsonResponse([
            'content' => $goat->generate($prompt),
        ]);

        return $this->render('home/publish_help.html.twig');
    }

    #[Route('/publication/{id}', name: 'app_publication_details')]
    public function publicationDetails(Publication $publication): Response
    {
        if (null === $publication) {
            throw $this->createNotFoundException();
        }


        return $this->render('home/publication_details.html.twig', [
            'publication' => $publication,
        ]);
    }

    #[Route('/publication/{id}/comment', name: 'app_publication_comment')]
    public function commentPublication(Request $request, Publication $publication): Response
    {
        $form = $this->createForm(CommentaryType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setAuthor($this->getUser());
            $comment->setPublication($publication);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_publication_details', ['id' => $publication->getId()]);
        }

        return $this->render('home/publication_comment.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/add_reaction', name: 'app_add_reaction', methods: ['POST'])]
    public function addToComment(Request $request): Response
    {
        $payload = $request->getPayload();
        $emoji = $payload->get('emoji');
        $class = $payload->get('class');
        $id = $payload->get('id');

        if (null === $emoji || null === $class || null === $id) {
            throw $this->createNotFoundException();
        }

        if (!in_array($class, [Publication::class, Commentary::class], true)) {
            throw $this->createNotFoundException();
        }

        if (!in_array($emoji, AppRuntime::EMOJIS, true)) {
            throw $this->createNotFoundException();
        }

        $reaction = new Reaction();
        $reaction->setEmoji($emoji);
        $reaction->setAuthor($this->getUser());

        match ($class) {
            Publication::class => $reaction->setPublication($this->entityManager->getReference(Publication::class, $id)),
            Commentary::class => $reaction->setCommentary($this->entityManager->getReference(Commentary::class, $id)),
        };

        $this->entityManager->persist($reaction);
        $this->entityManager->flush();

        if (Commentary::class === $class) {
            $commentary = $reaction->getCommentary();
            $this->hub->publish(new Update(
                "commentary-$id",
                $this->renderView('component/turbo/commentary.html.twig', [
                    'commentary' => $commentary,
                ])
            ));

            return new JsonResponse();
        }

        $this->hub->publish(new Update(
            "publication-$id",
            $this->renderView('components/turbo/reaction.html.twig', [
                'publication' => $reaction->getPublication(),
            ])
        ));

        return new JsonResponse();
    }

    #[Route('/me', name: 'app_me')]
    public function me(): Response
    {
        return $this->render('home/me.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
