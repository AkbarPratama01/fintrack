# M-KIOS Feature Documentation

## Overview
M-KIOS adalah fitur untuk mengelola transaksi digital seperti pulsa, e-wallet (DANA, GoPay), dan token listrik dalam aplikasi FinTrack. Fitur ini memungkinkan pengguna untuk mencatat setiap transaksi, menghitung profit otomatis, dan melacak saldo wallet yang digunakan.

## Database Schema

### Table: m_kios_transactions
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key -> users.id, cascade on delete)
- transaction_type (enum: pulsa, dana, gopay, token_listrik, default pulsa) - Jenis transaksi
- product_code (varchar 50, nullable) - Kode produk/nominal (10000, 20000, dll)
- phone_number (varchar 20) - Nomor telepon customer (untuk pulsa, DANA, GoPay)
- customer_id (varchar 100, nullable) - ID pelanggan/nomor meter (untuk token listrik)
- balance_deducted (decimal 15,2) - Modal yang keluar dari wallet
- cash_received (decimal 15,2) - Uang yang diterima dari customer
- profit (decimal 15,2, default 0) - Keuntungan = cash_received - balance_deducted
- provider (varchar 50, nullable) - Provider pulsa (Telkomsel, XL, Indosat, dll)
- wallet_id (bigint, nullable, foreign key -> wallets.id, set null on delete)
- notes (text, nullable) - Catatan tambahan
- status (enum: completed, pending, failed, default completed)
- transaction_date (timestamp, default current)
- created_at (timestamp)
- updated_at (timestamp)
```

## Files Structure

### Models
- **app/Models/MKiosTransaction.php**
  - Fillable: user_id, transaction_type, product_code, phone_number, customer_id, balance_deducted, cash_received, profit, provider, wallet_id, notes, status, transaction_date
  - Relationships:
    - `belongsTo(User)` - Pemilik transaksi
    - `belongsTo(Wallet)` - Wallet yang digunakan
  - Scopes:
    - `completed()` - Filter transaksi selesai
    - `pending()` - Filter transaksi pending
    - `failed()` - Filter transaksi gagal
    - `pulsa()` - Filter transaksi pulsa
    - `dana()` - Filter transaksi DANA
    - `gopay()` - Filter transaksi GoPay
    - `tokenListrik()` - Filter transaksi token listrik
  - Accessors:
    - `getFormattedBalanceDeductedAttribute()` - Format Rupiah
    - `getFormattedCashReceivedAttribute()` - Format Rupiah
    - `getFormattedProfitAttribute()` - Format Rupiah

- **app/Models/User.php**
  - Added relationship: `mkiosTransactions()` - hasMany(MKiosTransaction)

- **app/Models/Wallet.php**
  - Added relationship: `mkiosTransactions()` - hasMany(MKiosTransaction)

### Controllers
- **app/Http/Controllers/MKiosController.php**
  - `index()` - Menampilkan halaman M-KIOS dengan statistik dan daftar transaksi
  - `store()` - Menyimpan transaksi baru dan mengurangi saldo wallet otomatis
  - `show()` - Menampilkan detail transaksi (belum diimplementasikan di view)
  - `destroy()` - Menghapus transaksi dan mengembalikan saldo ke wallet

### Views
- **resources/views/m-kios/index.blade.php**
  - Statistics cards (4 cards):
    - Total Transaksi
    - Total Profit
    - Total Modal
    - Total Penjualan
  - Transaction table dengan kolom:
    - Tanggal
    - Nomor HP
    - Provider
    - Wallet
    - Modal (balance_deducted)
    - Uang Diterima (cash_received)
    - Profit
    - Status
    - Aksi (delete button)
  - Add transaction modal form
  - Pagination
  - Empty state

### Routes
```php
Route::get('/m-kios', [MKiosController::class, 'index'])->name('m-kios.index');
Route::post('/m-kios', [MKiosController::class, 'store'])->name('m-kios.store');
Route::get('/m-kios/{mkiosTransaction}', [MKiosController::class, 'show'])->name('m-kios.show');
Route::delete('/m-kios/{mkiosTransaction}', [MKiosController::class, 'destroy'])->name('m-kios.destroy');
```

### Navigation
- Desktop navigation: `resources/views/layouts/navigation.blade.php`
- Mobile navigation: `resources/views/layouts/navigation.blade.php`
- Menu item: "M-KIOS" dengan active state detection

## Features

### 1. Statistik Dashboard
- **Total Transaksi**: Jumlah transaksi dengan status completed
- **Total Profit**: Sum dari semua profit transaksi completed
- **Total Modal**: Sum dari semua balance_deducted
- **Total Penjualan**: Sum dari semua cash_received

### 2. Tambah Transaksi
Form input dengan field:
- **Jenis Transaksi** (required) - Dropdown: Pulsa, DANA, GoPay, Token Listrik
- **Nomor HP** (required untuk pulsa/DANA/GoPay) - Nomor telepon customer (conditional)
- **ID Pelanggan** (required untuk token listrik) - Nomor meter listrik (conditional)
- **Provider** (optional, untuk pulsa) - Dropdown: Telkomsel, Indosat, XL, Tri, Smartfren, Axis (conditional)
- **Nominal Produk** (optional) - Kode produk/nominal (10000, 20000, dll)
- **Modal** (required) - Jumlah saldo yang dipotong dari wallet
- **Uang Diterima** (required) - Jumlah uang dari customer
- **Wallet** (required) - Dropdown wallet yang tersedia dengan saldo
- **Catatan** (optional) - Textarea untuk catatan tambahan
- **Tanggal Transaksi** (optional) - Default: sekarang

**Dynamic Form Behavior:**
- Jika pilih **Pulsa**: tampil Nomor HP, Provider, Nominal Produk
- Jika pilih **DANA** atau **GoPay**: tampil Nomor HP, Nominal Produk
- Jika pilih **Token Listrik**: tampil ID Pelanggan, Nominal Produk

**Business Logic:**
1. Validasi saldo wallet mencukupi
2. Hitung profit = cash_received - balance_deducted
3. Create M-KIOS transaction dengan status 'completed'
4. Kurangi saldo wallet secara otomatis
5. Redirect dengan success message

### 3. Daftar Transaksi
- Tabel dengan pagination (20 per page)
- Sorting by transaction_date DESC
- Display:
  - Tanggal format: d/m/Y H:i
  - Nomor HP dengan catatan (truncated)
  - Provider atau "-" jika kosher
  - Wallet name atau "-" jika null
  - Modal dengan format Rupiah (text merah)
  - Uang Diterima dengan format Rupiah (text hijau)
  - Profit dengan format Rupiah (text biru, bold)
  - Status badge (completed=hijau, pending=kuning, failed=merah)
  - Delete button dengan konfirmasi

### 4. Hapus Transaksi
- Confirmation dialog
- Authorization check (user_id match)
- Restore balance ke wallet jika status=completed
- Soft atau hard delete (currently hard delete)
- Redirect dengan success message

### 5. Modal Form
- Full-screen overlay dengan z-index 50
- Toggle dengan JavaScript function `toggleModal()`
- Close button di header
- Form validation HTML5
- Default datetime value = now

## Usage Examples

### 1. Akses M-KIOS
```
1. Login ke aplikasi
2. Klik menu "M-KIOS" di navigation bar
3. Halaman M-KIOS akan menampilkan statistik dan daftar transaksi
```

### 2. Tambah Transaksi Pulsa
```
1. Klik tombol "Tambah Transaksi" (biru, di kanan atas tabel)
2. Modal form akan muncul
3. Isi form:
   - Nomor HP: 081234567890
   - Provider: Telkomsel
   - Modal: 10000
   - Uang Diterima: 12000
   - Wallet: Pilih wallet (misal: Cash)
   - Catatan: Pulsa 10rb
   - Tanggal: biarkan default atau ubah
4. Klik "Simpan Transaksi"
5. Sistem akan:
   - Validasi data
   - Check saldo wallet >= modal
   - Hitung profit (12000 - 10000 = 2000)
   - Simpan transaksi
   - Kurangi saldo wallet Rp 10.000
6. Redirect ke halaman M-KIOS dengan pesan sukses
7. Transaksi muncul di tabel
```

### 3. Hapus Transaksi
```
1. Pada baris transaksi, klik icon delete (merah)
2. Confirm dialog muncul: "Yakin ingin menghapus transaksi ini?"
3. Klik OK
4. Sistem akan:
   - Check authorization
   - Jika status=completed, kembalikan saldo ke wallet
   - Hapus transaksi dari database
5. Redirect dengan pesan sukses
6. Statistik dan tabel ter-update
```

### 4. View Empty State
```
Jika belum ada transaksi:
- Icon document ditampilkan
- Text: "Belum ada transaksi"
- Subtext: "Mulai dengan menambahkan transaksi pulsa pertama Anda."
- Button: "Tambah Transaksi" untuk open modal
```

## Integration

### Dengan Wallet System
- Setiap transaksi M-KIOS terhubung ke wallet
- Saldo wallet otomatis berkurang saat create transaksi
- Saldo wallet otomatis bertambah saat delete transaksi
- Wallet dropdown menampilkan nama dan saldo current

### Dengan User System
- Setiap transaksi terikat ke user yang sedang login
- Authorization check pada show dan destroy
- Query filtered by Auth::user()

### Dengan Report System (Future)
- M-KIOS bisa ditambahkan ke report system
- Report profit M-KIOS per periode
- Comparison dengan transaction reguler

## Security

### Authorization
- All routes protected by `auth` middleware
- User hanya bisa create transaksi untuk dirinya sendiri
- User hanya bisa view transaksi miliknya
- User hanya bisa delete transaksi miliknya
- Check user_id pada show() dan destroy()

### Validation
- phone_number: required, string, max 20
- balance_deducted: required, numeric, min 0
- cash_received: required, numeric, min 0
- provider: nullable, string, max 50
- wallet_id: required, exists in wallets table
- notes: nullable, string, max 1000
- transaction_date: nullable, date

### Data Integrity
- Foreign key constraints
- user_id cascade on delete (jika user dihapus, transaksi ikut terhapus)
- wallet_id set null on delete (jika wallet dihapus, transaction tetap ada dengan wallet_id=null)
- DB transaction untuk atomicity (create + wallet update)

## Testing

### Manual Testing Checklist
- [ ] Create transaksi dengan saldo mencukupi
- [ ] Create transaksi dengan saldo tidak mencukupi (error)
- [ ] Create transaksi tanpa provider (optional works)
- [ ] Create transaksi dengan catatan
- [ ] Create transaksi dengan custom date
- [ ] View statistik update setelah create
- [ ] View transaksi di tabel
- [ ] Pagination works dengan > 20 transaksi
- [ ] Delete transaksi completed (saldo kembali)
- [ ] Delete transaksi pending/failed (saldo tidak kembali)
- [ ] Modal open/close dengan JavaScript
- [ ] Form validation works
- [ ] Authorization works (tidak bisa delete transaksi user lain)

### Edge Cases
- Wallet dengan saldo pas dengan modal (works)
- Wallet dengan saldo 0 (error)
- Create dengan profit negatif (uang diterima < modal) - works, profit negatif
- Delete transaksi dengan wallet yang sudah dihapus (wallet_id null) - skip restore
- Multiple tab submit bersamaan (race condition) - handled by DB transaction

## Future Enhancements

### Priority HIGH
- [ ] Edit/Update transaction functionality
- [ ] Filter by date range
- [ ] Filter by provider
- [ ] Filter by status
- [ ] Search by phone number

### Priority MEDIUM
- [ ] Export to PDF/Excel/CSV
- [ ] Bulk import dari file
- [ ] Dashboard chart (profit per hari/bulan)
- [ ] Provider statistics (which provider most used)
- [ ] Customer history (by phone number)

### Priority LOW
- [ ] Recurring transactions
- [ ] Transaction reminders
- [ ] SMS notification to customer
- [ ] WhatsApp integration
- [ ] Stock management (pulsa stock)

## API Endpoints (Future)

```
GET    /api/m-kios                    - List transactions
POST   /api/m-kios                    - Create transaction
GET    /api/m-kios/{id}               - Show transaction
PUT    /api/m-kios/{id}               - Update transaction
DELETE /api/m-kios/{id}               - Delete transaction
GET    /api/m-kios/stats              - Get statistics
GET    /api/m-kios/export/{format}    - Export data
```

## Troubleshooting

### Problem: Saldo wallet tidak berkurang
- **Solution**: Check apakah DB transaction berhasil, check Wallet model method `subtractBalance()`

### Problem: Profit tidak muncul
- **Solution**: Check apakah profit di-calculate di controller, check casts di model

### Problem: Modal tidak bisa dibuka
- **Solution**: Check JavaScript function `toggleModal()`, check z-index modal

### Problem: Delete tidak restore saldo
- **Solution**: Check kondisi `if ($transaction->status === 'completed')`, check wallet relationship

### Problem: Pagination tidak works
- **Solution**: Check `->paginate(20)` di controller, check `{{ $transactions->links() }}` di view

## Credits
- **Developer**: AI Assistant
- **Framework**: Laravel 11
- **Frontend**: Tailwind CSS, Alpine.js
- **Database**: MySQL
- **Created**: January 2025
