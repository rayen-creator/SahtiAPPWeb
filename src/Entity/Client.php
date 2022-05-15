<?php

namespace App\Entity;


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Client
 *
 * @ORM\Table(name="client", uniqueConstraints={@ORM\UniqueConstraint(name="email", columns={"email"})}, indexes={@ORM\Index(name="id_coach", columns={"id_coach"}), @ORM\Index(name="id_nutri", columns={"id_nutri"}), @ORM\Index(name="fk", columns={"id_progclient"})})
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=true)
     * @Assert\Type("string")
     *  @Assert\NotBlank(message="Must be filled")
     *  @Groups("post:read")

     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenom", type="string", length=30, nullable=true)
     * @Assert\Type("string")
     * @Assert\NotBlank(message="Must be filled")
     * @Groups("post:read")
     */
    private $prenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=30, nullable=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     *  @Assert\NotBlank(message="Must be filled")
     * @Groups("post:read")
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="passwd", type="string", length=100, nullable=true)
     * @Assert\NotBlank(message="Must be filled")
     * @Assert\Length(
     *      min = 8,
     *      max = 20,
     *      minMessage = "Password must be at least {{ limit }} characters long",
     *      maxMessage = "Password cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex(
     *     pattern="/^(?=.*[a-z])(?=.*\d).{8,}$/i",
     *     message="Password is required to be minimum 8 chars in length and to include at least one letter and one number."
     * )
     * @Groups("post:read")
     */
    private $passwd;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse", type="string", length=30, nullable=true)
     * @Assert\NotBlank(message="Must be filled")
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 5,
     *      max = 20,
     *      minMessage = "Address must be at least {{ limit }} characters long",
     *      maxMessage = "Address cannot be longer than {{ limit }} characters"
     * )
     * @Groups("post:read")
     */
    private $adresse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="datenaiss", type="string", length=30, nullable=true)
     *@Groups("post:read")
     */
    private $datenaiss;

    /**
     * @var string|null
     *
     * @ORM\Column(name="img", type="string", length=100, nullable=true)
     * @Groups("post:read")
     */
    private $img;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IsBlocked", type="boolean", nullable=true)
     * @Groups("post:read")
     */
    private $isblocked = '0';

    /**
     * @var \Entraineur
     *
     * @ORM\ManyToOne(targetEntity="Entraineur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_coach", referencedColumnName="id")
     * })
     * @Groups("post:read")
     */
    private $idCoach;

    /**
     * @var \Nutritioniste
     *
     * @ORM\ManyToOne(targetEntity="Nutritioniste")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_nutri", referencedColumnName="id")
     * })
     * @Groups("post:read")
     */
    private $idNutri;

    /**
     * @CaptchaAssert\ValidCaptcha(
     *      message = "CAPTCHA validation failed, try again."
     * )
     */
    protected $captchaCode;

    /**
     * @var \Progclient
     *
     * @ORM\ManyToOne(targetEntity="Progclient")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_progclient", referencedColumnName="id")
     * })
     */
    private $idProgclient;

    /**
     * @ORM\OneToMany(targetEntity=Commandes::class, mappedBy="client", orphanRemoval=false)
     */
    private $commandes;
     /**
     * @ORM\OneToMany(targetEntity=Reclamations::class, mappedBy="client", orphanRemoval=false)
     */
    private $reclamations;

     /**
     * @ORM\OneToMany(targetEntity=Livraison::class, mappedBy="client")
     */
    private $livraisons;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
        $this->livraisons = new ArrayCollection();
    }
       /**
     * @return Collection<int, Commandes>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commandes $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setClient($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getClient() === $this) {
                $commande->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reclamations>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamations $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations[] = $reclamation;
            $reclamation->setClient($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamations $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getClient() === $this) {
                $reclamation->setClient(null);
            }
        }

        return $this;
    }

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPasswd(): ?string
    {
        return $this->passwd;
    }

    public function setPasswd(?string $passwd): self
    {
        $this->passwd = $passwd;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDatenaiss(): ?string
    {
        return $this->datenaiss;
    }

    public function setDatenaiss(?string $datenaiss): self
    {
        $this->datenaiss = $datenaiss;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getIsblocked(): ?bool
    {
        return $this->isblocked;
    }

    public function setIsblocked(?bool $isblocked): self
    {
        $this->isblocked = $isblocked;

        return $this;
    }

    public function getIdCoach(): ?Entraineur
    {
        return $this->idCoach;
    }

    public function setIdCoach(?Entraineur $idCoach): self
    {
        $this->idCoach = $idCoach;

        return $this;
    }

    public function getIdNutri(): ?Nutritioniste
    {
        return $this->idNutri;
    }

    public function setIdNutri(?Nutritioniste $idNutri): self
    {
        $this->idNutri = $idNutri;

        return $this;
    }

    public function setIdProgclient(?Progclient $idProgclient): self
    {
        $this->idProgclient = $idProgclient;

        return $this;
    }

    public function getIdProgclient(): ?Progclient
    {
        return $this->idProgclient;
    }


    public function getRoles()
    {
        // TODO: Implement getRoles() method.
        $roles[] = 'ROLE_USER';

        return array_unique($roles);    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
        return $this->passwd;

    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
        return $this->img;

    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
        return $this->email;

    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


}
