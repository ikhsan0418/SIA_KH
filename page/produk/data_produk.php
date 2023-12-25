<h1>halaman produk</h1>
<?php 
//ada tombol cari yang di jalankan atau tidak
if(isset($_POST['cari'])){
    $tgl1 = $_POST['tgl1'];
    $tgl2 = $_POST['tgl2'];
    $tampilKas = $conn->query("SELECT * FROM tb_kas WHERE tgl BETWEEN '$tgl1' and '$tgl2' AND jenis = 'Masuk'") or die(mysqli_error($conn));
}else{
// menampilkan semua table kas
    $tampilProduk = $conn->query("SELECT * FROM produk") or die(mysqli_error($conn));
}




// jika tombol tambah di tekan
if(isset($_POST['tambah'])) {
    $kode = htmlspecialchars($_POST['kode']);
    $namaProduk = htmlspecialchars($_POST['nama_produk']);
    $hargaSatuan = htmlspecialchars($_POST['harga_satuan']);
    $jumlah = htmlspecialchars($_POST['jumlah']);

    if(empty($kode && $namaProduk && $hargaSatuan && $jumlah)) {
        echo "<script>alert('Isi semua inputan');window.location='?p=produk';</script>";
    }

    $sql = $conn->query("INSERT INTO produk VALUES (null, '$namaProduk', '$hargaSatuan', '$jumlah', '$kode')") or die(mysqli_error($conn));
    if($sql) {
        echo "<script>alert('Data Berhasil Ditambahkan');window.location='?p=produk';</script>";
    } else {
        echo "<sciprt>alert('Data Gagal Ditambahkan');window.location='?p=produk';</script>";
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
    $id_edit = htmlspecialchars($_POST['id_edit']);
    $kode_edit = htmlspecialchars($_POST['kode_edit']);
    $nama_produk_edit = htmlspecialchars($_POST['nama_produk_edit']);
    $harga_satuan_edit = htmlspecialchars($_POST['harga_satuan_edit']);
    $jumlah_edit = htmlspecialchars($_POST['jumlah_edit']); 

    $sql = $conn->query("UPDATE produk SET kode = '$kode_edit', nama = '$nama_produk_edit', harga_satuan = '$harga_satuan_edit', jumlah = '$jumlah_edit' WHERE id = $id_edit ") or die(mysqli_error($conn));
    if($sql) {
        echo "<script>alert('Data Berhasil Diubah');window.location='?p=produk';</script>";
    } else {
        echo "<sciprt>alert('Data Gagal Diubah');window.location='?p=produk';</script>";
    }
}

?>
<h2>Halaman Produk</h2>
<hr>
<!--  Modals-->                  
<div class="panel-body">
    <button class="btn btn-danger" data-toggle="modal" data-target="#myModal">
      Tambah Data Produk
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
                            <label for="kode">Kode Produk</label>
                            <input type="text" class="form-control" name="kode" id="kode" value="" required="" />
                        </div>
                        <div class="form-group">
                            <label for="nama_produk">nama Produk</label>
                            <input type="text" class="form-control" name="nama_produk"  id="nama_produk" value="" required="" />
                        </div>
                        <div class="form-group">
                            <label for="harga_satuan">harga satuan</label>
                            <input type="text" name ="harga_satuan" id="harga_satuan" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label for="jumlah">jumlah</label>
                            <input type="number" class="form-control" name="jumlah" id="jumlah" required="" />
                        </div>
                        <!-- <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" id="jumlah" required="" />
                        </div> -->
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
                        <th>kode</th>
                        <th>nama</th>
                        <th>jumlah</th>
                        <th>harga satuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 1;
                while($data = $tampilProduk->fetch_assoc()) : ?>
								<tr>
									<td><?= $no++; ?></td>
									<td><?= $data['kode']; ?></td>
									<td><?= $data['nama']; ?></td>
                                    <td><?= $data['jumlah']; ?></td>
									<td>Rp.<?= number_format($data['harga_satuan']); ?></td>
									<td>
										<a id="edit_data" data-toggle="modal" class="btn btn-success" data-target="#edit" data-id="<?= $data['id']; ?>" data-kode="<?= $data['kode']; ?>" data-harga="<?= $data['harga_satuan']; ?>" data-nama="<?= $data['nama']; ?>" data-jumlah="<?= $data['jumlah']; ?>"><i class="fa fa-edit"></i></a>
										<a href="?p=produk&aksi=hapus&id=<?= $data['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ?')"><i class="fa fa-trash-o"></i></a>
									</td>
								</tr>
								<?php 
								$totalM = $totalM + $data['harga_satuan'];
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
                        <input type="text" name="id_edit" id="id_edit">
                        <div class="form-group">
                            <label for="kode">Kode Produk</label>
                            <input type="text" class="form-control" name="kode_edit" id="kode_edit" value="" required="" />
                        </div>
                        <div class="form-group">
                            <label for="nama_produk">nama Produk</label>
                            <input type="text" class="form-control" name="nama_produk_edit"  id="nama_produk_edit" value="" required="" />
                        </div>
                        <div class="form-group">
                            <label for="harga_satuan">harga satuan</label>
                            <input type="text" name ="harga_satuan_edit" id="harga_satuan_edit" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label for="jumlah">jumlah</label>
                            <input type="number" class="form-control" name="jumlah_edit" id="jumlah_edit" required="" />
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
        const nama_produk = $(this).data('nama');
        const harga_satuan= $(this).data('harga');
        const jumlah = $(this).data('jumlah');

        // form modal edit
        $('#modal_edit #id_edit').val(id);
        $('#modal_edit #kode_edit').val(kode);
        $('#modal_edit #nama_edit').val(nama_produk);
        $('#modal_edit #harga_satuan_edit').val(harga_satuan);
        $('#modal_edit #jumlah_edit').val(jumlah);
    });
</script>