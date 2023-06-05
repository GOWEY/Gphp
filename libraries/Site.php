<?php
/**
 * Classe de connexion à la base de données
 */
class Site {
    /**
     * Constructeur de la classe
     * 
     * @param string $nom Nom du site
     * @param string $ip Adresse IP du serveur de base de données
     * @var mysql Objet de connexion à la base de données
     */
    function __construct($nom ,$ip) {
        $this->database = new mysql($ip,'radius','radpassaLL98','radius');
        $this->nom = $nom;
        if ($this->database->cx->connect_error) {
            die("Connexion error: " . $this->database->cx->connect_error);
        }
    }
}