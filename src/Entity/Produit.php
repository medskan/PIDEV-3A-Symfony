<?php

namespace App\Entity;

use JsonSerializable;
use App\Entity\Categorie;
use App\Repository\AbonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit
 *
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity
 */
class Produit implements JsonSerializable
{
    /**
     * @Assert\Positive
     * @ORM\Column(name="id_produit", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProduit;

    /**
     * @var string
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your product name must be at least {{ min }} characters long",
     *      maxMessage = "Your product name cannot be longer than {{ max }} characters"
     * )
     * @ORM\Column(name="nom_produit", type="string", length=100, nullable=false)
     */
    public $nomProduit;

    /**
     * @Assert\Length(
     *      min = 10,
     *      max = 1000,
     *      minMessage = "Your product description  must be at least {{ limit }} characters long",
     *      maxMessage = "Your product description cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(name="description", type="string", length=225, nullable=false)
     */
    private $description;


    /**
     * @Assert\Positive
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @Assert\Positive
     * @ORM\Column(name="prix_produit", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixProduit;

    /**
     * @Assert\PositiveOrZero
     * @ORM\Column(name="promotion", type="float", precision=10, scale=0, nullable=false)
     */
    private $promotion;

    /**
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderDetail", mappedBy="produit")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    private $orderDetails;

    /**
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity=categorie::class, inversedBy="produits" )
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=ProduitLike::class, mappedBy="produit")
     */
    private $likes;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }


    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setNomProduit(string $nomProduit): self
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getPrixProduit(): ?float
    {
        return $this->prixProduit;
    }

    public function setPrixProduit(float $prixProduit): self
    {
        $this->prixProduit = $prixProduit;

        return $this;
    }

    public function getPromotion(): ?float
    {
        return $this->promotion;
    }

    public function setPromotion(float $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|OrderDetail[]
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetail $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails[] = $orderDetail;
            $orderDetail->setProduit($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): self
    {
        if ($this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->removeElement($orderDetail);
            // set the owning side to null (unless already changed)
            if ($orderDetail->getProduit() === $this) {
                $orderDetail->setProduit(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, ProduitLike>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(ProduitLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setProduit($this);
        }

        return $this;
    }

    public function removeLike(ProduitLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getProduit() === $this) {
                $like->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isliked(): bool

    {
        foreach ($this->likes as $like) {
            if ($like->getAbonne() === 14403755)
                return true;

        }
        return false;
    }


    /**
     * @return bool
     */
    public function isliked1(Abonne $abonne): bool

    {
        foreach ($this->likes as $like) {
            if ($like->getAbonne() === $abonne)
                return true;

        }
        return false;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->idProduit,
            'nomP' => $this->nomProduit,
            'description' => $this->description,
            'nombre' => $this->quantite,
            'prix' => $this->prixProduit,
            'reduction' => $this->promotion,
            'categorie' => $this->categorie,
            'image' => $this->image
        );
    }

    public function setUp($description, $nomP, $nombre, $prix, $reduction, $categorie, $image)
    {
        $this->nomProduit = $nomP;
        $this->prixProduit = $prix;
        $this->promotion = $reduction;
        $this->image = $image;
        $this->categorie = $categorie;
        $this->description = $description;
        $this->quantite = $nombre;
    }

}
