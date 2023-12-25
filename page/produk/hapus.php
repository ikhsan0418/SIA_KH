<?php 
// mengambil id dari url
$id = $_GET['id'];

$sql = $conn->query("DELETE FROM produk WHERE id = $id") or die(mysqli_error($conn));
if ($sql) {
	echo "<script>alert('Data berhasil dihapus.');window.location='?p=produk';</script>";
} else {
	echo "<script>alert('Data berhasil dihapus.');window.location='?p=produk';</script>";
}

?>