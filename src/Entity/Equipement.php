<?php

namespace App\Entity;

use JsonSerializable;

use App\Repository\EquipementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EquipementRepository::class)
 */
class Equipement implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "le nom doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le nom doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $nomE;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     *      minMessage = "le champs doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le champs doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $typeE;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     *      minMessage = "le champs doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le champs doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     *      minMessage = "le champs doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le champs doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $gamme;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     * @Assert\NotBlank
     */
    private $quantite;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     * @Assert\NotBlank
     */
    private $dateCommande;

    /**
     * @ORM\Column(type="float")
     * @Assert\Positive
     * @Assert\NotBlank
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Fournisseur::class, inversedBy="equipements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Fournisseur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getNomE(): ?string
    {
        return $this->nomE;
    }

    public function setNomE(string $nomE): self
    {
        $this->nomE = $nomE;

        return $this;
    }

    public function getTypeE(): ?string
    {
        return $this->typeE;
    }

    public function setTypeE(string $typeE): self
    {
        $this->typeE = $typeE;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getGamme(): ?string
    {
        return $this->gamme;
    }

    public function setGamme(string $gamme): self
    {
        $this->gamme = $gamme;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->Fournisseur;
    }

    public function setFournisseur(?Fournisseur $Fournisseur): self
    {
        $this->Fournisseur = $Fournisseur;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'nomE' => $this->nomE,
            'typeE' => $this->typeE,
            'marque' => $this->marque,
            'gamme' => $this->gamme,
            'quantite' => $this->quantite,
            'dateCommande' => $this->dateCommande->format("d-m-Y"),
            'prix' => $this->prix,
            'fournisseur' => $this->Fournisseur,
        );
    }

    public function setUp($nomE, $typeE, $marque, $gamme, $quantite, $dateCommande, $prix, $Fournisseur)
    {
        $this->nomE = $nomE;
        $this->typeE = $typeE;
        $this->marque = $marque;
        $this->gamme = $gamme;
        $this->quantite = $quantite;
        $this->dateCommande = $dateCommande;
        $this->prix = $prix;
        $this->Fournisseur = $Fournisseur;
    }
}
