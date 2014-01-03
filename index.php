<?php
    require_once 'loader.php';

    class Concept {
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
    }

    $concepts = array();

    $concepts[] = new Concept("Concept / Descripteur Vedette","linkconcept1");
        $concepts[0]->AddTerme(new Terme("Synonyme 1","linkterme1"));
        $concepts[0]->AddTerme(new Terme("Synonyme 2","linkterme2"));
        $concepts[0]->AddTerme(new Terme("Synonyme 3","linkterme3"));
    $concepts[] = new Concept("Concept2","linkconcept2");
        $concepts[1]->AddTerme(new Terme("Terme1","linkterme1"));
        $concepts[1]->AddTerme(new Terme("Terme2","linkterme2"));
        $concepts[1]->AddTerme(new Terme("Terme3","linkterme3"));
    $concepts[] = new Concept("Concept3","linkconcept3");
        $concepts[2]->AddTerme(new Terme("Terme1","linkterme1"));
        $concepts[2]->AddTerme(new Terme("Terme2","linkterme2"));
        $concepts[2]->AddTerme(new Terme("Terme3","linkterme3"));
        $concepts[2]->AddTerme(new Terme("Terme1","linkterme1"));
        $concepts[2]->AddTerme(new Terme("Terme2","linkterme2"));
        $concepts[2]->AddTerme(new Terme("Terme3","linkterme3"));
        $concepts[2]->AddTerme(new Terme("Terme1","linkterme1"));
        $concepts[2]->AddTerme(new Terme("Terme2","linkterme2"));
        $concepts[2]->AddTerme(new Terme("Terme3","linkterme3"));
        $concepts[2]->AddTerme(new Terme("Terme1","linkterme1"));
        $concepts[2]->AddTerme(new Terme("Terme2","linkterme2"));
        $concepts[2]->AddTerme(new Terme("Terme3","linkterme3"));
        $concepts[2]->AddTerme(new Terme("Terme1","linkterme1"));
        $concepts[2]->AddTerme(new Terme("Terme2","linkterme2"));
        $concepts[2]->AddTerme(new Terme("Terme3","linkterme3"));
    $concepts[] = new Concept("Concept3","linkconcept3");
    $concepts[] = new Concept("Concept3","linkconcept3");
    $concepts[] = new Concept("Concept3","linkconcept3");
    $concepts[] = new Concept("Concept3","linkconcept3");
    $concepts[] = new Concept("Concept3","linkconcept3");
    $concepts[] = new Concept("Concept3","linkconcept3");
    $concepts[] = new Concept("Concept3","linkconcept3");
    $concepts[] = new Concept("Concept3","linkconcept3");
    $concepts[] = new Concept("Concept3","linkconcept3");

    echo $twig->render('index.twig', array('page' => 'index', 'concepts' => $concepts));
?>
