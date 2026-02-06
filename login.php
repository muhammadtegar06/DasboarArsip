<!DOCTYPE html>
<html lang="id">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="Sistem Informasi Persediaan Barang Gudang Material" />
	<meta name="author" content="Indra Styawantoro" />

	<title>Login | Repository Arsip</title>

	<link rel="icon" href="assets/img/favicon.png" type="image/x-icon" />

	<script src="assets/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: { "families": ["Lato:300,400,700,900"] },
			custom: {
				"families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
				urls: ['assets/css/fonts.min.css']
			},
			active: function () { sessionStorage.fonts = true; }
		});
	</script>

	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/atlantis.min.css">

	<style>
		body {
			background: linear-gradient(135deg, #1266f1 0%, #4a90e2 100%);
			height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			font-family: 'Lato', sans-serif;
		}

		.login-card {
			background: #fff;
			border-radius: 15px;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
			overflow: hidden;
			width: 100%;
			max-width: 400px;
			padding: 40px 30px;
			position: relative;
		}

		.logo-container {
			text-align: center;
			margin-bottom: 20px;
		}

		.logo-container img {
			width: 80px;
			margin-bottom: 10px;
			filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
		}

		.app-title {
			font-weight: 800;
			color: #333;
			text-align: center;
			margin-bottom: 5px;
			font-size: 24px;
		}

		.app-subtitle {
			text-align: center;
			color: #777;
			font-size: 14px;
			margin-bottom: 30px;
		}

		.form-group {
			position: relative;
			margin-bottom: 20px;
		}

		.form-group i {
			position: absolute;
			left: 15px;
			top: 50%;
			transform: translateY(-50%);
			color: #aaa;
			transition: 0.3s;
		}

		.form-control {
			padding-left: 45px;
			height: 50px;
			border-radius: 30px;
			border: 1px solid #ddd;
			font-size: 15px;
		}

		.form-control:focus {
			border-color: #1266f1;
			box-shadow: 0 0 0 0.2rem rgba(18, 102, 241, 0.25);
		}

		.form-control:focus+i {
			color: #1266f1;
		}

		.btn-login {
			background: linear-gradient(to right, #1266f1, #4a90e2);
			border: none;
			border-radius: 30px;
			height: 50px;
			font-size: 16px;
			font-weight: bold;
			color: white;
			width: 100%;
			cursor: pointer;
			transition: 0.3s;
			box-shadow: 0 5px 15px rgba(18, 102, 241, 0.4);
		}

		.btn-login:hover {
			transform: translateY(-2px);
			box-shadow: 0 8px 20px rgba(18, 102, 241, 0.6);
		}

		/* Styling Alert agar lebih rapi */
		.alert {
			font-size: 13px;
			border-radius: 10px;
		}

		.footer-copyright {
			text-align: center;
			font-size: 12px;
			color: rgba(255, 255, 255, 0.7);
			position: absolute;
			bottom: 20px;
			width: 100%;
		}
	</style>
</head>

<body>

	<div class="login-card animated fadeInDown">
		<div class="logo-container">
			<img src="assets/img/logo1.png" alt="Logo">
		</div>

		<h3 class="app-title">Repository Arsip</h3>
		<p class="app-subtitle">Silakan login untuk melanjutkan</p>

		<?php
		if (isset($_GET['pesan'])) {
			if ($_GET['pesan'] == 1) {
				echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-times-circle mr-2"></i> <b>Gagal!</b> Username atau Password salah.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
			} elseif ($_GET['pesan'] == 2) {
				echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i> <b>Perhatian!</b> Silakan login dahulu.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
			} elseif ($_GET['pesan'] == 3) {
				echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i> <b>Sukses!</b> Anda berhasil logout.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
			}
		}
		?>

		<form action="proses_login.php" method="post" class="needs-validation" novalidate>
			<div class="form-group">
				<input type="text" id="username" name="username" class="form-control" placeholder="Username"
					autocomplete="off" required>
				<i class="fas fa-user"></i>
				<div class="invalid-feedback ml-3">Username wajib diisi.</div>
			</div>

			<div class="form-group">
				<input type="password" id="password" name="password" class="form-control" placeholder="Password"
					autocomplete="off" required>
				<i class="fas fa-lock"></i>
				<div class="invalid-feedback ml-3">Password wajib diisi.</div>
			</div>

			<div class="form-action mt-4">
				<input type="submit" name="login" value="MASUK" class="btn btn-login">
			</div>
		</form>
	</div>

	<div class="footer-copyright">
		&copy; <?php echo date('Y'); ?> Repository Arsip. All Rights Reserved.
	</div>

	<script src="assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap.min.js"></script>
	<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>

	<script>
		(function () {
			'use strict';
			window.addEventListener('load', function () {
				var forms = document.getElementsByClassName('needs-validation');
				var validation = Array.prototype.filter.call(forms, function (form) {
					form.addEventListener('submit', function (event) {
						if (form.checkValidity() === false) {
							event.preventDefault();
							event.stopPropagation();
						}
						form.classList.add('was-validated');
					}, false);
				});
			}, false);
		})();
	</script>
</body>

</html>