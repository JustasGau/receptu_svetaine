<?php

namespace App\Entity;

use App\Repository\IngredientsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IngredientsRepository::class)
 */
class Ingredients
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Calories;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Sugar;

    /**
     * @ORM\ManyToOne(targetEntity=Recipe::class, inversedBy="Ingredient")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Recipe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getCalories(): ?float
    {
        return $this->Calories;
    }

    public function setCalories(?float $Calories): self
    {
        $this->Calories = $Calories;

        return $this;
    }

    public function getSugar(): ?float
    {
        return $this->Sugar;
    }

    public function setSugar(?float $Sugar): self
    {
        $this->Sugar = $Sugar;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->Recipe;
    }

    public function setRecipe(?Recipe $Recipe): self
    {
        $this->Recipe = $Recipe;

        return $this;
    }
}
