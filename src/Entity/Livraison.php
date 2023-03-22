<?php

namespace App\Entity;

use JsonSerializable;

use App\Repository\LivraisonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LivraisonRepository::class)
 */
class Livraison implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     * @Assert\NotBlank
     * @Groups("livraison")
     */
    private $numL;


    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank
     * @Groups("livraison")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "le nom doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le nom doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $nomLivreur;


    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank
     * @Groups("livraison")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "le prenom doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le prenom doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $prenomLivreur;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank
     * @Groups("livraison")
     */
    private $telLivreur;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank
     * @Groups("livraison")
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "le champs doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "le champs doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $adresseLivraison;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateLivraison;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateArrive;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNumL()
    {
        return $this->numL;
    }

    /**
     * @param mixed $numL
     */
    public function setNumL($numL): void
    {
        $this->numL = $numL;
    }

    /**
     * @return mixed
     */
    public function getNomLivreur()
    {
        return $this->nomLivreur;
    }

    /**
     * @param mixed $nomLivreur
     */
    public function setNomLivreur($nomLivreur): void
    {
        $this->nomLivreur = $nomLivreur;
    }

    /**
     * @return mixed
     */
    public function getPrenomLivreur()
    {
        return $this->prenomLivreur;
    }

    /**
     * @param mixed $prenomLivreur
     */
    public function setPrenomLivreur($prenomLivreur): void
    {
        $this->prenomLivreur = $prenomLivreur;
    }

    /**
     * @return mixed
     */
    public function getTelLivreur()
    {
        return $this->telLivreur;
    }

    /**
     * @param mixed $telLivreur
     */
    public function setTelLivreur($telLivreur): void
    {
        $this->telLivreur = $telLivreur;
    }

    /**
     * @return mixed
     */
    public function getAdresseLivraison()
    {
        return $this->adresseLivraison;
    }

    /**
     * @param mixed $adresseLivraison
     */
    public function setAdresseLivraison($adresseLivraison): void
    {
        $this->adresseLivraison = $adresseLivraison;
    }

    /**
     * @return mixed
     */
    public function getDateLivraison()
    {
        return $this->dateLivraison;
    }

    /**
     * @param mixed $dateLivraison
     */
    public function setDateLivraison($dateLivraison): void
    {
        $this->dateLivraison = $dateLivraison;
    }


    public function getDateArrive(): ?\DateTimeInterface
    {
        return $this->dateArrive;
    }

    public function setDateArrive(?\DateTimeInterface $dateArrive): self
    {
        $this->dateArrive = $dateArrive;

        return $this;
    }


    public function jsonSerialize(): array
    {
        if ($this->dateLivraison == null) {
            return array(
                'id' => $this->id,
                'numL' => $this->numL,
                'nomLivreur' => $this->nomLivreur,
                'prenomLivreur' => $this->prenomLivreur,
                'telLivreur' => $this->telLivreur,
                'adresseLivraison' => $this->adresseLivraison,
                'dateDebut' => null,
                'dateFin' => null,
            );
        } else {
            return array(
                'id' => $this->id,
                'numL' => $this->numL,
                'nomLivreur' => $this->nomLivreur,
                'prenomLivreur' => $this->prenomLivreur,
                'telLivreur' => $this->telLivreur,
                'adresseLivraison' => $this->adresseLivraison,
                'dateDebut' => $this->dateLivraison->format("d-m-Y"),
                'dateFin' => $this->dateLivraison->format("d-m-Y"),
            );
        }
    }

    public function setUp($numL, $nomLivreur, $prenomLivreur, $telLivreur, $addresseLivraison, $dateLivraison, $dateArrive)
    {
        $this->numL = $numL;
        $this->nomLivreur = $nomLivreur;
        $this->prenomLivreur = $prenomLivreur;
        $this->telLivreur = $telLivreur;
        $this->adresseLivraison = $addresseLivraison;
        $this->dateLivraison = $dateLivraison;
        $this->dateArrive = $dateArrive;
    }
}
