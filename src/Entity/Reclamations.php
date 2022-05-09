<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReclamationRepository;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reclamations
 *
 * @ORM\Table(name="reclamations")
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamations
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
     * @var int|null
     *
     * @ORM\Column(name="idEntraineur", type="integer", nullable=true)
     */
    private $identraineur;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idNutritionniste", type="integer", nullable=true)
     */
    private $idnutritionniste;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idClient", type="integer", nullable=true)
     */
    private $idclient;

    /**
     * @var string|null
     *
     * @ORM\Column(name="numReclamation", type="string", length=50, nullable=true)
     * 
     */
    private $numreclamation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="titre", type="string", length=60, nullable=true)
     * @Assert\NotBlank(message = "Le champ titre est requis")
     */
    private $titre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="message", type="string", length=65000, nullable=true)
     * @Assert\NotBlank(message = "Le champ message est requis")
     */
    private $message;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=150, nullable=true)
     * @Assert\NotBlank(message = "Ce champ est requis")
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=60, nullable=true)
     * 
     */
    private $image;

    /**
     * @var bool
     *
     * @ORM\Column(name="etat", type="boolean", nullable=false)
     */
    private $etat_reclamation;

    /**
     * @var bool
     *
     * @ORM\Column(name="cloturer", type="boolean", nullable=false)
     */
    private $cloturer;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dateReclamation", type="string", length=60, nullable=true)
     */
    private $datereclamation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dateCloture", type="string", length=60, nullable=true)
     */
    private $datecloture;
    /**
     * @Assert\File(maxSize="500000000k")
     */
    public  $file;


    public function getWebpath(){


        return null === $this->image ? null : $this->getUploadDir().'/'.$this->image;
    }
    protected  function  getUploadRootDir(){

        return __DIR__.'/public/Upload'.$this->getUploadDir();
    }
    protected function getUploadDir(){

        return '/';
    }
    public function getUploadFile(){
        if (null === $this->getFile()) {
            $this->image = "3.jpg";
            return;
        }
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()
        );
        // set the path property to the filename where you've saved the file
        $this->image = $this->getFile()->getClientOriginalName();
        // clean up the file property as you won't need it anymore
        $this->file = null;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentraineur(): ?int
    {
        return $this->identraineur;
    }

    public function setIdentraineur(?int $identraineur): self
    {
        $this->identraineur = $identraineur;

        return $this;
    }

    public function getIdnutritionniste(): ?int
    {
        return $this->idnutritionniste;
    }

    public function setIdnutritionniste(?int $idnutritionniste): self
    {
        $this->idnutritionniste = $idnutritionniste;

        return $this;
    }

    public function getIdclient(): ?int
    {
        return $this->idclient;
    }

    public function setIdclient(?int $idclient): self
    {
        $this->idclient = $idclient;

        return $this;
    }

    public function getNumreclamation(): ?string
    {
        return $this->numreclamation;
    }

    public function setNumreclamation(?string $numreclamation): self
    {
        $this->numreclamation = $numreclamation;

        return $this;
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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

     /**
     * Get the value of etat_reclamation
     *
     * @return  bool
     */ 
    public function getEtat_reclamation()
    {
        return $this->etat_reclamation;
    }

    /**
     * Set the value of etat_reclamation
     *
     * @param  bool  $etat_reclamation
     *
     * @return  self
     */ 
    public function setEtat_reclamation(bool $etat_reclamation)
    {
        $this->etat_reclamation = $etat_reclamation;

        return $this;
    }

    public function getCloturer(): ?bool
    {
        return $this->cloturer;
    }

    public function setCloturer(bool $cloturer): self
    {
        $this->cloturer = $cloturer;

        return $this;
    }

    public function getDatereclamation(): ?string
    {
        return $this->datereclamation;
    }

    public function setDatereclamation(?string $datereclamation): self
    {
        $this->datereclamation = $datereclamation;

        return $this;
    }

    public function getDatecloture(): ?string
    {
        return $this->datecloture;
    }

    public function setDatecloture(?string $datecloture): self
    {
        $this->datecloture = $datecloture;

        return $this;
    }

    public function getEtatReclamation(): ?bool
    {
        return $this->etat_reclamation;
    }

    public function setEtatReclamation(bool $etat_reclamation): self
    {
        $this->etat_reclamation = $etat_reclamation;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }

}
