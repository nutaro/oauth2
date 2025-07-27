<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
class Token
{

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->created_at = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $token = null;

    #[ORM\Column(type: DateTimeType::class)]
    private ?DateTime $created_at;

    #[ORM\Column(type: DateTimeType::class)]
    private ?DateTime $valid_until = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?App $app = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getApp(): ?App
    {
        return $this->app;
    }

    public function setApp(?App $app): static
    {
        $this->app = $app;

        return $this;
    }

    public function getValidUntil(): ?DateTime
    {
        return $this->valid_until;
    }

    /**
     * @param DateTime|null $valid_until
     */
    public function setValidUntil(?DateTime $valid_until): static
    {
        $this->valid_until = $valid_until;
        return $this;
    }
}
