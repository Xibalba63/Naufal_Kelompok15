<?php
// Program Sistem Parkir Kendaraan (Versi Web)
// Tujuan: Mencatat kendaraan masuk melalui form web, menghitung
//         biaya parkir berdasarkan jenis & durasi, memberi diskon,
//         dan mencetak struk pembayaran pelanggan.
// Watermark: Kelompok 15

session_start();

// Bagian Function: fungsi pembantu di luar class
// Fungsi ini mengembalikan nama tempat parkir (return tanpa parameter)
function getNamaTempat(): string {
    return "Parkir Kelompok 15";
}

// Fungsi ini menghitung biaya parkir (return berparameter)
function hitungBiayaParkir(string $jenis, int $durasi): int {
    $tarifPerJam = 0;

    if ($jenis == "Motor")      $tarifPerJam = 2000;
    elseif ($jenis == "Mobil")  $tarifPerJam = 5000;
    elseif ($jenis == "Truk")   $tarifPerJam = 10000;

    return $tarifPerJam * $durasi;
}

// Fungsi ini menampilkan header program (non-return tanpa parameter)
function tampilkanHeader(): void {
    echo "<h1 style='text-align:center; color:#2c3e50;'>" . getNamaTempat() . "</h1>";
    echo "<hr>";
}

// Fungsi ini mencetak garis pemisah (non-return berparameter)
function cetakGaris(int $panjang): void {
    echo "<div style='border-top:2px dashed #888; margin:10px 0;'></div>";
}

// Bagian Class: menyimpan data kendaraan yang terparkir
class Parkir {
    private array $platKendaraan   = [];
    private array $jenisKendaraan  = [];
    private array $durasiKendaraan = [];

    // Method ini menambah kendaraan baru (non-return berparameter)
    public function tambahKendaraan(string $plat, string $jenis, int $durasi): void {
        $this->platKendaraan[]   = $plat;
        $this->jenisKendaraan[]  = $jenis;
        $this->durasiKendaraan[] = $durasi;
    }

    // Method ini menampilkan daftar kendaraan (non-return tanpa parameter)
    public function tampilkanDaftar(): void {
        echo "<h3>--- Daftar Kendaraan Terparkir ---</h3>";
        echo "<table border='1' cellpadding='8' cellspacing='0' 
              style='border-collapse:collapse; width:100%;'>";
        echo "<tr style='background:#3498db; color:white;'>
                <th>No</th><th>Plat</th><th>Jenis</th><th>Durasi</th><th>Biaya</th>
              </tr>";

        for ($i = 0; $i < count($this->platKendaraan); $i++) {
            $biaya = hitungBiayaParkir(
                $this->jenisKendaraan[$i],
                $this->durasiKendaraan[$i]
            );
            echo "<tr>
                    <td>" . ($i + 1) . "</td>
                    <td>" . htmlspecialchars($this->platKendaraan[$i]) . "</td>
                    <td>" . $this->jenisKendaraan[$i] . "</td>
                    <td>" . $this->durasiKendaraan[$i] . " jam</td>
                    <td>Rp " . number_format($biaya, 0, ',', '.') . "</td>
                  </tr>";
        }
        echo "</table>";
    }

    // Method ini menghitung total biaya semua kendaraan (return tanpa parameter)
    public function totalBiaya(): int {
        $total = 0;
        for ($i = 0; $i < count($this->platKendaraan); $i++) {
            $total += hitungBiayaParkir(
                $this->jenisKendaraan[$i],
                $this->durasiKendaraan[$i]
            );
        }
        return $total;
    }

    // Method ini menghitung diskon berdasarkan total (return berparameter)
    public function hitungDiskon(int $total): int {
        if ($total >= 50000)      return (int)($total * 0.15);
        elseif ($total >= 20000)  return (int)($total * 0.05);
        else                      return 0;
    }

    // Getter jumlah data (non-return tanpa parameter tambahan)
    public function jumlahData(): int {
        return count($this->platKendaraan);
    }
}

// ============ PROSES LOGIKA WEB ============

// Inisialisasi session untuk menyimpan objek parkir
if (!isset($_SESSION['parkir'])) {
    $_SESSION['parkir'] = new Parkir();
}
$parkir = $_SESSION['parkir'];

$pesan = "";

// Pengkondisian: proses form tambah kendaraan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $plat   = trim($_POST['plat'] ?? '');
    $jenis  = $_POST['jenis'] ?? '';
    $durasi = (int)($_POST['durasi'] ?? 0);

    if ($plat == "" || $durasi <= 0) {
        $pesan = "<p style='color:red;'>!! Data tidak boleh kosong & durasi harus > 0 !!</p>";
    } elseif (!in_array($jenis, ["Motor", "Mobil", "Truk"])) {
        $pesan = "<p style='color:red;'>!! Jenis kendaraan tidak valid !!</p>";
    } else {
        $parkir->tambahKendaraan($plat, $jenis, $durasi);
        $pesan = "<p style='color:green;'>>> Kendaraan berhasil ditambahkan!</p>";
    }
}

// Pengkondisian: reset data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] == 'reset') {
    $_SESSION['parkir'] = new Parkir();
    $parkir = $_SESSION['parkir'];
    $pesan = "<p style='color:orange;'>>> Data berhasil direset!</p>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Parkir - Kelompok 15</title>
    <style>
        body { font-family: Arial, sans-serif; background:#ecf0f1; padding:20px; }
        .container { max-width: 750px; margin:auto; background:white; padding:25px;
                     border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
        input, select, button { padding:8px; margin:5px 0; width:100%;
                                box-sizing:border-box; border-radius:5px; border:1px solid #ccc; }
        button { background:#3498db; color:white; border:none; cursor:pointer; }
        button:hover { background:#2980b9; }
        .btn-reset { background:#e74c3c; }
        .btn-reset:hover { background:#c0392b; }
        .struk { background:#f9f9f9; padding:15px; border-radius:8px; margin-top:20px; }
    </style>
</head>
<body>
<div class="container">

<?php
// Menampilkan header dari function
tampilkanHeader();
?>

<!-- Form input kendaraan -->
<form method="POST" action="">
    <input type="hidden" name="aksi" value="tambah">
    <label>Plat Kendaraan:</label>
    <input type="text" name="plat" placeholder="Contoh: B1234XY" required>

    <label>Jenis Kendaraan:</label>
    <select name="jenis" required>
        <option value="">-- Pilih Jenis --</option>
        <option value="Motor">Motor (Rp 2.000/jam)</option>
        <option value="Mobil">Mobil (Rp 5.000/jam)</option>
        <option value="Truk">Truk (Rp 10.000/jam)</option>
    </select>

    <label>Durasi (jam):</label>
    <input type="number" name="durasi" min="1" required>

    <button type="submit">Tambah Kendaraan</button>
</form>

<form method="POST" action="">
    <input type="hidden" name="aksi" value="reset">
    <button type="submit" class="btn-reset">Reset Semua Data</button>
</form>

<?php
// Menampilkan pesan notifikasi
echo $pesan;

// Menampilkan daftar kendaraan + struk jika ada data
if ($parkir->jumlahData() > 0) {
    $parkir->tampilkanDaftar();

    $total  = $parkir->totalBiaya();
    $diskon = $parkir->hitungDiskon($total);
    $bayar  = $total - $diskon;

    echo "<div class='struk'>";
    echo "<h3>--- Rincian Pembayaran ---</h3>";
    echo "Total Biaya : Rp " . number_format($total, 0, ',', '.') . "<br>";
    echo "Diskon      : Rp " . number_format($diskon, 0, ',', '.') . "<br>";
    cetakGaris(30);
    echo "<b>Total Bayar : Rp " . number_format($bayar, 0, ',', '.') . "</b>";
    echo "<p style='text-align:center;'>Terima kasih telah menggunakan <b>"
         . getNamaTempat() . "</b>!</p>";
    echo "</div>";
} else {
    echo "<p style='color:gray;'>Belum ada kendaraan yang terparkir.</p>";
}
?>

</div>
</body>
</html>