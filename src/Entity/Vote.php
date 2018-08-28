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

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Vote.
 *
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 * @ORM\Table()
 */
class Vote
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Idea
     * @ORM\ManyToOne(targetEntity="App\Entity\Idea", inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idea;

    /**
     * @return Vote
     */
    public static function create(Idea $idea, User $user): self
    {
        $vote = new self();
        $vote->setIdea($idea);
        $vote->setUser($user);

        return $vote;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        /** @var User $user */
        $user = $this->getUser();

        return "{$user->getFullname()} [{$user->getUsername()}]";
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Vote
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Idea
     */
    public function getIdea(): Idea
    {
        return $this->idea;
    }

    /**
     * @return Vote
     */
    public function setIdea(Idea $idea): self
    {
        $this->idea = $idea;

        return $this;
    }
}
