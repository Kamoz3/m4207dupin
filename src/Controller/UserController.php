<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utilisateur;
use PhpParser\Node\Expr\Empty_;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function user(Request $request): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => '',
        ]);
    }
    /**
     * @Route("/createUser", name="createUser")
     */
    public function createUser(Request $request, EntityManagerInterface $manager): Response
    {
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
            $User = new Utilisateur();
            $User->setNom($request->request->get('nom'));
            $User->setPrenom($request->request->get('prenom'));
            $User->setCode($request->request->get('code'));
            $User->setSalt($request->request->get('salt'));
            $manager->persist($User);
            $manager->flush();
            return $this->render('user/index.html.twig', [
                'controller_name' => 'Un utilisateur a été ajouté',
            ]);
        }
        else{
            return $this->redirectToRoute('authentification');
        }
    }
    /**    
     * @Route("/listeUser", name="listeUser")
     */
    public function listeUser(Request $request, EntityManagerInterface $manager): Response
    {
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
            //Requête pour récupérer toute la table User
            $listeUser = $manager->getRepository(Utilisateur::class)->findAll();
            return $this->render('user/listeUser.html.twig', [
                'controller_name' => 'Liste des utilisateurs',
                'listeUser' => $listeUser,
            ]);
        } 
        else{
            return $this->redirectToRoute('authentification');
        }
    }
    /**    
     * @Route("/deleteUser/{id}", name="deleteUser")
     */
    public function deleteUser(Request $request, EntityManagerInterface $manager, Utilisateur $id): Response
    {
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
            //Suppression de l'objet avec l'id passé en paramètre
            $manager->remove($id);
            $manager->flush();
            return $this->redirectToRoute('listeUser');
        }
        else{
            return $this->redirectToRoute('authentification');
        }
    }
    /**    
     * @Route("/updateUser/{id}", name="updateUser")
     */
    public function updateUser(Request $request, EntityManagerInterface $manager, Utilisateur $id): Response
    {
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
            //Requête pour récupérer toute la table User
            $updateUser = $manager->getRepository(Utilisateur::class)->findAll();
            $sess = $request->getSession();
            //Créer des variables utilisateur
            $sess->set("idUserModif", $id->getId());
            return $this->render('user/updateUser.html.twig', [
                'controller_name' => "Mise à jour d'un utilisateur",
                'user' => $id,
            ]);
        }
        else{
            return $this->redirectToRoute('authentification');
        }
    }
    /**    
     * @Route("/updateUserBdd", name="updateUserBdd")
     */
    public function updateUserBdd(Request $request, EntityManagerInterface $manager): Response
    {
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
            //Créer des variables de session
            $id = $sess->get("idUserModif");
            $user = $manager->getRepository(Utilisateur::class)->findOneById($id);
            if(!empty($request->request->get('nom')))
                $user->setNom($request->request->get('nom'));
            if(!empty($request->request->get('prenom')))
                $user->setPrenom($request->request->get('prenom'));
            if(!empty($request->request->get('code')))
                $user->setCode($request->request->get('code'));
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('listeUser');
        } 
        else{     
            return $this->redirectToRoute('authentification');
        }
    }
}