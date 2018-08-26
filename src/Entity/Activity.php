<?php

declare(strict_types=1);

/*
 * This file is part of the `idea` project.
 *
 * (c) Aula de Software Libre de la UCO <aulasoftwarelibre@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\RestBundle\Validator\Constraints\Regex;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
 * @ORM\Table()
 */
class Activity
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue("AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(length=255)
     * @Assert\Length(min="3", max="255")
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $occurredOn;

    /**
     * @var string
     * @ORM\Column(length=32)
     * @Assert\NotBlank()
     * @Regex("/\d{4}\/\d{4}/")
     */
    private $academicYear;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(min="1")
     */
    private $duration;

    /**
     * @var string
     * @ORM\Column(length=255, unique=true)
     * @Gedmo\Slug(fields={"title"}, unique=true, updatable=false)
     */
    private $slug;

    /**
     * @var Participation[]|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Participation", mappedBy="activity", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $participations;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * Activity constructor.
     */
    public function __construct(string $title, \DateTime $occurredOn, string $academicYear, int $duration)
    {
        $this->title = $title;
        $this->occurredOn = $occurredOn;
        $this->academicYear = $academicYear;
        $this->duration = $duration;
        $this->participations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getTitle() ?? '';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return Activity
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getOccurredOn(): \DateTime
    {
        return $this->occurredOn;
    }

    /**
     * @return Activity
     */
    public function setOccurredOn(\DateTime $occurredOn): self
    {
        $this->occurredOn = $occurredOn;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @return Activity
     */
    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Participation[]|Collection
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    /**
     * @return Activity
     */
    public function addParticipation(Participation $participant): self
    {
        $participant->setActivity($this);
        $this->participations[] = $participant;

        return $this;
    }

    /**
     * @param Participation $participant
     */
    public function removeParticipation(Participation $participant): void
    {
        $this->participations->removeElement($participant);
    }

    /**
     * @return string
     */
    public function getAcademicYear(): string
    {
        return $this->academicYear;
    }

    /**
     * @return Activity
     */
    public function setAcademicYear(string $academicYear): self
    {
        $this->academicYear = $academicYear;

        return $this;
    }
}
