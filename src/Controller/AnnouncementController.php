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
use Symfony\Component\HttpFoundation\RedirectResponse;
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
            $defaultPhoto = 'default.jpg';
            if(empty($photoFile)){
                $photo=new Photo();
                $photo->setUrl($defaultPhoto);
                $photo->setAnnouncement($announcement);
                $entityManager->persist($photo);
            }else{
                foreach ($photoFiles as $photoFile) {
                    if ($photoFile) {
                        $allowedExtensions = ['jpg', 'png', 'jpeg'];
                        $fileExtension = $photoFile->guessExtension();
        
                        if (in_array($fileExtension, $allowedExtensions)) {
                            $newFilename = uniqid() . '.' . $fileExtension;
        
                            try {
                                $photoFile->move(
                                    $this->getParameter('uploads_directory'),
                                    $newFilename
                                );
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
            }
            $entityManager->persist($announcement);
            $entityManager->flush();

            return $this->redirectToRoute('app_announcement');
        }
        return $this->render('announcement/index-create.html.twig', [
            'addAnnouncementForm' => $form->createView(),
        ]);
    }

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/announcement/{id}/delete', name: 'announcement_delete', methods:"POST")]
    public function delete(Announcement $announcement, Request $request): RedirectResponse
    {
        if ($announcement->getUser() === $this->getUser()) {
            foreach ($announcement->getPhotos() as $photo) {
                $this->entityManager->remove($photo);
            }
            $this->entityManager->remove($announcement);
            $this->entityManager->flush();

            $this->addFlash('success', 'Annonce supprimée avec succès.');
        }

        // Rediriger vers la liste des annonces
        return $this->redirectToRoute('app_announcement');
    }

}
