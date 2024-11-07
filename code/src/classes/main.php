<?php

use iutnc\nrv\evenement\Spectacle;


require_once __DIR__ . '/../../vendor/autoload.php';

// Test du css 
$spect = new Spectacle(1, "Pestacle", "Lofi Girl", "../../images/photo_profil/Lofi_Girl_logo.jpg", 140, "Lo-fi", "Un spectacle de lo-fi", "https://www.youtube.com/embed/5qap5aO4i9A");

$renderer = new iutnc\nrv\render\RenderSpectacle($spect);
echo <<<END
			<!doctype html>
			<head>
				<title>NRV</title>
				<link rel="stylesheet" type="text/css" href="style.css">
			</head>
END;
echo $renderer->render(2);
