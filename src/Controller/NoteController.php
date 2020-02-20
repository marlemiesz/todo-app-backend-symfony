<?php

namespace App\Controller;

use App\Entity\Note;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractFOSRestController
{
    private $noteRepository;
    private $entityManager;


    /**
     * NoteController constructor.
     * @param NoteRepository $noteRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(NoteRepository $noteRepository, EntityManagerInterface $entityManager)
    {
        $this->noteRepository = $noteRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Note $note
     * @return \FOS\RestBundle\View\View
     */
    public function deleteNoteAction(Note $note)
    {
        if($note){

            $this->entityManager->remove($note);
            $this->entityManager->flush();

            return $this->view(null, Response::HTTP_OK);
        }

        return $this->view(['message' => 'someting went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);

    }

    /**
     * @param Note $note
     * @return \FOS\RestBundle\View\View
     */
    public function getNoteAction(Note $note){
        return $this->view($note,Response::HTTP_OK);
    }
}
