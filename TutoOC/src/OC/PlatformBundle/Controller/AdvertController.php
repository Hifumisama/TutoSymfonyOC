<?php 

namespace OC\PlatformBundle\Controller;

// on annonce que l'on va utiliser l'objet response (qui permet de retourner d'afficher quelque chose dans notre cas.)
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; // on oublie pas cette instanciation afin de pouvoir utiliser l'objet request.

//cet objet est présent dans le but de pouvoir générer des URLs
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

// Cet objet permet cette fois de générer nos vues en .twig.
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



// On crée notre classe AdvertController dans laquelle va se trouver les méthodes que l'on va utiliser, La classe contient son nom ainsi que le suffixe Controller, ajouté pour que Symfony la reconnaisse comme tel.

// Pourquoi l'extends ? : Car on a hérité des caractéristiques et des méthodes du controleur de base tout simplement.

class AdvertController extends Controller
{
    // on crée notre méthode dans la classe advert, qui contient également son nom suivi du suffixe Action. qui définit son rôle. 
    
    public function HelloWorldAction(Request $request)
    {
        $session = $request->getSession();
        $userId = $session->get('user_id');
        $session->set('user_id',92);
        return $this->redirectToRoute('oc_platform_home');
    }
    
    public function indexAction($page)
    {
        // si le nombre de page est inférieur à 1 alors on indique que la page n'existe pas.
        if($page < 1){
            //la ligne ci dessous permet d'envoyer une erreur 404, qui peut d'ailleurs renvoyer vers une page d'erreur personnalisée.
            throw new NotFoundHttpException('Page'.$page.'Inexistante');
        }
        
        // Ici sera récupérée la liste des annonces, puis sera filé au template.
        
        
        
        return $this->render('OCPlatformBundle:Advert:index.html.twig');
        // et on apelle la vue d'index.
    }
    public function viewAction($id)
    {
        // ici on va récupérer l'annonce qui nous intéresse et qui portera l'id voulu.
        return $this->render('OCPlatformBundle:Advert:view.html.twig',array(
            'id' => $id
        ));
    }
    
    public function addAction(Request $request)
    {
        // ici on va observer comment on peut gérer un formulaire : 
        
        // Si la requête est un post, alors le visiteur a soumis le formulaire.
        if($request->isMethod('POST')) {
            // Dans cette zone nous allons gérer la création et la gestion du formulaire. 
            
            // la ligne ci dessous renvoie un "message flash" qui sera détruit au changement de page mais qui peut permettre de faire quelques trucs utiles comme afficher des messages par exemple.
            $request->getSesstion()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            
            // on redirige ensuite l'utilisateur vers la page de l'annonce
            return $this->redirectToRoute('oc_platform_view', array('id' => 5));
        }
        // Cette page sera celle qui contiendra le formulaire d'action :D
        return $this->render('OCPlatformBundle:Advert:add.html.twig');
    }
    
    public function editAction($id, Request $request)
    {
        // ici on récupérera l'annonce correspondante à $id
        
        if($request->isMethod('POST')){
            return $this->redirectToRoute('oc_platform_view', array('id' => 5));
            
        }
        return $this->render('OCPlatformBundle:Advert:edit.html.twig');
    }
    public function deleteAction($id)
    {
        // Ici, on récupérera l'annonce correspondant à $id

        // Ici, on gérera la suppression de l'annonce en question
        
        return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }
}