<?php

namespace App\Entity;

use App\Repository\AaaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AaaRepository::class)
 */
class Aaa
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $a;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getA(): ?string
    {
        return $this->a;
    }

    public function setA(?string $a): self
    {
        $this->a = $a;

        return $this;
    }
}
