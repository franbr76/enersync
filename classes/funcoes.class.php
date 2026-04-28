<?php

class Funcoes
{
    public function dtNasc($vlr, $tipo)
    {
        switch ($tipo) {
            case 1:
                $rst = implode("-", array_reverse(explode("/", $vlr))); // converte data brasil p/ internacional
                break;
            case 2:
                $rst = implode("/", array_reverse(explode("-", $vlr)));
                // faz o inverso
                break;

        }
        return $rst;
    }
    public function formatarDataHora($dataHora)
    {
        if (empty($dataHora)) {
            return "";
        }

        // Converte string do banco para timestamp
        $timestamp = strtotime($dataHora);

        // Se der erro ao converter, retorna o valor original
        if (!$timestamp) {
            return $dataHora;
        }

        // Formata no padrão brasileiro: DD/MM/YYYY HH:MM
        return date("d/m/Y H:i", $timestamp);
    }

                
}

?>