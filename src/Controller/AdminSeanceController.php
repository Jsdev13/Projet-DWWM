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
            'seance' => $seance,
            'is_edit' => false,
        ]);
    }

    #[Route('/dashboard/admin/cours/{id}/modifier', name: 'admin_seance_edit', requirements: ['id' => '\\d+'], methods: ['GET', 'POST'])]
    public function edit(Seance $seance, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $fileName = $this->uploadImage($imageFile, $slugger);

                if ($fileName === null) {
                    $this->addFlash('error', "L'image n'a pas pu être enregistrée. L'ancienne image a été conservée.");
                } else {
                    $seance->setImage($fileName);
                }
            }

            $em->flush();
            $this->addFlash('success', 'Le cours « ' . $seance->getName() . ' » a bien été modifié.');

            return $this->redirectToRoute('app_seance_index');
        }

        return $this->render('admin/seance/new.html.twig', [
            'form' => $form,
            'seance' => $seance,
            'is_edit' => true,
        ], new Response(status: $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
    }

    #[Route('/dashboard/admin/cours/{id}/supprimer', name: 'admin_seance_delete', requirements: ['id' => '\\d+'], methods: ['POST'])]
    public function delete(Seance $seance, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete-seance-' . $seance->getId(), (string) $request->request->get('_token'))) {
            $this->addFlash('danger', 'La suppression a été annulée : jeton de sécurité invalide.');

            return $this->redirectToRoute('app_seance_index');
        }

        $name = $seance->getName();

        foreach ($seance->getReservations() as $reservation) {
            $em->remove($reservation);
        }

        foreach ($seance->getNotes() as $note) {
            $em->remove($note);
        }

        $em->remove($seance);
        $em->flush();

        $this->addFlash('success', 'Le cours « ' . $name . ' » a bien été supprimé.');

        return $this->redirectToRoute('app_seance_index');
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
