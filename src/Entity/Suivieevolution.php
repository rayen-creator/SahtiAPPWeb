<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Suivieevolution
 *
 * @ORM\Table(name="suivieevolution")
 * @ORM\Entity(repositoryClass="App\Repository\SuivieevolutionRepository")
 */
class Suivieevolution
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("suivieevolutions")
     */
    private $id;

    /**
     * @var int
     *@Groups("suivieevolutions")
     * @ORM\Column(name="idUser", type="integer", nullable=false)
     * @Assert\NotBlank(message="id user is required")
     */
    private $iduser;

    /**
     * @var int
     *@Groups("suivieevolutions")
     * @ORM\Column(name="poids", type="integer", nullable=false)
     * @Assert\NotBlank(message="poids is required")
     */
    private $poids;

    /**
     * @var \DateTime
     *@Groups("suivieevolutions")
     * @ORM\Column(name="dateDebutProgramme", type="date", nullable=false)
     * @Assert\NotBlank(message="date debut is required")
     */
    private $datedebutprogramme;

    /**
     * @var \DateTime
     *@Groups("suivieevolutions")
     * @ORM\Column(name="dateEvolution", type="date", nullable=false)
     * @Assert\NotBlank(message="date evolution is required")
     * @Assert\GreaterThanOrEqual(propertyPath="datedebutprogramme",
    message="La date d'evolution doit être supérieure à la date début programme")
     */
    private $dateevolution;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(int $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(int $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getDatedebutprogramme(): ?\DateTimeInterface
    {
        return $this->datedebutprogramme;
    }

    public function setDatedebutprogramme(\DateTimeInterface $datedebutprogramme): self
    {
        $this->datedebutprogramme = $datedebutprogramme;

        return $this;
    }

    public function getDateevolution(): ?\DateTimeInterface
    {
        return $this->dateevolution;
    }

    public function setDateevolution(\DateTimeInterface $dateevolution): self
    {
        $this->dateevolution = $dateevolution;

        return $this;
    }


}
