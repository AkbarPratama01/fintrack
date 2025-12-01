# Dokumentasi Fitur Notes dan Wish List

## Overview
Dokumentasi ini menjelaskan fitur **Notes** dan **Wish List** yang telah ditambahkan ke aplikasi FinTrack.

## 🗒️ Fitur Notes

### Deskripsi
Fitur Notes memungkinkan pengguna untuk membuat catatan personal dengan berbagai tingkat prioritas dan kemampuan untuk mem-pin catatan penting.

### Struktur Database

#### Tabel: `notes`
| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| id | bigint unsigned | Primary key |
| user_id | bigint unsigned | Foreign key ke tabel users |
| title | varchar(255) | Judul note (required) |
| content | text | Isi note (nullable) |
| priority | enum('low', 'medium', 'high') | Tingkat prioritas (default: medium) |
| is_pinned | boolean | Status pin (default: false) |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

### Fitur Utama

#### 1. Create Note
- **Route**: `POST /notes`
- **Controller Method**: `NoteController@store`
- **Validasi**:
  - `title`: required, string, max 255 karakter
  - `content`: nullable, string
  - `priority`: required, in:low,medium,high
  - `is_pinned`: boolean

#### 2. Read Notes
- **Route**: `GET /notes`
- **Controller Method**: `NoteController@index`
- **Fitur**:
  - Menampilkan notes dalam format grid
  - Sorting berdasarkan pinned status dan tanggal dibuat (descending)
  - Pagination (10 items per halaman)
  - Filter berdasarkan user yang sedang login

#### 3. Update Note
- **Route**: `PUT /notes/{note}`
- **Controller Method**: `NoteController@update`
- **Fitur**: Edit semua field note

#### 4. Delete Note
- **Route**: `DELETE /notes/{note}`
- **Controller Method**: `NoteController@destroy`
- **Fitur**: Soft delete dengan konfirmasi

#### 5. Toggle Pin
- **Route**: `POST /notes/{note}/toggle-pin`
- **Controller Method**: `NoteController@togglePin`
- **Fitur**: Pin/unpin note untuk menampilkan di bagian atas

### Views

#### 1. index.blade.php
- Grid layout dengan 3 kolom (responsive)
- Color-coded berdasarkan priority (low=green, medium=yellow, high=red)
- Visual indicator untuk pinned notes
- Quick actions: Pin/Unpin, Edit, Delete

#### 2. create.blade.php
- Form untuk membuat note baru
- Input fields: Title, Content, Priority, Is Pinned
- Validation messages

#### 3. edit.blade.php
- Form untuk mengedit note
- Pre-filled dengan data existing
- Validation messages

### Keamanan
- Setiap operasi memvalidasi kepemilikan note (user_id === Auth::id())
- Authorization check menggunakan kondisi `if ($note->user_id !== Auth::id()) abort(403)`

---

## ❤️ Fitur Wish List

### Deskripsi
Fitur Wish List memungkinkan pengguna untuk membuat daftar keinginan/tujuan finansial dengan tracking progress tabungan.

### Struktur Database

#### Tabel: `wish_lists`
| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| id | bigint unsigned | Primary key |
| user_id | bigint unsigned | Foreign key ke tabel users |
| name | varchar(255) | Nama wishlist (required) |
| description | text | Deskripsi (nullable) |
| target_amount | decimal(15,2) | Target jumlah yang ingin dicapai (required) |
| saved_amount | decimal(15,2) | Jumlah yang sudah ditabung (default: 0) |
| target_date | date | Target tanggal pencapaian (nullable) |
| priority | enum('low', 'medium', 'high') | Tingkat prioritas (default: medium) |
| status | enum('planning', 'saving', 'completed', 'cancelled') | Status wishlist (default: planning) |
| image_url | varchar(255) | Path ke gambar wishlist (nullable) |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

### Fitur Utama

#### 1. Create Wish List
- **Route**: `POST /wishlists`
- **Controller Method**: `WishListController@store`
- **Validasi**:
  - `name`: required, string, max 255
  - `description`: nullable, string
  - `target_amount`: required, numeric, min 0
  - `saved_amount`: nullable, numeric, min 0
  - `target_date`: nullable, date
  - `priority`: required, in:low,medium,high
  - `status`: required, in:planning,saving,completed,cancelled
  - `image`: nullable, image, max 2MB

#### 2. Read Wish Lists
- **Route**: `GET /wishlists`
- **Controller Method**: `WishListController@index`
- **Fitur**:
  - Grid layout dengan gambar
  - Progress bar untuk tracking savings
  - Status badges (color-coded)
  - Pagination (12 items per halaman)

#### 3. Update Wish List
- **Route**: `PUT /wishlists/{wishlist}`
- **Controller Method**: `WishListController@update`
- **Fitur**:
  - Edit semua field
  - Upload/replace image
  - Auto-delete old image saat upload baru

#### 4. Delete Wish List
- **Route**: `DELETE /wishlists/{wishlist}`
- **Controller Method**: `WishListController@destroy`
- **Fitur**:
  - Delete wishlist dan image terkait
  - Konfirmasi sebelum delete

#### 5. Add Savings
- **Route**: `POST /wishlists/{wishlist}/add-savings`
- **Controller Method**: `WishListController@addSavings`
- **Fitur**:
  - Menambah jumlah tabungan
  - Auto-update status ke 'saving' jika dari 'planning'
  - Auto-update status ke 'completed' jika target tercapai
  - Modal popup untuk input amount

### Model Attributes & Methods

#### Computed Attributes
```php
// Progress percentage (0-100)
$wishlist->progress_percentage

// Remaining amount to reach target
$wishlist->remaining_amount
```

### Views

#### 1. index.blade.php
- Grid layout dengan gambar/placeholder
- Progress bar dengan percentage
- Detail: Target, Saved, Remaining amount, Target date
- Status badges (color-coded)
- Quick actions: Add Savings, Edit, Delete
- Modal untuk add savings

#### 2. create.blade.php
- Form untuk membuat wishlist baru
- Upload image support
- All input fields dengan validation

#### 3. edit.blade.php
- Form untuk mengedit wishlist
- Preview current image
- Option untuk replace image
- Pre-filled dengan data existing

### Storage
- Images disimpan di `storage/app/public/wishlists/`
- Accessible via public URL: `storage/wishlists/{filename}`
- Max file size: 2MB
- Supported formats: jpg, jpeg, png, gif, svg

### Keamanan
- Setiap operasi memvalidasi kepemilikan wishlist (user_id === Auth::id())
- Authorization check: `if ($wishlist->user_id !== Auth::id()) abort(403)`
- Image validation: max 2MB, image files only
- Auto-delete old images saat update/delete

---

## 🚀 Routes

### Notes Routes
```php
Route::resource('notes', NoteController::class);
Route::post('/notes/{note}/toggle-pin', [NoteController::class, 'togglePin'])
    ->name('notes.toggle-pin');
```

### Wish List Routes
```php
Route::resource('wishlists', WishListController::class);
Route::post('/wishlists/{wishlist}/add-savings', [WishListController::class, 'addSavings'])
    ->name('wishlists.add-savings');
```

Semua routes berada dalam middleware `auth`.

---

## 📱 Navigasi

Kedua fitur ditambahkan ke sidebar navigation dengan icon yang sesuai:
- **Notes**: Icon dokumen/note
- **Wish List**: Icon heart/love

---

## 🎨 UI/UX Features

### Notes
- Color-coded borders berdasarkan priority
- Pin indicator dengan icon
- Truncated content dengan `line-clamp-3`
- Responsive grid (1 col mobile, 2 col tablet, 3 col desktop)
- Empty state dengan call-to-action

### Wish List
- Hero images atau gradient placeholder
- Animated progress bars
- Status badges dengan warna sesuai state
- Responsive grid layout
- Empty state dengan call-to-action
- Modal untuk add savings (smooth open/close)

---

## 🔄 Status Management

### Wish List Status Flow
1. **Planning** → Initial state
2. **Saving** → Auto-set saat first savings ditambahkan
3. **Completed** → Auto-set saat saved_amount >= target_amount
4. **Cancelled** → Manual set by user

---

## 💾 Migration Commands

```bash
# Run migrations
php artisan migrate --path=database/migrations/2025_12_01_225442_create_notes_table.php
php artisan migrate --path=database/migrations/2025_12_01_225502_create_wish_lists_table.php
```

---

## ✅ Testing Checklist

### Notes
- [ ] Create note dengan semua field
- [ ] Create note tanpa content (nullable)
- [ ] Edit note
- [ ] Delete note dengan konfirmasi
- [ ] Toggle pin/unpin
- [ ] View notes dengan pagination
- [ ] Cek sorting (pinned first, then by date)
- [ ] Cek authorization (user hanya bisa akses notes sendiri)

### Wish List
- [ ] Create wishlist dengan image
- [ ] Create wishlist tanpa image (gradient placeholder)
- [ ] Edit wishlist
- [ ] Edit wishlist dengan replace image
- [ ] Delete wishlist (image ikut terhapus)
- [ ] Add savings
- [ ] Auto status update: planning → saving
- [ ] Auto status update: saving → completed
- [ ] Progress bar calculation
- [ ] View wishlists dengan pagination
- [ ] Cek authorization (user hanya bisa akses wishlists sendiri)

---

## 📝 Notes untuk Developer

1. **Image Storage**: Pastikan symbolic link sudah dibuat dengan `php artisan storage:link`
2. **File Size**: Max 2MB untuk upload image (bisa diubah di validation rules)
3. **Pagination**: Bisa disesuaikan jumlahnya di controller (notes: 10, wishlists: 12)
4. **Dark Mode**: Semua UI sudah support dark mode dengan Tailwind classes
5. **Responsive**: Tested untuk mobile, tablet, dan desktop breakpoints

---

## 🔐 Security Best Practices

1. ✅ Authorization checks di setiap method
2. ✅ CSRF protection dengan @csrf token
3. ✅ Input validation di server-side
4. ✅ File upload validation (type & size)
5. ✅ User isolation (hanya bisa akses data sendiri)
6. ✅ Proper HTTP methods (GET, POST, PUT, DELETE)
7. ✅ Confirmation before delete actions

---

## 🎯 Future Enhancements (Optional)

### Notes
- [ ] Search functionality
- [ ] Filter by priority
- [ ] Rich text editor
- [ ] Tags/categories
- [ ] Color themes per note

### Wish List
- [ ] Link to financial goals/budgets
- [ ] Recurring savings scheduler
- [ ] Share wishlist with others
- [ ] Notifications for target date approaching
- [ ] Import from online shopping platforms

---

Created: December 2, 2025
Version: 1.0.0
