#Bank Sampah By kelompok 15

# Watermark kelompok, ditampilkan di header dan footer program
WATERMARK = "Kelompok 15"

# Daftar jenis sampah beserta harga per kilogram (dalam rupiah)
HARGA_PER_KG = {
    "plastik": 3000,
    "kertas": 1500,
    "logam": 5000,
    "kaca": 1000,
}

# Menampilkan judul program dan watermark kelompok di layar
def cetak_header():
    print("=" * 46)
    print("          BANK SAMPAH DIGITAL")
    print(f"             [ {WATERMARK} ]")
    print("=" * 46)

# Menampilkan pesan dengan format seragam: [JUDUL] isi pesan
def cetak_pesan(judul, isi):
    print(f"[{judul}] {isi}")

# Menampilkan daftar menu, meminta pilihan pengguna,
# lalu mengirimkan pilihan tersebut ke program utama
def baca_menu():
    print("\n------------------- MENU -------------------")
    print("1. Setor sampah")
    print("2. Tarik saldo")
    print("3. Cek saldo dan level nasabah")
    print("4. Lihat riwayat setoran")
    print("5. Keluar")
    pilihan = input("Pilih menu (1-5): ")
    return pilihan

# Menghitung nilai rupiah dari setoran sampah berdasarkan jenis dan berat.
# Mengembalikan 0 jika jenis sampah tidak terdaftar
def hitung_nilai_setoran(jenis, berat_kg):
    if jenis in HARGA_PER_KG:
        return HARGA_PER_KG[jenis] * berat_kg
    return 0

# Menentukan level nasabah (Emas/Perak/Perunggu) berdasarkan besar saldo
def tentukan_level(saldo):
    if saldo >= 100000:
        return "Emas"
    elif saldo >= 50000:
        return "Perak"
    else:
        return "Perunggu"

# Mengubah angka menjadi tulisan rupiah, contoh: 60000 menjadi Rp60.000
def format_rupiah(angka):
    return "Rp" + f"{angka:,.0f}".replace(",", ".")


# Class yang mewakili satu nasabah beserta data dan aktivitasnya
class Nasabah:
    # Constructor: menyiapkan data awal nasabah saat objek dibuat
    # (nama, nomor ID, saldo awal 0, dan riwayat setoran kosong)
    def __init__(self, nama, no_id):
        self.nama = nama
        self.no_id = no_id
        self.saldo = 0
        self.riwayat = []

    # Mencatat setoran sampah: memvalidasi berat dan jenis sampah,
    # menambah saldo, lalu menyimpan data setoran ke riwayat
    def setor(self, jenis, berat_kg):
        nilai = hitung_nilai_setoran(jenis, berat_kg)
        if berat_kg <= 0:
            cetak_pesan("GAGAL", "Berat sampah harus lebih dari 0 kg.")
        elif nilai == 0:
            cetak_pesan("GAGAL", f"Jenis sampah '{jenis}' tidak terdaftar.")
        else:
            self.saldo += nilai
            self.riwayat.append((jenis, berat_kg, nilai))
            cetak_pesan("BERHASIL", f"Setor {berat_kg} kg {jenis} senilai {format_rupiah(nilai)}.")

    # Memproses penarikan saldo: saldo dikurangi hanya jika
    # jumlah penarikan valid dan saldo mencukupi
    def tarik(self, jumlah):
        if self.cek_saldo_cukup(jumlah):
            self.saldo -= jumlah
            cetak_pesan("BERHASIL", f"Penarikan {format_rupiah(jumlah)} berhasil.")
        else:
            cetak_pesan("GAGAL", "Jumlah tidak valid atau saldo tidak mencukupi.")

    # Menampilkan seluruh riwayat setoran nasabah secara berurutan
    # beserta total berat sampah yang sudah disetor
    def tampilkan_riwayat(self):
        print(f"\nRiwayat setoran {self.nama} ({self.no_id}):")
        if len(self.riwayat) == 0:
            print("Belum ada setoran.")
        else:
            for no, (jenis, berat, nilai) in enumerate(self.riwayat, start=1):
                print(f"{no}. {jenis:<8} {berat:>5} kg  {format_rupiah(nilai)}")
            print(f"Total berat: {self.hitung_total_berat()} kg")

    # Mengambil jumlah saldo nasabah saat ini
    def ambil_saldo(self):
        return self.saldo

    # Menjumlahkan berat seluruh sampah yang pernah disetor nasabah
    def hitung_total_berat(self):
        total = 0
        for _, berat, _ in self.riwayat:
            total += berat
        return total

    # Memeriksa apakah jumlah penarikan valid (lebih dari 0)
    # dan tidak melebihi saldo; hasilnya True atau False
    def cek_saldo_cukup(self, jumlah):
        return 0 < jumlah <= self.saldo

# Mengatur jalannya program: membuat objek nasabah lalu menampilkan
# menu berulang kali sampai pengguna memilih keluar
def main():
    cetak_header()
    nama = input("Nama nasabah : ")
    no_id = input("No. ID       : ")
    nasabah = Nasabah(nama, no_id)
    cetak_pesan("INFO", f"Selamat datang, {nasabah.nama}!")

    # Penanda perulangan menu; menjadi False saat pengguna memilih keluar
    berjalan = True
    while berjalan:
        pilihan = baca_menu()

        # Menu 1: setor sampah (input berat divalidasi agar harus angka)
        if pilihan == "1":
            jenis = input("Jenis sampah (plastik/kertas/logam/kaca): ").lower()
            try:
                berat = float(input("Berat (kg): "))
                nasabah.setor(jenis, berat)
            except ValueError:
                cetak_pesan("GAGAL", "Berat harus berupa angka.")

        # Menu 2: tarik saldo (input jumlah divalidasi agar harus angka bulat)
        elif pilihan == "2":
            try:
                jumlah = int(input("Jumlah penarikan (Rp): "))
                nasabah.tarik(jumlah)
            except ValueError:
                cetak_pesan("GAGAL", "Jumlah harus berupa angka bulat.")

        # Menu 3: menampilkan saldo dan level nasabah
        elif pilihan == "3":
            saldo = nasabah.ambil_saldo()
            cetak_pesan("SALDO", format_rupiah(saldo))
            cetak_pesan("LEVEL", tentukan_level(saldo))

        # Menu 4: menampilkan riwayat setoran
        elif pilihan == "4":
            nasabah.tampilkan_riwayat()

        # Menu 5: menghentikan perulangan menu
        elif pilihan == "5":
            berjalan = False

        # Pilihan di luar menu 1 sampai 5
        else:
            cetak_pesan("GAGAL", "Menu tidak tersedia, pilih 1 sampai 5.")

    # Penutup program beserta watermark kelompok
    print("\n" + "=" * 46)
    print(f"  Terima kasih telah menabung sampah! | {WATERMARK}")
    print("=" * 46)


# Menjalankan program utama
main()