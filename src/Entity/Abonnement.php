<?php

namespace App\Entity;

use JsonSerializable;

use App\Repository\AbonnementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AbonnementRepository::class)
 */
class Abonnement implements JsonSerializable
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
    private $typeA;

    /**
     * @ORM\Column(type="float")
     * * @Assert\Positive
     */
    private $tarifA;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFin;

    /**
     * @ORM\OneToMany(targetEntity=Abonne::class, mappedBy="Abonnement", orphanRemoval=true)
     */
    private $abonnes;

    public function __construct()
    {
        $this->abonnes = new ArrayCollection();
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


    public function getTypeA(): ?string
    {
        return $this->typeA;
    }

    public function setTypeA(string $typeA): self
    {
        $this->typeA = $typeA;

        return $this;
    }

    public function getTarifA(): ?float
    {
        return $this->tarifA;
    }

    public function setTarifA(float $tarifA): self
    {
        $this->tarifA = $tarifA;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * @return Collection<int, Abonne>
     */
    public function getAbonnes(): Collection
    {
        return $this->abonnes;
    }

    public function addAbonne(Abonne $abonne): self
    {
        if (!$this->abonnes->contains($abonne)) {
            $this->abonnes[] = $abonne;
            $abonne->setAbonnement($this);
        }

        return $this;
    }

    public function removeAbonne(Abonne $abonne): self
    {
        if ($this->abonnes->removeElement($abonne)) {
            // set the owning side to null (unless already changed)
            if ($abonne->getAbonnement() === $this) {
                $abonne->setAbonnement(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'type' => $this->typeA,
            'tarif' => $this->tarifA,
            'dateDebut' => $this->dateDebut->format("d-m-Y"),
            'dateFin' => $this->dateFin->format("d-m-Y")
        );
    }

    public function setUp($typeA, $tarifA, $dateDebut, $dateFin)
    {
        $this->typeA = $typeA;
        $this->tarifA = $tarifA;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
    }
}
