<?php

/**
 * Klasse Validation
 *
 * Eine Klasse zur Validierung von Eingabedaten mit zusätzlichen Funktionen für Regeldefinition und Fehlerverwaltung.
 *
 * Autor:   K3nguruh <k3nguruh at mail dot de>
 * Version: 1.0.0
 * Datum:   2024-03-13 16:41
 * Lizenz:  MIT-Lizenz
 */
class Validation extends Validate
{
  private const DEFAULT_SEPARATOR = "||";

  private $check;
  private $alias;
  private $rules = [];
  private $errors = [];

  /**
   * Legt die zu prüfenden Daten und das zugehörige Feld fest.
   *
   * Diese Methode akzeptiert ein Array ($array) und den Namen des zu prüfenden Felds ($name).
   * Sie setzt das zu prüfende Datenfeld ($check) auf den entsprechenden Wert im Array.
   * Das Alias ($alias) des Felds wird auf den angegebenen Feldnamen festgelegt.
   * Die Methode initialisiert auch die Regeln ($rules) für die Validierung auf ein leeres Array.
   *
   * @param array $array Das Array, das die zu prüfenden Daten enthält.
   * @param string $name Der Name des zu prüfenden Felds.
   * @return $this Das Objekt selbst, um Methodenverkettungen zu ermöglichen.
   */
  public function setCheck($array, $name)
  {
    $this->check = trim($array[$name]);
    $this->alias = $name;
    $this->rules = [];

    return $this;
  }

  /**
   * Legt das Alias für das zu prüfende Feld fest.
   *
   * Diese Methode akzeptiert den Namen des zu prüfenden Felds ($name) und setzt das Alias ($alias) des Felds auf diesen Wert.
   *
   * @param string $name Der Name des zu prüfenden Felds.
   * @return $this Das Objekt selbst, um Methodenverkettungen zu ermöglichen.
   */
  public function setAlias($name)
  {
    $this->alias = $name;

    return $this;
  }

  /**
   * Fügt eine Validierungsregel für das zu prüfende Feld hinzu.
   *
   * Diese Methode akzeptiert eine Validierungsregel ($rule) und eine zugehörige Fehlermeldung ($message).
   * Sie fügt das Regel-Array ["rule" => $rule, "message" => $message] zu den Regeln ($rules) für die Validierung hinzu.
   *
   * @param string $rule Die Validierungsregel, die hinzugefügt werden soll.
   * @param string $message Die Fehlermeldung, die der Regel zugeordnet ist.
   * @return $this Das Objekt selbst, um Methodenverkettungen zu ermöglichen.
   */
  public function setRule($rule, $message)
  {
    $this->rules[] = ["rule" => $rule, "message" => $message];

    return $this;
  }

  /**
   * Führt die Validierung des übergebenen Felds gemäß den festgelegten Validierungsregeln durch.
   *
   * Diese Methode durchläuft die festgelegten Validierungsregeln ($rules) für das zu prüfende Feld.
   * Für jede Regel wird die entsprechende Validierungsmethode aufgerufen und überprüft, ob die Validierung erfolgreich ist.
   * Wenn eine Regel verletzt wird, wird die entsprechende Fehlermeldung festgehalten und der Validierungsprozess abgebrochen.
   * Am Ende wird das Objekt selbst zurückgegeben, um Methodenverkettungen zu ermöglichen.
   *
   * @return $this Das Objekt selbst, um Methodenverkettungen zu ermöglichen.
   * @throws InvalidArgumentException Wenn eine ungültige Validierungsregel gefunden wird.
   */
  public function validate()
  {
    foreach ($this->rules as $rule) {
      $args = explode(self::DEFAULT_SEPARATOR, $rule["rule"]);
      $methodName = "validate" . ucfirst(array_shift($args));

      if (!method_exists($this, $methodName)) {
        throw new InvalidArgumentException("Ungültige Validierungsregel: {$methodName}");
      }

      if (!$this->$methodName($this->check, ...$args)) {
        $this->errors[$this->alias] = $rule["message"];
        break;
      }
    }

    return $this;
  }

  /**
   * Gibt die gesammelten Fehlermeldungen zurück, die während des Validierungsprozesses aufgetreten sind.
   *
   * Diese Methode gibt ein Array von Fehlermeldungen zurück, das die Fehler für jedes validierte Feld enthält.
   * Die Fehlermeldungen sind nach dem Feldnamen (Alias) indiziert.
   *
   * @return array Ein assoziatives Array von Fehlermeldungen, das die Fehler für jedes validierte Feld enthält.
   */
  public function errors()
  {
    return $this->errors;
  }
}
