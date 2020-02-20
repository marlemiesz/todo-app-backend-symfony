<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Task
{
    use Timestamps;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isComplete = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TaskList", inversedBy="tasks")
     */
    private $list;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="task")
     */
    private $notes;

    /**
     * Task constructor.
     */
    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsComplete(): ?bool
    {
        return $this->isComplete;
    }

    /**
     * @param bool $isComplete
     * @return $this
     */
    public function setIsComplete(bool $isComplete): self
    {
        $this->isComplete = $isComplete;

        return $this;
    }

    /**
     * @return TaskList|null
     */
    public function getList(): ?TaskList
    {
        return $this->list;
    }

    /**
     * @param TaskList|null $list
     * @return $this
     */
    public function setList(?TaskList $list): self
    {
        $this->list = $list;

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    /**
     * @param Note $note
     * @return $this
     */
    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setTask($this);
        }

        return $this;
    }

    /**
     * @param Note $note
     * @return $this
     */
    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getTask() === $this) {
                $note->setTask(null);
            }
        }

        return $this;
    }
}
