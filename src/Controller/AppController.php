<?php

namespace App\Controller;

use App\Entity\App;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class AppController extends AbstractController
{
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/app', name: 'app', methods: ['POST'])]
    public function createApp(EntityManagerInterface $entityManager, Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $this->logger->info("json data: ", [
                'data' => $data
            ]);
            $app = new App();
            $app->setName($data['name']);
            $clientSecret = Uuid::v4();
            $app->setClientSecret($clientSecret);
            $app->setId(Uuid::v4());
            $entityManager->persist($app);
            $entityManager->flush();
            return $this->json([
                "client_secret" => $clientSecret,
                "client_id" => $app->getId(),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return $this->json([], Response::HTTP_BAD_REQUEST);
        }
    }

}
