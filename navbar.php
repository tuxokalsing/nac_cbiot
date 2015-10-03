<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Este arquivo define a barra de navegação do sistema.
 *				Uma navbar para cada nivel de acesso.
 *				Muda dinamicamente em função da página acessada
 *
 *	Observação: Mostra os dados do professor, alunos e associados, dispositivos cadastrados
 *
**/

$current_page = $_SERVER['REQUEST_URI'];

// set every menu var to false
$home = false;
$new = false;
$list = false;
$search = false;
$groups = false;
$system = false;
$edit = false;
$contacts = false;
$pending = false;
// find out which page are we navigating
if(stripos($current_page,"dashboard_admin.php") OR stripos($current_page,"dashboard_user.php")) {
	$home = true;
} elseif(stripos($current_page,"new_user.php")) {
	$new = true;
} elseif(stripos($current_page,"list.php")) {
	$list = true;
} elseif(stripos($current_page,"search.php")) {
	$search = true;
} elseif(stripos($current_page,"groups.php")) {
	$groups = true;
} elseif(stripos($current_page,"pending.php")) {
	$pending = true;
} elseif(stripos($current_page,"contacts.php")) {
	$contacts = true;
} elseif(stripos($current_page,"escolaridade.php") OR 
			stripos($current_page,"estado_civil.php") OR
			stripos($current_page,"municipios.php") OR
			stripos($current_page,"estados.php") OR
			stripos($current_page,"categorias.php") OR
			stripos($current_page,"tipos_contato.php") OR
			stripos($current_page,"tipos_dispositivo.php") OR
			stripos($current_page,"paises.php")) {
	$system = true;
}

if($access_level == 1) :	//navbar para administração
?>

<!-- Fixed navbar -->
  <div class="navbar navbar-default navbar-fixed-top noprint" role="navigation">
	<div class="container">
	  <div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		  <span class="sr-only">Toggle navigation</span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="dashboard_admin.php">INTRANET CBIOT</a>
	  </div>
	  <div class="collapse navbar-collapse">
		<ul class="nav navbar-nav">
		  <li <?php if($home) echo"class=\"active\"" ?> >
		     <a href="dashboard_admin.php">
			 <span class="glyphicon glyphicon-home"></span>
			 <?php echo8("  Início"); ?>
			 </a>
		  </li>
		  <li <?php if($contacts) echo"class=\"active\"" ?> >
		     <a href="contacts.php">
			 <span class="glyphicon glyphicon-phone"></span>
			 <?php echo8("  Contatos"); ?>
			 </a>
		  </li>
		  <li class="dropdown<?php if($new) echo" active" ?>">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			<span class="glyphicon glyphicon-user"></span>
			<?php echo8("  Cadastrar"); ?>
			<b class="caret"></b></a>
			<ul class="dropdown-menu">
			  <li><a href="new_user.php?cat=1">Professor</a></li>
			  <li><a href="new_user.php?cat=2">Aluno</a></li>
			  <li><a href="new_user.php?cat=3"><?php echo8("Funcionário"); ?></a></li>
			  <li><a href="new_user.php?cat=4">Visitante</a></li>
			  <!-- <li><a href="new_outros.php">Outros</a></li> -->
			</ul>
		  </li>
		  <li <?php if($search) echo"class=\"active\"" ?> >
		     <a href="teachers_management.php">
			 <span class="glyphicon glyphicon-star"></span>
			 <?php echo8("  Ger. Líderes"); ?>
			 </a>
		  </li>
		  <li class="dropdown<?php if($list) echo" active" ?>">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			<span class="glyphicon glyphicon-list"></span>
			<?php echo8("  Listar"); ?>
			<b class="caret"></b></a>
			<ul class="dropdown-menu">
<!-- pode ser automatizado para todas as categorias... -->
			  <li><a href="list.php"><?php echo8("Todos os Usuários"); ?></a></li>
			  <li class="divider"></li>
			  <li class="dropdown-header">por Categoria</li>
			  <li><a href="list.php?cat=1&ati=1">Professores</a></li>
			  <li><a href="list.php?cat[]=2&cat[]=3&cat[]=4&cat[]=5&cat[]=6&ati=1">Alunos</a></li>
			  <li><a href="list.php?cat[]=7&cat[]=8&cat[]=9&ati=1"><?php echo8("Funcionários"); ?></a></li>
			  <li><a href="list.php?cat=10&ati=1"><?php echo8("Visitantes"); ?></a></li>
			  <li><a href="list.php?lid=1&ati=1"><?php echo8("Líderes de Grupo"); ?></a></li>
			  <li class="divider"></li>
			  <li class="dropdown-header">por Escolaridade</li>
			  <li><a href="list.php?esc=7&ati=1">Pós-Doutores</a></li>
			  <li><a href="list.php?esc=6&ati=1">Doutores</a></li>
			  <li><a href="list.php?esc=5&ati=1">Mestres</a></li>
			  <li><a href="list.php?esc=4&ati=1">Graduados</a></li>
			  <li class="divider"></li>
			  <li class="dropdown-header">por Atividade</li>
			  <li><a href="list.php?ati=0">Inativos</a></li>
			  <li><a href="list.php?next=1&ati=1"><?php echo8("Próximos a Expirar (1 mês)"); ?></a></li>
			  <li><a href="list.php?next=3&ati=1"><?php echo8("Próximos a Expirar (3 meses)"); ?></a></li>
			  <li><a href="list.php?next=6&ati=1"><?php echo8("Próximos a Expirar (6 meses)"); ?></a></li>
			  <li class="divider"></li>
			  <li class="dropdown-header">Dispositivos</li>
			  <li><a href="list_devices.php"><?php echo8("Dispositivos por Usuário"); ?></a></li>
			</ul>
		  </li>
		   <li <?php if($pending) echo"class=\"active\"" ?> >
		     <a href="pending.php">
			 <span class="glyphicon glyphicon-list-alt"></span>
			 <?php echo8("  Pendências"); ?>
			 </a>
		  </li>
		  
		  <li <?php if($groups) echo"class=\"active\"" ?> >
		     <a href="groups.php">
			 <span class="glyphicon glyphicon-tags"></span>
			 <?php echo8("    Grupos"); ?>
			 </a>
		  </li>
		  <li class="dropdown<?php if($system) echo" active" ?>" >
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			<span class="glyphicon glyphicon-globe"></span>
			<?php echo8("  Outros"); ?>
			<b class="caret"></b></a>
			<ul class="dropdown-menu">
<!-- pode ser automatizado para todas as categorias... -->
			  <li><a href="categorias.php">Categoria</a></li>
			  <li><a href="escolaridade.php">Escolaridade</a></li>
			  <li><a href="estado_civil.php">Estado Civil</a></li>
			  <li><a href="municipios.php"><?php echo8("Municípios"); ?></a></li>
			  <li><a href="estados.php"><?php echo8("Estados"); ?></a></li>
			  <li><a href="paises.php"><?php echo8("Países"); ?></a></li>
			  <li><a href="tipos_contato.php"><?php echo8("Tipos de Contato"); ?></a></li>
			  <li><a href="tipos_dispositivo.php"><?php echo8("Tipos de Dispositivo"); ?></a></li>
			  <li><a href="change_passwd.php"><?php echo8("Senha do Administrador"); ?></a></li>
			</ul>
		  </li>
		  <li>
		     <a href="logout.php">
			 <span class="glyphicon glyphicon-log-out"></span>
			 Logout
			 </a>
		  </li>
		</ul>
	  </div><!--/.nav-collapse -->
	</div>
  </div>

<?php elseif($access_level == 2) :	//navbar para professores ?>

<!-- Fixed navbar -->
  <div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
	  <div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		  <span class="sr-only">Toggle navigation</span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="dashboard_prof.php">INTRANET CBIOT</a>
	  </div>
	  <div class="collapse navbar-collapse">
		<ul class="nav navbar-nav">
		  <li <?php if($home) echo"class=\"active\"" ?> >
		     <a href="dashboard_prof.php">
			 <span class="glyphicon glyphicon-user"></span>
			 <?php echo8("  Perfil"); ?>
			 </a>
		  </li>
		   <li <?php if($contacts) echo"class=\"active\"" ?> >
		     <a href="contacts.php">
			 <span class="glyphicon glyphicon-phone"></span>
			 <?php echo8("  Contatos"); ?>
			 </a>
		  </li>
		  <li <?php if($edit) echo"class=\"active\"" ?> >
		     <a href="edit_me.php">
			 <span class="glyphicon glyphicon-edit"></span>
			 <?php echo8("  Editar Dados Pessoais"); ?>
			 </a>
		  </li>
		  
		  <li class="dropdown<?php if($list) echo" active" ?>">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			<span class="glyphicon glyphicon-user"></span>
			<?php echo8("  Listar"); ?>
			<b class="caret"></b></a>
			<ul class="dropdown-menu">
			  <li><a href="list_prof.php?cat[]=2&cat[]=3&cat[]=4&cat[]=5&cat[]=6&cat[]=10&cat[]=11&ati=1">Meus Alunos</a></li>
			  <li><a href="list_prof.php?cat[]=2&cat[]=3&cat[]=4&cat[]=5&cat[]=6&cat[]=10&cat[]=11&ati=0">Meus Alunos (inativos)</a></li>
			  <li><a href="list_prof.php?cat=1">Meus Associados</a></li>
			  <li><a href="list_prof.php">Todos</a></li>
			</ul>
		  </li>
		  <li>
		     <a href="logout.php">
			 <span class="glyphicon glyphicon-log-out"></span>
			 Logout
			 </a>
		  </li>
		</ul>
	  </div><!--/.nav-collapse -->
	</div>
  </div>
 
<?php elseif($access_level > 2) :	//navbar para outros (alunos e funcionarios) ?>

<!-- Fixed navbar -->
  <div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
	  <div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		  <span class="sr-only">Toggle navigation</span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="dashboard_user.php">INTRANET CBIOT</a>
	  </div>
	  <div class="collapse navbar-collapse">
		<ul class="nav navbar-nav">
		  <li <?php if($home) echo"class=\"active\"" ?> >
		     <a href="dashboard_user.php">
			 <span class="glyphicon glyphicon-user"></span>
			 <?php echo8("  Perfil"); ?>
			 </a>
		  </li>
		   <li <?php if($contacts) echo"class=\"active\"" ?> >
		     <a href="contacts.php">
			 <span class="glyphicon glyphicon-phone"></span>
			 <?php echo8("  Contatos"); ?>
			 </a>
		  </li>
		  <li <?php if($edit) echo"class=\"active\"" ?> >
		     <a href="edit_me.php">
			 <span class="glyphicon glyphicon-edit"></span>
			 <?php echo8("  Editar Dados Pessoais"); ?>
			 </a>
		  </li>
		  <li>
		     <a href="logout.php">
			 <span class="glyphicon glyphicon-log-out"></span>
			 Logout
			 </a>
		  </li>
		</ul>
	  </div><!--/.nav-collapse -->
	</div>
  </div>
  
<?php endif; ?>