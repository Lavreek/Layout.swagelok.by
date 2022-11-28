<?php

namespace App\Entity;

use App\Repository\UserRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRequestRepository::class)]
class UserRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $user_email = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $user_comment = null;

    #[ORM\Column(length: 15)]
    private ?string $user_ip = null;

    #[ORM\Column(length: 255)]
    private ?string $user_ym_uid = null;

    #[ORM\Column(length: 255)]
    private ?string $user_geo = null;

    #[ORM\Column(length: 255)]
    private ?int $user_width = null;

    #[ORM\Column(length: 255)]
    private ?string $user_fingerprint_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserEmail(): ?string
    {
        return $this->user_email;
    }

    public function setUserEmail(string $user_email): self
    {
        $this->user_email = $user_email;

        return $this;
    }

    public function getUserComment(): ?string
    {
        return $this->user_comment;
    }

    public function setUserComment(string $user_comment): self
    {
        $this->user_comment = $user_comment;

        return $this;
    }

    public function getUserIp(): ?string
    {
        return $this->user_ip;
    }

    public function setUserIp(string $user_ip): self
    {
        $this->user_ip = $user_ip;

        return $this;
    }

    public function getUserYmUid(): ?string
    {
        return $this->user_ym_uid;
    }

    public function setUserYmUid(string $user_ym_uid): self
    {
        $this->user_ym_uid = $user_ym_uid;

        return $this;
    }

    public function getUserGeo(): ?string
    {
        return $this->user_geo;
    }

    public function setUserGeo(string $user_geo): self
    {
        $this->user_geo = $user_geo;

        return $this;
    }

    public function getUserWidth(): ?string
    {
        return $this->user_width;
    }

    public function setUserWidth(string $user_width): self
    {
        $this->user_width = $user_width;

        return $this;
    }

    public function getUserFingerprintId(): ?string
    {
        return $this->user_fingerprint_id;
    }

    public function setUserFingerprintId(string $user_fingerprint_id): self
    {
        $this->user_fingerprint_id = $user_fingerprint_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
