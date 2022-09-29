<?php

// Langue du site

define("langueDuSite","fr");

// Localisation de quelques messages tres generiques (sans s'occuper du contenu des donnees)

function message($id_message, $langue)
{
  $localisation=array("date_a_preciser" => array("fr" => "Date à préciser", "en" => "Date: to be announced", "de" => "Datum noch offen"),
                      "lieu_a_preciser" => array("fr" => "", "en" => "Location: to be announced", "de" => "Ort noch offen"));
  $message="";
  if ((isset($localisation[$id_message]))&&(isset($localisation[$id_message][$langue])))
  {
    $message=$localisation[$id_message][$langue];
  };
  return $message;
}

// Envoi d'en-tetes HTTP

function envoiEnTetesHTTP()
{
// En-tetes HTTP, transmis au client avant meme le contenu du medium lui-meme :
  http_response_code(200);
  header("Content-Type: text/html; charset=UTF-8");
  header("Content-Transfer-Encoding: 8bit");
// La transmission des flux de donnees entre client et serveur se fera
// en UTF-8, sans encodage de transfert supplementaire.
};

// Envoi du debut du flux de sortie (balise ouvrante de l'element racine HTML et element <head>)

function echoBaliseOuvranteEtEnTeteHTML($titre)
{


// Declaration simplifiee HTML 5 :
  printf("<!DOCTYPE html>\n");

// Balise racine avec indication de la langue par defaut et du sens de lecture par defaut :
  printf("<html xml:lang=\"%s\" lang=\"%s\" dir=\"ltr\">\n",langueDuSite,langueDuSite);

// Partie en-tete HTML :
  printf("  <head>\n");

// ci-dessous on dit la meme chose (par precaution) sur l'encodage des caracteres
// que ce qu'on a deja dit dans les en-tetes HTTP :
  printf("    <meta charset=\"UTF-8\"/>\n");
  printf("    <meta name=\"Author\" lang=\"fr\" content=\"Chloe BILBAULT-YOUNG\">\n");
// NB. L'encodage est en UTF-8 et la transmission se fait en UTF-8
// cependant, par prudence, dans certaines portions de code HTML,
// on peut utiliser les codes Unicode pour tous les caracteres
// non-ASCII --- afin d'eviter d'eventuels problemes d'encodage.
// Exemple : &#xE9; = e accent aigu ; &#x2019; = apostrophe "typographique"
// Liste des codes Unicode ici : https://www.unicode.org/charts/

// Lien vers de jolies polices de caracteres telechargeables sur le site Google Fonts :
  printf("    <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">\n");
  printf("    <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>\n");
  printf("    <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700\"/>\n");
  printf("    <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css2?family=PT+Serif&display=swap\">\n");

  printf("   <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\"\n>");
  printf("  <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin> \n");
  printf (" <link href=\"https://fonts.googleapis.com/css2?family=Anonymous+Pro:wght@700&family=Anton&family=Concert+One&family=Inter:wght@300&family=Mochiy+Pop+P+One&display=swap\" rel=\"stylesheet\">");
  printf ("<link href=\"https://fonts.googleapis.com/css2?family=Do+Hyeon&family=Dongle&display=swap\" rel=\"stylesheet\">");
  
// Lien vers une feuille de style CSS pour ce site :
  printf("   <link rel=\"stylesheet\" type=\"text/css\" href=\"css/artiste.css\" media=\"screen\"/>\n");

// Titre affiche en haut de la fenetre de navigateur :
  printf("    <title>Artiste ♥</title>\n",$titre);
  printf("  <link rel=\"icon\" type=\"image/png\" href=\"img/logo.png\">");
  printf("  </head>\n");
};

// Envoi de la balise ouvrante du <body> HTML

function echoBaliseOuvranteBody()
{
  printf("  <body>\n");
};


// Affichage du titre de la page decrivant les différents artistes

function echoTitrefestival($festival)
{
  $separateur_lieu_date="";
  if ((!(is_null($festival->lieu)))&&(!(is_null($festival->date)))) { $separateur_lieu_date=", "; };
  printf("    <h1>%s</h1><nav class=\"logo\">\n<a href=\"%s\"><img alt=\"Logo du festival, lien vers la page d'accueil\" src=\"%s\"/></a></nav>\n",$festival->titre,$_SERVER['PHP_SELF'],"img/logo.png");
  printf("    <h2>%s</h2>\n",$festival->descriptif);
  printf("    <h2>%s%s%s</h2>\n",$festival->lieu,$separateur_lieu_date,$festival->date);
};

// Affichage du titre d'une page decrivant un artiste en particulier

function echoTitreartiste($artiste)
{
  printf("    <h1>%s</h1><nav class=\"logo\"><a href=\"%s\"><img alt=\"Logo du festival, lien vers la page d'accueil\" src=\"%s\"/></a></nav> \n",$artiste->titre,$_SERVER['PHP_SELF'],"img/logo.png");
  if ((isset($artiste->Passion))&&(isset($artiste->annee))) {
    printf("    <h2>%s (%s)</h2>\n",$artiste->Passion,$artiste->annee);
  };
};



// Affichage du programme de l'accueil sur les artistes

function echoProgramme($festival)
{
  printf("    <main class=\"programme\">\n");
  foreach ($festival->seances as $seance)
  {
    echo $seance->afficheSeance(langueDuSite);
  };
  printf("    </main>\n");
}

// Affichage d'un pied de page 

function echoPiedDePage()
{
  printf("    <footer class=\"pied_de_page\">© Chloé, 2022. tous droits réservés <a href=\"mentions_legales.html\">Mentions l&#xE9;gales</a>.</footer>\n");
}

// Affichage de la fin du flux de sortie (balises fermantes </body> et </html>

function echoBalisesFermantesBodyEtHTML()
{
  printf("  </body>\n");
  printf("</html>\n");
};

// Envoi au client de tout ce qui permet l'affichage du programme complet des artistes nommé "$festival".

function echoPageProgramme($festival)
{
  echoBaliseOuvranteEtEnTeteHTML($festival->titre);
  echoBaliseOuvranteBody();
  echoTitrefestival($festival);
  echoProgramme($festival);
  echoPiedDePage();
  echoBalisesFermantesBodyEtHTML();
};

// Envoi au client de tout ce qui permet l'affichage d'une page specifique sur un artiste

function echoDetailsSurUnArtiste($festival,$id_artiste)
{
  $artiste_trouve=0;
  foreach ($festival->seances as $seance)
  {
    if ((isset($seance->artiste))&&(isset(($seance->artiste)->id_artiste))&&(($seance->artiste)->id_artiste==$id_artiste))
    {
      $artiste_trouve=1;
      $artiste=$seance->artiste;
    };
  };
  echoBaliseOuvranteEtEnTeteHTML($festival->titre);
  echoBaliseOuvranteBody();
  echoTitreArtiste($artiste);
  echo $artiste->afficheArtisteComplet();
  echoPiedDePage();
  echoBalisesFermantesBodyEtHTML();
};

// Page des mentions légales

function echoPageMentionsLegales($festival)
{
  echoBaliseOuvranteEtEnTeteHTML($festival->titre);
  echoBaliseOuvranteBody();
  echoPiedDePage();
  echoBalisesFermantesBodyEtHTML();
};

?>
