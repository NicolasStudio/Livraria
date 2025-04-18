<?php
// criei variaveis de acesso para criar uma conexão com o banco
$dbHost = 'Localhost';
$dbUsarname = 'root';
$dbPassword = '';
$dbName = 'cadastro';

// conexão orientado a objeto
$conexao = new mysqli($dbHost, $dbUsarname, $dbPassword,$dbName );

/*      if($conexao-> connect_errno){
            echo"erro";
        }else {
            echo "realizado com sucesso";
        }
 */
?>