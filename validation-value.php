<?php

//
// Notwendige Dateien einbinden.
require_once "includes/classes/validate.php";
require_once "includes/classes/validation.php";

//
// Klasse initialisieren
$validation = new Validation();

//
//
$post = [
  "id" => "100", // = Fehler -> 4-stellige Zahl ist erforderlich
  "name" => "", // = Fehler -> name darf nicht leer sein
  "datum" => "1980-06-15 00:00", // Fehler -> datum muss im Format 'Y-m-d' sein
  "alter" => "15", // Fehler -> alter muss >= 16 sein
];

//
//
$validation
  ->setValue($post["id"])
  ->setRule("required", "Bitte eine ID eingeben.")
  ->setRule("match||[1-9]\d{3}", "Bitte eine gültige ID eingeben.")
  ->validate();

//
//
$validation
  ->setValue($post["name"])
  ->setAlias("name-2")
  ->setRule("required", "Bitte einen Namen eingeben.")
  ->validate();

//
//
$validation
  ->setValue($post["datum"])
  ->setRule("required", "Bitte eine Datum eingeben.")
  ->setRule("date||Y-m-d", "Bitte ein gültiges Datum eingeben.")
  ->validate();

//
//
$validation
  ->setValue($post["alter"])
  ->setRule("required", "Bitte ein Alter eingeben.")
  ->setRule("min||16", "Du musst 16 Jahre oder älter sein.")
  ->validate();

//
//
$errors = $validation->errors();

//
//
if ($errors) {
  print_r($errors);
} else {
  echo "Alles in Ordnung.";
}
