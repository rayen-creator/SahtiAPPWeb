<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\CommandesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandesRepository::class)
 */
class Commandes
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
     * @ORM\Column(name="num_cmd", type="string", length=255, nullable=false)
     */
    private $numCmd;

    /**
     * @var float
     *
     * @ORM\Column(name="montant_cmd", type="float", precision=10, scale=0, nullable=false)
     */
    private $montantCmd;

    /**
     * @var string|null
     *
     * @ORM\Column(name="commentaire", type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @var bool
     *
     * @ORM\Column(name="etat", type="boolean", nullable=false)
     */
    private $etat;

    /**
     * @var int
     *
     * @ORM\Column(name="qtecmd", type="integer", nullable=false)
     */
    private $qtecmd;

    /**
     * @var string
     *
     * @ORM\Column(name="mode_pay", type="string", length=255, nullable=false)
     */
    private $modePay;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_commande", type="datetime", nullable=true)
     */
    private $dateCommande;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_modif", type="datetime", nullable=true)
     */
    private $dateModif;

    /**
     * @ORM\ManyToOne(targetEntity=Livraison::class, inversedBy="commande")
     */
    private $livraison;

    /**
     * @ORM\OneToMany(targetEntity=Livraison::class, mappedBy="commande")
     */
    private $livraisons;

    /**
     * @ORM\OneToMany(targetEntity=Facture::class, mappedBy="commande", orphanRemoval=true)
     */
    private $factures;

    /**
     * @ORM\ManyToMany(targetEntity=Produit::class, inversedBy="commandes")
     */
    private $produit;

     /**
    * @var int 
     * @ORM\Column(name="id_client", type="integer", nullable=false)
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

   

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->livraisons = new ArrayCollection();
        $this->factures = new ArrayCollection();
        $this->produit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCmd(): ?string
    {
        return $this->numCmd;
    }

    public function setNumCmd(string $numCmd): self
    {
        $this->numCmd = $numCmd;

        return $this;
    }

    public function getMontantCmd(): ?float
    {
        return $this->montantCmd;
    }

    public function setMontantCmd(float $montantCmd): self
    {
        $this->montantCmd = $montantCmd;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getQtecmd(): ?int
    {
        return $this->qtecmd;
    }

    public function setQtecmd(int $qtecmd): self
    {
        $this->qtecmd = $qtecmd;

        return $this;
    }

    public function getModePay(): ?string
    {
        return $this->modePay;
    }

    public function setModePay(string $modePay): self
    {
        $this->modePay = $modePay;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(?\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getDateModif(): ?\DateTimeInterface
    {
        return $this->dateModif;
    }

    public function setDateModif(?\DateTimeInterface $dateModif): self
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }

    /**
     * @return Collection<int, Livraison>
     */
    public function getLivraisons(): Collection
    {
        return $this->livraisons;
    }

    public function addLivraison(Livraison $livraison): self
    {
        if (!$this->livraisons->contains($livraison)) {
            $this->livraisons[] = $livraison;
            $livraison->setCommande($this);
        }

        return $this;
    }

    public function removeLivraison(Livraison $livraison): self
    {
        if ($this->livraisons->removeElement($livraison)) {
            // set the owning side to null (unless already changed)
            if ($livraison->getCommande() === $this) {
                $livraison->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->setCommande($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getCommande() === $this) {
                $facture->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produit->contains($produit)) {
            $this->produit[] = $produit;
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        $this->produit->removeElement($produit);

        return $this;
    }
    public function getClient(): ?Client
    {
        return $this->client;
    }
    public function getIdClient(): ?int
    {
        return $this->client;
    }

    public function setClient(?int $client): self
    {
        $this->client = $client;

        return $this;
    }

}
