<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @UniqueEntity("title")
 * @UniqueEntity("color")
 */
class Category
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Merci de remplir un titre")
     * @Assert\Type(
     *     type="string",
     *     message="Votre titre doit être un texte."
     * )
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Votre titre doit avoir au minimum {{ limit }} caractères,
     *      maxMessage = "Votre titre doit avoir au maximum {{ limit }} caractères"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\CssColor(message="Merci de rentrer une couleur hexadécimale")
     */
    private $color;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull(message="Merci de mettre une description")
     * @Assert\Type(
     *     type="string",
     *     message="Votre description doit être un texte."
     * )
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Votre description doit avoir au minimum {{ limit }} caractères,
     *      maxMessage = "Votre description doit avoir au maximum {{ limit }} caractères"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type(
     *     type="boolean",
     *     message="A publier doit être vrai ou faux."
     * )
     */
    private $isPublished;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="category")
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }
}
