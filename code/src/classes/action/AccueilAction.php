<?php

namespace iutnc\nrv\action;

class AccueilAction extends Action {

    public function execute(): string
    {
        return <<<END
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Bienvenue sur notre site</title>
            <link rel="stylesheet" type="text/css" href="style.css?v=1">
        </head>
        <body>
            <div class="container">
                <h1>Bienvenue sur notre site</h1>
                <p>Réalisé par Emma, Mathieu, Manech et Antoine.</p>
            </div>
        </body>
        </html>
        END;
    }
}
