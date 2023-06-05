<?php namespace router;

/**
 * Retourne le nom du contrôleur par défaut
 * 
 * @return string Nom du contrôleur par défaut
 */
function index(){
	return $_SESSION['index'];
}

/**
 * Retourne le chemin de base du site
 * 
 * @return string Chemin de base du site
 */
function base() {
	return str_replace("/index.php", "", $_SERVER["SCRIPT_NAME"]);
}

/**
 * Retourne le chemin d'un fichier du dossier public
 * 
 * @param string $path Chemin du fichier
 * @param string $file Nom du fichier
 * @return string Chemin du fichier
 */
function web($path="", $file="") {
	return base()."/public/".$path.$file;
}

/**
 * Retourne le chemin d'un fichier du dossier views
 * 
 * @param string $path Chemin du fichier
 * @param string $file Nom du fichier
 * @return string Chemin du fichier
 */
function query() {
	return explode("/",substr($_SERVER["REQUEST_URI"], strlen(base())+1));
}

/**
 * Retourne le nom du contrôleur
 * 
 * @return string Nom du contrôleur
 */
function controller(){
	return isset(query()[0]) && !empty(query()[0]) ? query()[0] : index();
}

/**
 * Retourne le nom de l'action
 * 
 * @return string Nom de l'action
 */
function action(){
	eval("\$action=\\ctrl\\".controller()."\\index();");
    return isset(query()[1]) && !empty(query()[1]) ? query()[1] : $action;
}

/**
 * Retourne les paramètres de l'action
 * 
 * @return array Paramètres de l'action
 */
function param(){
	return array_slice(query(), 2);
}

/**
 * Retourne l'URL d'une action
 * 
 * @param string $ctrl Nom du contrôleur
 * @param string $action Nom de l'action
 * @param array $param Tableau associatif contenant les paramètres à passer à l'action
 * @return string URL de l'action
 */
function url($ctrl, $action, $param=[]){
	return base()."/".$ctrl."/".$action."/".implode("/",$param);
}

/**
 * Retourne le chemin du dossier racine du site
 * 
 * @return string Chemin du dossier racine du site
 */
function root() {
    return str_replace("index.php","",$_SERVER["SCRIPT_FILENAME"]);
}

?>