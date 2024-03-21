<?php

/**
 * Klasse Validation
 *
 * Eine Klasse zur Validierung von Eingabedaten mit zusätzlichen Funktionen für Regeldefinition und Fehlerverwaltung.
 *
 * Autor:   K3nguruh <k3nguruh at mail dot de>
 * Version: 1.1.2
 * Datum:   2024-03-21 23:45
 * Lizenz:  MIT-Lizenz
 */

class Validation extends Validate
{
  private const DEFAULT_SEPARATOR = "||";

  private $value;
  private $alias;
  private $rules;
  private $errors = [];
  private $count = 0;

  /**
   * Setzt den Wert für die Validierung.
   *
   * Diese Methode akzeptiert einen Wert und entfernt führende und abschließende Leerzeichen.
   * Der Wert wird dann für die Validierung festgelegt, und ein Alias wird automatisch generiert,
   * um eine eindeutige Identifikation während des Validierungsprozesses zu gewährleisten.
   *
   * @param mixed $value Der Wert, der validiert werden soll.
   * @return $this Das Validation-Objekt für Methodenverkettungen.
   */
  public function setValue($value)
  {
    $this->value = trim($value);
    $this->alias = $this->count++;
    $this->rules = [];

    return $this;
  }

  /**
   * Setzt den Wert aus einem Array basierend auf dem angegebenen Namen.
   *
   * Diese Methode extrahiert den Wert aus dem übergebenen Array unter Verwendung des angegebenen Namens.
   * Der Wert wird dann für die Validierung festgelegt, und der angegebene Name wird als Alias verwendet.
   *
   * @param array $array Das Array, aus dem der Wert entnommen wird.
   * @param string $name Der Name des Werts im Array.
   * @return $this Das Validation-Objekt für Methodenverkettungen.
   */
  public function setArray($array, $name)
  {
    $this->value = trim($array[$name]);
    $this->alias = $name;
    $this->rules = [];

    return $this;
  }

  /**
   * Setzt einen Alias für den aktuellen Wert.
   *
   * Diese Methode setzt einen benutzerdefinierten Alias für den aktuellen Wert, um eine klarere Identifikation
   * während des Validierungsprozesses zu ermöglichen.
   *
   * @param string $name Der Alias des aktuellen Werts.
   * @return $this Das Validation-Objekt für Methodenverkettungen.
   */
  public function setAlias($name)
  {
    $this->alias = $name;

    return $this;
  }

  /**
   * Fügt eine Validierungsregel hinzu.
   *
   * Diese Methode fügt eine Validierungsregel hinzu, die angibt, wie der Wert validiert werden soll,
   * und die entsprechende Fehlermeldung im Falle eines Fehlers.
   *
   * @param string $rule Die Validierungsregel.
   * @param string $message Die Fehlermeldung im Falle eines Fehlers.
   * @return $this Das Validation-Objekt für Methodenverkettungen.
   */
  public function setRule($rule, $message)
  {
    $this->rules[] = ["rule" => $rule, "message" => $message];

    return $this;
  }

  /**
   * Validiert den gesetzten Wert anhand der festgelegten Regeln.
   *
   * Diese Methode durchläuft alle festgelegten Regeln und überprüft den Wert gemäß jeder Regel.
   * Im Falle eines Fehlers wird die entsprechende Fehlermeldung gespeichert.
   *
   * @return $this Das Validation-Objekt für Methodenverkettungen.
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

      if (!$this->$methodName($this->value, ...$args)) {
        $this->errors[$this->alias] = $rule["message"];
        break;
      }
    }

    return $this;
  }

  /**
   * Gibt alle Fehlermeldungen zurück, die während der Validierung aufgetreten sind.
   *
   * @return array Die Fehlermeldungen.
   */
  public function getErrors()
  {
    return $this->errors;
  }
}
