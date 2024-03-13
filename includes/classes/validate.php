<?php

/**
 * Klasse Validate
 *
 * Eine Klasse zur Validierung von Eingabedaten mit verschiedenen Methoden.
 * Unterstützt Validierung von erforderlichen Feldern, Gleichheit, Übereinstimmung mit regulären Ausdrücken,
 * E-Mail-Adressen, URLs, Datumsangaben und numerischen Werten.
 *
 * Autor:   K3nguruh <k3nguruh at mail dot de>
 * Version: 1.0.0
 * Datum:   2024-03-13 16:41
 * Lizenz:  MIT-Lizenz
 */

class Validate
{
  /**
   * Überprüft, ob ein Wert vorhanden ist.
   *
   * Diese Methode akzeptiert einen Wert ($value).
   * Sie gibt false zurück, wenn der Wert leer ist, ansonsten true.
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @return bool Gibt false zurück, wenn der Wert leer ist, ansonsten true.
   */
  public function validateRequired($value)
  {
    return !($value === "" || $value === null || $value === false || (is_array($value) && empty($value)));
  }

  /**
   * Überprüft, ob ein Wert gleich einem anderen Wert ist.
   *
   * Diese Methode akzeptiert zwei Werte ($value und $compare).
   * Sie gibt false zurück, wenn die Werte nicht übereinstimmen, ansonsten true.
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param mixed $compare Der Wert, mit dem verglichen werden soll.
   * @return bool Gibt false zurück, wenn die Werte nicht übereinstimmen, ansonsten true.
   */
  public function validateEqual($value, $compare)
  {
    return $value === $compare;
  }

  /**
   * Überprüft, ob ein Wert dem angegebenen regulären Ausdruck entspricht.
   *
   * Diese Methode akzeptiert einen Wert ($value) und einen regulären Ausdruck ($regex).
   * Sie gibt false zurück, wenn der Wert nicht dem regulären Ausdruck entspricht, ansonsten true.
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param string $regex Der reguläre Ausdruck, dem der Wert entsprechen soll.
   * @return bool Gibt false zurück, wenn der Wert nicht dem regulären Ausdruck entspricht, ansonsten true.
   */
  public function validateMatch($value, $regex)
  {
    return preg_match("/^{$regex}$/", $value) === 1;
  }

  /**
   * Überprüft, ob ein Wert eine gültige E-Mail-Adresse ist.
   *
   * Diese Methode akzeptiert einen Wert ($value) und überprüft, ob er eine gültige E-Mail-Adresse ist.
   * Sie gibt false zurück, wenn der Wert keine gültige E-Mail-Adresse ist, ansonsten true.
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @return bool Gibt false zurück, wenn der Wert keine gültige E-Mail-Adresse ist, ansonsten true.
   */
  public function validateEmail($value)
  {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
  }

  /**
   * Überprüft, ob ein Wert eine gültige URL ist.
   *
   * Diese Methode akzeptiert einen Wert ($value) und überprüft, ob er eine gültige URL ist.
   * Sie gibt false zurück, wenn der Wert keine gültige URL ist, ansonsten true.
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @return bool Gibt false zurück, wenn der Wert keine gültige URL ist, ansonsten true.
   */
  public function validateUrl($value)
  {
    return filter_var($value, FILTER_VALIDATE_URL) !== false;
  }

  /**
   * Überprüft, ob ein Wert ein gültiges Datum im angegebenen Format ist.
   *
   * Diese Methode akzeptiert einen Wert ($value) und optional ein Datumsformat ($format).
   * Sie gibt false zurück, wenn der Wert kein gültiges Datum im angegebenen Format ist, ansonsten true.
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param string $format Das Datumsformat, falls der Wert ein Datum ist (Standard: 'Y-m-d').
   * @return bool Gibt false zurück, wenn der Wert kein gültiges Datum im angegebenen Format ist, ansonsten true.
   */
  public function validateDate($value, $format = "Y-m-d")
  {
    $date = DateTime::createFromFormat($format, $value);
    return $date && $date->format($format) === $value;
  }

  /**
   * Überprüft, ob ein Wert größer oder gleich dem minimalen Wert ist, optional unter Berücksichtigung eines Datumsformats.
   *
   * Diese Methode akzeptiert einen Wert ($value) und einen minimalen Wert ($minValue).
   * Optional kann ein Datumsformat ($format) angegeben werden, falls der Wert ein Datum ist.
   * Die Methode gibt false zurück, wenn der Wert kleiner als der minimale Wert ist, ansonsten true.
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param mixed $minValue Der minimale Wert, der nicht unterschritten werden darf.
   * @param string|null $format Das Datumsformat, falls der Wert ein Datum ist (Standard: null).
   * @return bool Gibt false zurück, wenn der Wert kleiner als der minimale Wert ist, ansonsten true.
   */
  public function validateMin($value, $minValue, $format = null)
  {
    if ($format !== null) {
      $thisValue = DateTime::createFromFormat($format, $value);
      $minValue = DateTime::createFromFormat($format, $minValue);

      if (!$thisValue || !$minValue) {
        return false;
      }
    } else {
      $thisValue = is_numeric($value) ? $value : strlen($value);
    }

    return $thisValue >= $minValue;
  }

  /**
   * Überprüft, ob ein Wert kleiner oder gleich dem maximalen Wert ist, optional unter Berücksichtigung eines Datumsformats.
   *
   * Diese Methode akzeptiert einen Wert ($value) und einen maximalen Wert ($maxValue).
   * Optional kann ein Datumsformat ($format) angegeben werden, falls der Wert ein Datum ist.
   * Die Methode gibt false zurück, wenn der Wert größer als der maximale Wert ist, ansonsten true.
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param mixed $maxValue Der maximale Wert, der nicht überschritten werden darf.
   * @param string|null $format Das Datumsformat, falls der Wert ein Datum ist (Standard: null).
   * @return bool Gibt false zurück, wenn der Wert größer als der maximale Wert ist, ansonsten true.
   */
  public function validateMax($value, $maxValue, $format = null)
  {
    if ($format !== null) {
      $thisValue = DateTime::createFromFormat($format, $value);
      $maxValue = DateTime::createFromFormat($format, $maxValue);

      if (!$thisValue || !$maxValue) {
        return false;
      }
    } else {
      $thisValue = is_numeric($value) ? $value : strlen($value);
    }

    return $thisValue <= $maxValue;
  }

  /**
   * Überprüft, ob ein Wert zwischen einem minimalen und einem maximalen Wert liegt, optional unter Berücksichtigung eines Datumsformats.
   *
   * Diese Methode akzeptiert einen Wert ($value), einen minimalen Wert ($minValue) und einen maximalen Wert ($maxValue).
   * Optional kann ein Datumsformat ($format) angegeben werden, falls der Wert ein Datum ist.
   * Die Methode gibt false zurück, wenn der Wert nicht zwischen dem minimalen und maximalen Wert liegt, ansonsten true.
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param mixed $minValue Der minimale Wert, der nicht unterschritten werden darf.
   * @param mixed $maxValue Der maximale Wert, der nicht überschritten werden darf.
   * @param string|null $format Das Datumsformat, falls der Wert ein Datum ist (Standard: null).
   * @return bool Gibt false zurück, wenn der Wert nicht zwischen dem minimalen und maximalen Wert liegt, ansonsten true.
   */
  public function validateBetween($value, $minValue, $maxValue, $format = null)
  {
    if ($format !== null) {
      $thisValue = DateTime::createFromFormat($format, $value);
      $minValue = DateTime::createFromFormat($format, $minValue);
      $maxValue = DateTime::createFromFormat($format, $maxValue);

      if (!$thisValue || !$minValue || !$maxValue) {
        return false;
      }
    } else {
      $thisValue = is_numeric($value) ? $value : strlen($value);
    }

    return $thisValue >= $minValue && $thisValue <= $maxValue;
  }

  /**
   * Überprüft, ob ein Wert kleiner als der maximale Wert ist, optional unter Berücksichtigung eines Datumsformats.
   *
   * Diese Methode akzeptiert einen Wert ($value) und einen maximalen Wert ($maxValue).
   * Optional kann ein Datumsformat ($format) angegeben werden, falls der Wert ein Datum ist.
   * Die Methode gibt false zurück, wenn der Wert größer oder gleich dem maximalen Wert ist, ansonsten true.
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param mixed $maxValue Der maximale Wert, der nicht überschritten werden darf.
   * @param string|null $format Das Datumsformat, falls der Wert ein Datum ist (Standard: null).
   * @return bool Gibt false zurück, wenn der Wert größer oder gleich dem maximalen Wert ist, ansonsten true.
   */
  public function validateLess($value, $maxValue, $format = null)
  {
    if ($format !== null) {
      $thisValue = DateTime::createFromFormat($format, $value);
      $maxValue = DateTime::createFromFormat($format, $maxValue);

      if (!$thisValue || !$maxValue) {
        return false;
      }
    } else {
      $thisValue = is_numeric($value) ? $value : strlen($value);
    }

    return $thisValue < $maxValue;
  }

  /**
   * Überprüft, ob ein Wert größer als der minimale Wert ist, optional unter Berücksichtigung eines Datumsformats.
   *
   * Diese Methode akzeptiert einen Wert ($value) und einen minimalen Wert ($minValue).
   * Optional kann ein Datumsformat ($format) angegeben werden, falls der Wert ein Datum ist.
   * Die Methode gibt false zurück, wenn der Wert kleiner oder gleich dem minimalen Wert ist, ansonsten true.
   *
   * @param mixed $value Der zu überprüfende Wert.
   * @param mixed $minValue Der minimale Wert, der nicht unterschritten werden darf.
   * @param string|null $format Das Datumsformat, falls der Wert ein Datum ist (Standard: null).
   * @return bool Gibt false zurück, wenn der Wert kleiner oder gleich dem minimalen Wert ist, ansonsten true.
   */
  public function validateGreater($value, $minValue, $format = null)
  {
    if ($format !== null) {
      $thisValue = DateTime::createFromFormat($format, $value);
      $minValue = DateTime::createFromFormat($format, $minValue);

      if (!$thisValue || !$minValue) {
        return false;
      }
    } else {
      $thisValue = is_numeric($value) ? $value : strlen($value);
    }

    return $thisValue > $minValue;
  }
}
