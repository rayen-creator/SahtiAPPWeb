<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit
 *
 * @ORM\Table(name="produit", indexes={@ORM\Index(name="id_cat", columns={"id_cat"})})
 * @ORM\Entity
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_prod", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProd;

    /**
     * @Assert\NotBlank(message=" nom doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer un titre au mini de 5 caracteres"
     *
     *     )
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @Assert\NotNull(message=" prix doit etre non vide")
     *@Assert\Positive(message="le prix doit etre positive")
     * @ORM\Column(name="prix", type="float", precision=10, scale=0)
     */
    private $prix;

    /**
     * @Assert\NotBlank(message="path de l'image doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      max = 200,
     *      minMessage = "doit etre >=7 ",
     *      maxMessage = "doit etre <=500" )
     * @ORM\Column(type="string", length=1000)
     */
    private $image;

    /**
     * @Assert\NotBlank(message=" quantite doit etre non vide")
     *@Assert\Positive(message="la quantite doit etre positive")
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @Assert\NotBlank(message="description doit etre non vide")
     * @ORM\Column(type="string")
     */
    private $descprod;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="id_cat", referencedColumnName="id_cat")
     * })
     */
    private $idCat;

    public function getIdProd(): ?int
    {
        return $this->idProd;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getDescprod(): ?string
    {
        return $this->descprod;
    }

    public function setDescprod(?string $descprod): self
    {
        $this->descprod = $descprod;

        return $this;
    }

    public function getIdCat(): ?Categorie
    {
        return $this->idCat;
    }

    public function setIdCat(?Categorie $idCat): self
    {
        $this->idCat = $idCat;

        return $this;
    }


}
