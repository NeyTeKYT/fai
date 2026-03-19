<?php

    // Fonction qui renvoie un message d'interprétation en fonction du score calculé pour l'IA
    function interpreter_similarite($score) {

        if($score >= 0.6) return ["label" => "Très similaire", "class" => "bg-success"];
        elseif($score >= 0.4) return ["label" => "Bonne similarité", "class" => "bg-info"]; 
        elseif($score >= 0.2) return ["label" => "Similarité moyenne", "class" => "bg-warning text-dark"]; 
        else return ["label" => "Faible similarité", "class" => "bg-secondary"];

    }

?>