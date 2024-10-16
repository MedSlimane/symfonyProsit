<?php

namespace App\Controller;

use App\Entity\Aeroport;
use App\Form\AeroportType;
use App\Repository\AeroportRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AeroportController extends AbstractController
{
    #[Route('/aeroport', name: 'app_aeroport')]
    public function index(): Response
    {
        return $this->render('aeroport/index.html.twig', [
            'controller_name' => 'AeroportController',
        ]);
    }
    #[Route('/aeroport/show', name : 'show_aero')]
    public function show(AeroportRepository $aeroportRepository): Response
    {
        $list = $aeroportRepository->findAll();
        return $this->render('aeroport/show.html.twig', [
            'aeroports' => $list,
        ]);
    }
    #[Route('/aeroport/add', name: 'aeroport_new')]
    public function new(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $aeroport = new Aeroport();

        $form = $this->createForm(AeroportType::class, $aeroport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $e = $managerRegistry->getManager();
            $e->persist($aeroport);
            $e->flush();
            return $this->redirectToRoute('show_aero');


        }

        return $this->render('aeroport/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/aeroport/edit/{id}', name: 'aeroport_edit')]
    public function edit(Request $request, ManagerRegistry $managerRegistry, Aeroport $aeroport): Response
    {

        $form = $this->createForm(AeroportType::class, $aeroport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $e = $managerRegistry->getManager();
            $e->persist($aeroport);
            $e->flush();
            return $this->redirectToRoute('show_aero');


        }

        return $this->render('aeroport/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
