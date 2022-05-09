<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Repas
 *
 * @ORM\Table(name="repas")
 * @ORM\Entity
 */

class Repas
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_repas", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRepas;

    /**
     * @var string
     * @Assert\NotBlank(message="merci de remplir ce champs")
     * @ORM\Column(name="nom_rep", type="string", length=100, nullable=false)
     */
    private $nomRep;

    /**
     * @var int
     *@Assert\Positive()
     * @Assert\GreaterThanOrEqual(0)
     * @ORM\Column(name="nb_cal", type="integer", nullable=false)
     */
    private $nbCal;

    /**
     * @var int
     * @Assert\Positive()
     * @Assert\GreaterThanOrEqual(0)
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Regime", mappedBy="idRepas")
     */
    private $idRegime;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idRegime = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getIdRepas(): int
    {
        return $this->idRepas;
    }

    /**
     * @return Collection
     */
    public function getIdRegime(): Collection
    {
        return $this->idRegime;
    }

    /**
     * @param int $idRepas
     */
    public function setIdRepas(int $idRepas): void
    {
        $this->idRepas = $idRepas;
    }

    /**
     * @return int
     */
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    /**
     * @param string $nomRep
     */
    public function setNomRep(string $nomRep): void
    {
        $this->nomRep = $nomRep;
    }

    /**
     * @return string
     */
    public function getNomRep(): ?string
    {
        return $this->nomRep;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    public function __toString(): string
    {
        return $this->nomRep;
    }

    /**
     * @param int $nbCal
     */
    public function setNbCal(int $nbCal): void
    {
        $this->nbCal = $nbCal;
    }

    /**
     * @return int
     */
    public function getNbCal(): ?int
    {
        return $this->nbCal;
    }

    /**
     * @param Collection $idRegime
     */
    public function setIdRegime(Collection $idRegime): void
    {
        $this->idRegime = $idRegime;
    }

    /**
     * @return Collection|Regime[]
     */
    public function getReg(): Collection
    {
        return $this->idRegime;
    }


    public function addRegime(Regime $regime): self
    {
        if (!$this->idRegime->contains($regime)) {
            $this->idRegime[] = $regime;
        }

        return $this;
    }

    public function removeRegime(Regime $regime): self
    {
        $this->idRegime->removeElement($regime);

        return $this;
    }


}