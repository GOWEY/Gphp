<?php

/**
 * Classe mysql
 * 
 * Cette classe permet de se connecter à une base de données MySQL et d'exécuter des requêtes SQL
 */
class mysql
{	public $cx;
	private $last_error;
	private $num_rows;
	private $last_affected_rows;

	/**
	 * Constructeur de la classe mysql
	 * @param string $dbhost Hôte de la base de données
	 * @param string $dbuser Nom d'utilisateur de la base de données
	 * @param string $dbpass Mot de passe de la base de données
	 * @param string $dbname Nom de la base de données
	 */
	function __construct($dbhost, $dbuser, $dbpass, $dbname)
	{
		$this->cx = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		if ($this->cx->connect_error) {
			die("Connexion error: " . $this->cx->connect_error);
		}
	}

	/**
	 * Exécute une requête SQL et renvoie un tableau associatif contenant tous les résultats
	 * @param string $sql Requête SQL à exécuter
	 * @param array $params Paramètres à lier à la requête (facultatif)
	 * @return array|bool Tableau associatif contenant tous les résultats ou false en cas d'erreur
	 */
	function query_array($sql, $params = [])
	{
		if ($stmt = $this->cx->prepare($sql)) {
			if (!empty($params)) {
				$types = str_repeat('s', count($params));
				$stmt->bind_param($types, ...$params);
			}
			$stmt->execute();
			if ($stmt->error) {
				$this->last_error = $stmt->error;
				return false;
			}
			$result = $stmt->get_result();
			$this->num_rows = $result->num_rows;
			return $result->fetch_all(MYSQLI_ASSOC);
		} else {
			return false;
		}
	}

	/**
	 * Exécute une requête SQL et renvoie un tableau associatif contenant le premier résultat
	 * @param string $sql Requête SQL à exécuter
	 * @param array $params Paramètres à lier à la requête (facultatif)
	 * @return array|bool Tableau associatif contenant le premier résultat ou false en cas d'erreur
	 */
	function query_assoc($sql, $params = [])
	{
		if ($stmt = $this->cx->prepare($sql)) {
			if (!empty($params)) {
				$types = str_repeat('s', count($params));
				$stmt->bind_param($types, ...$params);
			}
			$stmt->execute();
			if ($stmt->error) {
				$this->last_error = $stmt->error;
				return false;
			}
			$result = $stmt->get_result();
			$this->num_rows = $result->num_rows;
			return $result->fetch_assoc();
		} else {
			return false;
		}
	}

	/**
	 * Exécute une requête SQL simple (INSERT, UPDATE, DELETE)
	 * @param string $sql Requête SQL à exécuter
	 * @param array $params Paramètres à lier à la requête (facultatif)
	 * @return bool True en cas de succès ou false en cas d'erreur
	 */
	function query_simple($sql, $params = [])
	{
		if ($stmt = $this->cx->prepare($sql)) {
			if (!empty($params)) {
				$types = str_repeat('s', count($params));
				$stmt->bind_param($types, ...$params);
			}
			if (!$stmt->execute()) {
				return false;
			}
			if ($stmt->error) {
				$this->last_error = $stmt->error;
				return false;
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Échappe une chaîne pour l'utiliser dans une requête SQL
	 * @param string $string Chaîne à échapper
	 * @return string Chaîne échappée
	 */
	function escape_string($string)
	{
		return $this->cx->real_escape_string($string);
	}

	/**
	 * Ferme la connexion à la base de données
	 * @return bool True en cas de succès ou false en cas d'erreur
	 */
	function close()
	{
		return $this->cx->close();
	}

	/**
	 * Récupère une ligne d'une table en fonction de son identifiant ou toutes les lignes si l'identifiant est égal à 0
	 * @param string $table Nom de la table
	 * @param int $id Identifiant de la ligne à récupérer ou 0 pour récupérer toutes les lignes
	 * @param string $order_by Nom de la colonne pour trier les résultats (facultatif)
	 * @return array|bool Tableau associatif contenant la ligne ou toutes les lignes ou false en cas d'erreur
	 */
	function get($table, $id = 0, $order_by = '')
	{
		$table = $this->cx->real_escape_string($table);
		if ($id == 0) {
			$sql = "SELECT * FROM `$table`";
		} else {
			$sql = "SELECT * FROM `$table` WHERE id = ?";
		}
		if (!empty($order_by)) {
			$order_by = $this->cx->real_escape_string($order_by);
			$sql .= " ORDER BY `$order_by`";
		}
		if ($stmt = $this->cx->prepare($sql)) {
			if ($id != 0) {
				$stmt->bind_param('i', $id);
			}
			$stmt->execute();
			if ($stmt->error) {
				$this->last_error = $stmt->error;
				return false;
			}
			$result = $stmt->get_result();
			$this->num_rows = $result->num_rows;
			if ($id == 0) {
				return $result->fetch_all(MYSQLI_ASSOC);
			} else {
				return $result->fetch_assoc();
			}
		} else {
			return false;
		}
	}
}
?>
