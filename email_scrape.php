<?php
session_start();
$url= !empty($_POST['url']) ? $_POST['url'] : NUll;

//Message d'erreur lorsque le champ n'est pas rempli
if($_POST['url'] == NULL){
    header("Location:index.php");
    $_SESSION['error'] = "Merci de renseigner une URL dans le champ correspondant.";
    exit;
}

// $url = 'https://www.lagrandemotte.fr/activites-loisirs/associations/annuaire-des-associations/';
$emails = scrape_email($url);
if($emails == null){
    header("Location:index.php");
    $_SESSION['error'] = "Aucunes adresse mails trouvées.";
    exit;
}

$fp=fopen("Mails.txt", "w+");
foreach($emails as $email){
    fwrite($fp, $email.PHP_EOL);
}
//Affichage de fichier txt
header('Content-Type: application/txt');
//nom du fichier txt
header('Content-Disposition: attachment; filename="Liste.txt');
//source du PDF original
readfile("Mails.txt");
fclose($fp);

function scrape_email($url) {
    if ( !is_string($url) ) {
        return '';
    }
    //$result = @file_get_contents($url);
    $result = @curl_get_contents($url);
    
    if ($result === FALSE) {
        return '';
    }
    
    // Convertit en minuscule
    $result = strtolower($result);


    // Remplace les adresses protégées contre les bots (xxxATgmmailDOTcom)// 
    $result = preg_replace('#[(\\[\\<]?AT[)\\]\\>]?\\s*(\\w*)\\s*[(\\[\\<]?DOT[)\\]\\>]?\\s*([a-z]{2,5})#ms', '@$1.$4', $result);
    // Email matches/ va chercher les emails nettoyés avec le preg_replace si besoin
    preg_match_all('#\\b([\\w\\._]*)[\\s(]*@[\\s)]*([\\w_\\-]{3,})\\s*\\.\\s*([a-z]{2,5})\\b#msi', $result, $matches);
    
    $usernames = $matches[1];
    $accounts = $matches[2];
    $suffixes = $matches[3];
    $emails = array();
    for ($i = 0; $i < count($usernames); $i++) {
        $emails[$i] = $usernames[$i] . '@' . $accounts[$i] . '.' . $suffixes[$i];
    }
    
return $emails;
}
function clean($str) {
    if ( !is_string($str) ) {
        return '';
    } else {
        return trim(strtolower($str));
    }
}


function curl_get_contents($url) {
//initialisation nouvelle session cURL
    $curl = curl_init($url);
//Options de transmission à définir
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    // pour les connexions https, pas de vérifications SSL
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($curl, CURLOPT_MAXREDIRS, 5);
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    $content = curl_exec($curl);
    //$error = curl_error($ch);
    //$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return $content;
}
?>