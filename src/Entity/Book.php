<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"listAuthorFull"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listGenreFull", "listAuthorFull"})
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listGenreFull", "listAuthorFull"})
     */
    private $title;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"listGenreFull", "listAuthorFull"})
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Genre", inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listAuthorFull"})
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Editor", inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listGenreFull", "listAuthorFull"})
     */
    private $editor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author", inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listGenreFull"})
     */
    private $author;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"listGenreFull", "listAuthorFull"})
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"listGenreFull", "listAuthorFull"})
     */
    private $langue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEditor(): ?Editor
    {
        return $this->editor;
    }

    public function setEditor(?Editor $editor): self
    {
        $this->editor = $editor;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function __toString(){
        return (string) $this->title;
    }
}
