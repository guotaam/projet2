<?php

namespace App\Controller;
use App\Form\VehiculeType;
use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VehiculeController extends AbstractController
{
    #[Route('/vehicule', name: 'app_vehicule')]
    public function index(VehiculeRepository $repo): Response
    {
        $vehicules=$repo->findAll();
        return $this->render('vehicule/index.html.twig', [
           
            'vehicules'=>$vehicules
        ]);
    }
    #[Route('/vehicule/show', name: 'vehicule_show')]
    public function show(VehiculeRepository $repo): Response
    {
        $vehicules=$repo->findAll();
        return $this->render('vehicule/liste.html.twig', [
           
            'vehicules'=>$vehicules
        ]);
    }

    #[Route('/', name:'home')]
    public function home()
    {
    return $this->render('vehicule/home.html.twig');
    }

    #[Route("/vehicule/edit/{id}", name:'vehicule_edit')]
    #[Route('/vehicule/new', name:"vehicule_create")]
    public function form(Request $request, EntityManagerInterface $manager, Vehicule $vehicule = null)
    {   
        if(!$vehicule)
        {
            $vehicule = new Vehicule;
          
        }
        

        $form = $this->createForm(VehiculeType::class, $vehicule);
        
        //dd($request);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() )
               {
                  
                   $manager->persist($vehicule);
                   $manager->flush();
                  return $this->redirectToRoute('home',[
                       'id' => $vehicule->getId()
                   ]);
                }
        return $this->render('vehicule/form.html.twig', [
            'formVehicule' => $form->createView(),
            'editMode' => $vehicule->getId() !== NULL
        ]);
    }
    #[Route('/vehicule/delete/{id}', name: 'vehicule_delete')]
    public function delete ($id, EntityManagerInterface $manager, VehiculeRepository $repo)
    {
        $vehicule=$repo->find($id);

        $manager->remove($vehicule); 
        $manager->flush(); 

        return $this->redirectToRoute('app_vehicule'); //redirection vers la liste des articles
    }


    }










