<?php
// Autoload PSR-4
spl_autoload_register(
    function ($pathClassName) {
        include '../'.str_replace('\\', '/', $pathClassName).'.php';
    }
);

// Imports
use \Classes\Webforce3\Config\Config;
use \Classes\Webforce3\DB\Training;
use \Classes\Webforce3\DB\Location;
use \Classes\Webforce3\DB\Session;

// Get the config object
$conf = Config::getInstance();

// Récupère la liste complète des session, location, training en DB
$sessionsList = Session::getAllForSelect();
$locationsList = Location::getAllForSelect();
$trainingsList = Training::getAllForSelect();

/*id=0&ses_start_date=2015-11-30&ses_end_date=2016-03-27&loc_id=1&ses_number=1&tra_id=1*/

$sessionId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$start = isset($_POST['ses_start_date']) ? date('Y-m-d', strtotime($_POST['ses_start_date'])) : 0;
$end = isset($_POST['ses_end_date']) ? date('Y-m-d', strtotime($_POST['ses_end_date'])) : 0;
$sessionNumber = isset($_POST['ses_number']) ? (int)$_POST['ses_number'] : 0;
$locId = isset($_POST['loc_id']) ? (int)$_POST['loc_id'] : 0;
$traId = isset($_POST['tra_id']) ? (int)$_POST['tra_id'] : 0;


if (!array_key_exists($sessionNumber, $sessionsList)) {
    $conf->addError('Session non valide');
}
if (!array_key_exists($locId, $locationsList)) {
    $conf->addError('Location non valide');
}
if (!array_key_exists($traId, $trainingsList)) {
    $conf->addError('Trainning non valide');
}
if (strlen($start) < 10) {
    $conf->addError('Date de début non correcte');
}
if (strlen($end) < 10) {
    $conf->addError('Date de fin non correcte');
}

// je remplis l'objet qui est lu pour les inputs du formulaire, ou pour l'ajout en DB
$sessionObject = new session(
    $sessionId,
    $sessionNumber,
    $start,
    $end,
    new Location($locId),
    new Training($traId)
);

// Si tout est ok
if (!$conf->haveError()) {
    if ($sessionObject->saveDB()) {
        //header('Location: student.php?success='.urlencode('Ajout/Modification effectuée').'&stu_id='.$studentObject->getId());
        echo json_encode(array(
            'code'=>1,
            'msg'=>'Ajout/Modification effectuée'
        ));
        exit;
    } else {
        $conf->addError('Erreur dans l\'ajout ou la modification');
        echo json_encode(array(
            'code'=>0,
            'msg'=>$conf->getErrorList()
        ));
        exit;
    }
}else{
    echo json_encode(array(
        'code'=>0,
        'msg'=>$conf->getErrorList()
    ));
    exit;
}
