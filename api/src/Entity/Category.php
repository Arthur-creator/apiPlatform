<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(mercure: true)]
#[Get(
    normalizationContext: ['groups' => ['category_get']]
)]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[Groups(['joke_get', 'joke_getc'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['category_get'])]
    #[ORM\ManyToMany(targetEntity: Joke::class, mappedBy: 'category')]
    private Collection $jokes;

    public function __construct()
    {
        $this->jokes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Joke>
     */
    public function getJokes(): Collection
    {
        return $this->jokes;
    }

    public function addJoke(Joke $joke): self
    {
        if (!$this->jokes->contains($joke)) {
            $this->jokes[] = $joke;
            $joke->addCategory($this);
        }

        return $this;
    }

    public function removeJoke(Joke $joke): self
    {
        if ($this->jokes->removeElement($joke)) {
            $joke->removeCategory($this);
        }

        return $this;
    }
}
