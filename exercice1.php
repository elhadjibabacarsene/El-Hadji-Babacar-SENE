<?php

session_start();

set_time_limit(0);
            /**
                 * renvoi bool True si le nombre est premier, False sinon
            **/
            function nombrePremier($n){
                $i=2;
                $c=0;
                while ($i <= $n/2 && $c==0) {
                    if($n%$i==0){
                        $c=1;
                    }
                    $i++;
                }
                if($c==0){
                    $st = true;
                }else{
                    $st = false;
                }
                return $st;
            }
             /**
                * renvoi la taille du tableau
            **/
            function compteur($tab){
                $cpt = 0;
                foreach($tab as $key){
                    $cpt++;
                }
                return $cpt;
            }
            /**
                * calcul et renvoi la moyenne
            **/
            function moyenne($tab){
                for($i=0;$i<compteur($tab);$i++){
                    $som = $som + $tab[$i];
                }
                return $som/compteur($tab);
            }
            
    
            /**
                * Permet d'afficher un tableau
            **/

            function affichageTable($tab){
                $nbreCol = 20;
                $nbreData = compteur($tab);
                //calcul du nombre de ligne
                if(round($nbreData/$nbreCol)!=($nbreData/$nbreCol)){
                    $nbreLigne = round(($nbreData/$nbreCol)+0.5);
                }else{
                    $nbreLigne = $nbreData / $nbreCol;
                }
                return $nbreLigne;
            }


            function afficherTable($tab){
                $nbreCol = 20;
                $nbreData = compteur($tab);
                //calcul du nombre de ligne
                if(round($nbreData/$nbreCol)!=($nbreData/$nbreCol)){
                    $nbreLigne = round(($nbreData/$nbreCol)+0.5);
                }else{
                    $nbreLigne = $nbreData / $nbreCol;
                }
                //Affichage
                if($nbreData != 0){
                    $k=0;
                    echo '<table border="1">';
                        for($i=1;$i<=$nbreLigne;$i++){
                            echo '<tr>';
                                for($j=1;$j<=$nbreCol;$j++){
                                    if($k<$nbreData){
                                        echo '<td>';
                                        echo $tab[$k];
                                        echo '<td>';
                                        $k++;
                                    }else{
                                        //vide
                                        echo '<td> </td>';
                                    }
                                }
                            echo '</tr>';
                            $j=1;
                        }
                echo '</table>';
                }else{
                    echo 'Pas de données à afficher';
                }

            }


            function afficheTable($tab){
                echo '<table class="tableau">';
                echo '<tr>';
                for($i=0;$i<compteur($tab);$i++){
                    echo '<td>'.$tab[$i].'</td>';
                }
                echo '</tr>';
                echo '</table>';

            }



            /**
             * FONCTION DE PAGINATION
             */
            function pagination($tab){
                
                //TOTAL VALEUR
                $totalValeur = compteur($tab);
                //NOMBRE DE VALEUR PAR PAGE
                $nbreParPage = 100;
                //NOMBRE DE PAGES
                $nbreDePage = ceil($totalValeur / $nbreParPage);
                
                

                //Affichage de la valeur de la première page
                if(!isset($_GET['page'])){
                    $page = 1;
                }else{
                    $page = $_GET['page'];
                }
                if($page<=$nbreDePage){
                    $indiceFin = ($page)*$nbreParPage;
                    $indiceDepart = $indiceFin - $nbreParPage;
                    echo '<br><br>';
                    echo '<table border="1">';

                            echo '<tr>';
                                    for($i=$indiceDepart;$i<$indiceFin;$i++){
                                        if(!empty($tab[$i])){
                                            echo '<td>'.$tab[$i].'</td>';
                                        }
                                        
                                    }
                            echo '</tr>';

                    echo '</table>';
                    //AFFICHAGE DES NUMEROS DE PAGES
                    for($page=1;$page<=$nbreDePage;$page++){
                        echo '<a href="exercice1.php?page='.$page.'&c=true"> '.$page.' </a>';
                    }
                }else{
                    echo '<br>Pas de données à afficher';
                    
                }
                
            }
            

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="exo1.css<?php echo "?".rand();?>" rel="stylesheet">
    <title>Exercice 1</title>
</head>
<body>
    

  

<?php
    /**
     * TRAITEMENT
     */
    
    $valError = "";

    if($_GET['c'] == "true"){//Vérifie si la session doit toujours être tenue en compte
        $isSuccess = true;
    }else{
        $isSuccess = false;
        session_destroy(); // Destruction de la session au cas contraire
    }
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $val = $_POST['val'];
            $isSuccess = true;
            $t1 = array();
            $t = array();
        

        
        
        if(preg_match('#[^0-9]#',$val)){//permet de savoir si le user à saisie une valeur numérique
            $valError = "Veuillez saisir une valeur numérique";
            $isSuccess = false;
        }
                
                /**
                 * SI TOUT LES CONTRÔLE SONT VALIDES
                 */
                if($isSuccess){

                        $valError ="";
                        for($i=2;$i<$val;$i++){
                            if(nombrePremier($i)){
                                $t1[] = $i;
                            }
                        }
                        
                        //Trier en fonction de la moyenne et faire l'affectation des les 2 arrays
                            for($i=0;$i<compteur($t1);$i++){
                                if(moyenne($t1)>$t1[$i]){
                                    $inferieur[]=$t1[$i];
                                }else{
                                    $superieur[]=$t1[$i];
                                }
                            }

                        //affection de inf et sup à T
                        $t['inferieur'] = $inferieur;
                        $t['superieur'] = $superieur;
                        
                        $_SESSION['val'] = $val;
                        $_SESSION['inferieur'] = $inferieur;
                        $_SESSION['superieur'] = $superieur;
                        
                       
            }
        }
        
?>

    <form action="exercice1.php?c=true" method="POST" class="form">
        <label class="lblPut">Entrer une valeur</label><br><br>
            <input type="text" name="val" id="val" class="putVal" value="<?=($isSuccess ? $_SESSION['val'] : $val);?>"><br>
                <p class="comment"><?=$valError?></p>
            <input type="submit" value="Valider" class="btnPut">
    </form>
        
        <?php

            
                if($isSuccess){
                    pagination($_SESSION['inferieur']);
                    pagination($_SESSION['superieur']);
                }
                
        ?>

</body>
</html>