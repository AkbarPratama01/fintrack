# Budget Auto-Reset Feature Documentation

## Overview
Fitur ini secara otomatis mereset budget setiap awal bulan (untuk monthly budget) dan awal tahun (untuk yearly budget).

## ✨ Fitur

### 1. Automatic Monthly Reset
- Budget bulanan akan direset otomatis setiap tanggal 1 bulan baru
- Periode budget akan diupdate ke bulan berjalan
- `start_date` diset ke tanggal 1 bulan ini
- `end_date` diset ke akhir bulan ini

### 2. Automatic Yearly Reset
- Budget tahunan akan direset otomatis setiap tanggal 1 Januari
- Periode budget akan diupdate ke tahun berjalan
- `start_date` diset ke 1 Januari tahun ini
- `end_date` diset ke 31 Desember tahun ini

## 🔧 Technical Implementation

### Command: `budgets:reset-monthly`

**File**: `app/Console/Commands/ResetMonthlyBudgets.php`

**Fungsi**:
- Mengecek semua budget aktif (is_active = true)
- Membandingkan start_date dengan bulan/tahun saat ini
- Mereset budget yang sudah expired ke periode baru
- Logging setiap budget yang direset

**Schedule**: Berjalan otomatis setiap tanggal 1 pukul 00:01

### Scheduler Configuration

**File**: `routes/console.php`

```php
Schedule::command('budgets:reset-monthly')->monthlyOn(1, '00:01');
```

- Command berjalan setiap tanggal 1 jam 00:01 dini hari
- Menggunakan Laravel Task Scheduler

### Database Structure

**Table**: `budgets`

| Field | Type | Description |
|-------|------|-------------|
| start_date | date | Tanggal mulai periode budget |
| end_date | date | Tanggal akhir periode budget (nullable) |
| period | enum | 'monthly' atau 'yearly' |
| is_active | boolean | Status aktif budget |

### Model Updates

**File**: `app/Models/Budget.php`

Method `getSpending()` sudah disesuaikan untuk:
- Monthly budget: filter by month & year
- Yearly budget: filter by year
- Menggunakan start_date sebagai referensi periode

### Controller Updates

**File**: `app/Http/Controllers/CategoryController.php`

Method `setBudget()`:
- Set start_date dan end_date saat create/update budget
- Monthly: start = awal bulan, end = akhir bulan
- Yearly: start = 1 Januari, end = 31 Desember

## 📅 Usage

### Manual Reset (For Testing)

```bash
php artisan budgets:reset-monthly
```

Output:
```
Reset budget for category Food & Dining (ID: 1)
Reset budget for category Transportation (ID: 2)
Successfully reset 2 budget(s).
```

### Automatic Reset

Budget akan otomatis direset setiap awal bulan tanpa perlu intervensi manual.

**Prerequisites**:
Laravel Task Scheduler harus berjalan. Tambahkan cron job:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

**Atau untuk development di Windows (Laragon)**:
```bash
php artisan schedule:work
```

## 🔍 How It Works

### Monthly Budget Reset Flow

1. **Trigger**: Setiap tanggal 1 jam 00:01
2. **Check**: Ambil semua budget dengan `is_active = true` dan `period = 'monthly'`
3. **Compare**: Bandingkan bulan/tahun pada `start_date` dengan bulan/tahun saat ini
4. **Update**: Jika berbeda, update:
   - `start_date` → awal bulan ini
   - `end_date` → akhir bulan ini
5. **Log**: Catat budget yang direset

### Yearly Budget Reset Flow

1. **Trigger**: Setiap 1 Januari jam 00:01
2. **Check**: Ambil semua budget dengan `is_active = true` dan `period = 'yearly'`
3. **Compare**: Bandingkan tahun pada `start_date` dengan tahun saat ini
4. **Update**: Jika berbeda, update:
   - `start_date` → 1 Januari tahun ini
   - `end_date` → 31 Desember tahun ini
5. **Log**: Catat budget yang direset

## 💡 Example Scenarios

### Scenario 1: Monthly Budget Reset

**Sebelum Reset (31 Januari 2025)**:
```
Budget ID: 1
Category: Food & Dining
Amount: 5,000,000
Period: monthly
Start Date: 2025-01-01
End Date: 2025-01-31
Spending: 4,200,000
```

**Setelah Reset (1 Februari 2025)**:
```
Budget ID: 1
Category: Food & Dining
Amount: 5,000,000 (unchanged)
Period: monthly (unchanged)
Start Date: 2025-02-01 (updated)
End Date: 2025-02-28 (updated)
Spending: 0 (fresh start)
```

### Scenario 2: Yearly Budget Reset

**Sebelum Reset (31 Desember 2024)**:
```
Budget ID: 5
Category: Entertainment
Amount: 20,000,000
Period: yearly
Start Date: 2024-01-01
End Date: 2024-12-31
Spending: 18,500,000
```

**Setelah Reset (1 Januari 2025)**:
```
Budget ID: 5
Category: Entertainment
Amount: 20,000,000 (unchanged)
Period: yearly (unchanged)
Start Date: 2025-01-01 (updated)
End Date: 2025-12-31 (updated)
Spending: 0 (fresh start)
```

## 🛡️ Safety Features

1. **Only Active Budgets**: Hanya budget dengan `is_active = true` yang direset
2. **Period Check**: Budget hanya direset jika periodenya sudah lewat
3. **Preserve Settings**: Amount dan period tidak berubah, hanya tanggal
4. **Transaction History**: Transaksi lama tetap tersimpan, tidak terhapus
5. **Logging**: Setiap reset tercatat dalam command output

## ⚠️ Important Notes

1. **Spending Calculation**: Spending dihitung fresh setiap periode baru
2. **Old Transactions**: Transaksi lama tidak ikut dihitung setelah reset
3. **Budget Amount**: Jumlah budget tetap sama, tidak reset ke 0
4. **Manual Adjustment**: User tetap bisa manual edit budget amount kapan saja
5. **Inactive Budgets**: Budget dengan `is_active = false` tidak akan direset

## 🔄 Maintenance

### Check Schedule Status
```bash
php artisan schedule:list
```

### Test Specific Date (For Testing)
Gunakan Carbon::setTestNow() dalam testing environment:
```php
Carbon::setTestNow('2025-02-01 00:01:00');
$this->artisan('budgets:reset-monthly');
```

## 📊 Monitoring

Command akan output informasi:
- Jumlah budget yang direset
- Detail setiap budget (category name & ID)
- Status sukses/gagal

**Success Message**:
```
Reset budget for category Food & Dining (ID: 1)
Successfully reset 1 budget(s).
```

**No Reset Needed**:
```
No budgets need to be reset at this time.
```

## 🚀 Deployment Checklist

- [x] Command created: `budgets:reset-monthly`
- [x] Scheduled in console.php
- [x] Migration includes end_date field
- [x] Controller sets start_date and end_date
- [x] Model calculates spending correctly
- [ ] Cron job configured (production)
- [ ] Tested in staging environment
- [ ] Monitoring/logging configured

## 📝 Future Enhancements (Optional)

- [ ] Email notification saat budget direset
- [ ] Budget rollover (carry unused budget to next period)
- [ ] Custom reset schedule per budget
- [ ] Budget history/archive
- [ ] Reset confirmation in UI

---

**Created**: December 2, 2025  
**Version**: 1.0.0  
**Command**: `php artisan budgets:reset-monthly`  
**Schedule**: Monthly on 1st at 00:01
