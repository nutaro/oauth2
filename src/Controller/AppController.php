<?php

namespace App\Controller;

use App\Entity\App;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class AppController extends AbstractController
{

    #[Route('/app', name: 'app')]
    public function createApp(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
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
            return $this->json([], Response::HTTP_BAD_REQUEST);
        }
    }

}
