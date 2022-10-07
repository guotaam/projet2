<?php

namespace App\Controller;
use App\Entity\Membre;
use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Repository\MembreRepository;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    #[Route('/admin/vehicules', name: 'admin_vehicules')]

    public function adminVehicule(VehiculeRepository $repo,EntityManagerInterface $manager)
    {

    $colonnes = $manager->getClassMetadata(Vehicule::class)->getFieldNames();
    //dd($colonnes);
    $vehicules = $repo->findAll();
     return $this->render('admin/admin_vehicules.html.twig',[
    'vehicules'=>$vehicules,
    'colonnes'=>$colonnes
]);
    }
    #[Route('/admin/vehicule/new', name: 'admin_new_vehicules')]
    #[Route('/admin/vehicule/edit/{id}', name: 'admin_edit_vehicules')]
public function formVehicule(Request $globals, EntityManagerInterface $manager, Vehicule $Vehicule = null)
{
    if($Vehicule == null) {
        $vehicule= new Vehicule; 
        $vehicule->setdateenregistrement(new \DateTime); 
    }

    $form=$this->createForm(VehiculeType::class, $vehicule); 
    $form->handleRequest($globals);

    if($form->isSubmitted() && $form->isValid()) {
        $manager->persist($vehicule); 
        $manager->flush();
        $this->addFlash('succes',"Vehicule a bien été enregistré");

        return $this->redirectToRoute('admin_vehicule', [
           
        ]);
       
    }
    return $this->renderForm('admin/admin_vehicules.html.twig', [
        'formVehicule'=> $form,
        'editMode'=> $vehicule->getId() !== null
    ]);
   

}
#[Route('/admin/vehicule/delete/{id}', name: 'admin_delete_vehicules')]
public function delete ($id, EntityManagerInterface $manager, VehiculeRepository $repo)
{
    $vehicule=$repo->find($id);

    $manager->remove($vehicule); 
    $manager->flush(); 
    $this->addFlash('warning', "vehicule a bien été supprimé !");

    return $this->redirectToRoute('admin_vehicules');
}

#[Route('/admin/membre', name: 'admin_membres')]
public function showmembre(VehiculeRepository $repo,EntityManagerInterface $manager)
{
    $membres = $repo->findAll();
    return $this->render('admin/admin_membres.html.twig',[
   'membres'=>$membres
 
]);



}
#[Route('/admin/membre/new', name: 'admin_new_membre')]
#[Route('/admin/membre/edit/{id}', name: 'admin_edit_membres')]
public function formMembre(Request $globals, EntityManagerInterface $manager,  $membre = null)
{
if($membre == null) {
    $membre= new Membre; 
   //$membre->setdateenregistrement(new \DateTime); 
}

$form=$this->createForm(MembreType::class, $membre); 
$form->handleRequest($globals);

if($form->isSubmitted() && $form->isValid()) {
    $manager->persist($membre); 
    $manager->flush();
    $this->addFlash('succes',"Vehicule a bien été enregistré");

    return $this->redirectToRoute('admin_membre', [
       
    ]);
   
}
return $this->renderForm('admin/form_membre.html.twig', [
    'formMembre'=> $form,
    'editMode'=> $membre->getId() !== null
]);


}





}
