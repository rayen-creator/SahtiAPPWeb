<?php

namespace App\Entity;

use App\Repository\LigneCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneCommandeRepository::class)
 */
class LigneCommande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var int
     *
     * @ORM\Column(name="idProduit", type="integer", nullable=true)
     */
    private $idProd;
    /**
     * @var int
     *
     * @ORM\Column(name="idCommande", type="integer", nullable=true)
     */
    private $idCmd;

    
    


    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * Get the value of idProd
     */ 
    public function getIdProd()
    {
        return $this->idProd;
    }

    /**
     * Set the value of idProd
     *
     * @return  self
     */ 
    public function setIdProd($idProd)
    {
        $this->idProd = $idProd;

        return $this;
    }

    /**
     * Get the value of idCmd
     */ 
    public function getIdCmd()
    {
        return $this->idCmd;
    }

    /**
     * Set the value of idCmd
     *
     * @return  self
     */ 
    public function setIdCmd($idCmd)
    {
        $this->idCmd = $idCmd;

        return $this;
    }
}
