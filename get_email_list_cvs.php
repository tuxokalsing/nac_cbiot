<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página que retorna o download de uma lista de emails.
 *
 *	Observação: A página recebe o ID do grupo e retorna a lista de emails
 *
 *
**/

$page_access_level = 2;							// Administração e Professores
require("valida_session.php");

$id = $_GET['id'];								// id do usuario a ser editado


// select all emails from users from group
$sql = "SELECT usu.nome, cont.contato FROM grupos_usuario AS gr 
		INNER JOIN usuarios AS usu ON usu.id = gr.usuarios_id
		INNER JOIN contato AS cont ON cont.usuarios_id = usu.id AND cont.tipos_contato_id = 4 OR cont.tipos_contato_id = 5
		WHERE gr.grupo_id = %d AND cont.usuarios_id = usu.id";
$result = query($sql,$id);
$emails_list = array();
while($row = $result->fetch_assoc()) {
	array_push($emails_list, $row['contato']);
}

// open raw memory as file so no temp files needed, you might run out of memory though
$f = fopen('php://output', 'w'); 
// generate line separate by comma
fputcsv($f, $emails_list);
// tell the browser it's going to be a csv file
header('Content-Type: application/csv');
// tell the browser we want to save it instead of displaying it
header('Content-Disposition: attachment; filename="export.csv";');
// make php send the generated csv lines to the browser
fpassthru($f);

?>