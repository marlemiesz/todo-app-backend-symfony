<?php

namespace App\Controller;

use App\Entity\TaskList;
use App\Repository\TaskListRepository;
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

    public function __construct(TaskListRepository $taskListRepository, EntityManagerInterface $entityManager)
    {
        $this->taskListRepository = $taskListRepository;
        $this->entityManager = $entityManager;
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
        $list = $this->taskListRepository->findOneBy(['id'=>$id]);
        
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
            $list->setBackgroundPath('/uploads/'.$filename);

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
}
