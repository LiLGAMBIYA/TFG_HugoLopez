<?php

namespace App\Controller;

use App\Entity\Cita;
use App\Entity\Usuario;
use App\Entity\Vehiculo;
use App\Form\CitaPublicType;
use App\Repository\ServicioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_root')]
    public function root(): Response
    {
        return $this->redirectToRoute('app_home');
    }

    #[Route('/home', name: 'app_home')]
    public function index(ServicioRepository $servicioRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'servicios' => $servicioRepository->findAll(),
        ]);
    }

    #[Route('/reservar', name: 'app_home_reservar', methods: ['GET', 'POST'])]
    public function reservar(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cita = new Cita();
        $form = $this->createForm(CitaPublicType::class, $cita);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matricula = strtoupper(trim($form->get('matricula')->getData()));
            $marca     = $form->get('marca')->getData();
            $modelo    = $form->get('modelo')->getData();
            $usuario   = $this->getUser();

            $vehiculo = $entityManager->getRepository(Vehiculo::class)->findOneBy(['matricula' => $matricula]);
            if (!$vehiculo) {
                $vehiculo = new Vehiculo();
                $vehiculo->setMatricula($matricula);
                $vehiculo->setMarca($marca);
                $vehiculo->setModelo($modelo);
                $entityManager->persist($vehiculo);
            }

            if ($usuario instanceof Usuario) {
                $cita->setCliente($usuario);

                if (!$vehiculo->getPropietario()) {
                    $vehiculo->setPropietario($usuario);
                }
            }

            $cita->setVehiculo($vehiculo);

            $entityManager->persist($cita);
            $entityManager->flush();

            $this->addFlash('success', '¡Su cita ha sido solicitada correctamente! Nos pondremos en contacto con usted en breve.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/reservar.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
