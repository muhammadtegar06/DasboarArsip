<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
	header('location: 404.html');
} else {
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		// Query menggunakan ID dan menambahkan id_divisi
		$query = mysqli_query($mysqli, "SELECT id, nama_user, username, hak_akses, id_divisi FROM tbl_user WHERE id='$id'")
			or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
		$data = mysqli_fetch_assoc($query);
	}
	?>
	<div class="panel-header bg-secondary-gradient">
		<div class="page-inner py-4">
			<div class="page-header text-white">
				<h4 class="page-title text-white"><i class="fas fa-user mr-2"></i> Manajemen User</h4>
				<ul class="breadcrumbs">
					<li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
					<li class="separator"><i class="flaticon-right-arrow"></i></li>
					<li class="nav-item"><a href="?module=user" class="text-white">User</a></li>
					<li class="separator"><i class="flaticon-right-arrow"></i></li>
					<li class="nav-item"><a>Ubah</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="page-inner mt--5">
		<div class="card">
			<div class="card-header">
				<div class="card-title">Ubah Data User</div>
			</div>
			<form id="formUbahUser" action="modules/user/proses_ubah.php" method="post" class="needs-validation" novalidate>
				<div class="card-body">
					<input type="hidden" name="id" value="<?php echo $data['id']; ?>">

					<div class="form-group col-lg-5">
						<label>Nama User <span class="text-danger">*</span></label>
						<input type="text" name="nama_user" class="form-control" autocomplete="off"
							value="<?php echo $data['nama_user']; ?>" required>
						<div class="invalid-feedback">Nama user tidak boleh kosong.</div>
					</div>

					<div class="form-group col-lg-5">
						<label>Username <span class="text-danger">*</span></label>
						<input type="text" name="username" class="form-control" autocomplete="off"
							value="<?php echo $data['username']; ?>" required>
						<div class="invalid-feedback">Username tidak boleh kosong.</div>
					</div>

					<div class="form-group col-lg-5">
						<label>Password</label>
						<input type="password" name="password" class="form-control"
							placeholder="Kosongkan password jika tidak diubah" autocomplete="off">
					</div>

					<div class="form-group col-lg-5">
						<label>Hak Akses <span class="text-danger">*</span></label>
						<select name="hak_akses" class="form-control chosen-select" autocomplete="off" required>
							<option value="<?php echo $data['hak_akses']; ?>"><?php echo $data['hak_akses']; ?></option>
							<option disabled value="">-- Pilih --</option>
							<option value="Super Admin">Super Admin</option>
							<option value="Administrator">Administrator</option>
							<option value="Admin Gudang">Admin Gudang</option>
							<option value="Kepala Gudang">Kepala Gudang</option>
							<option value="User Divisi">User Divisi</option>
						</select>
						<div class="invalid-feedback">Hak akses tidak boleh kosong.</div>
					</div>

					<div class="form-group col-lg-5">
						<label>Divisi <span class="text-danger">*</span></label>
						<select name="id_divisi" class="form-control chosen-select" autocomplete="off" required>
							<option value="">-- Pilih Divisi --</option>
							<?php
							$q_divisi = mysqli_query($mysqli, "SELECT id, nama_divisi FROM tbl_divisi ORDER BY nama_divisi ASC");
							while ($div = mysqli_fetch_assoc($q_divisi)) {
								$selected = ($data['id_divisi'] == $div['id']) ? 'selected' : '';
								echo "<option value='{$div['id']}' $selected>{$div['nama_divisi']}</option>";
							}
							?>
						</select>
						<div class="invalid-feedback">Divisi tidak boleh kosong.</div>
					</div>
				</div>
				<div class="card-action">
					<input type="hidden" name="simpan" value="Simpan">
					<button type="button" onclick="konfirmasiUbah()"
						class="btn btn-secondary btn-round pl-4 pr-4 mr-2">Simpan</button>
					<a href="?module=user" class="btn btn-default btn-round pl-4 pr-4">Batal</a>
				</div>
			</form>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		function konfirmasiUbah() {
			var form = document.getElementById('formUbahUser');

			if (form.checkValidity() === false) {
				form.classList.add('was-validated');
				Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap!', text: 'Silakan isi bagian yang ditandai merah.' });
				return;
			}

			Swal.fire({
				title: 'Simpan Perubahan?',
				text: "Pastikan data baru sudah sesuai.",
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Simpan!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					form.submit();
				}
			});
		}
	</script>
<?php } ?>