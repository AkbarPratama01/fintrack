<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard Analytics') }}
            </h2>
            
            <!-- Period Filter -->
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <select name="period" onchange="this.form.submit()" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="7" {{ $period == 7 ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30" {{ $period == 30 ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="90" {{ $period == 90 ? 'selected' : '' }}>90 Hari Terakhir</option>
                        <option value="365" {{ $period == 365 ? 'selected' : '' }}>1 Tahun Terakhir</option>
                    </select>
                </form>
                
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white text-sm font-semibold rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Reports
                </a>
            </div>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Balance -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm font-medium">Total Saldo</p>
                            <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h3>
                            <p class="text-indigo-100 text-xs mt-1">{{ $wallets->count() }} Wallet</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Income -->
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Pemasukan</p>
                            <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($income + $mkiosProfit, 0, ',', '.') }}</h3>
                            <p class="text-green-100 text-xs mt-1">{{ $transactionCount + $mkiosTransactionCount }} Transaksi</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Expense -->
                <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm font-medium">Total Pengeluaran</p>
                            <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($expense, 0, ',', '.') }}</h3>
                            <p class="text-red-100 text-xs mt-1">{{ $transactionCount }} Transaksi</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Net Income -->
                <div class="bg-gradient-to-br {{ $netIncome >= 0 ? 'from-blue-500 to-cyan-600' : 'from-orange-500 to-red-600' }} rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Pendapatan Bersih</p>
                            <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($netIncome, 0, ',', '.') }}</h3>
                            <p class="text-blue-100 text-xs mt-1">{{ $period }} Hari Terakhir</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Income vs Expense Chart -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 hover:shadow-xl transition duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Pemasukan vs Pengeluaran</h3>
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative h-64">
                        <canvas id="incomeExpenseChart"></canvas>
                    </div>
                </div>

                <!-- Category Breakdown -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 hover:shadow-xl transition duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Pengeluaran per Kategori</h3>
                        <div class="bg-gradient-to-r from-pink-500 to-rose-600 rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                        </div>
                    </div>
                    @if($expenseByCategory->count() > 0)
                    <div class="relative h-64">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    @else
                    <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-sm">Belum ada data pengeluaran</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Cash Flow Chart -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Tren Arus Kas ({{ $period }} Hari)</h3>
                    <div class="bg-gradient-to-r from-cyan-500 to-blue-600 rounded-full p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                </div>
                <div class="relative h-80">
                    <canvas id="cashFlowChart"></canvas>
                </div>
            </div>

            <!-- Charts and Recent Transactions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Recent Transactions -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Transactions</h3>
                            <a href="{{ route('transactions.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">View All</a>
                        </div>
                        @if($recentTransactions->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentTransactions as $transaction)
                                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition duration-150">
                                        <div class="flex items-center space-x-4">
                                            <div class="p-2 {{ $transaction->type == 'income' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} rounded-lg">
                                                @if($transaction->type == 'income')
                                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $transaction->category->icon }} {{ $transaction->category->name }}
                                                    @if($transaction->description)
                                                        <span class="text-xs text-gray-500 dark:text-gray-400"> - {{ Str::limit($transaction->description, 20) }}</span>
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $transaction->date->format('M d, Y') }} • {{ $transaction->wallet->name }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="text-sm font-semibold {{ $transaction->type == 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $transaction->type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No transactions yet</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Start by adding your first income or expense</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Wallet Performance & Top Categories -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Performa Wallet</h3>
                    <div class="space-y-3">
                        @foreach($walletStats as $stat)
                        <div class="border dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                        {{ substr($stat['wallet']->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $stat['wallet']->name }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($stat['wallet']->type) }} - {{ $stat['wallet']->currency }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($stat['wallet']->balance, 0, ',', '.') }}</p>
                                    <p class="text-xs {{ $stat['net'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $stat['net'] >= 0 ? '+' : '' }}Rp {{ number_format($stat['net'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-3 grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Pemasukan</p>
                                    <p class="font-semibold text-green-600">Rp {{ number_format($stat['income'], 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Pengeluaran</p>
                                    <p class="font-semibold text-red-600">Rp {{ number_format($stat['expense'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Top Expense Categories -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Top 5 Pengeluaran</h3>
                    <div class="space-y-3">
                        @forelse($topExpenseCategories as $index => $category)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold" style="background-color: {{ $category['category']->color ?? '#6366f1' }}">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100 text-sm">{{ $category['category']->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $category['count'] }} transaksi</p>
                                </div>
                            </div>
                            <p class="font-bold text-red-600 text-sm">Rp {{ number_format($category['total'], 0, ',', '.') }}</p>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm">Belum ada pengeluaran</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Regular Transactions -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Transaksi Terbaru</h3>
                        <a href="{{ route('transactions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold">Lihat Semua →</a>
                    </div>
                    <div class="space-y-2">
                        @forelse($recentTransactions as $transaction)
                        <div class="flex items-center justify-between p-3 border dark:border-gray-700 rounded-lg hover:shadow-md transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white" style="background-color: {{ $transaction->category->color ?? '#6366f1' }}">
                                    {{ $transaction->category->icon ?? '📝' }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100 text-sm">{{ $transaction->description }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->date->format('d M Y') }} - {{ $transaction->wallet->name }}</p>
                                </div>
                            </div>
                            <p class="font-bold text-sm {{ $transaction->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </p>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-400">
                            <p class="text-sm">Belum ada transaksi</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- M-KIOS Transactions -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">M-KIOS Terbaru</h3>
                        <a href="{{ route('m-kios.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold">Lihat Semua →</a>
                    </div>
                    <div class="space-y-2">
                        @forelse($recentMKiosTransactions as $transaction)
                        <div class="flex items-center justify-between p-3 border dark:border-gray-700 rounded-lg hover:shadow-md transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white bg-gradient-to-r from-indigo-500 to-purple-600 text-xs font-bold">
                                    {{ substr($transaction->transaction_type, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100 text-sm">{{ ucfirst($transaction->transaction_type) }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->transaction_date->format('d M Y') }} - {{ $transaction->wallet->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-sm text-green-600">+Rp {{ number_format($transaction->profit, 0, ',', '.') }}</p>
                                <span class="text-xs px-2 py-0.5 rounded {{ $transaction->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-400">
                            <p class="text-sm">Belum ada transaksi M-KIOS</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // ========== Chart.js Configuration ==========
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? '#e5e7eb' : '#374151';
        const gridColor = isDarkMode ? '#374151' : '#e5e7eb';
        
        Chart.defaults.color = textColor;
        Chart.defaults.borderColor = gridColor;

        // Income vs Expense Chart
        const incomeExpenseCtx = document.getElementById('incomeExpenseChart');
        if (incomeExpenseCtx) {
            new Chart(incomeExpenseCtx, {
                type: 'bar',
                data: {
                    labels: ['Pemasukan', 'Pengeluaran', 'Profit Bersih'],
                    datasets: [{
                        label: 'Jumlah (Rp)',
                        data: [{{ $income + $mkiosProfit }}, {{ $expense }}, {{ $netIncome }}],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(99, 102, 241, 0.8)'
                        ],
                        borderColor: [
                            'rgb(34, 197, 94)',
                            'rgb(239, 68, 68)',
                            'rgb(99, 102, 241)'
                        ],
                        borderWidth: 2,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        // Category Breakdown Chart
        const categoryCtx = document.getElementById('categoryChart');
        @if($expenseByCategory->count() > 0)
        if (categoryCtx) {
            const categoryData = @json($expenseByCategory);
            const categoryLabels = Object.keys(categoryData);
            const categoryValues = Object.values(categoryData);
            
            const colors = [
                'rgba(239, 68, 68, 0.8)',
                'rgba(249, 115, 22, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(34, 197, 94, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(99, 102, 241, 0.8)',
                'rgba(168, 85, 247, 0.8)',
                'rgba(236, 72, 153, 0.8)'
            ];

            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        data: categoryValues,
                        backgroundColor: colors.slice(0, categoryLabels.length),
                        borderWidth: 2,
                        borderColor: isDarkMode ? '#1f2937' : '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return label + ': Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value) + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
        @endif

        // Cash Flow Chart
        const cashFlowCtx = document.getElementById('cashFlowChart');
        if (cashFlowCtx) {
            const cashFlowData = @json($dailyCashFlow);
            const dates = cashFlowData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            });
            const incomeData = cashFlowData.map(item => item.income);
            const expenseData = cashFlowData.map(item => item.expense);
            const netData = cashFlowData.map(item => item.net);

            new Chart(cashFlowCtx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: incomeData,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        },
                        {
                            label: 'Pengeluaran',
                            data: expenseData,
                            borderColor: 'rgb(239, 68, 68)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        },
                        {
                            label: 'Profit Bersih',
                            data: netData,
                            borderColor: 'rgb(99, 102, 241)',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value);
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</x-app-layout>
