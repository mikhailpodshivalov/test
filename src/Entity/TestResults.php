<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TestResultsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestResultsRepository::class)]
class TestResults
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    /**
     * @var Collection<int, Answer>
     */
    #[ORM\ManyToMany(targetEntity: Answer::class)]
    private Collection $answers;

    #[ORM\Column(length: 32)]
    private ?string $name = null;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setName(?string $name): TestResults
    {
        $this->name = $name;

        return $this;
    }

    public function setQuestion(?Question $question): TestResults
    {
        $this->question = $question;

        return $this;
    }

    public function isQuestionRight(): ?bool
    {
        return empty(array_filter($this->answers->toArray(), fn(Answer $answer) => !$answer->isQuestionRight()));
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): static
    {
        $this->answers->removeElement($answer);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
