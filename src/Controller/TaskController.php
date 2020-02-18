<?php

namespace App\Controller;

use App\Repository\TaskListRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

class TaskController extends AbstractFOSRestController
{
    private $taskListRepository;
    private $entityManager;
    
    public function __construct(TaskListRepository $taskListRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->taskListRepository = $taskListRepository;

    }

    /**
     * @Rest\RequestParam(name="title", description="Title for the new task", nullable=false)
     */
    public function deleteTaskAction(ParamFetcher $paramFetcher, int $id)
    {
        $task = $this->taskRepository->findOneBy(['id' => $id]);

        if ($task) {

            $this->entityManager->remove($task);
            $this->entityManager->flush();

            return $this->view(null, Response::HTTP_NO_CONTENT);
        }

        return $this->view(['message' => 'someting went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Rest\RequestParam(name="title", description="Title for the new task", nullable=false)
     */
    public function statusTaskAction(ParamFetcher $paramFetcher, int $id)
    {
        $task = $this->taskRepository->findOneBy(['id' => $id]);

        if ($task) {

            $this->entityManager->remove($task);
            $this->entityManager->flush();

            return $this->view(null, Response::HTTP_NO_CONTENT);
        }

        return $this->view(['message' => 'someting went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
