<?php

namespace iutnc\nrv\action;

class AccueilAction extends Action{

    public function execute(): string
    {
        return <<<END
        <p>page accueil</p>
        END;

    }
}