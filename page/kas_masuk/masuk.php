<?php 
//ada tombol cari yang di jalankan atau tidak
if(isset($_POST['cari'])){
    $tgl1 = $_POST['tgl1'];
    $tgl2 = $_POST['tgl2'];
    $tampilKas = $conn->query("SELECT * FROM tb_kas WHERE tgl BETWEEN '$tgl1' and '$tgl2' AND jenis = 'Masuk'") or die(mysqli_error($conn));
}else{
// menampilkan semua table kas
    $tampilKas = $conn->query("SELECT * FROM tb_kas WHERE jenis = 'Masuk'") or die(mysqli_error($conn));
}




// jika tombol tambah di tekan
if(isset($_POST['tambah'])) {
    $kode = htmlspecialchars($_POST['kode']);
    $keterangan = htmlspecialchars($_POST['keterangan']);
    $tanggal = htmlspecialchars($_POST['tanggal']);
    $jumlah = htmlspecialchars($_POST['jumlah']);

    if(empty($kode && $keterangan && $tanggal && $jumlah)) {
        echo "<script>alert('Isi semua inputan');window.location='?p=masuk';</script>";
    }

    $sql = $conn->query("INSERT INTO tb_kas VALUES (null, '$kode', '$keterangan', '$tanggal', '$jumlah', 'Masuk', '0')") or die(mysqli_error($conn));
    if($sql) {
        echo "<script>alert('Data Berhasil Ditambahkan');window.location='?p=masuk';</script>";
    } else {
        echo "<sciprt>alert('Data Gagal Ditambahkan');window.location='?p=masuk';</script>";
    }
}

// acak kode
$kode = $conn->query("SELECT max(kode) as kodeTerbesar FROM tb_kas") or die(mysqli_error($conn));
$pecahKode = $kode->fetch_assoc();
$kodeId = $pecahKode['kodeTerbesar'];
// var_dump($pecahKode);

$urutan = (int) substr($kodeId, 4, 4);
$urutan++;

$huruf = "KS-";
$kodeId = $huruf . sprintf("%03s", $urutan);
// echo $kodeId;

if($format === $kodeId) {
    echo "<script>alert('Kode yang di inputkan sama, gagal.')</script>";
    return false;
}


// ------UBAH-----------
if(isset($_POST['ubah'])) {
    $id = htmlspecialchars($_POST['id']);
    $kode = htmlspecialchars($_POST['kode']);
    $keterangan = htmlspecialchars($_POST['keterangan']);
    $tanggal = htmlspecialchars($_POST['tanggal']);
    $jumlah = htmlspecialchars($_POST['jumlah']); 

    $sql = $conn->query("UPDATE tb_kas SET kode = '$kode', keterangan = '$keterangan', tgl = '$tanggal', jumlah = '$jumlah', jenis = 'Masuk', keluar = '0' WHERE id = $id ") or die(mysqli_error($conn));
    if($sql) {
        echo "<script>alert('Data Berhasil Diubah');window.location='?p=masuk';</script>";
    } else {
        echo "<sciprt>alert('Data Gagal Diubah');window.location='?p=masuk';</script>";
    }
}

?>
<h2>Kas Masuk</h2>
<hr>
<!--  Modals-->                  
<div class="panel-body">
    <button class="btn btn-danger" data-toggle="modal" data-target="#myModal">
      Tambah Data Kas
    </button>
    <br><br> 
    <form action="" method="post">
        Pilih Tanggal Awal
        <input type="date" class="form-control" name="tgl1"/>
        <br>
        Pilih Tanggal Akhir
        <input type="date" class="form-control" name="tgl2"/>
        <br>
        <button type="submit" name="cari" class="btn btn-warning">lihat</button>
    </form>
    <br>
    <?php 
    if(isset($_POST['cari'])){
    ?>
    <a href="page/kas_masuk/print.php?tgl1=<?= $tgl1; ?>&tgl2=<?= $tgl2; ?>" onclick="return confirm('Yakin Ingin mencetak ?')" target="_blank">
        <button type="submit" name="cari" class="btn btn-success"><i class="fa fa-print"></i></button>
    </a>
    <?php } ?>
    <!-- Form Modal Tambah -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Tambah Data Kas</h4>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="kode">Kode</label>
                            <input type="text" class="form-control" name="kode" readonly="" id="kode" value="<?= $kodeId ?>" required="" />
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal" required="" />
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" id="jumlah" required="" />
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" name="tambah" class="btn btn-danger">Tambah Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modals-->
<!-- Akhir Form Modal Tambah -->

<div class="panel panel-danger">
    <div class="panel-heading">
         Data Kas Masuk
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 1;
                while($data = $tampilKas->fetch_assoc()) : ?>
								<tr>
									<td><?= $no++; ?></td>
									<td><?= $data['kode']; ?></td>
									<td><?= date('d-m-Y', strtotime($data['tgl'])); ?></td>
									<td><?= $data['keterangan']; ?></td>
									<td>Rp.<?= number_format($data['jumlah']); ?></td>
									<td>
										<a id="edit_data" data-toggle="modal" class="btn btn-success" data-target="#edit" data-id="<?= $data['id']; ?>" data-kode="<?= $data['kode']; ?>" data-ket="<?= $data['keterangan']; ?>" data-tgl="<?= $data['tgl']; ?>" data-jml="<?= $data['jumlah']; ?>"><i class="fa fa-edit"></i></a>
										<a href="?p=masuk&aksi=hapus&id=<?= $data['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ?')"><i class="fa fa-trash-o"></i></a>
									</td>
								</tr>
								<?php 
								$totalM = $totalM + $data['jumlah'];
								?>
                <?php endwhile; ?>
                </tbody>
                <tfoot>
                	<tr>
                		<th colspan="4">Total Kas Masuk</th>
                		<td>Rp.<?= number_format($totalM); ?></td>
                	</tr>
                </tfoot>
            </table>
        </div>
        
    </div>
</div>


<!-- Form Modal Ubah -->
<div class="panel-body">
    
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Ubah Data Kas</h4>
                </div>
                <div class="modal-body" id="modal_edit">
                    <form action="" method="post">
                        <input type="text" name="id" id="id">
                        <div class="form-group">
                            <label for="kode">Kode</label>
                            <input type="text" class="form-control" name="kode" readonly="" id="kode" required="" />
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal" required="" />
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" id="jumlah" required="" />
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" name="ubah" class="btn btn-danger">Ubah Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/jquery-1.10.2.js"></script>
<script>
    // #edit_data = button a href (edit)
    $(document).on('click', '#edit_data', function() {
        // data('id,ket,dll') = berasal dari a href (attribute baru).
        const id = $(this).data('id');
        const kode = $(this).data('kode');
        const ket = $(this).data('ket');
        const tgl = $(this).data('tgl');
        const jml = $(this).data('jml');

        // form modal edit
        $('#modal_edit #id').val(id);
        $('#modal_edit #kode').val(kode);
        $('#modal_edit #keterangan').val(ket);
        $('#modal_edit #tanggal').val(tgl);
        $('#modal_edit #jumlah').val(jml);
    });
</script>