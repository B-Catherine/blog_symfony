<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @UniqueEntity("title")
 */
class Article
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
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Votre titre doit avoir au minimum {{ limit }} caractères,
     *      maxMessage = "Votre titre doit avoir au maximum {{ limit }} caractères"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type(
     *     type="boolean",
     *     message="A publier doit être vrai ou faux."
     * )
     */
    private $isPublished;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Merci de remplir un auteur")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Votre nom doit avoir au minimum {{ limit }} caractères,
     *      maxMessage = "Votre nom doit avoir au maximum {{ limit }} caractères"
     * )
     */
    private $author;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull(message="Merci de mettre un contenu")
     *
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Merci de remplir une image")
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "Votre URL doit avoir au minimum {{ limit }} caractères,
     *      maxMessage = "Votre URL doit avoir au maximum {{ limit }} caractères"
     * )
     * @Assert\Url(message="Merci de bien mettre une URL", protocols={"http", "https"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="articles")
     *
     */
    private $category;

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

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
