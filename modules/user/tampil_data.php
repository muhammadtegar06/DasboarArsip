<?php
// mencegah direct access file PHP
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
	header('location: 404.html');
} else {
	?>
	<div class="panel-header bg-secondary-gradient">
		<div class="page-inner py-45">
			<div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
				<div class="page-header text-white">
					<h4 class="page-title text-white"><i class="fas fa-users mr-2"></i> Manajemen User</h4>
					<ul class="breadcrumbs">
						<li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
						<li class="separator"><i class="flaticon-right-arrow"></i></li>
						<li class="nav-item"><a href="?module=user" class="text-white">User</a></li>
						<li class="separator"><i class="flaticon-right-arrow"></i></li>
						<li class="nav-item"><a>Data</a></li>
					</ul>
				</div>
				<div class="ml-md-auto py-2 py-md-0">
					<a href="?module=form_entri_user" class="btn btn-secondary btn-round mr-2">
						<span class="btn-label"><i class="fa fa-plus mr-2"></i></span> Entri Data
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="page-inner mt--5">
		<div class="card">
			<div class="card-header">
				<div class="card-title">Data User Terdaftar</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="basic-datatables" class="display table table-bordered table-striped table-hover">
						<thead class="bg-light">
							<tr>
								<th class="text-center" width="5%">No.</th>
								<th class="text-center" width="20%">Nama User</th>
								<th class="text-center" width="15%">Username</th>
								<th class="text-center" width="25%">Divisi</th>
								<th class="text-center" width="15%">Hak Akses</th>
								<th class="text-center" width="15%">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							$query = mysqli_query($mysqli, "
                                SELECT u.id, u.nama_user, u.username, u.hak_akses, d.nama_divisi 
                                FROM tbl_user u
                                LEFT JOIN tbl_divisi d ON u.id_divisi = d.id
                                ORDER BY u.id DESC
                            ") or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));

							while ($data = mysqli_fetch_assoc($query)) {
								$badge_color = ($data['hak_akses'] == 'Administrator' || $data['hak_akses'] == 'Super Admin') ? 'badge-primary' : 'badge-info';
								$nama_divisi = !empty($data['nama_divisi']) ? $data['nama_divisi'] : '<span class="text-muted font-italic">- Tidak Ada Divisi -</span>';
								?>
								<tr>
									<td class="text-center"><?php echo $no++; ?></td>
									<td class="font-weight-bold text-dark"><?php echo $data['nama_user']; ?></td>
									<td><?php echo $data['username']; ?></td>
									<td><?php echo $nama_divisi; ?></td>
									<td class="text-center">
										<span
											class="badge <?php echo $badge_color; ?> px-3 py-1"><?php echo $data['hak_akses']; ?></span>
									</td>
									<td class="text-center">
										<div>
											<a href="?module=form_ubah_user&id=<?php echo $data['id']; ?>"
												class="btn btn-icon btn-round btn-secondary btn-sm mr-md-1"
												data-toggle="tooltip" data-placement="top" title="Ubah">
												<i class="fas fa-pencil-alt fa-sm"></i>
											</a>

											<button type="button"
												onclick="konfirmasiHapus(<?php echo $data['id']; ?>, '<?php echo $data['username']; ?>')"
												class="btn btn-icon btn-round btn-danger btn-sm" data-toggle="tooltip"
												data-placement="top" title="Hapus">
												<i class="fas fa-trash fa-sm"></i>
											</button>
										</div>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		// Notifikasi Otomatis Berdasarkan parameter ?pesan=...
		<?php if (isset($_GET['pesan'])) { ?>
			<?php if ($_GET['pesan'] == 1) { ?>
				Swal.fire({ icon: 'success', title: 'Sukses!', text: 'Data user berhasil disimpan.', timer: 2000, showConfirmButton: false });
			<?php } elseif ($_GET['pesan'] == 2) { ?>
				Swal.fire({ icon: 'success', title: 'Sukses!', text: 'Data user berhasil diubah.', timer: 2000, showConfirmButton: false });
			<?php } elseif ($_GET['pesan'] == 3) { ?>
				Swal.fire({ icon: 'success', title: 'Terhapus!', text: 'Data user berhasil dihapus.', timer: 2000, showConfirmButton: false });
			<?php } elseif ($_GET['pesan'] == 4) { ?>
				Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Username <?php echo htmlspecialchars($_GET['username']); ?> sudah digunakan. Silahkan ganti.' });
			<?php } ?>
		<?php } ?>

		// Fungsi Konfirmasi Hapus
		function konfirmasiHapus(id, username) {
			Swal.fire({
				title: 'Hapus Data?',
				text: "Anda yakin ingin menghapus user '" + username + "'?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					// Jika Ya, arahkan ke proses hapus
					window.location.href = "modules/user/proses_hapus.php?id=" + id;
				}
			});
		}
	</script>
<?php } ?>