<?php
  // Les chemins d'acces aux fichiers contenant les donnees sont dans "load_data.php"
  require_once("inc/load_data.php");

  // Recuperation de toutes les informations sur les artistes
  $festival=creefestivalEtChargeSeances();

  // Fonctions pour la presentation au client
  require_once("inc/output.php");

  // Envoi des en-tetes HTTP
  envoiEnTetesHTTP();
  
  // Envoi du flux de sortie vers le client web
  // Si un artiste particulier est precise dans l'URL on donne les infos sur un artiste
  if (isset($_GET['id_artiste'])) {
    $id_artiste=$_GET['id_artiste'];
    echoDetailsSurUnArtiste($festival,$id_artiste);
  }
  // Sinon on donne le programme de tout le festival
  else {
    echoPageProgramme($festival);
  };
?>
