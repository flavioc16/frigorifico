<?php

namespace frigorifico\Model;

class Alert {

    public static function showMensage($tipo,$tempo){
        $mensagem = "";
        switch ($tipo)
            {
                case "error":
                    $mensagem = "alertify.notify('Deu bom :)', 'error', ".$tempo.", function(){});";
                    return $mensagem;
                    break;
                case "success":
                    $mensagem = "alertify.notify('Deu bom :)', 'success', ".$tempo.", function(){});";
                    return $mensagem;
                    break;
            } 

        return $mensagem;

    }
}   
?>