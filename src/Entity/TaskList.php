<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskListRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class TaskList
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
     * @ORM\Column(type="string", options={"default"="background.png"}, length=255, nullable=true)
     */
    private $background;

    /**
     * @ORM\Column(type="string", options={"default"="background.png"}, length=255, nullable=true)
     */
    private $backgroundPath;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="list", cascade={"REMOVE"})
     */
    private $tasks;

    /**
     * TaskList constructor.
     */
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
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
     * @return string|null
     */
    public function getBackground(): ?string
    {
        return $this->background;
    }

    /**
     * @param string|null $background
     * @return $this
     */
    public function setBackground(?string $background): self
    {
        $this->background = $background;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBackgroundPath(): ?string
    {
        return $this->backgroundPath;
    }

    /**
     * @param string|null $backgroundPath
     * @return $this
     */
    public function setBackgroundPath(?string $backgroundPath): self
    {
        $this->backgroundPath = $backgroundPath;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * @param Task $task
     * @return $this
     */
    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setList($this);
        }

        return $this;
    }

    /**
     * @param Task $task
     * @return $this
     */
    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getList() === $this) {
                $task->setList(null);
            }
        }

        return $this;
    }
}
