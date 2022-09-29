<?php

// Classe pour l'objet contenant les infos globales de début de page
// Dans séance ne contient que le prénom en titre de l'artiste

class festival {

  public $titre;
  public $descriptif;
  public $lieu;
  public $date;
  public $seances;

  // constructeur à partir d'une chaine JSON contenant les informations totales
  public function __construct(string $json_string = null)
  {
    if (!(is_null($json_string))) // On ne fait tout ca que si une chaine JSON a effectivement été passee en argument
    {
      $valeurs_attributs_festival = json_decode($json_string);
      if (!(is_null($valeurs_attributs_festival))) // et on ne fait tout ca que si la chaine JSON a correctement ete decodee
      {
        foreach ($valeurs_attributs_festival as $attribut => $valeur)
        {
          $this->$attribut=$valeurs_attributs_festival->$attribut;
        };
      };
    }; // (sinon les propriétés internes de l'objet restent par défaut à NULL)
  }

}

// Classe pour les objets contenant les infos totales sur les artistes

class Artiste {

  public $id_artiste;
  public $titre;
  public $age;
  public $Passion;
  public $type; 
  public $synopsis;
  public $affiche;
  public $image_artiste;
  public $oeuvres;


  // constructeur a partir d'un objet de classe anonyme contenant les informations
  public function __construct($obj = null)
  {
    if (!(is_null($obj))) // On ne fait tout ca que si un objet a effectivement ete passe en argument
    {
      foreach ($obj as $attribut => $valeur)
      {
	if (property_exists($this,$attribut)) // on verifie qu'il s'agit bien d'une propriete definie dans la classe en cours
	{
	  if (($attribut=='id_artiste')&&(is_numeric($obj->$attribut)))
	    { $this->$attribut=intval($obj->$attribut); }
	  else
	    { $this->$attribut=$obj->$attribut; };
	};
      };
    }; // (sinon les propriétés internes de l'objet restent par défaut à NULL)
  }

  // fonction servant a afficher une vue reduite d'un artiste pour l'affichage dans le programme complet

  public function afficheartisteReduit($lateralite = "affiche_a_droite")
  {
    $reponse="";
    $reponse.=sprintf("          <div class=\"artiste %s\">\n",$lateralite);
    if (isset($this->titre))
    {
      $reponse.=sprintf("            <div class=\"liste_artiste\">\n");
      $reponse.=sprintf("              <h4 class=\"titre_artiste\">%s</h4>\n",$this->titre);
      $reponse.=sprintf("              <p class=\"type\">Type: %s </p>\n",$this->type);
      
      $debut_synopsis="";
      if (isset($this->synopsis)) {
	if (strlen($this->synopsis)<=256)
	  {
	    $debut_synopsis=$this->synopsis;
	  }
	else
	  {
	    $i=256;
	    // La ligne qui suit c'est pour ne pas couper au milieu d'un caractere Unicode code sur plusieurs octets en UTF-8
	    while(ord(substr($this->synopsis,$i,$i+1))>127) { $i--; };
	    $debut_synopsis=substr($this->synopsis,0,$i);
	  };
      };
      $reponse.=sprintf("              <p class=\"type\">%s&#x202F;...  <a class=\"voir_plus\" href=\"%s?id_artiste=%d\">(voir plus)<span>%s</span></a></p>\n",$debut_synopsis,$_SERVER['PHP_SELF'],$this->id_artiste,$this->synopsis);
      $reponse.=sprintf("            </div>\n");
    };
    if (isset($this->affiche))
    {
      $reponse.=sprintf("            <div class=\"affiche\">\n");
      $reponse.=sprintf("              <a href=\"%s?id_artiste=%d\"><img alt=\"Affiche de l'artiste *%s*\" src=\"%s\"/></a>\n",$_SERVER['PHP_SELF'],$this->id_artiste,$this->titre,$this->affiche);
      $reponse.=sprintf("            </div>\n");
    };
    $reponse.=sprintf("          </div>\n");
    return $reponse;
  }

  // fonction servant a afficher une vue complete d'un artiste pour son affichage en page entière

  public function afficheartisteComplet($lateralite = "affiche_a_droite")
  {
    $reponse="";
    $reponse.=sprintf("        <main class=\"page_artiste\">\n");
    $reponse.=sprintf("          <div class=\"artiste pleine_page %s\">\n",$lateralite);
    if (isset($this->titre))
    {
      $reponse.=sprintf("            <div class=\"liste_artiste\">\n");
      $reponse.=sprintf("              <h4 class=\"titre_artiste\">%s</h4>\n",$this->titre);
      $reponse.=sprintf("              <table>\n");
      if (isset($this->Passion)) {
	$reponse.=sprintf("                <tr><td>Passion:</td><td>%s</td></tr>\n",$this->Passion);
      };
      if (isset($this->type)) {
	$reponse.=sprintf("                <tr><td>Type: </td><td>%s</td></tr>\n",$this->type);
      };
      if (isset($this->age)) {
	$reponse.=sprintf("                <tr><td>Age:</td><td>%s</td></tr>\n",$this->age);
      };
      
      if (isset($this->synopsis)) {
	$reponse.=sprintf("                <tr><td>Résumé:</td><td>%s</td></tr>\n",$this->synopsis);
      };
  
      $reponse.=sprintf("              </table>\n");
    };
    if (isset($this->affiche))
    {
      $reponse.=sprintf("            <div class=\"affiche\">\n");
      $reponse.=sprintf("              <img alt=\"Affiche_artiste *%s*\" src=\"%s\"/>\n",$this->titre,$this->affiche);
      $reponse.=sprintf("            </div>\n");
    };
    $reponse.=sprintf("          </div>\n");
      if (isset($this->image_artiste)) {
	$reponse.=sprintf("          <div class=\"oeuvres\">\n");
	$reponse.=sprintf("            <h4>Leurs oeuvres</h4>\n");
	$reponse.=sprintf("<img class=\"oeuvres\" src=\"%s\" alt=\"image\">  \n",$this->image_artiste);
  $reponse.=sprintf("%s \n",$this->oeuvres);
	$reponse.=sprintf("          </div>\n");
  $reponse.=sprintf("          </div>\n");
      };
      
    $reponse.=sprintf("        </main>\n");
    return $reponse;
  }

}



class Seance {

  public $id_seance;
  public $prenom_nom;
  public $artiste;

  // constructeur a partir d'un objet de classe anonyme contenant les informations sur le prénom des artistes
  // ces index et les objets artiste et Lieu contenant le detail des informations sur eux
  // projete et le lieu de projection
  public function __construct($obj = null, $tableauArtistes)
  {
    if (!(is_null($obj))) // On ne fait tout ca que si un objet a effectivement ete passe en argument
    {
      foreach ($obj as $attribut => $valeur)
      {
	if ($attribut=='id_seance')
	{
	  if (is_numeric($obj->$attribut))
	    { $this->$attribut=intval($obj->$attribut); }
	  else
	    { $this->$attribut=$obj->$attribut; };
	}
	// si l'objet passe en argument a un attribut id_artiste et qu'on retrouve
	// un objet artiste avec cet indice dans la table des artistes, on accroche ici
	// l'objet artiste en question
	else if (($attribut=='id_artiste')&&(is_numeric($obj->$attribut)))
	{
	  $id_artiste=intval($obj->id_artiste);
	  if (isset($tableauArtistes[$id_artiste]))
	    { $this->artiste=$tableauArtistes[$id_artiste]; };
	}


	// l'objet Lieu en question

	else if ($attribut=='prenom_nom')
	{
	  $this->$attribut=$obj->$attribut;
	};
      };
    }; // (sinon les propriétés internes de l'objet restent par défaut à NULL)
  }

  public function afficheDateEtHeureEnLangueNaturelle($langue)
  {
    $reponse="";
    if (isset($this->prenom_nom))
      {
	$reponse=$this->prenom_nom;
	$separation_niveau_1=explode(" ",$this->prenom_nom);
	if (count($separation_niveau_1)==2)
	  {
	    $partie_date=$separation_niveau_1[0];
	    $partie_heure=$separation_niveau_1[1];
	    $separation_niveau_2_date=explode("-",$partie_date);
	    $separation_niveau_2_heure=explode(":",$partie_heure);
	    if ((count($separation_niveau_2_date)==3)&&(count($separation_niveau_2_heure)==3))
	      {
		$ch_age=$separation_niveau_2_date[0];
		$ch_mois=$separation_niveau_2_date[1];
		$ch_jour=$separation_niveau_2_date[2];
		$ch_heure=$separation_niveau_2_heure[0];
		$ch_minutes=$separation_niveau_2_heure[1];
		$ch_secondes=$separation_niveau_2_heure[2];
		if ((is_numeric($ch_age))&&(is_numeric($ch_mois))&&(is_numeric($ch_jour))
		    &&(is_numeric($ch_heure))&&(is_numeric($ch_minutes))&&(is_numeric($ch_secondes)))
		  {
		    $age=intval($ch_age);
		    $mois=intval($ch_mois);
		    $jour=intval($ch_jour);
		    $suffixesChiffres=array();
		    for ($i=1; $i<=31; $i++) { $suffixesChiffres[$i]=""; };
		    switch ($langue)
		      {	       
		      case "en":
			$entreJourEtMois=" of ";
			for ($i=1; $i<=31; $i++) { $suffixesChiffres[$i]="th"; };
			$suffixesChiffres[1]="st";
			$suffixesChiffres[2]="nd";
			$suffixesChiffres[3]="rd";
			$suffixesChiffres[21]="st";
			$suffixesChiffres[22]="nd";
			$suffixesChiffres[23]="rd";
			$suffixesChiffres[31]="st";
			$entreDateEtHeure=" at ";
			$nomsMois=array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June",
					7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");
			break;
		      case "fr":
			$entreJourEtMois=" ";
			$suffixesChiffres[1]="er";
			$entreDateEtHeure=" à ";
			$nomsMois=array(1 => "janvier", 2 => "f&#xE9;vrier", 3 => "mars", 4 => "avril", 5 => "mai", 6 => "juin",
					7 => "juillet", 8 => "ao&#xFB;t", 9 => "septembre", 10 => "octobre", 11 => "novembre", 12 => "d&#xE9;cembre");
			break;
		      case "de":
			$entreJourEtMois=". ";
			$entreDateEtHeure=" um ";
			$nomsMois=array(1 => "Januar", 2 => "Februar", 3 => "MÃ¤rz", 4 => "April", 5 => "Mai", 6 => "Juni",
					7 => "Juli", 8 => "August", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Dezember");
			break;
		      default:
			$entreJourEtMois="/";
			$entreDateEtHeure=", ";
			$nomsMois=array(1 => "01", 2 => "02", 3 => "03", 4 => "04", 5 => "05", 6 => "06", 7 => "07", 8 => "08", 9 => "09", 10 => "10", 11 => "11", 12 => "12");
		      };
		    $reponse=$jour;
		    $reponse.=$suffixesChiffres[$jour].$entreJourEtMois.$nomsMois[$mois]." ".$age.$entreDateEtHeure.$ch_heure.":".$ch_minutes;
		  };
	      };
	  };
      };
    return $reponse;
  }

  public function afficheSeance($langue)
  {
    $reponse="";
    if (isset($this->id_seance))
    {
      $reponse.=sprintf("      <section class=\"seance\" id=\"seance%02d\">\n",($this->id_seance));
    }
    else
    {
      $reponse.=sprintf("      <section class=\"seance\">\n");
    };
    if (isset($this->prenom_nom))
    {
      $reponse.=sprintf("        <h3 class=\"dhseance\">%s</h3>\n",$this->afficheDateEtHeureEnLangueNaturelle($langue));
    }
    else
    {
      $reponse.=sprintf("        <p>%s</p>\n",message("date_a_preciser",$langue));
    };
    if (isset($this->artiste))
    {
      if ((isset($this->id_seance))&&(($this->id_seance % 2) == 0))
	{
	  $reponse.=($this->artiste)->afficheartisteReduit("affiche_a_gauche");
	}
      else
	{
	  $reponse.=($this->artiste)->afficheartisteReduit("affiche_a_droite");
	};
    };
    if (isset($this->lieu))
    {
      $reponse.=sprintf("        <p>%s</p>\n",($this->lieu)->afficheNomEtAdresseLieu());
    }
    else
    {
      $reponse.=sprintf("        <p>%s</p>\n",message("lieu_a_preciser",$langue));
    };
    $reponse.=sprintf("      </section>\n");
    return $reponse;
  }

}

?>
