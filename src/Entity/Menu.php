<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nomMenu = null;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: ArticleMenu::class)]
    private Collection $articleMenus;

    public function __construct()
    {
        $this->articleMenus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMenu(): ?string
    {
        return $this->nomMenu;
    }

    public function setNomMenu(string $nomMenu): static
    {
        $this->nomMenu = $nomMenu;

        return $this;
    }





    /**
     * @return Collection<int, ArticleMenu>
     */
    public function getArticleMenus(): Collection
    {
        return $this->articleMenus;
    }

    public function addArticleMenu(ArticleMenu $articleMenu): static
    {
        if (!$this->articleMenus->contains($articleMenu)) {
            $this->articleMenus->add($articleMenu);
            $articleMenu->setMenu($this);
        }

        return $this;
    }

    public function removeArticleMenu(ArticleMenu $articleMenu): static
    {
        if ($this->articleMenus->removeElement($articleMenu)) {
            // set the owning side to null (unless already changed)
            if ($articleMenu->getMenu() === $this) {
                $articleMenu->setMenu(null);
            }
        }

        return $this;
    }
}
