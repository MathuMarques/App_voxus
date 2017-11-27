<?php 
	session_start(); 
	if(!isset($_SESSION['email'])){
		header('Location: login.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home - Voxus</title>
</head>
    <style>
    	*{
    		font-family: Arial;
    		margin:0;
    		padding:0;
    	}

    	html, body{
    		position:relative;
    	}

    	header{
    		border-bottom: 1px solid black;
    	}


    	#site{
    		background-color: #ddd;
    		width:80%;
    		margin-left: 10%;
    	}

    	#titulo{
    		text-align:center;
    	}

    	#conteudo{

    	}

    	#titulo_2{
    		text-align:center;
    		margin: 25px 0 0 15px;
    	}

    	#nova_task{
    		width:50%;
    		margin-top:35px;
    		margin-bottom:50px;
    		margin-left:25%;
    		text-align: center;
    	}

    	#nova_task p{
    		cursor:pointer;
    		display: inline;
    		padding:25px;
    		background-color: #999;
    		border-radius:15px;
    	}

    	#cont_nova_task{
    		background-color: #ccc;
    		text-align:center;
    		display:none;
    	}

    	#cont_nova_task table{
    		text-align: center;
    		width:30%;
    		margin-left:35%;
    	}

    	.task{
    		background-color: #ccc;
    		padding:20px;
    		border-bottom:1px solid black;
    	}

    	.titulo {
    		margin-bottom:15px;
    	}

    	.descricao{
    		text-align:justify;
    		float:left;
    		width:65%;
    	}

    	.data{
    		float:left;
    		width:80%;
    		margin-top:25px;
    	}

    	.feito{
    		width:20%;
    		float:left;
    		margin-top:25px;
    	}


    </style>
<body>
<?php
	//conectando com banco de dados
	$conecta = mysqli_connect('localhost', 'root', '', 'app_voxus');


?>

<?php
	//Acionado quando botao nova task for pressionado
	if(isset($_POST['botones'])){
		$titulo = $_POST['titulo_f'];
		$descricao = $_POST['descricao_f'];
		$prioridade = $_POST['prioridade_f'];
		$data = date('Y-m-d h:i');
		//montando query
			$query = "INSERT INTO task (titulo, descricao, prioridade, data, feito, id_usuario)

			values(

			'$titulo', '$descricao', '$prioridade',
			 '$data', '0', " .
			  $_SESSION['id'] . "); ";	

			  //verificando se os campos estao preenchidos 
		if(!empty($titulo) && !empty($descricao) && !empty($prioridade)){
			$resultado = mysqli_query($conecta, $query);
		}else{
			echo '<script>alert("Preencha todos os campos")</script>';
		}
	}
	//acioando quando um anexo for inserido e o botao pressionado
	if(isset($_POST['botunes'])){
		//verificando se a variavel FILES EXISTE e processar os dados para inserção no banco de dados
		if(isset($_FILES)){
			if(isset($_FILES['anexo'])){
				$novo_end =  'arq_anexo/' . rand() . $_FILES['anexo']['name'];
				if(move_uploaded_file($_FILES['anexo']['tmp_name'], $novo_end)){
					echo '<script>alert("arquivo guardado com sucesso");</script>';
					$query = "INSERT INTO anexo (end_arquivo, task, nome_arquivo_original)values('" . $novo_end . "', '" . $_POST['idTask'] . "',  '" . $_FILES['anexo']['name'] . "'); ";
					$resultado = mysqli_query($conecta, $query);
					echo $query;
				}
			}
		}
	}
?>
	<div id="site">
		<div>
			<header>
				<div id="titulo">
					<h1>DASHBOARD</h1>
				</div>
				<div style="text-align:center;">
					<a href="logout.php">Logout</a>
				</div>
				<div id="nova_task">
					<p onclick="abreOuFecha();">
						Nova Task
					</p>
				</div>
				<div id="cont_nova_task">
					<form action="index.php" method="POST">
						<table>
							<?php 
							?>
							<tr>
								<td><label for="titulo_f">Título:</label></td>
								<td><input type="text" name="titulo_f" id="titulo_f"></td>
							</tr>

							<tr>
								<td>
									<label for="descricao_f">Descrição</label>
								</td>
								<td>
									<textarea style="width:200px; height:150px;" name="descricao_f"></textarea>
								</td>
							</tr>

							<tr>
								<td>
									<label for="prioridade_f">Escolha a prioridade de sua task</label>
								</td>

								<td>
									<select name="prioridade_f"  id="prioridade_f">
										<option value="1">Baixa</option>
										<option value="2">Média</option>
										<option value="3">Alta</option>
									</select>
								</td>
							</tr>

							<tr>
								<td>
									<input type="submit" style="margin-top:15px;" name="botones" />
								</td>
							</tr>
						</table>
					</form>
				</div>
			</header>
			<div id="conteudo">
				<div id="titulo_2">
					<h2>TASK</h2>
				</div>

					<?php
						$boolean = '';
						$id_sess = $_SESSION['id'];
						$query = "SELECT * FROM task WHERE feito='0' AND id_usuario = '$id_sess' ORDER BY prioridade DESC;";
						$resultado = mysqli_query($conecta, $query) or die (mysqli_error($conecta));
						while($linha = mysqli_fetch_array($resultado)){
						if($linha['feito'] == 0){
							$boolean = 'Não';
						}else{
							$boolean = 'Sim';
						}
							echo '
							<div class="task">
								<div class="titulo">
									<h3>' . $linha['titulo'] . '</h3>' . '
								</div>
								<div class="descricao">
									<p>' .
										$linha['descricao'] 
									. '</p>
								</div>
								<div class="data">
									<p>Data:' . $linha['data'] . '</p>
									<form method="POST" enctype="multipart/form-data">
										<input type="file" name="anexo" /><input type="hidden" name="idTask" value="' . $linha['id'] . '" /> <input type="submit" name="botunes" />
										<br/><br/>
										</form>';


										$query = "SELECT nome_arquivo_original, end_arquivo FROM anexo WHERE task='" . $linha['id'] . "'";
										//echo $query;
										$resultado = mysqli_query($conecta, $query);
										if(mysqli_num_rows($resultado) > 0){
 											while($linha2 = mysqli_fetch_array($resultado)){
 												echo '<p style="font-weight:bold;"><a href="' . $linha2['end_arquivo'] . '">' . $linha2['nome_arquivo_original'] . '</a></p>';
 											}
										}else{
											echo '<p>Nenhum anexo inserido</p>';
										}
									echo '
								</div>
								<div class="feito">
									Feitos: ' . $boolean . '
									<a href="feitoSim.php?idTask=' . $linha['id'] . '">SIM</a>
								</div>
								<div style="clear:both"></div>
							</div>
							';
						} 
					?>
				
				

				
			</div>
		</div>
	</div>

<script>
	var abreOuFecha_ = 0;
	function abreOuFecha(){
		console.log('kk' + abreOuFecha_);
		if(abreOuFecha_ == 0){
			document.getElementById('cont_nova_task').style.display = "inline";
			abreOuFecha_ = 1;
		}else{
			document.getElementById('cont_nova_task').style.display = "none";
			abreOuFecha_ = 0;
		}
	}
</script>
</body>
</html>