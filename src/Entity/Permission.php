<?php

namespace App\Entity;

use JsonSerializable;

use App\Repository\PermissionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PermissionRepository::class)
 */
class Permission implements JsonSerializable
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
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 1000,
     *      minMessage = "le champs doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le champs doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $reclamation;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date
     * "d-m-Y" formatted value
     * @Assert\NotBlank
     ** @Assert\NotNull
     *
     *
     */
    private $dateD;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date
     * "d-m-Y" formatted value
     * @Assert\NotBlank
     *
     */
    private $dateF;

    /**
     * @ORM\ManyToOne(targetEntity=Personnel::class, inversedBy="permissions")
     * @ORM\JoinColumn(name="id", referencedColumnName="id",onDelete="CASCADE")
     */
    private $personnel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    protected $captchaCode;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getReclamation(): ?string
    {
        return $this->reclamation;
    }

    public function setReclamation(string $reclamation): self
    {
        $this->reclamation = $reclamation;

        return $this;
    }

    public function getDateD(): ?\DateTimeInterface
    {
        return $this->dateD;
    }

    public function setDateD(\DateTimeInterface $dateD): self
    {
        $this->dateD = $dateD;

        return $this;
    }

    public function getDateF(): ?\DateTimeInterface
    {
        return $this->dateF;
    }

    public function setDateF(\DateTimeInterface $dateF): self
    {
        $this->dateF = $dateF;

        return $this;
    }

    public function getPersonnel(): ?Personnel
    {
        return $this->personnel;
    }

    public function setPersonnel(?Personnel $personnel): self
    {
        $this->personnel = $personnel;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage( $image)
    {
        $this->image = $image;

        return $this;
    }

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode= $captchaCode;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'type' => $this->type,
            'reclamation' => $this->reclamation,
            'dateDebut' => $this->dateD->format("d-m-Y"),
            'dateFin' => $this->dateF->format("d-m-Y"),
            'personnel' => $this->personnel,
            'image' => $this->image,
        );
    }

    public function setUp($type, $reclamation, $dateD, $dateF, $personnel, $image)
    {
        $this->type = $type;
        $this->reclamation = $reclamation;
        $this->dateD = $dateD;
        $this->dateF = $dateF;
        $this->personnel = $personnel;
        $this->image = $image;
    }
}
