<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use App\Form\ChampSearchType;
use App\Entity\PersonSearch;
use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/personne")
 */
class PersonneController extends AbstractController
{
    /**
     * @Route("/", name="personne_index", methods={"GET"})
     */
    public function index(PersonneRepository $personneRepository, Request $request): Response
    {
        $search = new PersonSearch();
        // $pers = $personneRepository->findOneBy([
        //     'nom'=>'LY',
        //     'prenom'=>'Yahya'
        // ]);
        $pers = $personneRepository->findAll();
        
        
        //dump($value->getNom());
        // $nom = $pers['nom'];
        // $prenom =  $pers['prenom'];  
        
        //var_dump($id);die();
        $form = $this->createForm(ChampSearchType::class,$search); 
        $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    foreach($pers as $value)
                     {
                         //dump($value->getPrenom());die();
                        if ($search->getNom() == $value->getNom() && $search->getPrenom() == $value->getPrenom()) {
                            return $this->render('personne/index.html.twig', [
                            'personnes' => $personneRepository->findBy([
                                'nom'=>$value->getNom(),
                                'prenom'=>$value->getPrenom()]),
                            'form' => $form->createView()
                            ]);

                     }    
                 }if ($search->getNom() != $value->getNom() && $search->getPrenom() != $value->getPrenom()) {
                     return new response('Erreur !!!');
                 }

                           
        } return $this->render('personne/index.html.twig', [ 'personnes' => $personneRepository->findAll(),
            'form' => $form->createView()
        ]); }

    /**
     * @Route("/new", name="personne_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $personne = new Personne();
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($personne);
            $entityManager->flush();

            return $this->redirectToRoute('personne_index');
        }

        return $this->render('personne/new.html.twig', [
            'personne' => $personne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="personne_show", methods={"GET"})
     */
    public function show(Personne $personne): Response
    {
        return $this->render('personne/show.html.twig', [
            'personne' => $personne,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="personne_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Personne $personne): Response
    {
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('personne_index');
        }

        return $this->render('personne/edit.html.twig', [
            'personne' => $personne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="personne_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Personne $personne): Response
    {
        if ($this->isCsrfTokenValid('delete'.$personne->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($personne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('personne_index');
    }
    
}
