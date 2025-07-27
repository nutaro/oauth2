<?php

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Exception\UnauthorizeException;
use App\Service\TokenService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class TokenController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/token/password', name: 'token_password', methods: ['POST'])]
    public function token_password(EntityManagerInterface $entityManager, Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $this->logger->info("Create token service");
            $service = new TokenService($entityManager);
            $service->setApp($data["client_id"], $data["client_secret"]);
            $this->logger->info("App Setted");
            $service->setUserByPassword($data["email"], $data["password"]);
            $this->logger->info("User Logged");
            return $this->json($service->createToken(), Response::HTTP_CREATED);
        } catch (NotFoundException $e) {
            return $this->json([], Response::HTTP_FAILED_DEPENDENCY);
        } catch (UnauthorizeException $e) {
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return $this->json([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

}
