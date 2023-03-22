<?php

namespace App\Entity;

use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderDetailRepository")
 */
class OrderDetail implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit", inversedBy="orderDetails")
     * @ORM\JoinColumn(name="id_produit", referencedColumnName="id_produit",onDelete="CASCADE")
     */
    private $produit;

    /**
     * @Assert\Positive
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @Assert\PositiveOrZero
     * @ORM\Column(type="string", length=255)
     */
    private $prix;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'quantity' => $this->quantity,
            'prix' => $this->prix,
            'produit' =>  $this->produit,
        );
    }

    public function setUp($quantity, $prix, $produit)
    {
        $this->quantity = $quantity;
        $this->prix = $prix;
        $this->produit = $produit;
    }
}
