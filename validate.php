<?php

//
// Notwendige Dateien einbinden.
require_once "includes/classes/validate.php";

//
// Klasse initialisieren
$validate = new Validate();

//
//
$post = [
  "id" => "100", // = Fehler -> 4-stellige Zahl ist erforderlich
  "name" => "", // = Fehler -> name darf nicht leer sein
  "datum" => "1980-06-15 00:00", // Fehler -> datum muss im Format 'Y-m-d' sein
  "alter" => "15", // Fehler -> alter muss >= 16 sein
];
$errors = [];

//
//
if (!$validate->validateRequired($post["id"])) {
  $errors[] = "Bitte eine ID eingeben.";
}

if (!$validate->validateMatch($post["id"], "[1-9]\d{3}")) {
  $errors[] = "Bitte eine gültige ID eingeben.";
}

//
//
if (!$validate->validateRequired($post["name"])) {
  $errors[] = "Bitte einen Namen eingeben.";
}

//
//
if (!$validate->validateRequired($post["datum"])) {
  $errors[] = "Bitte ein Datum eingeben.";
}

if (!$validate->validateDate($post["datum"], "Y-m-d")) {
  $errors[] = "Bitte ein gültiges Datum eingeben.";
}

//
//
if (!$validate->validateRequired($post["alter"])) {
  $errors[] = "Bitte ein Alter eingeben.";
}

if (!$validate->validateMin($post["alter"], "16")) {
  $errors[] = "Du musst 16 Jahre oder älter sein.";
}

//
//
if ($errors) {
  print_r($errors);
} else {
  echo "Alles in Ordnung.";
}
