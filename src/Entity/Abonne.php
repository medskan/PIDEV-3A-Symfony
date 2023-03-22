<?php

namespace App\Entity;

use JsonSerializable;

use App\Repository\AbonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AbonneRepository::class)
 */
class Abonne implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * * @Assert\Length(
     *      min = 4,
     *      max = 20,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private $nomA;

    /**
     * @ORM\Column(type="string", length=50)
     * * @Assert\Length(
     *      min = 4,
     *      max = 20,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private $prenomA;

    /**
     * @ORM\Column(type="integer")
     * * @Assert\NotNull
     * * @Assert\Positive
     */
    private $ageA;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $sexeA;

    /**
     * @ORM\Column(type="string", length=50)
     *  * @Assert\NotBlank
     * * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $emailA;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $mdpA;

    /**
     * @ORM\Column(type="integer")
     * * @Assert\NotNull
     */
    private $telA;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $adresseA;

    /**
     * @ORM\ManyToOne(targetEntity=Abonnement::class, inversedBy="abonnes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Abonnement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $message;

    /**
     * @ORM\OneToMany(targetEntity=PubLike::class, mappedBy="abonne")
     */
    private $pubLikes;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="abonneA")
     */
    private $produits;

    /**
     * @ORM\OneToMany(targetEntity=ProduitLike::class, mappedBy="abonne")
     */
    private $produitLikes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    public function __construct()
    {
        $this->pubLikes = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->produitLikes = new ArrayCollection();
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

    public function getNomA(): ?string
    {
        return $this->nomA;
    }

    public function setNomA(string $nomA): self
    {
        $this->nomA = $nomA;

        return $this;
    }

    public function getPrenomA(): ?string
    {
        return $this->prenomA;
    }

    public function setPrenomA(string $prenomA): self
    {
        $this->prenomA = $prenomA;

        return $this;
    }

    public function getAgeA(): ?int
    {
        return $this->ageA;
    }

    public function setAgeA(int $ageA): self
    {
        $this->ageA = $ageA;

        return $this;
    }

    public function getSexeA(): ?string
    {
        return $this->sexeA;
    }

    public function setSexeA(string $sexeA): self
    {
        $this->sexeA = $sexeA;

        return $this;
    }

    public function getEmailA(): ?string
    {
        return $this->emailA;
    }

    public function setEmailA(string $emailA): self
    {
        $this->emailA = $emailA;

        return $this;
    }

    public function getMdpA(): ?string
    {
        return $this->mdpA;
    }

    public function setMdpA(string $mdpA): self
    {
        $this->mdpA = $mdpA;

        return $this;
    }

    public function getTelA(): ?int
    {
        return $this->telA;
    }

    public function setTelA(int $telA): self
    {
        $this->telA = $telA;

        return $this;
    }

    public function getAdresseA(): ?string
    {
        return $this->adresseA;
    }

    public function setAdresseA(string $adresseA): self
    {
        $this->adresseA = $adresseA;

        return $this;
    }

    public function getAbonnement(): ?Abonnement
    {
        return $this->Abonnement;
    }

    public function setAbonnement(?Abonnement $Abonnement): self
    {
        $this->Abonnement = $Abonnement;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Collection<int, PubLike>
     */
    public function getPubLikes(): Collection
    {
        return $this->pubLikes;
    }

    public function addPubLike(PubLike $pubLike): self
    {
        if (!$this->pubLikes->contains($pubLike)) {
            $this->pubLikes[] = $pubLike;
            $pubLike->setAbonne($this);
        }

        return $this;
    }

    public function removePubLike(PubLike $pubLike): self
    {
        if ($this->pubLikes->removeElement($pubLike)) {
            // set the owning side to null (unless already changed)
            if ($pubLike->getAbonne() === $this) {
                $pubLike->setAbonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setAbonneA($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getAbonneA() === $this) {
                $produit->setAbonneA(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProduitLike>
     */
    public function getProduitLikes(): Collection
    {
        return $this->produitLikes;
    }

    public function addProduitLike(ProduitLike $produitLike): self
    {
        if (!$this->produitLikes->contains($produitLike)) {
            $this->produitLikes[] = $produitLike;
            $produitLike->setAbonne($this);
        }

        return $this;
    }

    public function removeProduitLike(ProduitLike $produitLike): self
    {
        if ($this->produitLikes->removeElement($produitLike)) {
            // set the owning side to null (unless already changed)
            if ($produitLike->getAbonne() === $this) {
                $produitLike->setAbonne(null);
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
            'nom' => $this->nomA,
            'prenom' => $this->prenomA,
            'age' => $this->ageA,
            'sexe' => $this->sexeA,
            'email' => $this->emailA,
            'mdp' => $this->mdpA,
            'tel' => $this->telA,
            'adresse' => $this->adresseA,
            'abonnement' => $this->Abonnement,
            'message' => $this->message,
            'image' => $this->image
        );
    }

    public function setUp($nomA, $prenomA, $ageA, $sexeA, $emailA, $mdpA, $telA, $adresseA, $abonnement, $message, $image)
    {
        $this->nomA = $nomA;
        $this->prenomA = $prenomA;
        $this->ageA = $ageA;
        $this->sexeA = $sexeA;
        $this->emailA = $emailA;
        $this->mdpA = $mdpA;
        $this->telA = $telA;
        $this->adresseA = $adresseA;
        $this->Abonnement = $abonnement;
        $this->message = $message;
        $this->image = $image;
    }
}
