<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Progclient
 *
 * @ORM\Table(name="progclient", indexes={@ORM\Index(name="FK2", columns={"iduser"}), @ORM\Index(name="fk1_idprog", columns={"idprog"})})
 * @ORM\Entity(repositoryClass="App\Repository\ProgclientRepository")
 */
class Progclient
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
     * @var int
     *
     * @ORM\Column(name="idprog", type="integer", nullable=false)
     * @Assert\NotBlank(message="id prog is required")
     */
    private $idprog;

    /**
     * @var int
     *
     * @ORM\Column(name="iduser", type="integer", nullable=false)
     * @Assert\NotBlank(message="id user is required")
     */
    private $iduser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdprog(): ?int
    {
        return $this->idprog;
    }

    public function setIdprog(int $idprog): self
    {
        $this->idprog = $idprog;

        return $this;
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


}
