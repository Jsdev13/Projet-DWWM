<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\SeanceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;


#[IsGranted('ROLE_ADMIN')]
class AdminSeanceController extends AbstractController
{
    #[Route('/dashboard/admin/cours/nouveau', name: 'admin_seance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $seance = new Seance();
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $fileName = $this->uploadImage($imageFile, $slugger);

                if ($fileName === null) {
                    $this->addFlash('error', "L'image n'a pas pu être enregistrée, le cours a été créé sans image.");
                } else {
                    $seance->setImage($fileName);
                }
            }

            $em->persist($seance);
            $em->flush();

            $this->addFlash('success', 'Le cours « ' . $seance->getName() . ' » a bien été créé.');

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/seance/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Déplace le fichier uploadé dans public/images/seances et retourne son nom,
     * ou null si l'écriture a échoué.
     */
    private function uploadImage(UploadedFile $file, SluggerInterface $slugger): ?string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $slugger->slug($originalName)->lower();
        $fileName = $safeName . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getParameter('seance_images_directory'), $fileName);
        } catch (FileException) {
            return null;
        }

        return $fileName;
    }
}

