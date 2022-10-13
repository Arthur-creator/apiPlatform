<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\JokeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Blameable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

#[ApiResource]
#[Get(
    normalizationContext: ['groups' => ['joke_get']]
)]
#[GetCollection(
    normalizationContext: ['groups' => ['joke_getc']]
)]
#[Post(
    denormalizationContext: ['groups' => ['joke_post']],
    security: 'is_granted("ROLE_MODERATOR")'
)]
#[Patch(
    security: 'is_granted("ROLE_ADMIN") or object.getAuthor() == user'
)]
#[ORM\Entity(repositoryClass: JokeRepository::class)]
#[UniqueEntity('text')]
class Joke
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['joke_get', 'joke_getc','joke_post'])]
    #[NotBlank]
    #[NotNull]
    #[Type('string', message: 'The value {{ value }} is not a valid {{ type }}.')]
    #[Length(max : 255)]
    private ?string $text = null;

    #[Groups(['joke_get', 'joke_getc','joke_post'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $answer = null;

    #[Groups(['joke_get', 'joke_getc','joke_post'])]
    #[ORM\OneToMany(mappedBy: 'joke', targetEntity: Comment::class)]
    private Collection $comment;

    #[Groups(['joke_get', 'joke_getc','joke_post'])]
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'jokes')]
    private Collection $category;

    #[Groups(['joke_get', 'joke_getc','joke_post'])]
    #[ORM\OneToMany(mappedBy: 'joke', targetEntity: Rate::class, cascade: ['persist'])]
    private Collection $rate;

    #[Groups(['joke_get', 'joke_getc','joke_post'])]
    #[Blameable(on: 'create')]
    #[ORM\ManyToOne(inversedBy: 'jokes')]
    private ?User $Author = null;

    public function __construct()
    {
        $this->comment = new ArrayCollection();
        $this->category = new ArrayCollection();
        $this->rate = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setJoke($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getJoke() === $this) {
                $comment->setJoke(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Rate>
     */
    public function getRate(): Collection
    {
        return $this->rate;
    }

    public function addRate(Rate $rate): self
    {
        if (!$this->rate->contains($rate)) {
            $this->rate[] = $rate;
            $rate->setJoke($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rate->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getJoke() === $this) {
                $rate->setJoke(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->Author;
    }

    public function setAuthor(?User $Author): self
    {
        $this->Author = $Author;

        return $this;
    }
}
