<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Rodapé padrão.
 *
 *	Observação:	- Utilizar tag Wrap para juntar todo conteudo na parte superior da pagina.
 *				- Necessário declarar este footer para os menus dropdown funcionarem. (javascript)
 *				- Termina a conexão com o banco de dados.
 *
**/

?>

<div id="footer" class="noprint">
	<div class="container">
		<p class="text-muted credit">Sistema desenvolvido por Arthur Kalsing. Design by <a href="http://bootstrapdocs.com/"> Bootstrap</a>.<p>
	</div>
</div>
<!-- end #footer -->

<?php 
if(isset($db)) $db->close(); 
//if(isset($log)) $log->lclose();
?>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="./js/jquery-1.10.2.min.js"></script>
<script src="./js/bootstrap.min.js"></script>