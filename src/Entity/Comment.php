<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Comments")
     */
    private $User;

    /**
     * @ORM\Column(type="text")
     */
    private $Text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $PostDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(string $Text): self
    {
        $this->Text = $Text;

        return $this;
    }

    public function getPostDate(): ?\DateTimeInterface
    {
        return $this->PostDate;
    }

    public function setPostDate(\DateTimeInterface $PostDate): self
    {
        $this->PostDate = $PostDate;

        return $this;
    }
}
