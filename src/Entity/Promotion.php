<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Promotion
 *
 * @ORM\Table(name="promotion", indexes={@ORM\Index(name="id_prod", columns={"id_prod"})})
 * @ORM\Entity
 */
class Promotion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_prom", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProm;

    /**
     * @Assert\NotBlank(message=" titre doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer un titre au mini de 5 caracteres"
     *
     *     )
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @Assert\NotBlank(message=" nom doit etre non vide")
     *
     * @ORM\Column(name="porcentage", type="float", precision=10, scale=0, nullable=false)
     */
    private $porcentage;

    /**
     *
     *
     * @ORM\Column(name="ancienPrix", type="float", precision=10, scale=0, nullable=false)
     */
    private $ancienprix;

    /**
     * @Assert\NotBlank(message=" path de l'image doit etre non vide")
     * @Assert\Length(
     *      min = 7,
     *      minMessage=" Entrer un path au mini de 5 caracteres"
     *
     *     )
     * @ORM\Column(type="string", length=255)
     *
     */
    private $image;

    /**
     * @Assert\NotBlank(message="description  doit etre non vide")
     * @Assert\Length(
     *      min = 7,
     *      max = 100,
     *      minMessage = "doit etre >=7 ")
     * @ORM\Column(type="string")
     */
    private $descprom;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_prod", referencedColumnName="id_prod")
     * })
     */
    private $idProd;

    public function getIdProm(): ?int
    {
        return $this->idProm;
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

    public function getPorcentage(): ?float
    {
        return $this->porcentage;
    }

    public function setPorcentage(?float $porcentage): self
    {
        $this->porcentage = $porcentage;

        return $this;
    }

    public function getAncienprix(): ?float
    {
        return $this->ancienprix;
    }

    public function setAncienprix(?float $ancienprix): self
    {
        $this->ancienprix = $ancienprix;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescprom(): ?string
    {
        return $this->descprom;
    }

    public function setDescprom(?string $descprom): self
    {
        $this->descprom = $descprom;

        return $this;
    }

    public function getIdProd(): ?Produit
    {
        return $this->idProd;
    }

    public function setIdProd(?Produit $idProd): self
    {
        $this->idProd = $idProd;

        return $this;
    }


}
