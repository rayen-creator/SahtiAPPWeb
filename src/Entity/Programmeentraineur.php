<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Programmeentraineur
 *
 * @ORM\Table(name="programmeentraineur")
 * @ORM\Entity(repositoryClass="App\Repository\ProgrammeentraineurRepository")
 * @UniqueEntity("nompack")
 */
class Programmeentraineur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="idExercice", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="description is required")
     */
    private $idexercice;

    /**
     * @var string
     *
     * @ORM\Column(name="nomPack", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="nom pack is required")
     */
    private $nompack;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="type  is required")
     */
    private $type;
  //  /**
//* @ORM\ManyToMany(targetEntity=Client::class)
//* @ORM\JoinTable(name="Progclient")
//*/
  //  protected clients;
    //public function __construct()
    //{
      //  $this->Clients = new ArrayCollection();
    //}


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdexercice(): ?string
    {
        return $this->idexercice;
    }

    public function setIdexercice(string $idexercice): self
    {
        $this->idexercice = $idexercice;

        return $this;
    }

    public function getNompack(): ?string
    {
        return $this->nompack;
    }

    public function setNompack(string $nompack): self
    {
        $this->nompack = $nompack;

        return $this;
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


}
