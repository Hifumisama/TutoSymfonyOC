<?php
// src/OC/PlatformBundle/Antispam/OCAntispam.php

namespace OC\PlatformBundle\Antispam;

class OCAntispam
{
    // ici on crée des variables privées (qui n'existeront que dans la classe) et qui a pour but de récupérer les variables indiqués dans la configuration du service.
    private $mailer;
    private $locale;
    private $minLength;
    
    
    // cette fonction est spéciale, il s'agit d'un constructeur.
    /*
        son utilité réside dans le fait de récupérer les variables issues de la configuration du service et de les intégrer dans le service lui même.
        
        on l'écrit avec :
        - son nom (précédé de 2 underscores)
        - les arguments voulus (dans le cas d'un service on doit également indiquer le nom du service).
        - dans les accolades on y indique le mot clé this (pour la fonction en cours)
        - suivi du nom du paramètre dans le fichier de config.
        - puis on l'affecte à une variable préalablement instanciée.
        
        
    */
    /*
        Note intéressante ici, le fait d'avoir intégré le service mailer se nomme l'injection de dépendances: en TRES gros cela consiste à mettre un service au service d'un autre service xD.
    */
    public function __construct(\Swift_Mailer $mailer, $locale, $minLength)
    {
        $this->mailer = $mailer;
        $this->locale = $locale;
        $this->minLength = (int) $minLength;
    }
    
    
    /**
    Vérifie sur le texte est un spam ou non
    
    */
    public function isSpam($text)
    {
        // a noter que désormais on peut utiliser normalement les variables de configuration définies dans les autres fonctions de la classe : 
        return strlen($text) < $this->minLength;
    }
}