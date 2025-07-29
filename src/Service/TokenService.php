<?php

namespace App\Service;

use App\Entity\RefreshToken;
use App\Entity\Token;
use App\Entity\User;
use App\Entity\App;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizeException;

use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class TokenService
{
    private User $user;
    private App $app;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws NotFoundException
     */
    public function setApp(string $clientId, string $clientSecret): void
    {
        $secret = hash("sha512", $clientSecret);
        $repository = $this->entityManager->getRepository(App::class);
        $app = $repository->findOneBy(["client_id" => $clientId, "client_secret" => $secret]);
        if (!$app) {
            throw new NotFoundException();
        }
        $this->app = $app;
    }

    public function setUserByPassword(string $email, string $password): void
    {
        $password = hash("sha512", $password);
        $repository = $this->entityManager->getRepository(User::class);
        $user = $repository->findOneBy(["email" => $email, "password" => $password]);
        if (!$user) {
            throw new UnauthorizeException();
        }
        $this->user = $user;
    }

    public function createToken(): array
    {
        $token = new Token();
        $token->setApp($this->app);
        $token->setUser($this->user);
        $token->setToken(Uuid::v4());
        $date = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));
        $date->modify("+15 minutes");
        $token->setValidUntil($date);
        $this->entityManager->persist($token);
        $this->entityManager->flush();
        return ["token" => $token->getToken(), "refresh_token" => $this->createRefreshToken()];
    }

    protected function createRefreshToken(): string
    {
        $token = new RefreshToken();
        $token->setApp($this->app);
        $token->setUser($this->user);
        $token->setRefreshToken(Uuid::v4());
        $date = new DateTime("now");
        $date->modify("+30 minutes");
        $token->setValidUntil($date);
        $this->entityManager->persist($token);
        $this->entityManager->flush();
        return $token->getRefreshToken();
    }

    public function setUserByRefreshToken(string $refreshToken): void
    {
        $repository = $this->entityManager->getRepository(RefreshToken::class);
        $refreshToken = $repository->findOneBy([
            "refresh_token" => $refreshToken,
            "app" => $this->app
        ]);
        if (!$refreshToken)
            throw new UnauthorizeException();
        if ($refreshToken->getValidUntil() < (new DateTime("now")))
            throw new UnauthorizeException();
        $this->user = $refreshToken->getUser();
    }
}
