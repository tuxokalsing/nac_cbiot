<?php
/*
*	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
*
*	Desenvolvedores: Arthur Kalsing
*	Descri��o: Verifica��o de informa��es de login, utilizada ap�s login.
*
*/

session_start();
session_destroy();
header("Location: login.php");
//header("Location: http://www.cbiot.ufrgs.br");
?>