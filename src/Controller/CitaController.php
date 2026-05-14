<?php

namespace App\Controller;

use App\Entity\Cita;
use App\Form\CitaType;
use App\Repository\CitaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/cita')]
#[IsGranted('ROLE_USER')]
final class CitaController extends AbstractController
{
    #[Route(name: 'app_cita_index', methods: ['GET'])]
    public function index(CitaRepository $citaRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $citas = $citaRepository->findAll();
        } else {
            $citas = $citaRepository->findBy(['cliente' => $this->getUser()]);
        }

        return $this->render('cita/index.html.twig', [
            'citas' => $citas,
        ]);
    }

    #[Route('/{id}/estado', name: 'app_cita_change_estado', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function changeEstado(Request $request, Cita $citum, EntityManagerInterface $entityManager): Response
    {
        $estadosValidos = ['Pendiente', 'Confirmada', 'En proceso', 'Finalizada', 'Cancelada'];
        $nuevoEstado = $request->request->get('estado');

        if ($this->isCsrfTokenValid('estado'.$citum->getId(), $request->request->get('_token'))
            && in_array($nuevoEstado, $estadosValidos)
        ) {
            $citum->setEstado($nuevoEstado);
            $entityManager->flush();
            $this->addFlash('success', 'Estado de la cita actualizado a: ' . $nuevoEstado);
        } else {
            $this->addFlash('danger', 'No se pudo actualizar el estado. Acción no válida.');
        }

        return $this->redirectToRoute('app_cita_show', ['id' => $citum->getId()]);
    }

    #[Route('/new', name: 'app_cita_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $citum = new Cita();
        
        if (!$this->isGranted('ROLE_ADMIN')) {
            $citum->setCliente($this->getUser());
        }

        $form = $this->createForm(CitaType::class, $citum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->isGranted('ROLE_ADMIN')) {
                $citum->setCliente($this->getUser());
            }

            $entityManager->persist($citum);
            $entityManager->flush();

            return $this->redirectToRoute('app_cita_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cita/new.html.twig', [
            'citum' => $citum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cita_show', methods: ['GET'])]
    public function show(Cita $citum): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $citum->getCliente() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('cita/show.html.twig', [
            'citum' => $citum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cita_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cita $citum, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $citum->getCliente() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(CitaType::class, $citum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cita_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cita/edit.html.twig', [
            'citum' => $citum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cita_delete', methods: ['POST'])]
    public function delete(Request $request, Cita $citum, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $citum->getCliente() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$citum->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($citum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cita_index', [], Response::HTTP_SEE_OTHER);
    }
}
