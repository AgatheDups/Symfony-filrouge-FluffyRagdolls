<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Entity\Photo;
use App\Form\AddAnnouncementType;
use App\Repository\AnnouncementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnnouncementController extends AbstractController
{
    #[Route('/announcement', name: 'app_announcement')]
    public function announcement(AnnouncementRepository $announcementRepository): Response
    {
        $announcements = $announcementRepository ->findAll();

        return $this->render('announcement/index.html.twig', [
            'announcements' => $announcements,
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

            $photoFiles = $form->get('photos')->getData();
            foreach ($photoFiles as $photoFile) {
                if ($photoFile) {
                    $allowedExtensions = ['jpg', 'png', 'jpeg'];
                    $fileExtension = $photoFile->guessExtension();
    
                    if (in_array($fileExtension, $allowedExtensions)) {
                        $newFilename = uniqid() . '.' . $fileExtension;
    
                        try {
                            $photoFile->move(
                                $this->getParameter('uploads_directory'), // Configurez ce paramètre dans services.yaml
                                $newFilename
                            );
    
                            // Créer une nouvelle entité Photo et la lier à l'annonce
                            $photo = new Photo();
                            $photo->setUrl($newFilename);
                            $photo->setAnnouncement($announcement);
                            $entityManager->persist($photo);
    
                        } catch (FileException $e) {
                            $this->addFlash('error', 'Erreur lors du téléchargement de l\'image');
                        }
                    } else {
                        $this->addFlash('error', 'Format de fichier non autorisé');
                    }
                }
            }

            $entityManager->persist($announcement);
            $entityManager->flush();

            // $id = $announcement->getId();
            return $this->redirectToRoute('app_announcement');
        }
        return $this->render('announcement/index-create.html.twig', [
            'addAnnouncementForm' => $form->createView(),
        ]);
    }
}
