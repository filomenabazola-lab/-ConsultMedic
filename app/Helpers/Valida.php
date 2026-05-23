<?php

namespace App\Helpers;

class Valida
{

  public static function email($email)
  {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)):
      return true;
    else:
      return false;
    endif;
  }
  public static function number($numero)
  {
    if (strlen($numero) == 9) {
      return true;
    } elseif (strlen($numero) < 9) {
      return true;
    } else {
      return false;
    }
  }
  public static function samepass($senha, $c_senha)
  {
    if ($senha != $c_senha) {
      return true;
    } else {
      return false;
    }
  }
  public static function length_senha($senha)
  {
    if (strlen($senha) < 8) {
      return true;
    } else {
      return false;
    }
  }
  public static function length_nome($nome)
  {
    if (strlen($nome) >= 101) {
      return true;
    } else {
      return false;
    }
  }
  public static function length($var)
  {
    if (strlen($var) <= 255) {
      return true;
    } else {
      return false;
    }
  }
  public static function pass_segura($senha)
  {
    return password_hash($senha, PASSWORD_DEFAULT);
  }
  public static function ANG($dado)
  {
    return date('d/m/Y H:i:s', strtotime($dado));
  }
  public static function dataExtenso($dado)
  {
    $dia = date('d', strtotime($dado));
    $diaSemana = date('w', strtotime($dado));
    $ano = date('Y', strtotime($dado));
    $mes = date('n', strtotime($dado));
    $datasemana = ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'];
    $datames = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    return $datasemana[$diaSemana] .' '. $dia . ' de ' . $datames[$mes] . ' de ' . $ano .' ' . date('H:i', strtotime($dado));
  }
  public static function idade($dado)
  {
    $nasc = explode('-', $dado);
    return date('Y') - $nasc[0];
  }
  public static function regex($var, $rule = null)
  {
    $rule = $rule ?? '/^([áàãâéèêíìîóòôõúùûaÁÀÃÂÉÈÊÍÌÎÓÒÔÕÚÙÛA-zZ]+)+((\s[áàãâéèêíìîóòôõúùûaÁÀÃÂÉÈÊÍÌÎÓÒÔÕÚÙÛA-zZ]+)+)?$/';
    return preg_match($rule, $var);
  }

  public static function nomeComNumeros($nome): bool
  {
    return preg_match('/\d/u', (string)$nome) === 1;
  }

  public static function emailInvalido($email): bool
  {
    $email = trim((string)$email);
    if ($email === '') {
      return true;
    }

    return filter_var($email, FILTER_VALIDATE_EMAIL) === false;
  }

  public static function normalizarTelefone($telefone): string
  {
    return preg_replace('/\D+/', '', (string)$telefone);
  }

  public static function telefoneInvalido($telefone): bool
  {
    $digits = self::normalizarTelefone($telefone);
    return !preg_match('/^9\d{8}$/', $digits);
  }

  public static function formatarTelefone($telefone): string
  {
    $digits = self::normalizarTelefone($telefone);
    if (strlen($digits) !== 9) {
      return $digits;
    }

    return substr($digits, 0, 3) . ' ' . substr($digits, 3, 3) . ' ' . substr($digits, 6, 3);
  }
}
