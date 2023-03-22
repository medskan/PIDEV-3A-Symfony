<?php

namespace App\Entity;

use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity
 */
class Categorie implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotNull
     * @ORM\Column(name="nom_c", type="string", length=255, nullable=false)
     */
    private $nomC;

    /**
     * @var string
     *@Assert\NotBlank
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomC(): ?string
    {
        return $this->nomC;
    }

    public function setNomC(string $nomC): self
    {
        $this->nomC = $nomC;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
    public function __toString() {
        return $this->nomC;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'nomC' => $this->nomC,
            'type' => $this->type
        );
    }

    public function setUp($nomC, $type)
    {
        $this->nomC = $nomC;
        $this->type = $type;
    }
}
