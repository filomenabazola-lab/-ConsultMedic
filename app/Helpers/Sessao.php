<?php

namespace App\Helpers;

class Sessao
{
    # com bootstrap
    public static function sms($nome, $texto=null, $classe=null){
        if(!empty($nome)):
            if(!empty($texto) AND empty($_SESSION[$nome])):
                if(!empty($_SESSION[$nome])):
                    unset($_SESSION[$nome]);
                endif;
                $_SESSION[$nome]=$texto;
                $_SESSION[$nome.'classe']=$classe;
            elseif(!empty($_SESSION[$nome]) AND empty($texto)): 
                $classe= !empty($_SESSION[$nome.'classe'])?$_SESSION[$nome.'classe']:'alert alert-success';  
                echo "<div class='$classe'>".$_SESSION[$nome]."</div>";
    unset( $_SESSION[$nome]);
    unset($_SESSION[$nome.'classe']);
            endif;
        endif;
    }
   

    #com izitoast
    public static function izitoast($nome, $titulo=null, $texto=null, $classe=null, $position=null){
        if(!empty($nome)):
            if(!empty($texto)  AND !empty($titulo) AND empty($_SESSION[$nome])):
                if(!empty($_SESSION[$nome])):
                    unset($_SESSION[$nome]);
                endif;
                $_SESSION[$nome]=$texto;
                $_SESSION[$nome.'titulo']=$titulo;
                $_SESSION[$nome.'classe']=$classe;
                $_SESSION[$nome.'position']=$position;
                // AND empty($classe)  AND empty($position)

            elseif(!empty($_SESSION[$nome]) AND empty($texto) AND empty($titulo)   ): 
                $classe= !empty($_SESSION[$nome.'classe'])?$_SESSION[$nome.'classe']:'success';  
                $position= !empty($_SESSION[$nome.'position'])?$_SESSION[$nome.'position']:'topRight';  
                $titulo=!empty($_SESSION[$nome.'titulo'])?$_SESSION[$nome.'titulo']:'Nao editado';
                echo "<script>
                $(document).ready(function(){
            iziToast.$classe({
                    title:'$titulo',
                    position:'$position',
                    message:'$_SESSION[$nome]'
                });
        });
                    </script>";
               
                unset( $_SESSION[$nome]);
                unset($_SESSION[$nome.'titulo']);
                unset($_SESSION[$nome.'classe']);
                unset($_SESSION[$nome.'position']);
            endif;
        endif;
    }
    # com notify
    public static function notify($nome, $texto=null, $classe=null, $position=null, $element=null){
        if(!empty($nome)):
            if(!empty($texto) AND empty($_SESSION[$nome])):
                if(!empty($_SESSION[$nome])):
                    unset($_SESSION[$nome]);
                endif;
                $_SESSION[$nome]=$texto;
                $_SESSION[$nome.'classe']=$classe;
                $_SESSION[$nome.'position']=$position;
                $_SESSION[$nome.'element']=$element;
                
            elseif(!empty($_SESSION[$nome]) AND empty($texto)  ): 
                $classe= !empty($_SESSION[$nome.'classe'])?$_SESSION[$nome.'classe']:'success';  
                $element= !empty($_SESSION[$nome.'element'])?$_SESSION[$nome.'element']:'';  
                $position= !empty($_SESSION[$nome.'position'])?$_SESSION[$nome.'position']:'top right';  
                
                echo "<script>
                $(document).ready(function(){
                             $$element.notify('$_SESSION[$nome]', {position: '$position', className:'$classe'});
                    });
                    </script>";
                    
               
                unset( $_SESSION[$nome]);
                unset($_SESSION[$nome.'classe']);
                unset($_SESSION[$nome.'position']);
                // unset($_SESSION[$nome.'element']);
            endif;
        endif;
    }

    // Notificacao dos browsers
    public static function browser($nome, $title=null, $body=null){
      if(!empty($nome)):
          if(!empty($title) AND empty($_SESSION[$nome])):
              if(!empty($_SESSION[$nome])):
                  unset($_SESSION[$nome]);
              endif;
              $_SESSION[$nome]=$title;
              $_SESSION[$nome.'body']=$body;
          elseif(!empty($_SESSION[$nome]) AND empty($title)):
              $body= $_SESSION[$nome.'body'];
              echo "<script>
              async function start() {
                try {
                  await init()
                  browserNotify({
                    title: '{$_SESSION[$nome]}',
                    body: '{$body}',
                    icon: 'http://localhost:8080/refeitorio/public/img/favicon.png'
                  })
                } catch (error) {
                  console.log(error.message)
                }
              }
              start()
              </script>";
              unset($_SESSION[$nome]);
              unset($_SESSION[$nome.'body']);
          endif;
      endif;
  }


    #com css puro
    // public static function mensagem($nome, $texto = null, $estilo = null)
    // {
    //     if (!empty($nome)) :
    //         if (!empty($texto) and empty($_SESSION[$nome])) :
    //             if (!empty($_SESSION[$nome])) :
    //                 unset($_SESSION[$nome]);
    //             endif;
    //             $_SESSION[$nome] = $texto;
    //             $_SESSION[$nome . 'estilo'] = $estilo;
    //         elseif (!empty($_SESSION[$nome]) and empty($texto)) :
    //             //mensagen de sucesso
    //             $sucesso = "background-color:rgba(0, 100, 0, 0.526); font-family: Verdana, Geneva, Tahoma, sans-serif; padding: 10px; margin: auto;  width: auto;";
    //             //mensagen de erro
    //             $alerta = "background-color: rgba(165, 42, 42, 0.902); font-family: Verdana, Geneva, Tahoma, sans-serif; padding: 10px; margin: auto; width: auto;";

    //             $estilo = !empty($_SESSION[$nome . 'estilo'] and $_SESSION[$nome . 'estilo'] == 'alerta') ? $alerta : $sucesso;
    //             $mensagem = "<div style='$estilo'>" . $_SESSION[$nome] . "</div>";
    //             echo $mensagem;
    //             unset($_SESSION[$nome]);
    //             unset($_SESSION[$nome . 'classe']);


    //         endif;
    //     endif;
    // }


    // # Variaveis importantes para sessoes
   
    public static function nivel1(){
        if((isset($_SESSION['teste0_type'])) && ($_SESSION['teste0_type'] != 0)):
            return true;
        else:
            return false;    
        endif;
    }
    public static function nivel0(){
        if(isset($_SESSION['teste0'])):
            return true;
        else:
            return false;    
        endif;
    }
    public static function restrito1($id){
        if($id!=$_SESSION['teste0']):
            return true;
        else:
            return false;    
        endif;
    }
}
