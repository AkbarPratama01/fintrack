<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuranReading extends Model
{
    protected $fillable = [
        'user_id',
        'surah_number',
        'from_ayah',
        'to_ayah',
        'total_ayahs_read',
        'reading_date',
        'duration',
        'notes',
    ];

    protected $casts = [
        'reading_date' => 'date',
        'duration' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get Surah name in Arabic
    public function getSurahNameArabic()
    {
        return self::SURAHS[$this->surah_number]['arabic'] ?? '';
    }

    // Get Surah name in Indonesian
    public function getSurahNameIndonesian()
    {
        return self::SURAHS[$this->surah_number]['indonesian'] ?? '';
    }

    // Get Surah name in Latin
    public function getSurahNameLatin()
    {
        return self::SURAHS[$this->surah_number]['latin'] ?? '';
    }

    // Get total ayahs in surah
    public function getTotalAyahsInSurah()
    {
        return self::SURAHS[$this->surah_number]['total_ayahs'] ?? 0;
    }

    // Quran Surahs data
    const SURAHS = [
        1 => ['arabic' => 'الفاتحة', 'latin' => 'Al-Fatihah', 'indonesian' => 'Pembukaan', 'total_ayahs' => 7],
        2 => ['arabic' => 'البقرة', 'latin' => 'Al-Baqarah', 'indonesian' => 'Sapi Betina', 'total_ayahs' => 286],
        3 => ['arabic' => 'آل عمران', 'latin' => 'Ali \'Imran', 'indonesian' => 'Keluarga Imran', 'total_ayahs' => 200],
        4 => ['arabic' => 'النساء', 'latin' => 'An-Nisa\'', 'indonesian' => 'Wanita', 'total_ayahs' => 176],
        5 => ['arabic' => 'المائدة', 'latin' => 'Al-Ma\'idah', 'indonesian' => 'Hidangan', 'total_ayahs' => 120],
        6 => ['arabic' => 'الأنعام', 'latin' => 'Al-An\'am', 'indonesian' => 'Binatang Ternak', 'total_ayahs' => 165],
        7 => ['arabic' => 'الأعراف', 'latin' => 'Al-A\'raf', 'indonesian' => 'Tempat Tertinggi', 'total_ayahs' => 206],
        8 => ['arabic' => 'الأنفال', 'latin' => 'Al-Anfal', 'indonesian' => 'Harta Rampasan Perang', 'total_ayahs' => 75],
        9 => ['arabic' => 'التوبة', 'latin' => 'At-Taubah', 'indonesian' => 'Pengampunan', 'total_ayahs' => 129],
        10 => ['arabic' => 'يونس', 'latin' => 'Yunus', 'indonesian' => 'Yunus', 'total_ayahs' => 109],
        11 => ['arabic' => 'هود', 'latin' => 'Hud', 'indonesian' => 'Hud', 'total_ayahs' => 123],
        12 => ['arabic' => 'يوسف', 'latin' => 'Yusuf', 'indonesian' => 'Yusuf', 'total_ayahs' => 111],
        13 => ['arabic' => 'الرعد', 'latin' => 'Ar-Ra\'d', 'indonesian' => 'Guruh', 'total_ayahs' => 43],
        14 => ['arabic' => 'ابراهيم', 'latin' => 'Ibrahim', 'indonesian' => 'Ibrahim', 'total_ayahs' => 52],
        15 => ['arabic' => 'الحجر', 'latin' => 'Al-Hijr', 'indonesian' => 'Hijr', 'total_ayahs' => 99],
        16 => ['arabic' => 'النحل', 'latin' => 'An-Nahl', 'indonesian' => 'Lebah', 'total_ayahs' => 128],
        17 => ['arabic' => 'الإسراء', 'latin' => 'Al-Isra\'', 'indonesian' => 'Memperjalankan Malam Hari', 'total_ayahs' => 111],
        18 => ['arabic' => 'الكهف', 'latin' => 'Al-Kahf', 'indonesian' => 'Gua', 'total_ayahs' => 110],
        19 => ['arabic' => 'مريم', 'latin' => 'Maryam', 'indonesian' => 'Maryam', 'total_ayahs' => 98],
        20 => ['arabic' => 'طه', 'latin' => 'Ta-Ha', 'indonesian' => 'Ta Ha', 'total_ayahs' => 135],
        21 => ['arabic' => 'الأنبياء', 'latin' => 'Al-Anbiya\'', 'indonesian' => 'Para Nabi', 'total_ayahs' => 112],
        22 => ['arabic' => 'الحج', 'latin' => 'Al-Hajj', 'indonesian' => 'Haji', 'total_ayahs' => 78],
        23 => ['arabic' => 'المؤمنون', 'latin' => 'Al-Mu\'minun', 'indonesian' => 'Orang-Orang Mukmin', 'total_ayahs' => 118],
        24 => ['arabic' => 'النور', 'latin' => 'An-Nur', 'indonesian' => 'Cahaya', 'total_ayahs' => 64],
        25 => ['arabic' => 'الفرقان', 'latin' => 'Al-Furqan', 'indonesian' => 'Pembeda', 'total_ayahs' => 77],
        26 => ['arabic' => 'الشعراء', 'latin' => 'Asy-Syu\'ara\'', 'indonesian' => 'Penyair', 'total_ayahs' => 227],
        27 => ['arabic' => 'النمل', 'latin' => 'An-Naml', 'indonesian' => 'Semut', 'total_ayahs' => 93],
        28 => ['arabic' => 'القصص', 'latin' => 'Al-Qasas', 'indonesian' => 'Kisah-Kisah', 'total_ayahs' => 88],
        29 => ['arabic' => 'العنكبوت', 'latin' => 'Al-\'Ankabut', 'indonesian' => 'Laba-Laba', 'total_ayahs' => 69],
        30 => ['arabic' => 'الروم', 'latin' => 'Ar-Rum', 'indonesian' => 'Romawi', 'total_ayahs' => 60],
        // Continue with remaining surahs...
        31 => ['arabic' => 'لقمان', 'latin' => 'Luqman', 'indonesian' => 'Luqman', 'total_ayahs' => 34],
        32 => ['arabic' => 'السجدة', 'latin' => 'As-Sajdah', 'indonesian' => 'Sajdah', 'total_ayahs' => 30],
        33 => ['arabic' => 'الأحزاب', 'latin' => 'Al-Ahzab', 'indonesian' => 'Golongan-Golongan', 'total_ayahs' => 73],
        34 => ['arabic' => 'سبإ', 'latin' => 'Saba\'', 'indonesian' => 'Saba', 'total_ayahs' => 54],
        35 => ['arabic' => 'فاطر', 'latin' => 'Fatir', 'indonesian' => 'Pencipta', 'total_ayahs' => 45],
        36 => ['arabic' => 'يس', 'latin' => 'Ya-Sin', 'indonesian' => 'Ya Sin', 'total_ayahs' => 83],
        37 => ['arabic' => 'الصافات', 'latin' => 'As-Saffat', 'indonesian' => 'Barisan-Barisan', 'total_ayahs' => 182],
        38 => ['arabic' => 'ص', 'latin' => 'Sad', 'indonesian' => 'Sad', 'total_ayahs' => 88],
        39 => ['arabic' => 'الزمر', 'latin' => 'Az-Zumar', 'indonesian' => 'Rombongan', 'total_ayahs' => 75],
        40 => ['arabic' => 'غافر', 'latin' => 'Ghafir', 'indonesian' => 'Yang Mengampuni', 'total_ayahs' => 85],
        41 => ['arabic' => 'فصلت', 'latin' => 'Fussilat', 'indonesian' => 'Yang Dijelaskan', 'total_ayahs' => 54],
        42 => ['arabic' => 'الشورى', 'latin' => 'Asy-Syura', 'indonesian' => 'Musyawarah', 'total_ayahs' => 53],
        43 => ['arabic' => 'الزخرف', 'latin' => 'Az-Zukhruf', 'indonesian' => 'Perhiasan', 'total_ayahs' => 89],
        44 => ['arabic' => 'الدخان', 'latin' => 'Ad-Dukhan', 'indonesian' => 'Kabut', 'total_ayahs' => 59],
        45 => ['arabic' => 'الجاثية', 'latin' => 'Al-Jasiyah', 'indonesian' => 'Berlutut', 'total_ayahs' => 37],
        46 => ['arabic' => 'الأحقاف', 'latin' => 'Al-Ahqaf', 'indonesian' => 'Bukit Pasir', 'total_ayahs' => 35],
        47 => ['arabic' => 'محمد', 'latin' => 'Muhammad', 'indonesian' => 'Muhammad', 'total_ayahs' => 38],
        48 => ['arabic' => 'الفتح', 'latin' => 'Al-Fath', 'indonesian' => 'Kemenangan', 'total_ayahs' => 29],
        49 => ['arabic' => 'الحجرات', 'latin' => 'Al-Hujurat', 'indonesian' => 'Kamar-Kamar', 'total_ayahs' => 18],
        50 => ['arabic' => 'ق', 'latin' => 'Qaf', 'indonesian' => 'Qaf', 'total_ayahs' => 45],
        51 => ['arabic' => 'الذاريات', 'latin' => 'Az-Zariyat', 'indonesian' => 'Angin yang Menerbangkan', 'total_ayahs' => 60],
        52 => ['arabic' => 'الطور', 'latin' => 'At-Tur', 'indonesian' => 'Bukit Tursina', 'total_ayahs' => 49],
        53 => ['arabic' => 'النجم', 'latin' => 'An-Najm', 'indonesian' => 'Bintang', 'total_ayahs' => 62],
        54 => ['arabic' => 'القمر', 'latin' => 'Al-Qamar', 'indonesian' => 'Bulan', 'total_ayahs' => 55],
        55 => ['arabic' => 'الرحمن', 'latin' => 'Ar-Rahman', 'indonesian' => 'Yang Maha Pengasih', 'total_ayahs' => 78],
        56 => ['arabic' => 'الواقعة', 'latin' => 'Al-Waqi\'ah', 'indonesian' => 'Hari Kiamat', 'total_ayahs' => 96],
        57 => ['arabic' => 'الحديد', 'latin' => 'Al-Hadid', 'indonesian' => 'Besi', 'total_ayahs' => 29],
        58 => ['arabic' => 'المجادلة', 'latin' => 'Al-Mujadilah', 'indonesian' => 'Gugatan', 'total_ayahs' => 22],
        59 => ['arabic' => 'الحشر', 'latin' => 'Al-Hasyr', 'indonesian' => 'Pengusiran', 'total_ayahs' => 24],
        60 => ['arabic' => 'الممتحنة', 'latin' => 'Al-Mumtahanah', 'indonesian' => 'Wanita yang Diuji', 'total_ayahs' => 13],
        61 => ['arabic' => 'الصف', 'latin' => 'As-Saff', 'indonesian' => 'Barisan', 'total_ayahs' => 14],
        62 => ['arabic' => 'الجمعة', 'latin' => 'Al-Jumu\'ah', 'indonesian' => 'Jumat', 'total_ayahs' => 11],
        63 => ['arabic' => 'المنافقون', 'latin' => 'Al-Munafiqun', 'indonesian' => 'Orang-Orang Munafik', 'total_ayahs' => 11],
        64 => ['arabic' => 'التغابن', 'latin' => 'At-Taghabun', 'indonesian' => 'Hari Dinampakkan Kesalahan', 'total_ayahs' => 18],
        65 => ['arabic' => 'الطلاق', 'latin' => 'At-Talaq', 'indonesian' => 'Talak', 'total_ayahs' => 12],
        66 => ['arabic' => 'التحريم', 'latin' => 'At-Tahrim', 'indonesian' => 'Pengharaman', 'total_ayahs' => 12],
        67 => ['arabic' => 'الملك', 'latin' => 'Al-Mulk', 'indonesian' => 'Kerajaan', 'total_ayahs' => 30],
        68 => ['arabic' => 'القلم', 'latin' => 'Al-Qalam', 'indonesian' => 'Pena', 'total_ayahs' => 52],
        69 => ['arabic' => 'الحاقة', 'latin' => 'Al-Haqqah', 'indonesian' => 'Hari Kiamat', 'total_ayahs' => 52],
        70 => ['arabic' => 'المعارج', 'latin' => 'Al-Ma\'arij', 'indonesian' => 'Tempat Naik', 'total_ayahs' => 44],
        71 => ['arabic' => 'نوح', 'latin' => 'Nuh', 'indonesian' => 'Nuh', 'total_ayahs' => 28],
        72 => ['arabic' => 'الجن', 'latin' => 'Al-Jinn', 'indonesian' => 'Jin', 'total_ayahs' => 28],
        73 => ['arabic' => 'المزمل', 'latin' => 'Al-Muzzammil', 'indonesian' => 'Orang yang Berselimut', 'total_ayahs' => 20],
        74 => ['arabic' => 'المدثر', 'latin' => 'Al-Muddassir', 'indonesian' => 'Orang yang Berkemul', 'total_ayahs' => 56],
        75 => ['arabic' => 'القيامة', 'latin' => 'Al-Qiyamah', 'indonesian' => 'Hari Kiamat', 'total_ayahs' => 40],
        76 => ['arabic' => 'الانسان', 'latin' => 'Al-Insan', 'indonesian' => 'Manusia', 'total_ayahs' => 31],
        77 => ['arabic' => 'المرسلات', 'latin' => 'Al-Mursalat', 'indonesian' => 'Malaikat Yang Diutus', 'total_ayahs' => 50],
        78 => ['arabic' => 'النبإ', 'latin' => 'An-Naba\'', 'indonesian' => 'Berita Besar', 'total_ayahs' => 40],
        79 => ['arabic' => 'النازعات', 'latin' => 'An-Nazi\'at', 'indonesian' => 'Malaikat Yang Mencabut', 'total_ayahs' => 46],
        80 => ['arabic' => 'عبس', 'latin' => '\'Abasa', 'indonesian' => 'Ia Bermuka Masam', 'total_ayahs' => 42],
        81 => ['arabic' => 'التكوير', 'latin' => 'At-Takwir', 'indonesian' => 'Menggulung', 'total_ayahs' => 29],
        82 => ['arabic' => 'الإنفطار', 'latin' => 'Al-Infitar', 'indonesian' => 'Terbelah', 'total_ayahs' => 19],
        83 => ['arabic' => 'المطففين', 'latin' => 'Al-Mutaffifin', 'indonesian' => 'Orang-Orang Curang', 'total_ayahs' => 36],
        84 => ['arabic' => 'الإنشقاق', 'latin' => 'Al-Insyiqaq', 'indonesian' => 'Terbelah', 'total_ayahs' => 25],
        85 => ['arabic' => 'البروج', 'latin' => 'Al-Buruj', 'indonesian' => 'Gugusan Bintang', 'total_ayahs' => 22],
        86 => ['arabic' => 'الطارق', 'latin' => 'At-Tariq', 'indonesian' => 'Yang Datang di Malam Hari', 'total_ayahs' => 17],
        87 => ['arabic' => 'الأعلى', 'latin' => 'Al-A\'la', 'indonesian' => 'Yang Paling Tinggi', 'total_ayahs' => 19],
        88 => ['arabic' => 'الغاشية', 'latin' => 'Al-Ghasyiyah', 'indonesian' => 'Hari Pembalasan', 'total_ayahs' => 26],
        89 => ['arabic' => 'الفجر', 'latin' => 'Al-Fajr', 'indonesian' => 'Fajar', 'total_ayahs' => 30],
        90 => ['arabic' => 'البلد', 'latin' => 'Al-Balad', 'indonesian' => 'Negeri', 'total_ayahs' => 20],
        91 => ['arabic' => 'الشمس', 'latin' => 'Asy-Syams', 'indonesian' => 'Matahari', 'total_ayahs' => 15],
        92 => ['arabic' => 'الليل', 'latin' => 'Al-Lail', 'indonesian' => 'Malam', 'total_ayahs' => 21],
        93 => ['arabic' => 'الضحى', 'latin' => 'Ad-Duha', 'indonesian' => 'Waktu Duha', 'total_ayahs' => 11],
        94 => ['arabic' => 'الشرح', 'latin' => 'Asy-Syarh', 'indonesian' => 'Lapang', 'total_ayahs' => 8],
        95 => ['arabic' => 'التين', 'latin' => 'At-Tin', 'indonesian' => 'Buah Tin', 'total_ayahs' => 8],
        96 => ['arabic' => 'العلق', 'latin' => 'Al-\'Alaq', 'indonesian' => 'Segumpal Darah', 'total_ayahs' => 19],
        97 => ['arabic' => 'القدر', 'latin' => 'Al-Qadr', 'indonesian' => 'Kemuliaan', 'total_ayahs' => 5],
        98 => ['arabic' => 'البينة', 'latin' => 'Al-Bayyinah', 'indonesian' => 'Bukti yang Nyata', 'total_ayahs' => 8],
        99 => ['arabic' => 'الزلزلة', 'latin' => 'Az-Zalzalah', 'indonesian' => 'Kegoncangan', 'total_ayahs' => 8],
        100 => ['arabic' => 'العاديات', 'latin' => 'Al-\'Adiyat', 'indonesian' => 'Kuda Perang', 'total_ayahs' => 11],
        101 => ['arabic' => 'القارعة', 'latin' => 'Al-Qari\'ah', 'indonesian' => 'Hari Kiamat', 'total_ayahs' => 11],
        102 => ['arabic' => 'التكاثر', 'latin' => 'At-Takasur', 'indonesian' => 'Bermegah-megahan', 'total_ayahs' => 8],
        103 => ['arabic' => 'العصر', 'latin' => 'Al-\'Asr', 'indonesian' => 'Masa', 'total_ayahs' => 3],
        104 => ['arabic' => 'الهمزة', 'latin' => 'Al-Humazah', 'indonesian' => 'Pengumpat', 'total_ayahs' => 9],
        105 => ['arabic' => 'الفيل', 'latin' => 'Al-Fil', 'indonesian' => 'Gajah', 'total_ayahs' => 5],
        106 => ['arabic' => 'قريش', 'latin' => 'Quraisy', 'indonesian' => 'Quraisy', 'total_ayahs' => 4],
        107 => ['arabic' => 'الماعون', 'latin' => 'Al-Ma\'un', 'indonesian' => 'Barang-Barang yang Berguna', 'total_ayahs' => 7],
        108 => ['arabic' => 'الكوثر', 'latin' => 'Al-Kausar', 'indonesian' => 'Nikmat yang Berlimpah', 'total_ayahs' => 3],
        109 => ['arabic' => 'الكافرون', 'latin' => 'Al-Kafirun', 'indonesian' => 'Orang-Orang Kafir', 'total_ayahs' => 6],
        110 => ['arabic' => 'النصر', 'latin' => 'An-Nasr', 'indonesian' => 'Pertolongan', 'total_ayahs' => 3],
        111 => ['arabic' => 'المسد', 'latin' => 'Al-Masad', 'indonesian' => 'Api yang Bergejolak', 'total_ayahs' => 5],
        112 => ['arabic' => 'الإخلاص', 'latin' => 'Al-Ikhlas', 'indonesian' => 'Ikhlas', 'total_ayahs' => 4],
        113 => ['arabic' => 'الفلق', 'latin' => 'Al-Falaq', 'indonesian' => 'Waktu Subuh', 'total_ayahs' => 5],
        114 => ['arabic' => 'الناس', 'latin' => 'An-Nas', 'indonesian' => 'Manusia', 'total_ayahs' => 6],
    ];
}
