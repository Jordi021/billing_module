<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Client;

class ValidateCedulaRuc implements ValidationRule {
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        if ($this->isCedulaOrRucRegistered($value)) {
            $fail($attribute, 'Esta cédula o RUC ya está registrada.');
        }
        
        if (!$this->isValidCedulaRuc($value)) {
            $fail($attribute, 'No es una cédula o RUC válido.');
        }
    }

    /**
     * Verificar si la cédula o RUC ya está registrada en la base de datos.
     *
     * @param  string  $identificacion
     * @return bool
     */
    function isCedulaOrRucRegistered($identificacion) {
        $cedula = substr($identificacion, 0, 10);
        return Client::where('id', $cedula)->exists();
    }

    /**
     * Validar si es una cédula o RUC válido.
     *
     * @param  string  $identificacion
     * @return bool
     */
    function isValidCedulaRuc($identificacion) {
        $length = strlen($identificacion);

        if ($length == 10 && $this->isValidCedula($identificacion)) {
            return true;
        }

        if (
            $length == 13 &&
            substr($identificacion, 10, 3) === '001' &&
            $this->isValidCedula(substr($identificacion, 0, 10))
        ) {
            return true;
        }

        if ($length == 13 && $this->isValidRucEmpresa($identificacion)) {
            return true;
        }

        return false;
    }

    /**
     * Validar si la cédula es válida.
     *
     * @param  string  $cedula
     * @return bool
     */
    function isValidCedula($cedula) {
        if (strlen($cedula) != 10) {
            return false;
        }

        $provincia = intval(substr($cedula, 0, 2));
        if ($provincia < 1 || $provincia > 24) {
            return false;
        }

        $suma = 0;
        for ($i = 0; $i < 9; $i++) {
            $digito = intval($cedula[$i]);
            if ($i % 2 == 0) {
                $resultado = $digito * 2;
                if ($resultado >= 10) {
                    $resultado -= 9;
                }
            } else {
                $resultado = $digito * 1;
            }
            $suma += $resultado;
        }

        $digitoVerificadorCalculado = (10 - ($suma % 10)) % 10;

        return $digitoVerificadorCalculado == intval($cedula[9]);
    }

    /**
     * Validar si el RUC de una empresa es válido.
     *
     * @param  string  $ruc
     * @return bool
     */
    function isValidRucEmpresa($ruc) {
        $provincia = intval(substr($ruc, 0, 2));
        if ($provincia < 1 || $provincia > 24) {
            return false;
        }

        $tercerDigito = intval($ruc[2]);
        if ($tercerDigito < 6 || $tercerDigito > 9) {
            return false;
        }

        if (intval(substr($ruc, 10, 3)) == 0) {
            return false;
        }

        return true;
    }
}
