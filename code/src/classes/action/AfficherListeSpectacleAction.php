<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;
use iutnc\nrv\render\RenderSpectacle;

class AfficherListeSpectacleAction extends Action
{
    public function execute(): string {
        $status = '';

        // Vérifier si le formulaire d'annulation a été soumis
        if (isset($_POST['annuler']) && isset($_POST['id'])) {
            $idSpectacle = (int)$_POST['id'];
            $repo = Repository::getInstance();
            $spectacle = $repo->afficherSpectacle($idSpectacle);

            if ($spectacle !== null) {
                $repo->annulerSpectacle($idSpectacle);
                $status = 'annule';
            } else {
                $status = 'not-found';
            }
        }

        // Récupérer le filtre et la valeur sélectionnée dans la requête POST
        $filtre = $_POST['filtre'] ?? '';
        $valeur = $_POST['valeur'] ?? '';

        // Récupérer la liste des spectacles en fonction du filtre
        if ($filtre === '' || $valeur === '') {
            $spectacles = Repository::getInstance()->trouveTousSpectacles();
        } else {
            $spectacles = Repository::getInstance()->trouveSpectaclesFiltres($filtre, $valeur);
        }

        // Initialiser le HTML avec l'affichage des messages d'état
        $html = $this->afficherMessageStatus($status);

        // Générer l'affichage des spectacles
        $html .= "<h1>Liste des Spectacles</h1><ul>";
        foreach ($spectacles as $spectacle) {
            $renderSpectacle = new RenderSpectacle($spectacle);
            $html .= "<li>" . $renderSpectacle->render(1) . "</li><br>";
        }
        $html .= "</ul>";

        // Ajouter le formulaire de filtre
        $html .= $this->genererFormulaireFiltre($filtre);

        return $html;
    }

    private function afficherMessageStatus(string $status): string {
        $html = '';

        if ($status === 'annule') {
            $html = '<p class="message-success">Le spectacle a été annulé avec succès.</p>';
        } elseif ($status === 'not-found') {
            $html = '<p class="message-error">Le spectacle n\'a pas été trouvé.</p>';
        } elseif ($status === 'missing') {
            $html = '<p class="message-error">Aucun ID de spectacle fourni.</p>';
        }

        return $html;
    }

    private function genererFormulaireFiltre(string $filtreActuel): string {
        $form = '<form method="post" action="">';
        $form .= '<label for="cmbFiltre">Choisir un filtre :</label>';
        $form .= '<select id="cmbFiltre" name="filtre" onchange="this.form.submit()">';
        $form .= '<option value=""' . ($filtreActuel === '' ? ' selected' : '') . '>Choisir un filtre</option>';
        $form .= '<option value="style"' . ($filtreActuel === 'style' ? ' selected' : '') . '>Style</option>';
        $form .= '<option value="nom_lieu"' . ($filtreActuel === 'nom_lieu' ? ' selected' : '') . '>Nom du lieu</option>';
        $form .= '<option value="date"' . ($filtreActuel === 'date' ? ' selected' : '') . '>Date</option>';
        $form .= '</select>';

        // Ajouter la combobox des options dynamiques
        $form .= $this->genererComboboxOptions($filtreActuel);

        // Bouton de soumission
        $form .= '<input type="submit" value="Filtrer">';
        $form .= '</form>';

        return $form;
    }

    private function genererComboboxOptions(string $filtre): string {
        if ($filtre === '') return '';

        $options = Repository::getInstance()->trouveOptionsPourFiltre($filtre);
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
