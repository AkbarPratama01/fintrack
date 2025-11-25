# Budget Feature Documentation

## Overview
Fitur Budget memungkinkan user untuk mengatur batas pengeluaran (budget) untuk setiap kategori expense dan melacak spending mereka terhadap budget tersebut.

## Database Structure

### Table: `budgets`
```sql
- id (primary key)
- user_id (foreign key to users)
- category_id (foreign key to categories)
- amount (decimal) - jumlah budget
- period (enum: 'monthly', 'yearly') - periode budget
- start_date (date) - tanggal mulai period
- end_date (date, nullable) - tanggal akhir
- is_active (boolean) - status aktif
- created_at, updated_at (timestamps)
- UNIQUE constraint: (user_id, category_id, period, start_date)
```

## Models

### Budget Model
**Location:** `app/Models/Budget.php`

**Methods:**
- `getSpending()` - Menghitung total pengeluaran untuk periode budget
- `getPercentage()` - Menghitung persentase budget yang sudah terpakai
- `getRemaining()` - Menghitung sisa budget
- `isExceeded()` - Mengecek apakah budget sudah terlampaui
- `getStatus()` - Mendapatkan status budget ('safe', 'warning', 'exceeded')

**Relationships:**
- `user()` - BelongsTo User
- `category()` - BelongsTo Category

## Controllers

### CategoryController Methods
**Location:** `app/Http/Controllers/CategoryController.php`

**New Methods:**
1. `setBudget(Request, Category)` - Set atau update budget untuk kategori
   - POST `/categories/{category}/budget`
   - Validates: amount (required, numeric, min:0.01), period (required, monthly/yearly)
   - Auto-calculate start_date based on period

2. `deleteBudget(Budget)` - Hapus budget
   - DELETE `/budgets/{budget}`

3. `getBudgetStatus()` - Tampilkan semua budget dengan status tracking
   - GET `/budgets`
   - Returns budget data dengan spending, remaining, percentage, status

## Routes
**Location:** `routes/web.php`

```php
Route::post('/categories/{category}/budget', [CategoryController::class, 'setBudget'])->name('categories.set-budget');
Route::delete('/budgets/{budget}', [CategoryController::class, 'deleteBudget'])->name('budgets.destroy');
Route::get('/budgets', [CategoryController::class, 'getBudgetStatus'])->name('budgets.index');
```

## Views

### 1. Categories Index (Updated)
**Location:** `resources/views/categories/index.blade.php`

**New Features:**
- Set Budget button (💰 icon) untuk setiap expense category
- Budget modal dengan form:
  - Amount input (Rp format)
  - Period selection (Monthly/Yearly)
- JavaScript functions:
  - `setBudget(categoryId, categoryName)`
  - `closeBudgetModal()`

### 2. Budget Tracking Page
**Location:** `resources/views/budgets/index.blade.php`

**Features:**
- List semua active budgets
- Progress bar untuk setiap budget
- Color-coded status:
  - Green (safe) - < 80%
  - Yellow (warning) - 80-99%
  - Red (exceeded) - >= 100%
- Shows:
  - Spent amount vs Budget amount
  - Remaining amount
  - Percentage used
  - Status badge (On Track / Warning / Over Budget)
- Delete budget button

### 3. Navigation (Updated)
**Location:** `resources/views/layouts/navigation.blade.php`

**New Menu Item:**
- Budget menu dengan icon 💰
- Route: `budgets.index`
- Active state highlighting

## Usage Flow

### Setting a Budget
1. Go to Categories page
2. Click Set Budget button (💰) on any expense category
3. Enter budget amount in Rupiah
4. Select period (Monthly or Yearly)
5. Click "Set Budget"
6. Budget automatically starts from current month/year

### Tracking Budget
1. Go to Budget page from navigation menu
2. View all active budgets with:
   - Progress bars showing spending vs budget
   - Status indicators
   - Remaining amounts
3. System automatically:
   - Calculates spending from transactions
   - Updates progress in real-time
   - Shows warnings when approaching limit

### Budget Status Logic
- **Safe (Green):** 0-79% spent
- **Warning (Yellow):** 80-99% spent
- **Exceeded (Red):** 100%+ spent

## Features

✅ **Set Budget per Category**
- Monthly or Yearly periods
- Only for expense categories
- One active budget per category per period

✅ **Real-time Tracking**
- Auto-calculate spending from transactions
- Visual progress bars
- Percentage indicators

✅ **Status Alerts**
- Color-coded warnings
- Clear remaining amounts
- Over-budget notifications

✅ **Budget Management**
- Update existing budgets
- Delete budgets
- View all budgets in one place

## Technical Notes

### Budget Calculation
- Monthly: Sum transactions in specific month/year
- Yearly: Sum transactions in specific year
- Only includes transactions with:
  - Same user_id
  - Same category_id
  - Type = 'expense'

### Auto-Periods
- Monthly budget: starts at beginning of current month
- Yearly budget: starts at beginning of current year
- Unique constraint prevents duplicate budgets for same period

### Model Relationships Updated
- `User` model: added `budgets()` relationship
- `Category` model: added `budgets()` relationship

## Future Enhancements (Optional)
- Budget rollover to next period
- Budget templates
- Notifications when approaching limit
- Historical budget comparison
- Budget vs actual reports
- Category group budgets
- Recurring budget adjustments
