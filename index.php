<?php

session_start();

include_once("libraries/utility.php");
utility\includeAll("libraries/");
utility\includeAll("models/");

$_SESSION['index'] = "index";

if(file_exists("controllers/c_".router\controller().".php")){
    include("controllers/c_".router\controller().".php");
    $action="ctrl\\".router\controller()."\\".router\action();
    if(function_exists($action)){
        count(router\param())>0 ? call_user_func_array($action, router\param()) : $action(null);
    }
    else {
        echo "ERREUR : cette action n'existe pas pour le controleur ".router\controller();
    }
}
else {
    echo "ERREUR : ce controleur n'existe pas";
    echo "<br/>".router\controller();
}
?>