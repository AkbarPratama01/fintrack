<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('M-KIOS - Pulsa, E-Wallet & Token Listrik') }}
        </h2>
    </x-slot>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success Message -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Error!</strong>
                <ul class="mt-2 ml-4 list-disc">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Transaksi -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Transaksi</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTransactions }}</p>
                    </div>
                </div>

                <!-- Total Profit -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Profit</h4>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($totalProfit, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Total Modal -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Modal</h4>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400">Rp {{ number_format($totalBalanceDeducted, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Total Penjualan -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Penjualan</h4>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">Rp {{ number_format($totalCashReceived, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Transaction by Type Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Transaksi Berdasarkan Jenis</h3>
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="relative" style="height: 250px;">
                            @if($transactionsByType->count() > 0)
                            <canvas id="transactionTypeChart"></canvas>
                            @else
                            <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <p class="text-sm">Belum ada data transaksi</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Transaction by Status Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Status Transaksi</h3>
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="relative" style="height: 250px;">
                            @if($transactionsByStatus->count() > 0)
                            <canvas id="statusChart"></canvas>
                            @else
                            <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <p class="text-sm">Belum ada data transaksi</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Daily Transactions Chart (Full Width) -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Transaksi & Profit 7 Hari Terakhir</h3>
                            <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="relative" style="height: 300px;">
                            <canvas id="dailyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Transaction Button & Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Riwayat Transaksi</h3>
                        <div class="flex gap-2">
                            <button onclick="toggleFilterPanel()" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Filter
                            </button>
                            <button onclick="toggleModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 border border-transparent rounded-lg font-semibold text-sm text-white shadow-md hover:from-indigo-600 hover:to-purple-700 hover:shadow-lg transform hover:scale-105 transition-all duration-200 ease-in-out">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah Transaksi
                            </button>
                        </div>
                    </div>

                    <!-- Filter Panel -->
                    <div id="filterPanel" class="hidden mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <form method="GET" action="{{ route('m-kios.index') }}" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Search -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari</label>
                                    <input type="text" name="search" value="{{ request('search') }}" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="No HP / ID Pelanggan / Kode Produk">
                                </div>

                                <!-- Transaction Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Transaksi</label>
                                    <select name="transaction_type" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Semua</option>
                                        <option value="pulsa" {{ request('transaction_type') == 'pulsa' ? 'selected' : '' }}>Pulsa</option>
                                        <option value="dana" {{ request('transaction_type') == 'dana' ? 'selected' : '' }}>DANA</option>
                                        <option value="gopay" {{ request('transaction_type') == 'gopay' ? 'selected' : '' }}>GoPay</option>
                                        <option value="token_listrik" {{ request('transaction_type') == 'token_listrik' ? 'selected' : '' }}>Token Listrik</option>
                                    </select>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                    <select name="status" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Semua</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                                    </select>
                                </div>

                                <!-- Wallet -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Wallet</label>
                                    <select name="wallet_id" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Semua Wallet</option>
                                        @foreach($wallets as $wallet)
                                            <option value="{{ $wallet->id }}" {{ request('wallet_id') == $wallet->id ? 'selected' : '' }}>
                                                {{ $wallet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Start Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
                                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <!-- End Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
                                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div class="flex justify-end gap-2">
                                <a href="{{ route('m-kios.index') }}" 
                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                                    Reset
                                </a>
                                <button type="submit" 
                                    class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all">
                                    Terapkan Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Active Filters Badges -->
                    @if(request()->hasAny(['search', 'transaction_type', 'status', 'wallet_id', 'start_date', 'end_date']))
                    <div class="mb-4 flex flex-wrap gap-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Filter aktif:</span>
                        
                        @if(request('search'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                            Pencarian: "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-1 hover:text-indigo-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif
                        
                        @if(request('transaction_type'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            Jenis: {{ ucfirst(str_replace('_', ' ', request('transaction_type'))) }}
                            <a href="{{ request()->fullUrlWithQuery(['transaction_type' => null]) }}" class="ml-1 hover:text-blue-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif
                        
                        @if(request('status'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Status: {{ ucfirst(request('status')) }}
                            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="ml-1 hover:text-green-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif
                        
                        @if(request('wallet_id'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                            Wallet: {{ $wallets->find(request('wallet_id'))->name ?? 'Unknown' }}
                            <a href="{{ request()->fullUrlWithQuery(['wallet_id' => null]) }}" class="ml-1 hover:text-purple-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif
                        
                        @if(request('start_date'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            Dari: {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                            <a href="{{ request()->fullUrlWithQuery(['start_date' => null]) }}" class="ml-1 hover:text-yellow-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif
                        
                        @if(request('end_date'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            Sampai: {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                            <a href="{{ request()->fullUrlWithQuery(['end_date' => null]) }}" class="ml-1 hover:text-yellow-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif
                        
                        <a href="{{ route('m-kios.index') }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 hover:bg-red-200">
                            Reset Semua Filter
                        </a>
                    </div>
                    @endif

                    @if($transactions->count() > 0)
                    <!-- Results Count -->
                    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                        Menampilkan <span class="font-semibold">{{ $transactions->firstItem() }}</span> - <span class="font-semibold">{{ $transactions->lastItem() }}</span> dari <span class="font-semibold">{{ $transactions->total() }}</span> transaksi
                    </div>

                    <div class="overflow-x-auto rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-700">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Detail</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Wallet</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Modal</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Uang Diterima</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Profit</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $transaction->transaction_date->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($transaction->transaction_type === 'pulsa')
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 shadow-sm">Pulsa</span>
                                        @elseif($transaction->transaction_type === 'dana')
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gradient-to-r from-cyan-100 to-cyan-200 text-cyan-800 shadow-sm">DANA</span>
                                        @elseif($transaction->transaction_type === 'gopay')
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gradient-to-r from-green-100 to-green-200 text-green-800 shadow-sm">GoPay</span>
                                        @elseif($transaction->transaction_type === 'token_listrik')
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 shadow-sm">Token Listrik</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($transaction->transaction_type === 'token_listrik')
                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-200">{{ $transaction->customer_id ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">ID Pelanggan</div>
                                        @else
                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-200">{{ $transaction->phone_number ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->provider ?? 'Nomor HP' }}</div>
                                        @endif
                                        @if($transaction->product_code)
                                        <div class="text-xs text-indigo-600 dark:text-indigo-400 font-medium mt-1">{{ $transaction->product_code }}</div>
                                        @endif
                                        @if($transaction->notes)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">{{ Str::limit($transaction->notes, 30) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 font-medium">
                                        {{ $transaction->wallet ? $transaction->wallet->name : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-red-600 dark:text-red-400">
                                        Rp {{ number_format($transaction->balance_deducted, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-green-600 dark:text-green-400">
                                        Rp {{ number_format($transaction->cash_received, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-indigo-600 dark:text-indigo-400">
                                        Rp {{ number_format($transaction->profit, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($transaction->status === 'completed')
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gradient-to-r from-green-100 to-green-200 text-green-800 shadow-sm">Selesai</span>
                                        @elseif($transaction->status === 'pending')
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 shadow-sm">Pending</span>
                                        @else
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gradient-to-r from-red-100 to-red-200 text-red-800 shadow-sm">Gagal</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <form action="{{ route('m-kios.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center p-2 text-red-600 hover:text-white bg-red-50 hover:bg-red-600 rounded-lg transition-all duration-200 hover:shadow-md">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                    @else
                    <div class="text-center py-16 px-4">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900 dark:to-purple-900 rounded-full mb-4">
                            <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum ada transaksi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-6">Mulai dengan menambahkan transaksi pulsa, e-wallet, atau token listrik pertama Anda.</p>
                        <button onclick="toggleModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 border border-transparent rounded-lg font-semibold text-sm text-white shadow-md hover:from-indigo-600 hover:to-purple-700 hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Transaksi
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Transaction Modal -->
    <div id="transactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Transaksi</h3>
                <button onclick="toggleModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('m-kios.store') }}" method="POST" id="mkiosForm">
                @csrf
                <div class="space-y-4">
                    <!-- Jenis Transaksi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi <span class="text-red-500">*</span></label>
                        <select name="transaction_type" id="transaction_type" required onchange="toggleTransactionFields()" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('transaction_type') border-red-500 @enderror">
                            <option value="">Pilih Jenis Transaksi</option>
                            <option value="pulsa" {{ old('transaction_type') == 'pulsa' ? 'selected' : '' }}>Pulsa</option>
                            <option value="dana" {{ old('transaction_type') == 'dana' ? 'selected' : '' }}>DANA</option>
                            <option value="gopay" {{ old('transaction_type') == 'gopay' ? 'selected' : '' }}>GoPay</option>
                            <option value="token_listrik" {{ old('transaction_type') == 'token_listrik' ? 'selected' : '' }}>Token Listrik</option>
                        </select>
                        @error('transaction_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Field untuk Pulsa, DANA, GoPay -->
                    <div id="phone_number_field" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP <span class="text-red-500">*</span></label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('phone_number') border-red-500 @enderror"
                            placeholder="08xxxxxxxxxx">
                        @error('phone_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Field untuk Token Listrik -->
                    <div id="customer_id_field" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Pelanggan / Nomor Meter <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_id" id="customer_id" value="{{ old('customer_id') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('customer_id') border-red-500 @enderror"
                            placeholder="Nomor meter pelanggan">
                        @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Provider untuk Pulsa -->
                        <div id="provider_field" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Provider</label>
                            <select name="provider" id="provider" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Provider</option>
                                <option value="Telkomsel" {{ old('provider') == 'Telkomsel' ? 'selected' : '' }}>Telkomsel</option>
                                <option value="Indosat" {{ old('provider') == 'Indosat' ? 'selected' : '' }}>Indosat</option>
                                <option value="XL" {{ old('provider') == 'XL' ? 'selected' : '' }}>XL</option>
                                <option value="Tri" {{ old('provider') == 'Tri' ? 'selected' : '' }}>Tri</option>
                                <option value="Smartfren" {{ old('provider') == 'Smartfren' ? 'selected' : '' }}>Smartfren</option>
                                <option value="Axis" {{ old('provider') == 'Axis' ? 'selected' : '' }}>Axis</option>
                            </select>
                        </div>

                        <!-- Nominal/Kode Produk -->
                        <div id="product_code_field" class="hidden col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nominal Produk</label>
                            <input type="text" name="product_code" id="product_code" value="{{ old('product_code') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Contoh: 10000, 20000, 50000">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Modal (Saldo Terpotong) <span class="text-red-500">*</span></label>
                            <input type="number" name="balance_deducted" required min="0" step="0.01" value="{{ old('balance_deducted') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('balance_deducted') border-red-500 @enderror"
                                placeholder="0">
                            @error('balance_deducted')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Uang Diterima <span class="text-red-500">*</span></label>
                            <input type="number" name="cash_received" required min="0" step="0.01" value="{{ old('cash_received') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('cash_received') border-red-500 @enderror"
                                placeholder="0">
                            @error('cash_received')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Wallet <span class="text-red-500">*</span></label>
                        <select name="wallet_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('wallet_id') border-red-500 @enderror">
                            <option value="">Pilih Wallet</option>
                            @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}" {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}>{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                        @error('wallet_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea name="notes" rows="3" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transaksi</label>
                        <input type="datetime-local" name="transaction_date"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('transaction_date', now()->format('Y-m-d\TH:i')) }}">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="toggleModal()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Show modal if there are errors or old input
        @if($errors->any() || old('transaction_type'))
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('transactionModal').classList.remove('hidden');
            // Trigger toggle to show correct fields
            const type = document.getElementById('transaction_type').value;
            if (type) {
                toggleTransactionFields();
            }
        });
        @endif

        // Check if there are active filters and show panel
        @if(request()->hasAny(['search', 'transaction_type', 'status', 'wallet_id', 'start_date', 'end_date']))
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterPanel').classList.remove('hidden');
        });
        @endif

        function toggleFilterPanel() {
            const panel = document.getElementById('filterPanel');
            panel.classList.toggle('hidden');
        }

        function toggleModal() {
            const modal = document.getElementById('transactionModal');
            modal.classList.toggle('hidden');
            
            // Reset form when closing
            if (modal.classList.contains('hidden')) {
                document.getElementById('mkiosForm').reset();
                hideAllFields();
            }
        }

        function toggleTransactionFields() {
            const type = document.getElementById('transaction_type').value;
            
            // Hide all fields first
            hideAllFields();
            
            // Show relevant fields based on transaction type
            if (type === 'pulsa') {
                document.getElementById('phone_number_field').classList.remove('hidden');
                document.getElementById('phone_number').required = true;
                document.getElementById('provider_field').classList.remove('hidden');
                document.getElementById('product_code_field').classList.remove('hidden');
            } else if (type === 'dana' || type === 'gopay') {
                document.getElementById('phone_number_field').classList.remove('hidden');
                document.getElementById('phone_number').required = true;
                document.getElementById('product_code_field').classList.remove('hidden');
            } else if (type === 'token_listrik') {
                document.getElementById('customer_id_field').classList.remove('hidden');
                document.getElementById('customer_id').required = true;
                document.getElementById('product_code_field').classList.remove('hidden');
            }
        }

        function hideAllFields() {
            // Hide all conditional fields
            document.getElementById('phone_number_field').classList.add('hidden');
            document.getElementById('customer_id_field').classList.add('hidden');
            document.getElementById('provider_field').classList.add('hidden');
            document.getElementById('product_code_field').classList.add('hidden');
            
            // Remove required attributes
            document.getElementById('phone_number').required = false;
            document.getElementById('customer_id').required = false;
        }

        // Chart.js Configuration
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? 'rgb(229, 231, 235)' : 'rgb(55, 65, 81)';
        const gridColor = isDarkMode ? 'rgba(75, 85, 99, 0.3)' : 'rgba(229, 231, 235, 0.5)';
        
        const chartColors = {
            pulsa: 'rgb(59, 130, 246)',      // blue
            dana: 'rgb(6, 182, 212)',        // cyan
            gopay: 'rgb(34, 197, 94)',       // green
            token_listrik: 'rgb(234, 179, 8)', // yellow
            completed: 'rgb(34, 197, 94)',   // green
            pending: 'rgb(234, 179, 8)',     // yellow
            failed: 'rgb(239, 68, 68)',      // red
        };

        // Default chart options for dark mode
        Chart.defaults.color = textColor;
        Chart.defaults.borderColor = gridColor;

        // Transaction by Type Chart (Doughnut)
        const typeCtx = document.getElementById('transactionTypeChart');
        if (typeCtx) {
            const typeData = @json($transactionsByType);
            
            new Chart(typeCtx, {
                type: 'doughnut',
                data: {
                    labels: typeData.map(item => {
                        const labels = {
                            'pulsa': 'Pulsa',
                            'dana': 'DANA',
                            'gopay': 'GoPay',
                            'token_listrik': 'Token Listrik'
                        };
                        return labels[item.transaction_type] || item.transaction_type;
                    }),
                    datasets: [{
                        label: 'Jumlah Transaksi',
                        data: typeData.map(item => item.count),
                        backgroundColor: typeData.map(item => chartColors[item.transaction_type]),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
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
                                afterLabel: function(context) {
                                    const profit = typeData[context.dataIndex].total_profit;
                                    return 'Total Profit: Rp ' + new Intl.NumberFormat('id-ID').format(profit);
                                }
                            }
                        }
                    }
                }
            });
        }

        // Transaction by Status Chart (Pie)
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            const statusData = @json($transactionsByStatus);
            
            new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: statusData.map(item => {
                        const labels = {
                            'completed': 'Selesai',
                            'pending': 'Pending',
                            'failed': 'Gagal'
                        };
                        return labels[item.status] || item.status;
                    }),
                    datasets: [{
                        label: 'Jumlah',
                        data: statusData.map(item => item.count),
                        backgroundColor: statusData.map(item => chartColors[item.status]),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }

        // Daily Transactions Chart (Line + Bar)
        const dailyCtx = document.getElementById('dailyChart');
        if (dailyCtx) {
            const dailyData = @json($dates);
            
            // Check if there's any data
            const hasData = dailyData.some(item => item.count > 0 || item.profit > 0);
            
            if (!hasData) {
                // Hide canvas and show empty state message
                dailyCtx.style.display = 'none';
                const emptyState = dailyCtx.parentElement.querySelector('.empty-state');
                if (emptyState) {
                    emptyState.style.display = 'flex';
                }
            } else {
                new Chart(dailyCtx, {
                type: 'bar',
                data: {
                    labels: dailyData.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' });
                    }),
                    datasets: [
                        {
                            label: 'Jumlah Transaksi',
                            data: dailyData.map(item => item.count),
                            backgroundColor: 'rgba(99, 102, 241, 0.7)',
                            borderColor: 'rgb(99, 102, 241)',
                            borderWidth: 2,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Profit (Rp)',
                            data: dailyData.map(item => item.profit),
                            type: 'line',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            borderColor: 'rgb(34, 197, 94)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
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
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.datasetIndex === 1) {
                                        label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    } else {
                                        label += context.parsed.y + ' transaksi';
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Jumlah Transaksi'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Profit (Rp)'
                            },
                            grid: {
                                drawOnChartArea: false,
                            },
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
        }
    </script>
</x-app-layout>
