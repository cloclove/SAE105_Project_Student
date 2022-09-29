<?php

// ---------------------------------------------------------------------------

require_once("inc/model.php");

// ---------------------------------------------------------------------------

// Les fichiers de donnees au format JSON ou sont stockees les donnees
// (NB. Les chemins d'acces sont relatifs au repertoire
//      dans lequel s'execute le script controleur)

// Fichier contenant les informations generales sur les artistes (titre,  ...)
define("fichierInfoGen", "dat/infogen.json");



// Fichier contenant les infos sur les artistes
define("fichierArtistes", "dat/artistes.json");

// Fichier contenant les infos sur la programmation (les projections)
define("fichierSeances", "dat/seances.json");

// ---------------------------------------------------------------------------

// Fonction pour creer l'objet "festival" et charger les infos generales
// Valeur de retour : objet festival

function creefestivalEtChargeInfosGenerales()
{
// Determination de la taille du fichier contenant les infos generales
  $tailleFichierInfoGen = filesize(fichierInfoGen);
// Ouverture du fichier en lecture seulement (mode 'r')
  $accesFichierInfoGen = fopen(fichierInfoGen,'r');
// Lecture du contenu
  $contenuFichierInfoGen = fread($accesFichierInfoGen, $tailleFichierInfoGen);
// Fermeture du pointeur d'acces au fichier a present que le contenu est lu
  fclose($accesFichierInfoGen);

// Creation de l'objet contenant les infos de base sur le festival a partir du contenu du fichier 'infogen.json'
  $festival = new festival($contenuFichierInfoGen);
  return $festival;
};

// Fonction pour charger les infos specifiques sur les artistes
// Valeur de retour : tableau d'objets Artiste

function chargeTableArtistes()
{
// Determination de la taille du fichier contenant les infos sur les artistes
  $tailleFichierArtistes = filesize(fichierArtistes);
// Ouverture du fichier en lecture seulement (mode 'r')
  $accesFichierArtistes = fopen(fichierArtistes,'r');
// Lecture du contenu
  $contenuFichierArtistes = fread($accesFichierArtistes, $tailleFichierArtistes);
// Fermeture du pointeur d'acces au fichier a present que le contenu est lu
  fclose($accesFichierArtistes);

// Creation du tableau contenant les infos sur les differents Artistes
  $tableauInfosArtistes = json_decode($contenuFichierArtistes);
  $tableauArtistes = array();
  foreach ($tableauInfosArtistes as $infosArtiste)
  {
    $artiste=new Artiste($infosArtiste);
    $tableauArtistes[$artiste->id_artiste]=$artiste;
  };
  return $tableauArtistes;
};







function chargeTableSeances($tableauArtistes)
{
// Determination de la taille du fichier contenant les infos sur les seances
  $tailleFichierSeances = filesize(fichierSeances);
// Ouverture du fichier en lecture seulement (mode 'r')
  $accesFichierSeances = fopen(fichierSeances,'r');
// Lecture du contenu
  $contenuFichierSeances = fread($accesFichierSeances, $tailleFichierSeances);
// Fermeture du pointeur d'acces au fichier a present que le contenu est lu
  fclose($accesFichierSeances);

// Creation du tableau contenant les infos sur les differentes seances
  $tableauInfosSeances = json_decode($contenuFichierSeances);
  $tableauSeances = array();
  foreach ($tableauInfosSeances as $infosSeance)
  {
    $seance=new Seance($infosSeance, $tableauArtistes );
    $tableauSeances[$seance->id_seance]=$seance;
  };
  return $tableauSeances;
};

function creefestivalEtChargeSeances()
{
  // Recuperation de l'information generale
  $festival=creefestivalEtChargeInfosGenerales();
  // Recuperation de l'information sur les Artistes
  $tableArtistes=chargeTableArtistes();
  // Recuperation de l'information sur les seances et
  // raccrochage de ces infos a l'objet festival
  $festival->seances=chargeTableSeances($tableArtistes );
  return $festival;
};

?>
