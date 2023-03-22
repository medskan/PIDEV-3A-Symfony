<?php

namespace App\Entity;

use JsonSerializable;

use App\Entity\User;
use App\Repository\PublicationRepository;
use App\Repository\AbonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManager;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=PublicationRepository::class)
 */
class Publication implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\Length(
     *      min = 3,
     *      max = 20,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     * @Assert\NotBlank
     */
    private $redacteurPub;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePub;

    /**
     * @ORM\Column(type="string", length=2000)
     * @Assert\Length(
     *      min = 1,
     *      max = 2000,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     * @Assert\NotBlank
     */
    private $contenu;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="publication", orphanRemoval=true)
     * @Assert\NotBlank
     */
    private $commentaires;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;


    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\PositiveOrZero
     * @Assert\Range(
     *      min = 1,
     *      max = 5,)
     */
    private $note;

    /**
     * @ORM\OneToMany(targetEntity=PubLike::class, mappedBy="publication")
     */
    private $pubLikes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $userId;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->yes = new ArrayCollection();
        $this->pubLikes = new ArrayCollection();

    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRedacteurPub(): ?string
    {
        return $this->redacteurPub;
    }

    public function setRedacteurPub(string $redacteurPub): self
    {
        $this->redacteurPub = $redacteurPub;

        return $this;
    }

    public function getDatePub(): ?\DateTimeInterface
    {
        return $this->datePub;
    }

    public function setDatePub(\DateTimeInterface $datePub): self
    {
        $this->datePub = $datePub;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setPublication($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPublication() === $this) {
                $commentaire->setPublication(null);
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


    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

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
            $pubLike->setPublication($this);
        }

        return $this;
    }

    public function removePubLike(PubLike $pubLike): self
    {
        if ($this->pubLikes->removeElement($pubLike)) {
            // set the owning side to null (unless already changed)
            if ($pubLike->getPublication() === $this) {
                $pubLike->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function islikedd(): bool

    {
        foreach ($this->pubLikes as $like) {
            if ($like->getAbonne())
                return true;

        }
        return false;
    }

    /**
     * @param Abonne $abonne
     * @return bool
     */
    public function isliked2(Abonne $abonne): bool
    {
        foreach ($this->pubLikes as $like) {
            if ($like->getAbonne() === $abonne)
                return true;

        }
        return false;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'object' => $this->redacteurPub,
            'content' => $this->contenu,
            'date' => $this->datePub->format("d-m-Y"),
            'comments' => $this->commentaires,
            'image' => $this->image,
            'userId' => $this->userId
        );
    }

    public function setUp($object, $contenu, $image, $userId)
    {
        $this->redacteurPub = $object;
        $this->contenu = $contenu;
        $this->image = $image;
        $this->userId = $userId;
    }
}
