<?php

/**
 * Affiche une vue
 * 
 * @param string $vue Nom de la vue à afficher
 * @param array $tbData Tableau associatif contenant les données à passer à la vue
 */
function view($vue, $tbData=array()){
		if(!empty($tbData)) {
			extract($tbData);
		}
		include("views/v_".$vue.".php");
}
	
/**
 * Redirige vers une autre page
 * 
 * @param string $ctrl Nom du contrôleur
 * @param string $action Nom de l'action
 * @param array $param Tableau associatif contenant les paramètres à passer à l'action
 */
function redirect($ctrl, $action, $param=[]){
	header("Location: ".router\url($ctrl, $action, $param));
}

?>