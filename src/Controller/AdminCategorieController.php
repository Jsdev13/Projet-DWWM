<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/dashboard/admin/categories')]
class AdminCategorieController extends AbstractController
{
    #[Route('', name: 'admin_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('admin/categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/nouvelle', name: 'admin_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();
            $this->addFlash('success', 'La catégorie « ' . $categorie->getName() . ' » a bien été ajoutée.');

            return $this->redirectToRoute('admin_categorie_index');
        }

        return $this->render('admin/categorie/form.html.twig', [
            'form' => $form,
            'categorie' => $categorie,
            'is_edit' => false,
        ]);
    }

    #[Route('/{id}/modifier', name: 'admin_categorie_edit', requirements: ['id' => '\\d+'], methods: ['GET', 'POST'])]
    public function edit(Categorie $categorie, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'La catégorie « ' . $categorie->getName() . ' » a bien été modifiée.');

            return $this->redirectToRoute('admin_categorie_index');
        }

        return $this->render('admin/categorie/form.html.twig', [
            'form' => $form,
            'categorie' => $categorie,
            'is_edit' => true,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'admin_categorie_delete', requirements: ['id' => '\\d+'], methods: ['POST'])]
    public function delete(Categorie $categorie, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete-categorie-' . $categorie->getId(), (string) $request->request->get('_token'))) {
            $this->addFlash('danger', 'La suppression a échoué : jeton de sécurité invalide.');

            return $this->redirectToRoute('admin_categorie_index');
        }

        $name = $categorie->getName();
        foreach ($categorie->getSeances() as $seance) {
            $seance->setCategorie(null);
        }

        $entityManager->remove($categorie);
        $entityManager->flush();
        $this->addFlash('success', 'La catégorie « ' . $name . ' » a bien été supprimée.');

        return $this->redirectToRoute('admin_categorie_index');
    }
}
