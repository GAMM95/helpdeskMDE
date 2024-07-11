<!DOCTYPE html>
<html lang="es">

<head>

	<title>Sistema HelpDesk</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="" />
	<meta name="keywords" content="">
	<link rel="icon" href="public/assets/logo.ico"> <!-- Favicon icon -->

	<!-- vendor css -->
	<link rel="stylesheet" href="dist/assets/css/style.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	
</head>

<!-- [ auth-signin ] start -->
<div class="auth-wrapper">
	<div class="auth-content text-center">
		<!-- <img src="public/assets/logo_01.png" alt="imagen de mde" class="img-fluid mb-4"> -->


		<div class="card borderless">
			<div class="row align-items-center ">
				<!-- Verificación del estado para mostrar mensajes de error -->
				<?php
				$state = $_GET['state'] ?? '';
				if ($state === 'failed') {
					echo "<script>
          toastr.error('Credenciales incorrectas.', 'Inicio de sesión fallido.', {
            positionClass: 'toast-top-right',
            toastClass: 'bg-red-500 text-white font-bold',
          });
          </script>";
				}
				?>
				<form action="index.php?action=login" method="POST" class="col-md-12">
					<div class="card-body">
						<h4 class="mb-3 f-w-400">Inicio de Sesión</h4>
						<hr>
						<div class="form-group mb-3">
							<i class='bx bxs-user icon-input icon text-green-500 text-2xl mr-2'></i>
							<input type="text" class="form-control" id="username" placeholder="Nombre de usuario">
						</div>
						<div class="form-group mb-4">
							<input type="password" class="form-control" id="password" placeholder="Contraseña">
						</div>
						<button type="submit" class="btn btn-block btn-primary mb-4">Ingresar</button>
						<hr>
						<p class="mb-2 text-muted">¿Olvidaste tu contrase&ntilde;a? <a class="f-w-400">Pedir ayuda</a></p>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- [ auth-signin ] end -->

<!-- Required Js -->
<script src="assets/js/vendor-all.min.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>
<script src="assets/js/pcoded.min.js"></script>

</body>

</html>