<?php

namespace App\Entity;

use App\Repository\RefreshTokenRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RefreshTokenRepository::class)]
class RefreshToken
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

    #[ORM\Column(length: 255)]
    private ?string $refresh_token = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(referencedColumnName: "client_id", nullable: false)]
    private ?App $app = null;

    #[ORM\Column]
    private ?\DateTime $created_at;

    #[ORM\Column]
    private ?\DateTime $valid_until = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refresh_token;
    }

    public function setRefreshToken(string $refresh_token): static
    {
        $this->refresh_token = $refresh_token;

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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }


    public function getValidUntil(): ?\DateTime
    {
        return $this->valid_until;
    }

    public function setValidUntil(\DateTime $valid_until): static
    {
        $this->valid_until = $valid_until;

        return $this;
    }
}
