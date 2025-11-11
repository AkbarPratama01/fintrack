# Report Backend Documentation

## Overview
Sistem report yang komprehensif untuk menganalisis data keuangan dengan 5 jenis laporan berbeda: Income vs Expense, Category Breakdown, Monthly Summary, Wallet Balance, dan Cash Flow.

## Database Structure

Tidak ada tabel tambahan yang diperlukan. Report menggunakan data dari tabel yang sudah ada:
- `transactions` - Semua transaksi income dan expense
- `wallets` - Data wallet user
- `categories` - Kategori transaksi

## Files Created

### 1. Controller
**File**: `app/Http/Controllers/ReportController.php`

**Methods**:
- `index()` - Menampilkan form generate report
- `generate(Request $request)` - Generate report berdasarkan parameter
- `getDateRange()` - Helper untuk menentukan range tanggal
- `generateIncomeExpenseReport()` - Generate laporan income vs expense
- `generateCategoryBreakdownReport()` - Generate laporan breakdown per kategori
- `generateMonthlySummaryReport()` - Generate laporan summary bulanan
- `generateWalletBalanceReport()` - Generate laporan saldo wallet
- `generateCashFlowReport()` - Generate laporan cash flow
- `exportPdf()` - Export ke PDF (coming soon)
- `exportExcel()` - Export ke Excel (coming soon)
- `exportCsv()` - Export ke CSV (coming soon)

### 2. Views

**Main Views**:
- `resources/views/reports/index.blade.php` - Form untuk generate report
- `resources/views/reports/show.blade.php` - Menampilkan hasil report

**Partial Views** (untuk setiap jenis report):
- `resources/views/reports/partials/income-expense.blade.php`
- `resources/views/reports/partials/category-breakdown.blade.php`
- `resources/views/reports/partials/monthly-summary.blade.php`
- `resources/views/reports/partials/wallet-balance.blade.php`
- `resources/views/reports/partials/cash-flow.blade.php`

### 3. Routes
**File**: `routes/web.php`

```php
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
Route::post('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
Route::post('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
Route::post('/reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.export.csv');
```

## Report Types

### 1. Income vs Expense Report
**Purpose**: Membandingkan total pemasukan dan pengeluaran dalam periode tertentu

**Data yang ditampilkan**:
- Total Income (total pemasukan)
- Total Expense (total pengeluaran)
- Net Income (pemasukan bersih = income - expense)
- Savings Rate (persentase tabungan)
- Daily breakdown (breakdown harian income vs expense)
- Transaction count (jumlah transaksi)
- Average daily income/expense

**Use Case**: Untuk melihat performa keuangan secara keseluruhan

### 2. Category Breakdown Report
**Purpose**: Melihat distribusi pengeluaran dan pemasukan per kategori

**Data yang ditampilkan**:
- Income by Category (pemasukan per kategori dengan persentase)
- Expense by Category (pengeluaran per kategori dengan persentase)
- Transaction count per kategori
- Total amount per kategori
- Visual progress bar untuk setiap kategori

**Use Case**: Untuk mengidentifikasi kategori mana yang paling banyak menghabiskan uang

### 3. Monthly Summary Report
**Purpose**: Ringkasan transaksi bulanan dalam periode tertentu

**Data yang ditampilkan**:
- Monthly table dengan kolom: Month, Income, Expense, Net, Savings Rate, Transactions
- Total dan average untuk semua bulan
- Visual trend chart bulanan
- Perbandingan performa antar bulan

**Use Case**: Untuk melihat trend keuangan dari bulan ke bulan

### 4. Wallet Balance Report
**Purpose**: Laporan saldo dan aktivitas setiap wallet

**Data yang ditampilkan**:
- Total balance semua wallet
- Period income/expense (total dalam periode)
- Detail per wallet: current balance, period income, period expense, transaction count
- Wallet type badge (bank, cash, e-wallet, dll)
- Balance distribution chart (distribusi saldo per wallet)

**Use Case**: Untuk monitoring saldo di setiap wallet

### 5. Cash Flow Report
**Purpose**: Laporan arus kas mendetail

**Data yang ditampilkan**:
- Opening Balance (saldo awal periode)
- Cash Inflow (total pemasukan)
- Cash Outflow (total pengeluaran)
- Closing Balance (saldo akhir periode)
- Net Cash Flow (arus kas bersih)
- Daily cash flow table dengan visual bar chart
- Cash Flow Statement (laporan arus kas formal)

**Use Case**: Untuk memahami pergerakan uang masuk dan keluar

## Form Parameters

### Required Fields
1. **report_type** (required)
   - Values: `income-expense`, `category-breakdown`, `monthly-summary`, `wallet-balance`, `cash-flow`
   - Menentukan jenis report yang akan di-generate

2. **period** (required)
   - Values: `this-month`, `last-month`, `last-3-months`, `last-6-months`, `this-year`, `custom`
   - Menentukan periode report

### Conditional Fields
3. **start_date** (required if period = custom)
   - Format: Y-m-d
   - Tanggal mulai untuk custom range

4. **end_date** (required if period = custom)
   - Format: Y-m-d
   - Tanggal akhir untuk custom range
   - Harus >= start_date

### Optional Fields
5. **wallets** (optional, array)
   - Array of wallet IDs
   - Jika kosong, akan include semua wallet user
   - Example: `wallets[]=1&wallets[]=2`

## Usage Examples

### Generate Income vs Expense Report (This Month)
```
URL: POST /reports/generate
Data:
{
    "report_type": "income-expense",
    "period": "this-month"
}
```

### Generate Category Breakdown (Last 3 Months, Specific Wallets)
```
URL: POST /reports/generate
Data:
{
    "report_type": "category-breakdown",
    "period": "last-3-months",
    "wallets": [1, 3, 5]
}
```

### Generate Cash Flow (Custom Range)
```
URL: POST /reports/generate
Data:
{
    "report_type": "cash-flow",
    "period": "custom",
    "start_date": "2025-01-01",
    "end_date": "2025-10-31",
    "wallets": [1, 2]
}
```

## Testing Guide

### Test 1: Access Report Page
1. Login ke aplikasi
2. Klik tombol "View Reports" di dashboard
3. Verify: Halaman report form muncul dengan semua field

### Test 2: Generate Income vs Expense Report
1. Di halaman report, pilih:
   - Report Type: Income vs Expense
   - Period: This Month
2. Klik "Generate Report"
3. Verify: 
   - Tampil 4 kartu stats (Income, Expense, Net Income, Savings Rate)
   - Tampil daily chart
   - Tampil summary

### Test 3: Generate with Specific Wallets
1. Pilih report type apapun
2. Pilih period
3. Check beberapa wallet (tidak semua)
4. Generate report
5. Verify: Report hanya menampilkan data dari wallet yang dipilih

### Test 4: Generate with Custom Date Range
1. Pilih report type
2. Pilih Period: Custom Range
3. Input start_date dan end_date
4. Generate report
5. Verify: Data sesuai dengan range yang dipilih

### Test 5: Validation
1. Submit form tanpa memilih report type → Error validation
2. Submit dengan period "custom" tapi tanpa start_date → Error validation
3. Submit dengan end_date < start_date → Error validation

### Test 6: All Report Types
Test setiap jenis report:
1. Income vs Expense
2. Category Breakdown
3. Monthly Summary
4. Wallet Balance
5. Cash Flow

Verify: Setiap report menampilkan data yang sesuai dengan formatnya

## Features

### ✅ Completed Features
1. **5 Jenis Report**
   - Income vs Expense
   - Category Breakdown
   - Monthly Summary
   - Wallet Balance
   - Cash Flow

2. **Period Options**
   - This Month
   - Last Month
   - Last 3 Months
   - Last 6 Months
   - This Year
   - Custom Range

3. **Wallet Filtering**
   - Select specific wallets
   - Or include all wallets

4. **Visual Representations**
   - Progress bars
   - Color-coded data (green for income, red for expense)
   - Data tables
   - Summary cards

5. **Calculations**
   - Total income/expense
   - Net income
   - Savings rate
   - Averages
   - Percentages
   - Transaction counts

### 🚧 Future Features (Coming Soon)
1. **Export Functionality**
   - PDF Export
   - Excel Export
   - CSV Export

2. **Charts & Graphs**
   - Line charts untuk trend
   - Pie charts untuk category breakdown
   - Bar charts untuk comparisons

3. **Scheduled Reports**
   - Email reports otomatis
   - Weekly/Monthly reports

4. **Advanced Filters**
   - Filter by specific categories
   - Filter by transaction amount range
   - Filter by description keywords

## Integration with Dashboard

Dashboard sekarang memiliki link ke report page:
- Quick Action button "View Reports" mengarah ke `/reports`
- Old modal report sudah dihapus
- User langsung ke dedicated report page

## Security

1. **Authentication**: Semua route report protected dengan auth middleware
2. **Authorization**: User hanya bisa melihat report dari data mereka sendiri
3. **Validation**: Semua input divalidasi di controller
4. **SQL Injection Prevention**: Menggunakan Eloquent ORM dan query builder

## Performance Considerations

1. **Efficient Queries**: Menggunakan `sum()`, `count()`, dan agregasi di database level
2. **Eager Loading**: Menggunakan `with()` untuk load relationships
3. **Limited Data**: Report hanya load data dalam periode yang dipilih
4. **Pagination**: Bisa ditambahkan untuk daily data jika terlalu banyak

## Next Steps

1. Implement PDF/Excel/CSV export
2. Add charts menggunakan Chart.js atau ApexCharts
3. Add print functionality
4. Add compare periods feature
5. Add scheduled reports via email
6. Add report templates/favorites

## Troubleshooting

### Issue: Report tidak menampilkan data
**Solution**: 
- Pastikan ada transactions dalam periode yang dipilih
- Verify wallet filter (jika ada)
- Check apakah user memiliki wallet

### Issue: Date range error
**Solution**:
- Pastikan end_date >= start_date
- Pastikan format date valid (Y-m-d)
- Pastikan period "custom" saat menggunakan custom dates

### Issue: Calculations tidak akurat
**Solution**:
- Check timezone settings
- Verify transaction dates
- Check wallet balance updates

## API Endpoints Summary

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/reports` | Display report form |
| POST | `/reports/generate` | Generate report with parameters |
| POST | `/reports/export/pdf` | Export report to PDF (coming soon) |
| POST | `/reports/export/excel` | Export report to Excel (coming soon) |
| POST | `/reports/export/csv` | Export report to CSV (coming soon) |

---

**Created**: November 10, 2025
**Version**: 1.0
**Status**: Production Ready (Export features coming soon)
