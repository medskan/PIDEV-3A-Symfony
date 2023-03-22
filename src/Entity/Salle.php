<?php

namespace App\Entity;

use JsonSerializable;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SalleRepository::class)
 */
class Salle implements JsonSerializable
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
     * @Groups("salle")
     */
    private $nomS;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "le champs doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le champs doit comporter au plus {{ limit }} caractères"
     * )
     * @Groups("salle")
     */
    private $adresseS;
    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Positive
     * @Assert\NotBlank
     * @Groups("salle")
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "le champs doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le champs doit comporter au plus {{ limit }} caractères"
     * )
     * @Groups("salle")
     */
    private $ville;
    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Positive
     * @Assert\NotBlank
     * @Groups("salle")
     */
    private $nombreP;

    /**
     * @ORM\OneToMany(targetEntity=Personnel::class, mappedBy="salle")
     */
    private $personnels;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("salle")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lattitude;

    public function __construct()
    {
        $this->personnels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getNomS(): ?string
    {
        return $this->nomS;
    }

    public function setNomS(string $nomS): self
    {
        $this->nomS = $nomS;

        return $this;
    }

    public function getAdresseS(): ?string
    {
        return $this->adresseS;
    }

    public function setAdresseS(string $adresseS): self
    {
        $this->adresseS = $adresseS;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getNombreP(): ?int
    {
        return $this->nombreP;
    }

    public function setNombreP(int $nombreP): self
    {
        $this->nombreP = $nombreP;

        return $this;
    }

    /**
     * @return Collection<int, Personnel>
     */
    public function getPersonnels(): Collection
    {
        return $this->personnels;
    }

    public function addPersonnel(Personnel $personnel): self
    {
        if (!$this->personnels->contains($personnel)) {
            $this->personnels[] = $personnel;
            $personnel->setSalle($this);
        }

        return $this;
    }

    public function removePersonnel(Personnel $personnel): self
    {
        if ($this->personnels->removeElement($personnel)) {
            // set the owning side to null (unless already changed)
            if ($personnel->getSalle() === $this) {
                $personnel->setSalle(null);
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



    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLattitude(): ?string
    {
        return $this->lattitude;
    }

    public function setLattitude(?string $lattitude): self
    {
        $this->lattitude = $lattitude;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'nom' => $this->nomS,
            'adresse' => $this->adresseS,
            'codePostal' => $this->codePostal,
            'ville' => $this->ville,
            'nombre' => $this->nombreP,
            'image' => $this->image,
            'longitude' => $this->longitude,
            'lattitude' => $this->lattitude
        );
    }

    public function setUp($nomS, $adresseS, $codePostal, $ville, $nombreP, $image, $longitude, $lattitude)
    {
        $this->nomS = $nomS;
        $this->adresseS = $adresseS;
        $this->codePostal = $codePostal;
        $this->ville = $ville;
        $this->nombreP = $nombreP;
        $this->image = $image;
        $this->longitude = $longitude;
        $this->lattitude = $lattitude;
    }
}
