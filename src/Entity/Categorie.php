<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCat;

    /**
     * @Assert\NotBlank(message=" titre doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer un titre au mini de 5 caracteres"
     *
     *     )
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     *
     *
     * @Assert\NotBlank(message=" titre doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer un path du image au mini de 5 caracteres"
     *
     *     )
     * @ORM\Column(name="img_cat", type="string", length=255)
     */
    private $imgCat;

    public function getIdCat(): ?int
    {
        return $this->idCat;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getImgCat()
    {
        return $this->imgCat;
    }

    public function setImgCat($imgCat): self
    {
        $this->imgCat = $imgCat;

        return $this;
    }


}
