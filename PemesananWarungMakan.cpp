// Program Pemesanan Makanan Warung Sederhana
// Watermark: Kelompok 15

#include <iostream>
#include <string>
#include <vector>
#include <iomanip>
using namespace std;

// Bagian Function: kumpulan fungsi pembantu program
// Fungsi ini mengembalikan nama warung (return tanpa parameter)
string getNamaWarung() {
    return "Warung Makan Kelompok 15";
}

// Fungsi ini menghitung pajak 10% dari subtotal (return berparameter)
double hitungPajak(double subtotal) {
    return subtotal * 0.10;
}

// Fungsi ini menampilkan header warung (non-return tanpa parameter)
void tampilkanHeader() {
    cout << "==========================================\n";
    cout << "   " << getNamaWarung() << "\n";
    cout << "==========================================\n";
}

// Fungsi ini mencetak garis pemisah (non-return berparameter)
void cetakGaris(int panjang) {
    for (int i = 0; i < panjang; i++) cout << "-";
    cout << "\n";
}

// Bagian Class: menyimpan data pesanan pelanggan
class Pesanan {
private:
    vector<string> namaMenu;
    vector<int>    hargaMenu;
    vector<int>    jumlahMenu;

public:
    // Method ini menambah pesanan baru ke dalam daftar (non-return berparameter)
    void tambahPesanan(string nama, int harga, int jumlah) {
        namaMenu.push_back(nama);
        hargaMenu.push_back(harga);
        jumlahMenu.push_back(jumlah);
    }

    // Method ini menampilkan daftar pesanan (non-return tanpa parameter)
    void tampilkanPesanan() {
        cout << "\n--- Daftar Pesanan ---\n";
        cout << left << setw(18) << "Menu"
             << setw(10) << "Harga"
             << setw(8)  << "Qty"
             << setw(12) << "Subtotal" << "\n";
        cetakGaris(48);

        for (size_t i = 0; i < namaMenu.size(); i++) {
            cout << left << setw(18) << namaMenu[i]
                 << setw(10) << hargaMenu[i]
                 << setw(8)  << jumlahMenu[i]
                 << setw(12) << (hargaMenu[i] * jumlahMenu[i]) << "\n";
        }
        cetakGaris(48);
    }

    // Method ini menghitung total harga pesanan (return tanpa parameter)
    int totalHarga() {
        int total = 0;
        for (size_t i = 0; i < namaMenu.size(); i++) {
            total += hargaMenu[i] * jumlahMenu[i];
        }
        return total;
    }

    // Method ini menghitung diskon sesuai total (return berparameter)
    int hitungDiskon(int total) {
        if (total >= 100000) return total * 15 / 100;
        else if (total >= 50000) return total * 5 / 100;
        else return 0;
    }
};

// Program utama: mengatur alur pemesanan pelanggan
int main() {
    tampilkanHeader();

    Pesanan pesanan;
    int pilihan;
    int jumlahPesanan = 0;

    // Perulangan do-while untuk input menu berulang
    do {
        cout << "\n========== MENU ==========\n";
        cout << "1. Nasi Goreng   Rp 25.000\n";
        cout << "2. Mie Ayam      Rp 20.000\n";
        cout << "3. Ayam Bakar    Rp 30.000\n";
        cout << "4. Es Teh        Rp  5.000\n";
        cout << "5. Selesai\n";
        cout << "Pilih menu (1-5): ";
        cin >> pilihan;

        // Pengkondisian pemilihan menu
        if (pilihan >= 1 && pilihan <= 4) {
            int jumlah;
            cout << "Jumlah pesanan: ";
            cin >> jumlah;

            if (pilihan == 1)      pesanan.tambahPesanan("Nasi Goreng", 25000, jumlah);
            else if (pilihan == 2) pesanan.tambahPesanan("Mie Ayam",    20000, jumlah);
            else if (pilihan == 3) pesanan.tambahPesanan("Ayam Bakar",  30000, jumlah);
            else if (pilihan == 4) pesanan.tambahPesanan("Es Teh",       5000, jumlah);

            jumlahPesanan++;
            cout << ">> Pesanan berhasil ditambahkan!\n";
        }
        else if (pilihan != 5) {
            cout << "!! Pilihan tidak valid !!\n";
        }

    } while (pilihan != 5);

    // Pengkondisian jika pelanggan tidak memesan apapun
    if (jumlahPesanan == 0) {
        cout << "\nAnda tidak memesan apapun. Terima kasih!\n";
        return 0;
    }

    pesanan.tampilkanPesanan();

    // Perhitungan akhir pembayaran pelanggan
    int    total  = pesanan.totalHarga();
    int    diskon = pesanan.hitungDiskon(total);
    double pajak  = hitungPajak(total - diskon);
    double bayar  = (total - diskon) + pajak;

    cout << "\n--- Rincian Pembayaran ---\n";
    cout << "Total Harga     : Rp " << total   << "\n";
    cout << "Diskon          : Rp " << diskon  << "\n";
    cout << "Pajak (10%)     : Rp " << pajak   << "\n";
    cetakGaris(32);
    cout << "Total Bayar     : Rp " << bayar   << "\n";
    cout << "\nTerima kasih telah berkunjung ke "
         << getNamaWarung() << "!\n";

    return 0;
}
