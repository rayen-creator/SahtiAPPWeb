<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\Self_;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Regime
 *
 * @ORM\Table(name="regime", indexes={@ORM\Index(name="fk_regime_specialiste", columns={"id_specialiste"})})
 * @ORM\Entity
 */
class Regime
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_regime", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRegime;

    /**
     * @var string
     * @Assert\GreaterThanOrEqual(0)
     * @ORM\Column(name="objectif", type="string", length=20, nullable=false)
     */
    private $objectif;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date", nullable=false)
     */
    private $dateDebut;

    /**
     * @var int
     *@Assert\Positive()
     *@Assert\NotEqualTo(
     *     value = 0,
     *     message = "La duree ne doit pa etre 0"
     * )
     * @ORM\Column(name="duree", type="integer", nullable=false)
     */
    private $duree;

    /**
     * @var int
     * @Assert\Positive()
     * @Assert\LessThan(150)
     * @Assert\GreaterThanOrEqual(0)
     * @ORM\Column(name="max_calories", type="integer", nullable=false)
     */
    private $maxCalories;

    /**
     * @var int
     *@Assert\Positive()
     * @Assert\GreaterThanOrEqual(0)
     * @ORM\Column(name="nb_repas", type="integer", nullable=false)
     */
    private $nbRepas;

    /**
     * @var int
     *
     * @ORM\Column(name="id_specialiste", type="integer", nullable=false)
     */
    private $idSpecialiste;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Repas", inversedBy="idRegime")
     * @ORM\JoinTable(name="reg_repas",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_regime", referencedColumnName="id_regime")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_repas", referencedColumnName="id_repas")
     *   }
     * )
     */
    private $idRepas;



    public function __construct()
    {
        $this->idRepas = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getIdRegime(): ?int
    {
        return $this->idRegime;
    }


    public function setIdRepas($idRepas): void
    {
        $this->idRepas = $idRepas;
    }


    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
    public function getImage(): ?string
    {
        return $this->image;

    }

    public function __toString(): string
    {
        return $this->nbRepas;
    }


    /**
     * @return Collection|Repas[]
     */
    public function getRepas(): Collection
    {
        return $this->idRepas;
    }

    public function setIdRegime(int $idRegime): void
    {
        $this->idRegime = $idRegime;
    }


    public function setNbRepas(int $nbRepas): void
    {
        $this->nbRepas = $nbRepas;
    }


    public function getIdSpecialiste(): ?int
    {
        return $this->idSpecialiste;
    }


    public function getMaxCalories(): ?int
    {
        return $this->maxCalories;
    }


    public function getObjectif(): ?string
    {
        return $this->objectif;
    }


    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut ;
    }

    public function setDateDebut(\DateTimeInterface $dateTime): self
    {
        $this->dateDebut = $dateTime;

        return $this;
    }

    public function setMaxCalories(int $maxCalories): void
    {
        $this->maxCalories = $maxCalories;
    }

    /**
     * @return int
     */
    public function getNbRepas(): ?int
    {
        return $this->nbRepas;
    }

    /**
     * @param int $idSpecialiste
     */
    public function setIdSpecialiste(int $idSpecialiste): void
    {
        $this->idSpecialiste = $idSpecialiste;
    }

    /**
     * @param string $objectif
     */
    public function setObjectif(string $objectif): void
    {
        $this->objectif = $objectif;
    }

    /**
     * @return Collection
     */
    public function getIdRepas()
    {
        return $this->idRepas;
    }

    public function addRepa(Repas $repa): self
    {

        if (!$this->repas->contains($repa)) {
            $this->repas[] = $repa;
            $repa->addRegime($this);
        }



        return $this;
    }

    public function removeRepa(Repas $repa): self
    {
        if ($this->repas->removeElement($repa)) {
            $repa->removeRegime($this);
        }

        return $this;
    }


}
