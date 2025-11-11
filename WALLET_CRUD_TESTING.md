# Testing Wallet CRUD

## Backend yang Telah Dibuat

### 1. Migration
- File: `database/migrations/2025_11_10_153857_create_wallets_table.php`
- Fields:
  - `id` - Primary key
  - `user_id` - Foreign key ke users table
  - `name` - Nama wallet
  - `balance` - Saldo wallet (decimal 15,2)
  - `type` - Tipe wallet (cash, bank, e-wallet, credit-card, savings)
  - `currency` - Mata uang (3 karakter, default IDR)
  - `description` - Deskripsi opsional
  - `created_at`, `updated_at` - Timestamps

### 2. Model
- File: `app/Models/Wallet.php`
- Relationships:
  - `belongsTo(User::class)` - Wallet belongs to User
  - `hasMany(Transaction::class)` - Wallet has many Transactions
- Methods:
  - `addBalance($amount)` - Menambah saldo
  - `subtractBalance($amount)` - Mengurangi saldo
  - `getFormattedBalanceAttribute()` - Format saldo ke Rupiah

### 3. Controller
- File: `app/Http/Controllers/WalletController.php`
- Methods:
  - `index()` - List semua wallet user
  - `create()` - Form create wallet
  - `store(StoreWalletRequest)` - Simpan wallet baru
  - `show(Wallet)` - Detail wallet + transactions
  - `edit(Wallet)` - Form edit wallet
  - `update(UpdateWalletRequest, Wallet)` - Update wallet
  - `destroy(Wallet)` - Hapus wallet
  - `getWallets()` - API endpoint untuk AJAX

### 4. Form Requests
- `app/Http/Requests/StoreWalletRequest.php` - Validasi create wallet
- `app/Http/Requests/UpdateWalletRequest.php` - Validasi update wallet

### 5. Routes
```php
Route::middleware('auth')->group(function () {
    Route::resource('wallets', WalletController::class);
    Route::get('/api/wallets', [WalletController::class, 'getWallets'])->name('wallets.api');
});
```

## Cara Testing

### 1. Pastikan Database Migration Sudah Running
```bash
php artisan migrate
```

### 2. Login ke Aplikasi
- Buka browser: http://fintrack.test/login
- Login dengan kredensial user

### 3. Test Create Wallet
- Di dashboard, klik tombol "Add Wallet"
- Modal akan terbuka
- Isi form:
  - Wallet Name: "Main Wallet"
  - Initial Balance: 1000000
  - Wallet Type: Bank Account
  - Currency: IDR
  - Description: "My primary bank account"
- Klik "Create Wallet"
- Seharusnya muncul notifikasi success dan redirect ke dashboard

### 4. Test Validation
- Klik "Add Wallet" lagi
- Kosongkan field "Wallet Name"
- Submit form
- Modal akan tetap terbuka dengan error message

### 5. Test Routes Manual

#### Create Wallet (POST)
```
POST /wallets
Body:
- name: Test Wallet
- balance: 500000
- type: cash
- currency: IDR
- description: Testing wallet
```

#### List Wallets (GET)
```
GET /wallets
```

#### Show Single Wallet (GET)
```
GET /wallets/{id}
```

#### Update Wallet (PUT/PATCH)
```
PUT /wallets/{id}
Body:
- name: Updated Wallet Name
- type: bank
- currency: USD
- description: Updated description
```

#### Delete Wallet (DELETE)
```
DELETE /wallets/{id}
```

#### Get Wallets API (GET)
```
GET /api/wallets
Response: JSON array of wallets
```

## Features yang Sudah Diimplementasi

✅ Create wallet dengan validation
✅ Form validation dengan custom messages
✅ Auto-open modal saat ada error
✅ Success/Error notifications
✅ Authorization check (wallet hanya bisa diakses owner)
✅ Relationship dengan User model
✅ Helper methods untuk manage balance
✅ Formatted balance accessor (Rp format)
✅ CSRF protection
✅ Old input preservation saat validation error

## Next Steps (Opsional)

1. Buat view untuk list wallets (`wallets/index.blade.php`)
2. Buat view untuk edit wallet (`wallets/edit.blade.php`)
3. Tambahkan AJAX untuk create wallet tanpa page refresh
4. Tampilkan list wallets di dashboard dengan data real
5. Buat fitur update dan delete wallet di dashboard
6. Implementasi soft deletes untuk wallet
7. Buat seeder untuk sample wallets
8. Buat unit tests untuk Wallet model dan controller
