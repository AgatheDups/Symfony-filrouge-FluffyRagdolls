<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Form\AddAnnouncementType;
use App\Repository\AnnouncementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnnouncementController extends AbstractController
{
    #[Route('/announcement/{id}', name: 'app_announcement')]
    public function announcement(AnnouncementRepository $announcementRepository, int $id): Response
    {
        $announcements = $announcementRepository ->findByUserId($id);

        return $this->render('announcement/index.html.twig', [
            'announcements' => $announcements,
            'announcementId' => $id,
        ]);
    }

    #[Route('/announcement-create', name: 'app_announcement_create')]
    public function announcementCreate(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();

        $announcement = new Announcement();
        $form = $this->createForm(AddAnnouncementType::class, $announcement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user) {
                $announcement->setUser($user);
            }

            $entityManager->persist($announcement);
            $entityManager->flush();

            $id = $announcement->getId();
            return $this->redirect($this->generateUrl('app_announcement', ['id' => $id]));
        }
        return $this->render('announcement/index-create.html.twig', [
            'addAnnouncementForm' => $form->createView(),
        ]);
    }
}
