<?php 

namespace OC\PlatformBundle\Controller;

// on annonce que l'on va utiliser l'objet response (qui permet de retourner d'afficher quelque chose dans notre cas.)
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; // on oublie pas cette instanciation afin de pouvoir utiliser l'objet request.

//cet objet est présent dans le but de pouvoir générer des URLs
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

// Cet objet permet cette fois de générer nos vues en .twig.
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// on instancie l'entité Advert pour pouvoir l'utiliser après.
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;


// On crée notre classe AdvertController dans laquelle va se trouver les méthodes que l'on va utiliser, La classe contient son nom ainsi que le suffixe Controller, ajouté pour que Symfony la reconnaisse comme tel.

// Pourquoi l'extends ? : Car on a hérité des caractéristiques et des méthodes du controleur de base tout simplement.

class AdvertController extends Controller
{
    // on crée notre méthode dans la classe advert, qui contient également son nom suivi du suffixe Action. qui définit son rôle. 
    
    public function HelloWorldAction(Request $request)
    {
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony.');
        $advert->setAuthor('Alex');
        $advert->setContent('Ceci a une logique totalement évidente...');
        $advert->setDate(new \DateTime());
        
        $em=$this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->flush();
        
            // Reste de la méthode qu'on avait déjà écrit
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // Puis on redirige vers la page de visualisation de cettte annonce
      return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
    }

    // Si on n'est pas en POST, alors on affiche le formulaire
    return $this->render('OCPlatformBundle:Advert:add.html.twig');

    }
    
    public function indexAction($page)
    {
        // si le nombre de page est inférieur à 1 alors on indique que la page n'existe pas.
        if($page < 1){
            //la ligne ci dessous permet d'envoyer une erreur 404, qui peut d'ailleurs renvoyer vers une page d'erreur personnalisée.
            throw new NotFoundHttpException('Page'.$page.'Inexistante');
        }
        
        // Ici sera récupérée la liste des annonces, puis sera filé au template
        
        $em = $this->getDoctrine()->getManager();
        // le findAll() sert à récupérer l'ensemble des entrées de l'entité.
        $listAdverts = $em->getRepository('OCPlatformBundle:Advert')->findAll();
        
        
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts
        ));
        // et on apelle la vue d'index.
    }
    public function menuAction($limit)
    {
        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
    );

    return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe
      // les variables nécessaires au template !
      'listAdverts' => $listAdverts
    ));
    }
    public function viewAction($id)
    {
        // ici on va récupérer l'annonce qui nous intéresse et qui portera l'id voulu. En dur pour le moment.
        
        $em = $this->getDoctrine()->getManager();
        
        //on récupère l'annonce avec l'id prévu.
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
        
        
        
        if (null === $advert){
            throw new NotFoundHttpException("L'annonce est introuvable");
        }
        
        // ici il s'agit de la liste des annonces.
        $listApplications = $em
            ->getRepository('OCPlatformBundle:Application')
            ->findBy(array('advert' => $advert));
        
        return $this->render('OCPlatformBundle:Advert:view.html.twig',array(
            'advert' => $advert,
            'listApplications' => $listApplications
        ));
    }
    
    public function addAction(Request $request)
    {
        // Création de l'entité Advert
        $advert = new Advert();
        $advert->setTitle('Recherche Sauveur du JavaScript');
        $advert->setAuthor('Bertrand');
        $advert->setContent("Nous recherchons un Super héros du code Javascript et NodeJS, basé sur Paris oklm tavu :D");
        $advert->setDate(new \DateTime());

        // Création de l'entité Image
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');
        
        // On lie l'image à l'annonce
        $advert->setImage($image);
        
        // Création d'une candidature 
        
        $application1 = new Application();
        $application1->setAuthor("Francis");
        $application1->setContent("J'ai toutes les qualité requises pour ce boulot :o");
        
        $application2 = new Application();
        $application2->setAuthor("Gérard");
        $application2->setContent("Je suis le héros de votre vie, je suis telement coul et narcissiq que mon aurtohgraffe est parfffaite !!11");
        
        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Étape 1 : On « persiste » l'entité
        $em->persist($advert);
        $em->persist($application1);
        $em->persist($application2);

        // Étape 1 bis : si on n'avait pas défini le cascade={"persist"},
        // on devrait persister à la main l'entité $image
        // $em->persist($image);

        // Étape 2 : On déclenche l'enregistrement
        $em->flush();
        
        // on récupère notre service d'antispam.
        $antispam = $this->container->get('oc_platform.antispam');
        
        $text = 'cecinest passssssssssssssssssssssssssssssssss un spam c est pour contourner la protection antispam tavu';
        if($antispam->isSpam($text))
        {
            throw new \Exception('Ce message est un spam :p !');
        }
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
        $advert = array(
            'title'   => 'Recherche développpeur Symfony',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
        );

    return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
      'advert' => $advert
    ));
    }
    public function deleteAction($id)
    {
        // Ici, on récupérera l'annonce correspondant à $id

        // Ici, on gérera la suppression de l'annonce en question
        
        return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }
}