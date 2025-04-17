<?php

namespace App\Controller;

use App\Entity\Dumpling;
use App\Form\DumplingType;
use App\Repository\DumplingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DumplingController extends AbstractController
{
    #[Route('/dumplings', name: 'app_dumplings')]
    public function index(DumplingRepository $dumplingRepository): Response
    {

        $dumplings = $dumplingRepository->findAll();
        dump($dumplings);

        return $this->render('dumpling/index.html.twig', [
            'dumplings' => $dumplings,
        ]);
    }

    #[Route('/dumpling/{id}', name: 'app_dumpling', priority: -1)]
    public function show(Dumpling $dumpling): Response
    {
           if(!$dumpling)
           {
               return $this->redirectToRoute('app_dumplings');
           }

        return $this->render('dumpling/show.html.twig',[
            'dumpling' => $dumpling
        ]);
    }

    #[Route("/dumpling/create", name: 'create_dumpling', methods: ['GET','POST'])]
    public function create(Request $request, EntityManagerInterface $manager)  : Response
    {
        $dumpling = new Dumpling();


        $dumplingForm = $this->createForm(DumplingType::class, $dumpling);

        $dumplingForm->handleRequest($request);

            if($dumplingForm->isSubmitted())
            {

                $manager->persist($dumpling);
                $manager->flush();
                return $this->redirectToRoute('app_dumplings');

            }

        return $this->render('dumpling/create.html.twig', [
            "formulaire" => $dumplingForm->createView(),
        ]);
    }

    #[Route("/dumpling/delete/{id}", name: 'delete_dumpling')]
    public function delete(Dumpling $dumpling, EntityManagerInterface $manager): Response
    {
        if($dumpling)
        {

            $manager->remove($dumpling);
            $manager->flush();

        }

        return $this->redirectToRoute('app_dumplings');
    }

#[Route('/dumpling/edit/{id}', name: 'edit_dumpling', methods: ['GET', 'POST'])]
    public function edit(Dumpling $dumpling, Request $request, EntityManagerInterface $manager) : Response
    {



        if(!$dumpling)
        {
            return $this->redirectToRoute('app_dumplings');
        }

        $dumplingForm = $this->createForm(DumplingType::class, $dumpling);
        $dumplingForm->handleRequest($request);
        if($dumplingForm->isSubmitted())
        {
            $manager->persist($dumpling);
            $manager->flush();
            return $this->redirectToRoute('app_dumplings');
        }



        return $this->render('dumpling/edit.html.twig', [
            'formulaire' => $dumplingForm->createView(),
        ]);
    }


    #[Route("/dumpling/plutotcreate", name: 'plutot_create', methods: ['GET','POST'])]
    #[Route("/dumpling/plutotedit/{id}", name: 'plutot_edit', methods: ['GET','POST'])]
    public function createOrEdit(Dumpling $dumpling = null, Request $request, EntityManagerInterface $manager)  : Response
    {
        $editMode = true;
        if(!$dumpling){
            $editMode = false;
            $dumpling = new Dumpling();
        }



        $dumplingForm = $this->createForm(DumplingType::class, $dumpling);

        $dumplingForm->handleRequest($request);

        if($dumplingForm->isSubmitted())
        {

            $manager->persist($dumpling);
            $manager->flush();
            return $this->redirectToRoute('app_dumplings');

        }

        return $this->render('dumpling/createOrEdit.html.twig', [
            "formulaire" => $dumplingForm->createView(),
            "editMode" => $editMode,
        ]);
    }



}
