# Fitur Transfer Terjadwal - FinTrack

## Deskripsi
Fitur Transfer Terjadwal memungkinkan pengguna untuk mengatur transfer otomatis antar wallet yang berulang secara berkala (harian, mingguan, bulanan, atau tahunan).

## Komponen yang Dibuat

### 1. Database Migration
- **File**: `database/migrations/2025_11_11_114434_create_scheduled_transfers_table.php`
- **Tabel**: `scheduled_transfers`
- **Kolom**:
  - `id` - Primary key
  - `user_id` - Foreign key ke tabel users
  - `from_wallet_id` - Foreign key ke tabel wallets (wallet sumber)
  - `to_wallet_id` - Foreign key ke tabel wallets (wallet tujuan)
  - `amount` - Jumlah transfer (decimal 15,2)
  - `frequency` - Frekuensi (daily/weekly/monthly/yearly)
  - `start_date` - Tanggal mulai
  - `end_date` - Tanggal berakhir (nullable)
  - `next_execution_date` - Tanggal eksekusi berikutnya
  - `status` - Status (active/paused/completed/cancelled)
  - `description` - Deskripsi (nullable)
  - `execution_count` - Jumlah eksekusi yang telah dilakukan
  - `last_executed_at` - Waktu eksekusi terakhir

### 2. Model
- **File**: `app/Models/ScheduledTransfer.php`
- **Relasi**:
  - `belongsTo` User
  - `belongsTo` Wallet (fromWallet)
  - `belongsTo` Wallet (toWallet)
- **Method**:
  - `calculateNextExecutionDate()` - Menghitung tanggal eksekusi berikutnya
  - `shouldExecute()` - Mengecek apakah transfer siap dieksekusi

### 3. Controller
- **File**: `app/Http/Controllers/ScheduledTransferController.php`
- **Methods**:
  - `index()` - Menampilkan daftar transfer terjadwal
  - `store()` - Membuat transfer terjadwal baru
  - `update()` - Mengupdate transfer terjadwal
  - `destroy()` - Menghapus transfer terjadwal
  - `toggleStatus()` - Mengaktifkan/menjeda transfer
  - `execute()` - Menjalankan transfer secara manual

### 4. Routes
**File**: `routes/web.php`
```php
Route::resource('scheduled-transfers', ScheduledTransferController::class)->only(['index', 'store', 'update', 'destroy']);
Route::post('/scheduled-transfers/{scheduledTransfer}/toggle-status', [ScheduledTransferController::class, 'toggleStatus'])->name('scheduled-transfers.toggle-status');
Route::post('/scheduled-transfers/{scheduledTransfer}/execute', [ScheduledTransferController::class, 'execute'])->name('scheduled-transfers.execute');
```

### 5. View
- **File**: `resources/views/scheduled-transfers/index.blade.php`
- **Fitur**:
  - Statistics cards (Total, Aktif, Dijeda, Total Eksekusi)
  - Tabel daftar transfer terjadwal
  - Modal untuk tambah/edit transfer
  - Tombol aksi (Execute, Pause/Resume, Edit, Delete)

### 6. Command
- **File**: `app/Console/Commands/ProcessScheduledTransfers.php`
- **Command**: `php artisan transfers:process-scheduled`
- **Fungsi**: Mengeksekusi semua transfer terjadwal yang sudah waktunya
- **Scheduler**: Dijalankan setiap jam via Laravel Scheduler

### 7. Scheduler
- **File**: `routes/console.php`
- **Schedule**: Command `transfers:process-scheduled` dijalankan setiap jam

## Cara Menggunakan

### 1. Membuat Transfer Terjadwal
1. Buka menu "Transfer Terjadwal" di sidebar
2. Klik tombol "Tambah Transfer Terjadwal"
3. Isi form:
   - Pilih Wallet Asal
   - Pilih Wallet Tujuan
   - Masukkan Jumlah
   - Pilih Frekuensi (Harian/Mingguan/Bulanan/Tahunan)
   - Tentukan Tanggal Mulai
   - (Opsional) Tentukan Tanggal Berakhir
   - (Opsional) Tambahkan Deskripsi
4. Klik "Simpan"

### 2. Mengelola Transfer Terjadwal
- **Execute**: Menjalankan transfer secara manual sebelum jadwal
- **Pause/Resume**: Menjeda atau mengaktifkan kembali transfer
- **Edit**: Mengubah detail transfer
- **Delete**: Menghapus transfer terjadwal

### 3. Menjalankan Scheduler (Production)
Tambahkan cron job di server:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Testing Scheduler (Development)
Jalankan command secara manual:
```bash
php artisan transfers:process-scheduled
```

Atau jalankan scheduler worker:
```bash
php artisan schedule:work
```

## Validasi & Keamanan
1. ✅ Transfer hanya bisa dilakukan antar wallet dengan mata uang yang sama
2. ✅ Verifikasi ownership - user hanya bisa mengakses transfer miliknya sendiri
3. ✅ Cek saldo mencukupi sebelum eksekusi
4. ✅ Transaction dengan DB::beginTransaction untuk data consistency
5. ✅ Logging untuk monitoring eksekusi

## Status Transfer
- **Active**: Transfer aktif dan akan dieksekusi sesuai jadwal
- **Paused**: Transfer dijeda sementara
- **Completed**: Transfer selesai (sudah mencapai end_date)
- **Cancelled**: Transfer dibatalkan

## Frekuensi Transfer
- **Daily**: Setiap hari
- **Weekly**: Setiap minggu
- **Monthly**: Setiap bulan
- **Yearly**: Setiap tahun

## Statistik
Dashboard menampilkan:
- Total transfer terjadwal
- Jumlah transfer aktif
- Jumlah transfer dijeda
- Total eksekusi yang telah dilakukan

## Log & Monitoring
- Semua eksekusi dicatat di database (execution_count, last_executed_at)
- Log kesalahan disimpan di Laravel log
- Command output menampilkan summary eksekusi

## Fitur Tambahan
1. **Auto-completion**: Transfer otomatis berstatus "completed" jika sudah melewati end_date
2. **Manual execution**: User bisa menjalankan transfer kapan saja
3. **Flexible scheduling**: Support berbagai frekuensi transfer
4. **Transaction history**: Semua transfer tercatat di tabel wallet_transfers

## Testing
1. Buat transfer terjadwal dengan tanggal mulai hari ini
2. Jalankan command: `php artisan transfers:process-scheduled`
3. Cek di halaman Transfer untuk melihat history
4. Cek saldo wallet untuk memastikan transfer berhasil

## Notes
- Scheduler akan otomatis skip transfer yang saldo tidak mencukupi
- Transfer yang gagal akan dicatat di log tanpa mengubah status
- User mendapat feedback jelas untuk setiap aksi
- Dark mode support untuk semua interface
