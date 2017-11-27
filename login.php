<!DOCTYPE html>
<html>
<head>
	<title>Página - Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<meta name="viewport" content="width=500, initial-scale=1">
	<meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="513552591970-fre8soad7cps4oj9obhebijpp6rmcv2k.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <style>
    	*{
    		font-family: Arial;
    		margin:0;
    		padding:0;
    	}

    	html, body{
    		position:relative;
    	}

    	#login{
    		background-color:#e6e6e6;
    		border: 1px solid black;
    		border-radius: 15px;
    		/*width:50%;
    		margin-left: 25%;
    		height:250px;
    		position:relative;
    		margin-top:100px;*/
    		position:fixed;
    		left:25%;
    		top:50%;
    		height:300px;
    		width:50%;
    		margin-top:-300px;
    		text-align:center;
    		font-size:;
    		float:left;
    	}

    	#login p{
    		font-size:30px;
    	}

    	#login img{
    		width:30%;
    	}



    </style>
</head>
<body>
<?php
	session_start();
    //conectando com servidor do banco de dados
	$conecta = mysqli_connect('localhost', 'root', '', 'app_voxus');
    //se o usuario ja estiver logado e acessar essa pagin, redireciona-lo para o INDEX.PHP
	if(isset($_SESSION['email'])){
		echo '<script>document.location = "index.php"; </script>';
	}else if(isset($_GET['dados'])){
        //caso o usario submeta dados do login
		if($_GET['dados'] == 1){
			$nome = $_COOKIE['full_name'];
			$email = $_COOKIE['email'];
			$query = "SELECT * FROM usuario WHERE email = '$email'; ";
			$resultado = mysqli_query($conecta, $query) or die('Erro ' + mysqli_error($resultado));
            //verificar se esses dados sao de um novo usuario
			if(mysqli_num_rows($resultado) > 0){
                //caso nao, apenas resgatar dados e iniciar sessao
				$query = 	"SELECT id from usuario WHERE email = '$email' LIMIT 1; " ;
				$resultado = mysqli_query($conecta, $query) or die('Erro' . mysqli_error($conecta));
				if(mysqli_num_rows($resultado) > 0){
					$linha = mysqli_fetch_array($resultado);
					$_SESSION['id'] = $linha['id'];
					$_SESSION['nome'] = $nome;
					$_SESSION['email'] = $email;
					echo '<script>document.location = "index.php"; </script>';
				}
			}else{
                //caso  sim, inserir novo usuario no banco
				$query = "INSERT INTO usuario (nome, email, modo)values('$nome', '$email', '0'); ";
				$resultado = mysqli_query($conecta, $query) or die('Erro' . mysqli_error());

				echo '<script>document.location = "index.php"; </script>';
				$query = 	"SELECT id from usuario WHERE email = '$email' LIMIT 1; " ;
				$resultado = mysqli_query($conecta, $query);
				if(mysqli_num_rows($resultado) > 0){
					$linha = mysqli_fetch_array($resultado);
					$_SESSION['id'] = $linha['id'];
					$_SESSION['nome'] = $nome;
					$_SESSION['email'] = $email;
					}
			}
		}
	}else{
	?>
	    <div id="login">
	    	<p>Olá, faça login.</p>
	    	<img src="imagens/logo.png">
	    	<div id="caixa_login">
	    		<div id="caixona" class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
	    	</div>
	    </div>
<?php
	}
?>
</body>
</html>
    <script>
      function onSignIn(googleUser) {
        var profile = googleUser.getBasicProfile();
        var id_token = googleUser.getAuthResponse().id_token;
      	document.cookie = "full_name=" + profile.getName();
      	document.cookie = "email=" + profile.getEmail();
      	document.cookie = "senha=" + id_token;
      	
      	document.location="login.php?dados=1";

      };
    </script>