<?php

namespace App\Serializer;

use App\Entity\Note;
use App\Entity\Task;
use App\Entity\TaskList;
use Symfony\Component\Routing\RouterInterface;

class CircularReferenceHandler
{
    private $router;

    /**
     * CircularReferenceHandler constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param $object
     * @return string
     */
    public function __invoke($object)
    {
        switch ($object) {
            case $object instanceof TaskList:
                return $this->router->generate('get_list', ['list' => $object->getId()]);

            case $object instanceof Task:
                return $this->router->generate('get_task', ['task' => $object->getId()]);

            case $object instanceof Note:
                return $this->router->generate('get_note', ['note' => $object->getId()]);
        }

        return $object->getId();
    }
}