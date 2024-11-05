<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Form\AddAnnouncementType;
use App\Repository\AnnouncementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnnouncementController extends AbstractController
{
    #[Route('/announcement', name: 'app_announcement')]
    public function announcement(AnnouncementRepository $announcementRepository, int $id): Response
    {
        $announcements = $announcementRepository ->findAnnouncementsByUserID($id);

        return $this->render('announcement/index.html.twig', [
            'announcements' => $announcements,
            'announcementId' => $id,
        ]);
    }

    #[Route('/announcement-create', name: 'app_announcement_create')]
    public function announcementCreate(Request $request): Response
    {
        $announcement = new Announcement();
        $form = $this->createForm(AddAnnouncementType::class, $announcement);
        $form->handleRequest($request);
        return $this->render('announcement/index-create.html.twig', [
            'addAnnouncementForm' => $form,
        ]);
    }
}
