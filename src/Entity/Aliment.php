<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Aliment
 *
 * @ORM\Table(name="aliment")
 * @ORM\Entity
 */
class Aliment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_aliment", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAliment;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=20, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=false)
     */
    private $type;

    /**
     * @var string
     * @Assert\File(mimeTypes={"image/jpeg","image/gif","image/png"})
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var int
     *@Assert\Positive()
     * @Assert\LessThan(150)
     * @Assert\GreaterThanOrEqual(0)
     * @ORM\Column(name="calories", type="integer", nullable=false)
     */
    private $calories;

    /**
     * @var string
     * @Assert\Length(
     *      min = 4,
     *      max = 16,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your passowrd cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @return string
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getCalories(): ?int
    {
        return $this->calories;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param int $calories
     */
    public function setCalories(int $calories): void
    {
        $this->calories = $calories;
    }


    /**
     * @return int
     */
    public function getIdAliment(): ?int
    {
        return $this->idAliment;
    }

    /**
     * @param int $idAliment
     */
    public function setIdAliment(int $idAliment): void
    {
        $this->idAliment = $idAliment;
    }


}
