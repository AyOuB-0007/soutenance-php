<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateHeure = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $statut = null;

    #[ORM\Column(type: 'float')]
    private ?float $montantTotal = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notesSpeciales = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(name: 'id_table', referencedColumnName: 'id')]
    private ?Table $laTable = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(name: 'id_personnel', referencedColumnName: 'id')]
    private ?Personnel $personnel = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: ArticleCommande::class, cascade: ['persist', 'remove'])]
    private Collection $articleCommandes;

    #[ORM\OneToOne(mappedBy: 'commande', cascade: ['persist', 'remove'])]
    private ?SuiviCuisine $suiviCuisine = null;

    public function __construct()
    {
        $this->articleCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateHeure(): ?\DateTimeInterface
    {
        return $this->dateHeure;
    }

    public function setDateHeure(\DateTimeInterface $dateHeure): static
    {
        $this->dateHeure = $dateHeure;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getMontantTotal(): ?float
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(float $montantTotal): static
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    public function getNotesSpeciales(): ?string
    {
        return $this->notesSpeciales;
    }

    public function setNotesSpeciales(?string $notesSpeciales): static
    {
        $this->notesSpeciales = $notesSpeciales;

        return $this;
    }

    public function getLaTable(): ?Table
    {
        return $this->laTable;
    }

    public function setLaTable(?Table $laTable): static
    {
        $this->laTable = $laTable;

        return $this;
    }

    public function getPersonnel(): ?Personnel
    {
        return $this->personnel;
    }

    public function setPersonnel(?Personnel $personnel): static
    {
        $this->personnel = $personnel;

        return $this;
    }

    /**
     * @return Collection<int, ArticleCommande>
     */
    public function getArticleCommandes(): Collection
    {
        return $this->articleCommandes;
    }

    public function addArticleCommande(ArticleCommande $articleCommande): static
    {
        if (!$this->articleCommandes->contains($articleCommande)) {
            $this->articleCommandes->add($articleCommande);
            $articleCommande->setCommande($this);
        }

        return $this;
    }

    public function removeArticleCommande(ArticleCommande $articleCommande): static
    {
        if ($this->articleCommandes->removeElement($articleCommande)) {
            // set the owning side to null (unless already changed)
            if ($articleCommande->getCommande() === $this) {
                $articleCommande->setCommande(null);
            }
        }

        return $this;
    }

    public function getSuiviCuisine(): ?SuiviCuisine
    {
        return $this->suiviCuisine;
    }

    public function setSuiviCuisine(SuiviCuisine $suiviCuisine): static
    {
        // set the owning side of the relation if necessary
        if ($suiviCuisine->getCommande() !== $this) {
            $suiviCuisine->setCommande($this);
        }

        $this->suiviCuisine = $suiviCuisine;

        return $this;
    }
}
