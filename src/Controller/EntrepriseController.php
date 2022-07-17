<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EntrepriseController extends AbstractController
{
    // la route est le chemin d'accès URL ici /entreprise name correspond au chemin URL a la requête de la function index 
    // cet même function une fois éxécuter renverras un chemin vers une vue pour afficher le resultat
    /**
     * @Route("/entreprise", name="app_entreprise")
     */

    // on appelle la class native symfony ManagerRegisrty dont il faut importer la class ATTENTION !! plusieur choix possible selon les cas
    // lire la doc pour trouver la bonne class a importer. 
    // DOCTRINE est une couche d’abstraction à la base de données Cette technique permet l’utilisateur d’utiliser les tables d’une base 
    // de donnée comme des objets. Elle consiste à associer une ou plusieurs à chaque table , et un attribut de classe à chaque colonne
    //  de la table.
    public function index(ManagerRegistry $doctrine): Response // reponse car il a attend un return qu'on retourne quelque chose 
    {
        // Un Repository Doctrine est un objet dont la responsabilité est de récupérer une collection d’objets. Les repositories ont 
        // accès à deux objets principalement :EntityManager : Permet de manipuler nos entités ;
        // QueryBuilder : Un constructeur de requêtes qui permet de créer des requêtes personnalisé. 
        //Donc on récupere toutes les entreprises de la BDD 
        $entreprises = $doctrine->getRepository(Entreprise::class)->findBY([],["raisonSocial" => "ASC"]);
        // on return les infos dans la vue qui est dans le template entreprise/index.html.twig
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }
        // ici onb rajoute add pour que le nom de la route ne sois pas la même 
        // On peut mettre deux route pour editer on place l'id pour modifier la bonne entreprise
        /**
        * @Route("/entreprise/add", name="add_entreprise")
        * @Route("/entreprise/{id}edit", name="edit_entreprise")
        */
        // on appelle ManagerRegistery pour utilisé doctrine ensuite on ajoute l'objet Entreprise que l'on veut rajoute en BDD passez 
        // a null pour lui donnée une valeur par default, puis L'objet request pour 
        // cet fonction seras utilisé pour ajouter et éditer
        public function add(ManagerRegistry $doctrine, Entreprise $entreprise = null,Request $request ): Response
        {
            // si l'objet entreprise n'existe pas on crée un objet Entreprise donc => ' Route("/entreprise/add", name="add_entreprise")'
            //  Sinon   ' Route("/entreprise/{id}edit", name="edit_entreprise)'
            if(!$entreprise){
                $entreprise =new Entreprise();
            }

            // construit un formulaire qui se repose sur le builder qui crée les champs de l'entité entreprise car il récupère 
            // toutes les propriétes de la classe 
            $form = $this->createForm(EntrepriseType::class,$entreprise);
            // handleRequest analyse les infos envoyers quand il y a une action et récupère les données 
            $form->handleRequest($request);
            // si le formulaire est envoyé && et que le formulaire est valide ( gestion des filter_input faille xss gérer !!)
            if($form->isSubmitted() && $form->isValid())
            {
                // récupere les données envoyés et les hydrates l'objet entreprise donnée au départ
                $entreprise = $form->getData();
                // EntityManger est un service Doctrine qui nous permet de manipuler des entités (Entity). Donc pour accéder à notre 
                // service EntityManger nous devons passer par le service Doctrine.  ( persiste ,fluh => methode de l'objet doctrine)
                $entityManager = $doctrine->getManager();
                //persist prépare la requete ( comme fonction prepare dans les autres exo ) Gère la faills SQL
                $entityManager->persist($entreprise);
                // envoie les données concrètement en base de donnée ( fait le insert into )
                $entityManager->flush();
                // si succès ajout ou modif on redirige versla route qui envoie la liste des entreprises 
                return $this->redirectToRoute('app_entreprise');

            }
            // vue pour afficehr le formulaire d'ajout 
            return $this->render('entreprise/add.html.twig', [
                // $form->createView(), génère le formulaire dans la vue 
                'formAddEntreprise' =>  $form->createView(),
                // si getId existe on recupere 'edit' 
                'edit' => $entreprise->getId(),
            ]);
        }


        /**
        * @Route("/entreprise/{id}delete", name="delete_entreprise")
        */
        public function delete(ManagerRegistry $doctrine, Entreprise $entreprise ) : Response
        {
            // Doctrine Permet de manipuler nos entités et  accès BDD 
            $entityManager = $doctrine->getManager();
            $entityManager->remove($entreprise); // => requete delete sql
            $entityManager->flush();
            // si succès ajout ou modif on redirige versla route qui envoie la liste des entreprises 
            return $this->redirectToRoute("app_entreprise");
        }
    // POUR CE QUI EST DES ROUTE AVEC ID IL FAUT LES PLACES EN DESSOUS SINON SYMFONY VA CHERCHERUN ID CAR DEFINI AVANT LES AUTRES 
    // METHODES


    // on passe en parametre id dans la route qui va récuperer l'id de l'entreprise cibler pas besoins de faire de requete pour récuperer l'id
    //  pour afficher son detail pour la route il faut toujour que le 
    // name soit different deux route ne peuvent avoir le même nom 
    /**
     * @Route("/entreprise/{id}", name="show_entreprise")
     */
    // utilisation du param converter qui, en lui passant un OBJET ici entreprise recuperera l'id qui du a la route 
    public function show(Entreprise $entreprise) : Response
    {
            return $this->render('entreprise/detail.html.twig', [
                "entreprise" => $entreprise
            ]);
    }

}
