<?php
class Usuario {
    public $nome;

    public cadastrar(){
        if($this->validarEmail()){
            //logica para insert no banco
        }else{
            //esse email ja sendo usado!
        }
    }
    public conectar(){

    }
    public desconectar(){

    }
    private validarEmail(){
        //select 
        // if(totalLinha > 0)
        //     false
        // else
        //     true
    }
}