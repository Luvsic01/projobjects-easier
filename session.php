<?php

// Autoload PSR-4
spl_autoload_register(
    function ($pathClassName) {
        include str_replace('\\', '/', $pathClassName).'.php';
    }
);

// Imports 
use \Classes\Webforce3\Config\Config;
use \Classes\Webforce3\DB\Session;
use \Classes\Webforce3\DB\Training;
use \Classes\Webforce3\DB\Location;
use \Classes\Webforce3\Helpers\SelectHelper;

// Get the config object
$conf = Config::getInstance();

$sessionId = isset($_GET['ses_id']) ? (int)$_GET['ses_id'] : 0;
$sessionObject = new Session();
$trainingObject = new Training();

// Récupère la liste complète des session, location, training en DB
$sessionsList = Session::getAllForSelect();
$locationsList = Location::getAllForSelect();
$trainingsList = Training::getAllForSelect();

// Si modification d'une session, on charge les données pour le formulaire
if ($sessionId > 0) {
    $sessionObject = Session::get($sessionId);
}

// Si lien suppression
if (isset($_GET['delete']) && (int)$_GET['delete'] > 0) {
    if (Session::deleteById((int)$_GET['delete'])) {
        header('Location: session.php?success='.urlencode('Suppression effectuée'));
        exit;
    }
}

// Formulaire soumis
if(!empty($_POST)) {
    //appel ajax
}

$selectSessions = new SelectHelper($sessionsList, $sessionId, array(
    'name' => 'ses_id',
    'id' => 'ses_id',
    'class' => 'form-control',
));

$selectLocations = new SelectHelper($locationsList, $sessionObject->getLocation()->getId(), array(
    'name' => 'loc_id',
    'id' => 'loc_id',
    'class' => 'form-control',
));

$selectTrainings = new SelectHelper($trainingsList, $sessionObject->getTraining()->getId(), array(
    'name' => 'tra_id',
    'id' => 'tra_id',
    'class' => 'form-control',
));

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir().'header.php';
require $conf->getViewsDir().'session.php';
require $conf->getViewsDir().'footer.php';