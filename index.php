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
	session_start(); 

	$conecta = mysqli_connect('localhost', 'root', '', 'app_voxus');

?>

<?php

	if(isset($_POST['botones'])){
		$titulo = $_POST['titulo_f'];
		$descricao = $_POST['descricao_f'];
		$prioridade = $_POST['prioridade_f'];
		$data = date('Y-m-d h:i');
			$query = "INSERT INTO task (titulo, descricao, prioridade, data, feito, id_usuario)

			values(

			'$titulo', '$descricao', '$prioridade',
			 '$data', '0', " .
			  $_SESSION['id'] . "); ";	
		if(!empty($titulo) && !empty($descricao) && !empty($prioridade)){
			$resultado = mysqli_query($conecta, $query);
		}else{
			echo '<script>alert("Preencha todos os campos, filha da puta")</script>';
		}
	}
?>
	<div id="site">
		<div>
			<header>
				<div id="titulo">
					<h1>DASHBOARD</h1>
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
						if($linha == 0){
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
								</div>
								<div class="feito">
									Feitos: ' . $boolean . '
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