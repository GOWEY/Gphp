<?php namespace utility;

	/**
	 * Inclut tous les fichiers d'un dossier
	 * 
	 * @param string $dirname Nom du dossier
	 * @return void
	 */
	function includeAll($dirname){
		if ($dir = opendir($dirname)) {
			while (false !== ($file = readdir($dir))) {
				if (is_file($dirname.$file) && $file != "." && $file != "..") {
					include_once($dirname.$file);
				}
			}
			closedir($dir);
		}
	}

?>