<?php
/*
*	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
*
*	Desenvolvedores: Arthur Kalsing
*	Descriчуo: Verificaчуo de informaчѕes de login, utilizada apѓs login.
*
*/

session_start();
session_destroy();
header("Location: login.php");
//header("Location: http://www.cbiot.ufrgs.br");
?>