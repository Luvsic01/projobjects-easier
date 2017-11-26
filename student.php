<?php

// Autoload PSR-4
spl_autoload_register(
    function ($pathClassName) {
        include str_replace('\\', '/', $pathClassName).'.php';
    }
);

// Imports 
use \Classes\Webforce3\Config\Config;
use \Classes\Webforce3\Friendliness;
use \Classes\Webforce3\DB\Student;
use \Classes\Webforce3\DB\City;
use \Classes\Webforce3\DB\Session;
use \Classes\Webforce3\Helpers\SelectHelper;

// Get the config object
$conf = Config::getInstance();

$studentId = isset($_GET['stu_id']) ? (int)$_GET['stu_id'] : 0;
$studentObject = new Student();

// Récupère la liste de sympathie pour la vue
$friendlinessList = Friendliness::getListForSelect();
// Récupère la liste complète des students en DB
$studentList = Student::getAllForSelect();
// Récupère la liste complète des cities en DB
$citiesList = City::getAllForSelect();
// Récupère la liste complète des sessions en DB
$sessionsList = Session::getAllForSelect();

// Si modification d'un student, on charge les données pour le formulaire
if ($studentId > 0) {
	$studentObject = Student::get($studentId);
}

// Si lien suppression
if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
	if (Student::deleteById(intval($_GET['delete']))) {
		header('Location: student.php?success='.urlencode('Suppression effectuée'));
		exit;
	}
}

// Instancie le générateur de menu déroulant pour la sympathie
$selectFriendliness = new SelectHelper($friendlinessList, $studentObject->getFriendliness(), array(
	'name' => 'stu_friendliness',
	'id' => 'stu_friendliness',
	'class' => 'form-control',
));

// Instancie le générateur de menu déroulant pour la liste des étudiants
$selectStudents = new SelectHelper($studentList, $studentId, array(
	'name' => 'stu_id',
	'id' => 'stu_id',
	'class' => 'form-control',
));

// Instancie le générateur de menu déroulant pour les trainings
$selectSessions = new SelectHelper($sessionsList, $studentObject->getSession()->getId(), array(
	'name' => 'ses_id',
	'id' => 'ses_id',
	'class' => 'form-control',
));

// Instancie le générateur de menu déroulant pour les cities
$selectCities = new SelectHelper($citiesList, $studentObject->getCity()->getId(), array(
	'name' => 'cit_id',
	'id' => 'cit_id',
	'class' => 'form-control',
));

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir().'header.php';
require $conf->getViewsDir().'student.php';
require $conf->getViewsDir().'footer.php';