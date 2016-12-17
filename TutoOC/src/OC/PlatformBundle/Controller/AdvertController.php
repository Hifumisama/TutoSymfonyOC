<?php 

namespace OC\PlatformBundle\Controller;

// on annonce que l'on va utiliser l'objet response (qui permet de retourner d'afficher quelque chose dans notre cas.)
use Symfony\Component\HttpFoundation\Response;

//cet objet est présent dans le but de pouvoir générer des URLs
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

// Cet objet permet cette fois de générer nos vues en .twig.
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



// On crée notre classe AdvertController dans laquelle va se trouver les méthodes que l'on va utiliser, La classe contient son nom ainsi que le suffixe Controller, ajouté pour que Symfony la reconnaisse comme tel.

// Pourquoi l'extends ? : Car on a hérité des caractéristiques et des méthodes du controleur de base tout simplement.

class AdvertController extends Controller
{
    // on crée notre méthode dans la classe advert, qui contient également son nom suivi du suffixe Action. qui définit son rôle. 
    
    public function HelloWorldAction()
    {
        // On se contente de créer un message indiquant que le contrôleur fonctionne correctement.
        return new Response("Notre Propre Hello World fonctionne :o !");
    }
    
    public function indexAction()
    {
        $content = $this->get('templating')->render('OCPlatformBundle:Advert:index.html.twig');
        return new Response($content);
    }
    public function viewAction($id)
    {
    // $id vaut 5 si l'on a appelé l'URL /platform/advert/5

    // Ici, on récupèrera depuis la base de données
    // l'annonce correspondant à l'id $id.
    // Puis on passera l'annonce à la vue pour
    // qu'elle puisse l'afficher

    return new Response("Affichage de l'annonce d'id : ".$id);
    }
    
    public function viewSlugAction($slug, $year, $format)
    {
        return new Response("On pourrait afficher l'annonce correspondante au slug ' ".$slug." ', crée en ".$year." et au format ".$format.".");
    }
}