<?php

namespace App\Entity;

use JsonSerializable;

use App\Repository\PersonnelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PersonnelRepository::class)
 */
class Personnel implements JsonSerializable
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
     * @Groups("personnel")
     */
    private $nomP;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "le prenom doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Votre prenom doit comporter au plus {{ limit }} caractères"
     * )
     * @Assert\NotBlank
     * @Groups("personnel")
     */
    private $prenomP;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     * @Assert\NotBlank
     * @Groups("personnel")
     */
    private $telP;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Email(
     *     message = "L'e-mail '{{ value }}' n'est pas un e-mail valide."
     * )
     * @Assert\NotBlank
     * @Groups("personnel")
     */
    private $emailP;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\Length(
     *      min = 8,
     *      max = 50,
     *      minMessage = "le MDP doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le MDP doit comporter au plus {{ limit }} caractères"
     * )
     * @Assert\NotBlank
     * @Groups("personnel")
     */
    private $mdp;

    /**
     * @ORM\Column(type="float")
     * @Assert\Positive
     * @Assert\NotBlank
     * @Groups("personnel")
     */
    private $salaireP;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank
     * @Groups("personnel")
     */
    private $poste;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     * @Assert\NotBlank
     * @Groups("personnel")
     */
    private $hTravail;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     * @Assert\NotBlank
     * @Groups("personnel")
     */
    private $hAbsence;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     * @Groups("personnel")
     */
    private $dateEmbauche;

    /**
     * @ORM\ManyToOne(targetEntity=Salle::class, inversedBy="personnels")
     * @ORM\JoinColumn(nullable=false)
     *

     */
    private $salle;

    /**
     * @ORM\OneToMany(targetEntity=Permission::class, mappedBy="personnel", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     */
    private $permissions;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
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

    public function getNomP(): ?string
    {
        return $this->nomP;
    }

    public function setNomP(string $nomP): self
    {
        $this->nomP = $nomP;

        return $this;
    }

    public function getPrenomP(): ?string
    {
        return $this->prenomP;
    }

    public function setPrenomP(string $prenomP): self
    {
        $this->prenomP = $prenomP;

        return $this;
    }

    public function getTelP(): ?int
    {
        return $this->telP;
    }

    public function setTelP(int $telP): self
    {
        $this->telP = $telP;

        return $this;
    }

    public function getEmailP(): ?string
    {
        return $this->emailP;
    }

    public function setEmailP(string $emailP): self
    {
        $this->emailP = $emailP;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getSalaireP(): ?float
    {
        return $this->salaireP;
    }

    public function setSalaireP(float $salaireP): self
    {
        $this->salaireP = $salaireP;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getHTravail(): ?int
    {
        return $this->hTravail;
    }

    public function setHTravail(int $hTravail): self
    {
        $this->hTravail = $hTravail;

        return $this;
    }

    public function getHAbsence(): ?int
    {
        return $this->hAbsence;
    }

    public function setHAbsence(int $hAbsence): self
    {
        $this->hAbsence = $hAbsence;

        return $this;
    }

    public function getDateEmbauche(): ?\DateTimeInterface
    {
        return $this->dateEmbauche;
    }

    public function setDateEmbauche(\DateTimeInterface $dateEmbauche): self
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }

    public function getSalle(): ?salle
    {
        return $this->salle;
    }

    public function setSalle(?salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    /**
     * @return Collection<int, Permission>
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(Permission $permission): self
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions[] = $permission;
            $permission->setPersonnel($this);
        }

        return $this;
    }

    public function removePermission(Permission $permission): self
    {
        if ($this->permissions->removeElement($permission)) {
            // set the owning side to null (unless already changed)
            if ($permission->getPersonnel() === $this) {
                $permission->setPersonnel(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'nom' => $this->nomP,
            'prenom' => $this->prenomP,
            'dateEmbauche' => $this->dateEmbauche->format("d-m-Y"),
            'tel' => $this->telP,
            'email' => $this->emailP,
            'password' => $this->mdp,
            'salaire' => $this->salaireP,
            'poste' => $this->poste,
            'hTravail' => $this->hTravail,
            'hAbsence' => $this->hAbsence,
            'salle' => $this->salle
        );
    }


    public function setUp($nomP, $prenomP, $dateEmbauche, $telP, $emailP, $mdp, $salaireP, $poste, $hTravail, $hAbsence, $salle)
    {
        $this->nomP = $nomP;
        $this->prenomP = $prenomP;
        $this->dateEmbauche = $dateEmbauche;
        $this->telP = $telP;
        $this->emailP = $emailP;
        $this->mdp = $mdp;
        $this->salaireP = $salaireP;
        $this->poste = $poste;
        $this->hTravail = $hTravail;
        $this->hAbsence = $hAbsence;
        $this->salle = $salle;
    }
}
