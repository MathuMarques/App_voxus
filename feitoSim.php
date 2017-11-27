<?php
	// Seleciona se a tarefa foi feita.
	if(isset($_GET['idTask'])){
		$query = "UPDATE task set feito = 1  WHERE id='" . $_GET['idTask'] . "'; ";
		$conecta = mysqli_connect('localhost', 'root', '', 'app_voxus');
		$resultado =  mysqli_query($conecta, $query) or die ('Erro, contate o administradoir: '. mysqli_error($conecta));
		if($resultado){
			header('Location: index.php');
		}
	}
?>