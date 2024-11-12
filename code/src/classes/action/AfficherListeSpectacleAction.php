<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;
use iutnc\nrv\render\RenderSpectacle;

class AfficherListeSpectacleAction extends Action
{
    public function execute(): string {
        // Récupérer le filtre et la valeur sélectionnée dans la requête GET
        $filtre = $_POST['filtre'] ?? '';
        $valeur = $_POST['valeur'] ?? '';

        // Si aucun filtre n'est sélectionné, on affiche tous les spectacles par défaut
        if ($filtre === '' || $valeur === '') {
            $spectacles = Repository::getInstance()->trouveTousSpectacles();
        } else {
            $spectacles = Repository::getInstance()->trouveSpectaclesFiltres($filtre, $valeur);
        }

        // Générer l'affichage des spectacles
        $html = "<h1>Liste des Spectacles</h1><ul>";
        foreach ($spectacles as $spectacle) {
            $renderSpectacle = new RenderSpectacle($spectacle);
            $html .= "<li>" . $renderSpectacle->render(1) . "</li><br>";
        }
        $html .= "</ul>";

        // Ajouter le formulaire de filtre
        $html .= $this->genererFormulaireFiltre($filtre);

        return $html;
    }

    private function genererFormulaireFiltre(string $filtreActuel): string {
        // Générer le formulaire de sélection du filtre
        $form = '<form method="post" action="">';
        $form .= '<label for="cmbFiltre">Choisir un filtre :</label>';
        $form .= '<select id="cmbFiltre" name="filtre" onchange="this.form.submit()">';
        $form .= '<option value=""' . ($filtreActuel === '' ? ' selected' : '') . '>Choisir un filtre</option>';
        $form .= '<option value="style"' . ($filtreActuel === 'style' ? ' selected' : '') . '>Style</option>';
        $form .= '<option value="nom_lieu"' . ($filtreActuel === 'nom_lieu' ? ' selected' : '') . '>Nom du lieu</option>';
        $form .= '<option value="date"' . ($filtreActuel === 'date' ? ' selected' : '') . '>Date</option>';
        $form .= '</select>';

        // Ajouter la combobox des options en fonction du filtre sélectionné
        $form .= $this->genererComboboxOptions($filtreActuel);

        // Bouton de soumission
        $form .= '<input type="submit" value="Filtrer">';
        $form .= '</form>';

        return $form;
    }

    private function genererComboboxOptions(string $filtre): string {
        // Vérifier si un filtre est sélectionné
        if ($filtre === '') return '';

        // Récupérer les options disponibles pour le filtre via le Repository
        $options = Repository::getInstance()->trouveOptionsPourFiltre($filtre);

        // Générer la combobox des valeurs
        $combobox = '<select id="cmbValeur" name="valeur">';
        $combobox .= '<option value="">Choisir une valeur</option>';
        foreach ($options as $row) {
            $valeur = $row[$filtre];
            $combobox .= "<option value='{$valeur}'>{$valeur}</option>";
        }
        $combobox .= '</select>';

        return $combobox;
    }
}
