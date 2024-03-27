<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\Serializer\Attribute\Groups;


#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list_films','detail_film'])]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Groups(['list_films','detail_film'])]
    private ?string $titre = null;

    #[ORM\Column]
    #[Groups(['list_films','detail_film'])]
    private ?int $dureeMin = null;

    #[ORM\OneToMany(targetEntity: Seance::class, mappedBy: 'film')]
    #[Groups(['detail_film'])]
    private \Doctrine\Common\Collections\Collection $seances;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
    }
//    #[ORM\OneToMany(targetEntity: Seance::class, mappedBy: 'film')]
//    #[Groups(['show_film'])]
//    private Collection $seances;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDureeMin(): ?int
    {
        return $this->dureeMin;
    }

    public function setDureeMin(int $dureeMin): static
    {
        $this->dureeMin = $dureeMin;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, Seance>
     */
    public function getSeances(): \Doctrine\Common\Collections\Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): static
    {
        if (!$this->seances->contains($seance)) {
            $this->seances->add($seance);
            $seance->setFilm($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getFilm() === $this) {
                $seance->setFilm(null);
            }
        }

        return $this;
    }
}
