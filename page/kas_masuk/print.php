<html>
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Aplikasi Pengelolaan Kas</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="../../assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="../../assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="../../assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!-- TABLE STYLES-->
    <link href="../../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

    <style type="text/css">
    body {
        -webkit-print-color-adjust: exact;
        padding: 50px;
    }

    #table {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        font-size: 14px
    }

    #table td,
    #table th {
        padding: 8px;
        padding-top: 10px;
    }

    #table tr {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    #table tr:hover {
        background-color: #ddd;
    }

    #table th {
        padding-top: 10px;
        padding-bottom: 10px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }

    .biru {
        background-color: #06bbcc;
        color: white;
    }

    @page {
        size: auto;
        margin: 0;
    }
</style>
</head>


<body>
    <?php 
    require_once '../../config/koneksi.php';
    $tgl1 = $_GET['tgl1'];
    $tgl2 = $_GET['tgl2'];
    $tampilKas = $conn->query("SELECT * FROM tb_kas WHERE tgl BETWEEN '$tgl1' and '$tgl2' AND jenis = 'Masuk'") or die(mysqli_error($conn));
    
    ?>
    <center>
        <!-- <img src="foto/koplogo.png" width="680px"> -->
        <h2>Laporan Kas Masuk</h2>
        <h4>dari tanggal <?= date('d-m-Y', strtotime($tgl1)); ?> s/d <?= date('d-m-Y', strtotime($tgl2)); ?> </h4>
    </center>
    <br>
    <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
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
									
								</tr>
								<?php 
								$totalM[] = $data['jumlah'];
								?>
                <?php endwhile; 
                $tot = array_sum($totalM);
                ?>
                </tbody>
                <tfoot>
                	<tr>
                		<th colspan="4">Total Kas Masuk</th>
                		<td>Rp.<?= number_format($tot); ?></td>
                	</tr>
                </tfoot>
            </table>
        </div>

    <!-- JQUERY SCRIPTS -->
    <script src="../../assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="../../assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="../../assets/js/jquery.metisMenu.js"></script>
     <!-- DATA TABLE SCRIPTS -->
    <script src="../../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../../assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>
      <!-- CUSTOM SCRIPTS -->
    <script src="../../assets/js/custom.js"></script>

</body>
<script>
    window.print();
</script>

</html>