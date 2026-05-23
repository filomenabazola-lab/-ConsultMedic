<?php

namespace App\Helpers;

class DataActual
{
  public static function dataActual()
  {
    $dia = date('d');
    $diaSemana = date('w');
    $ano = date('Y');
    $mes = date('n');
    $datasemana = ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'];
    $datames = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    return $datasemana[$diaSemana] . ' Aos ' . $dia . ' de ' . $datames[$mes] . ' de ' . $ano;
  }
  public static function during($hora)
  {
    if ($hora) {

      // Hora que você deseja formatar
      // $hora = '2023-04-23 12:30:00';

      // Obtenha a data e hora actual em segundos
      date_default_timezone_set('Africa/Luanda');
      $agora = time();

      // Converta a hora que você deseja formatar em segundos
      $hora_em_segundos = strtotime($hora);

      // Calcule a diferença entre a hora actual e a hora que você deseja formatar
      $diferenca = $agora - $hora_em_segundos;

      // Converta a diferença em um formato legível
      if ($diferenca < 60) {
        return 'Há menos de 1 minuto';
      } elseif ($diferenca < 3600) {
        $minutos = round($diferenca / 60);
        if ($minutos == 1) {
          return 'Há ' . $minutos . ' minuto';
        } else {
          return 'Há ' . $minutos . ' minutos';
        }
      } elseif ($diferenca < 86400) {
        $horas = round($diferenca / 3600);
        if ($horas == 1) {
          return 'Há ' . $horas . ' hora';
        } else {
          return 'Há ' . $horas . ' horas';
        }
      } elseif ($diferenca < 2629056) {
        $dias = round($diferenca / 86400);
        if ($dias == 1) {
          return 'Há ' . $dias . ' dia';
        } else {
          return 'Há ' . $dias . ' dias';
        }
        return 'Há ' . $dias . ' dias';
      } elseif ($diferenca == 2629056) {
        return 'Há 1 mês';
      } else {
        return 'Há mais de 1 mês';
      }
    } else {
      return false;
    }
  }
}
