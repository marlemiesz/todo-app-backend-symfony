<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskList;
use App\Repository\TaskListRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListController extends AbstractFOSRestController
{
    private $taskListRepository;
    private $entityManager;
    private $taskRepository;

    public function __construct(TaskListRepository $taskListRepository, EntityManagerInterface $entityManager, TaskRepository $taskRepository)
    {
        $this->taskListRepository = $taskListRepository;
        $this->entityManager = $entityManager;
        $this->taskRepository = $taskRepository;
    }

    public function getListsAction()
    {
        $data = $this->taskListRepository->findAll();
        return $this->view($data, Response::HTTP_OK);
    }

    public function getListAction(int $id)
    {
        $data = $this->taskListRepository->findOneBy(['id' => $id]);

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * @Rest\RequestParam(name="title", description="Title of the list", nullable=false)
     */
    public function postListsAction(ParamFetcher $paramFetcher)
    {
        $title = $paramFetcher->get('title');
        if ($title) {
            $list = new TaskList();

            $list->setTitle($title);

            $this->entityManager->persist($list);
            $this->entityManager->flush();

            return $this->view($list, Response::HTTP_OK);
        }

        return $this->view(['title' => 'This cannot be null'], Response::HTTP_BAD_REQUEST);
    }

    public function getListTasksAction(int $id)
    {
        $list = $this->taskListRepository->findOneBy(['id' => $id]);

        return $this->view($list->getTasks(), Response::HTTP_OK);
    }

    public function putListsAction()
    {
    }

    public function stateListsAction($id)
    {
    }

    /**
     * @Rest\FileParam(name="image", description="The background of the list", nullable=false, image=true)
     * @param int $id
     */
    public function backgroundListAction(Request $request, ParamFetcher $paramFetcher, $id)
    {
        $list = $this->taskListRepository->findOneBy(['id' => $id]);

        $currentBackground = $list->getBackground();
        if (!is_null($currentBackground)) {
            $filesystem = new Filesystem();
            $filesystem->remove(
                $this->getUploadDir() . $currentBackground
            );
        }
        $file  = $paramFetcher->get('image');
        if ($file) {
            $filename = md5(uniqid()) . '.' . $file->guessClientExtension();

            $file->move(
                $this->getUploadDir(),
                $filename
            );

            $list->setBackground($filename);
            $list->setBackgroundPath('/uploads/' . $filename);

            $this->entityManager->persist($list);
            $this->entityManager->flush();

            $data = $request->getUrlForPath(
                $list->getBackgroundPath()
            );

            return $this->view($data, Response::HTTP_OK);
        }
        return $this->view(['message' => 'Someting went wrong'], Response::HTTP_BAD_REQUEST);
    }

    private function getUploadDir()
    {
        return $this->getParameter('uploads_dir');
    }


    public function deleteListAction(int $id)
    {
        $list = $this->taskListRepository->findOneBy(['id' => $id]);

        $this->entityManager->remove($list);
        $this->entityManager->flush();

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Rest\RequestParam(name="title", description="The new title for the list", nullable=false)
     * @param int $id
     */
    public function patchListTitleAction(ParamFetcher $paramFetcher, int $id)
    {
        $list = $this->taskListRepository->findOneBy(['id' => $id]);

        $errors = [];

        $title = $paramFetcher->get('title');

        if ($list) {
            if (trim($title) !== '') {

                $list->setTitle($title);

                $this->entityManager->persist($list);
                $this->entityManager->flush();

                return $this->view(null, Response::HTTP_NO_CONTENT);
            }
            $errors[] = ['title' => 'This value cannot be empty'];
        }
        $errors[] = ['list' => 'List not found'];

        return $this->view($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\RequestParam(name="title", description="Title for the new task", nullable=false)
     */
    public function postListTaskAction(ParamFetcher $paramFetcher, int $id)
    {
        $list = $this->taskListRepository->findOneBy(['id' => $id]);

        if ($list) {
            $title = $paramFetcher->get('title');

            $task = new Task();
            $task->setTitle($title);
            $task->setList($list);

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            return $this->view($list->getTasks(), Response::HTTP_OK);
        }

        return $this->view(['message' => 'someting went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

}
