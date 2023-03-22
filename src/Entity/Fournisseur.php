<?php

namespace App\Entity;

use JsonSerializable;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FournisseurRepository::class)
 */
class Fournisseur implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups("fournisseur")
     * @Assert\NotBlank

     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "le nom doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le nom doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $nomF;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank
     * @Groups("fournisseur")
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "le prenom doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le prenom doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $prenomF;

    /**
     * @ORM\Column(type="integer")
     * @Groups("fournisseur")
     *  * @Assert\NotEqualTo(
     *     value = 0
     * )
     * @Assert\NotBlank
     */
    private $telF;

    /**
     * @ORM\Column(type="string", length=70)
     * @Groups("fournisseur")
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $emailF;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups("fournisseur")
     * @Assert\NotBlank
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "le champs doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le champs doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank
     * @Groups("fournisseur")
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "le champs doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le champs doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $ribF;

    /**
     * @ORM\OneToMany(targetEntity=Equipement::class, mappedBy="Fournisseur", orphanRemoval=true)
     */
    private $equipements;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("fournisseur")
     */
    private $image;

    public function __construct()
    {
        $this->equipements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getNomF(): ?string
    {
        return $this->nomF;
    }

    public function setNomF(string $nomF): self
    {
        $this->nomF = $nomF;

        return $this;
    }

    public function getPrenomF(): ?string
    {
        return $this->prenomF;
    }

    public function setPrenomF(string $prenomF): self
    {
        $this->prenomF = $prenomF;

        return $this;
    }

    public function getTelF(): ?string
    {
        return $this->telF;
    }

    public function setTelF(string $telF): self
    {
        $this->telF = $telF;

        return $this;
    }

    public function getEmailF(): ?string
    {
        return $this->emailF;
    }

    public function setEmailF(string $emailF): self
    {
        $this->emailF = $emailF;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getRibF(): ?string
    {
        return $this->ribF;
    }

    public function setRibF(string $ribF): self
    {
        $this->ribF = $ribF;

        return $this;
    }

    /**
     * @return Collection<int, Equipement>
     */
    public function getEquipements(): Collection
    {
        return $this->equipements;
    }

    public function addEquipement(Equipement $equipement): self
    {
        if (!$this->equipements->contains($equipement)) {
            $this->equipements[] = $equipement;
            $equipement->setFournisseur($this);
        }

        return $this;
    }

    public function removeEquipement(Equipement $equipement): self
    {
        if ($this->equipements->removeElement($equipement)) {
            // set the owning side to null (unless already changed)
            if ($equipement->getFournisseur() === $this) {
                $equipement->setFournisseur(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'nomF' => $this->nomF,
            'prenomF' => $this->prenomF,
            'telF' => $this->telF,
            'emailF' => $this->emailF,
            'adresse' => $this->adresse,
            'ribF' => $this->ribF,
            'equipements' => $this->equipements,
            'image' => $this->image
        );
    }

    public function setUp($nomF, $prenomF, $telF, $emailF, $adresse, $ribF, $image)
    {
        $this->nomF = $nomF;
        $this->prenomF = $prenomF;
        $this->telF = $telF;
        $this->emailF = $emailF;
        $this->adresse = $adresse;
        $this->ribF = $ribF;
        $this->image = $image;
    }
}
