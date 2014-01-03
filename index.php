<?php
    require_once 'loader.php';

    /*class Concept {
        public $termes = array();
        public $nom;
        public $link;

        function Concept($nom, $link) {
            $this->nom = $nom;
            $this->link = $link;
        }

        function AddTerme($terme) {
            $this->termes[] = $terme;
        }
    }

    class Terme {
        public $nom;
        public $link;

        function Terme($nom, $link) {
            $this->nom = $nom;
            $this->link = $link;
        }
    }*/
    //Liste de termes
    $stmt = $db->prepare(   'SELECT d.libelle
                            FROM descripteurVedette d
                            ORDER BY d.libelle');
    $stmt->execute();
    $termes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo $twig->render('index.twig', array('page' => 'index', 'termes' => $termes));
?>
