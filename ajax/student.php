<?php
// Autoload PSR-4
spl_autoload_register(
    function ($pathClassName) {
        include '../'.str_replace('\\', '/', $pathClassName).'.php';
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

// Récupère la liste de sympathie pour la vue
$friendlinessList = Friendliness::getListForSelect();
// Récupère la liste complète des students en DB
$studentList = Student::getAllForSelect();
// Récupère la liste complète des cities en DB
$citiesList = City::getAllForSelect();
// Récupère la liste complète des sessions en DB
$sessionsList = Session::getAllForSelect();

$studentId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$sessionId = isset($_POST['ses_id']) ? (int)$_POST['ses_id'] : 0;
$cityId = isset($_POST['cit_id']) ? (int)$_POST['cit_id'] : 0;
$studentBirthdate = isset($_POST['stu_birthdate']) ? date('Y-m-d', strtotime($_POST['stu_birthdate'])) : 0;
$studentFriendliness = isset($_POST['stu_friendliness']) ? (int)$_POST['stu_friendliness'] : 0;
$studentLastName = isset($_POST['stu_lname']) ? trim($_POST['stu_lname']) : '';
$studentFirstName = isset($_POST['stu_fname']) ? trim($_POST['stu_fname']) : '';
$studentEmail = isset($_POST['stu_email']) ? trim($_POST['stu_email']) : '';

if (strlen($studentBirthdate) < 10) {
    $conf->addError('Birthdate non correcte');
}
if (!array_key_exists($studentFriendliness, $friendlinessList)) {
    $conf->addError('Sympathie non valide');
}
if (!array_key_exists($cityId, $citiesList)) {
    $conf->addError('Ville non valide');
}
if (!array_key_exists($sessionId, $sessionsList)) {
    $conf->addError('Session de formation non valide');
}
if (empty($studentEmail) || filter_var($studentEmail, FILTER_VALIDATE_EMAIL) === false) {
    $conf->addError('Email non valide');
}
if (empty($studentLastName)) {
    $conf->addError('Veuillez renseigner le nom');
}
if (empty($studentFirstName)) {
    $conf->addError('Veuillez renseigner le prénom');
}

// je remplis l'objet qui est lu pour les inputs du formulaire, ou pour l'ajout en DB
$studentObject = new Student(
    $studentId,
    new Session($sessionId),
    new City($cityId),
    $studentLastName,
    $studentFirstName,
    $studentEmail,
    $studentBirthdate,
    $studentFriendliness
);

// Si tout est ok
if (!$conf->haveError()) {
    if ($studentObject->saveDB()) {
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
