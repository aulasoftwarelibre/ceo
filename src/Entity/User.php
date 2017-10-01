<?php

/*
 * This file is part of the ceo project.
 *
 * (c) Aula de Software Libre de la UCO <aulasoftwarelibre@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\RestBundle\Validator\Constraints\Regex;
use Sonata\UserBundle\Entity\BaseUser;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class User.
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 * @Vich\Uploadable()
 */
class User extends BaseUser
{
    const STUDENT = 'student';
    const STAFF = 'staff';
    const TEACHER = 'teacher';
    const EXTERNAL = 'external';

    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(length=32, nullable=true)
     * @Assert\Choice(callback="getCollectives")
     * @Assert\NotBlank()
     */
    protected $collective;

    /**
     * @var Degree|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Degree")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $degree;

    /**
     * @var string|null
     * @ORM\Column(length=4, nullable=true)
     * @Regex("/\d{4}/")
     */
    private $year;

    /**
     * @var Idea[]
     * @ORM\OneToMany(targetEntity="App\Entity\Idea", mappedBy="owner", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $ideas;

    /**
     * @var Vote[]
     * @ORM\OneToMany(targetEntity="App\Entity\Vote", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $votes;

    /**
     * @var File
     * @Vich\UploadableField(mapping="avatars", fileNameProperty="image.name", size="image.size", mimeType="image.mimeType", originalName="image.originalName")
     */
    private $imageFile;

    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     *
     * @var string
     */
    private $image;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->ideas = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->image = new EmbeddedFile();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function equalsTo(User $user)
    {
        return $this->getId() === $user->getId();
    }

    /**
     * @return array
     */
    public static function getCollectives(): array
    {
        return [
            'Estudiante' => static::STUDENT,
            'PDI' => static::TEACHER,
            'PAS' => static::STAFF,
            'Otros' => static::EXTERNAL,
        ];
    }

    /**
     * @return string|null
     */
    public function getCollective(): ?string
    {
        return $this->collective;
    }

    /**
     * @param string|null $collective
     *
     * @return User
     */
    public function setCollective(?string $collective): User
    {
        $this->collective = $collective;

        return $this;
    }

    /**
     * @return Idea[]
     */
    public function getIdeas(): array
    {
        return $this->ideas;
    }

    /**
     * @param Idea $idea
     *
     * @return User
     */
    public function addIdea(Idea $idea): self
    {
        $this->ideas[] = $idea;

        return $this;
    }

    /**
     * @param Idea $idea
     */
    public function removeIdea(Idea $idea)
    {
        $this->ideas->removeElement($idea);
    }

    /**
     * @return Vote[]
     */
    public function getVotes(): array
    {
        return $this->votes;
    }

    /**
     * @param Vote $vote
     *
     * @return User
     */
    public function addVote(Vote $vote): self
    {
        $vote->setUser($this);
        $this->votes[] = $vote;

        return $this;
    }

    /**
     * @param Vote $vote
     */
    public function removeVote(Vote $vote)
    {
        $this->votes->removeElement($vote);
    }

    /**
     * @return Degree|null
     */
    public function getDegree(): ?Degree
    {
        return $this->degree;
    }

    /**
     * @param Degree|null $degree
     *
     * @return User
     */
    public function setDegree(?Degree $degree): User
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getYear(): ?string
    {
        return $this->year;
    }

    /**
     * @param string|null $year
     *
     * @return User
     */
    public function setYear(?string $year): User
    {
        $this->year = $year;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param EmbeddedFile $image
     *
     * @return User
     */
    public function setImage(EmbeddedFile $image): User
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return EmbeddedFile
     */
    public function getImage(): EmbeddedFile
    {
        return $this->image;
    }
}
