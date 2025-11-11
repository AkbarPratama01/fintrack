<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transfer Terjadwal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Error Messages -->
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Terjadwal</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $scheduledTransfers->count() }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Aktif</h4>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $scheduledTransfers->where('status', 'active')->count() }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Dijeda</h4>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $scheduledTransfers->where('status', 'paused')->count() }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Eksekusi</h4>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $scheduledTransfers->sum('execution_count') }}</p>
                    </div>
                </div>
            </div>

            <!-- Scheduled Transfers List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Transfer Terjadwal</h3>
                        <button onclick="toggleModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 border border-transparent rounded-lg font-semibold text-sm text-white shadow-md hover:from-indigo-600 hover:to-purple-700 hover:shadow-lg transform hover:scale-105 transition-all duration-200 ease-in-out">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Transfer Terjadwal
                        </button>
                    </div>

                    @if($scheduledTransfers->count() > 0)
                    <div class="overflow-x-auto rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-700">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Dari → Ke</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Frekuensi</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Tanggal Mulai</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Eksekusi Berikutnya</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Eksekusi</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($scheduledTransfers as $transfer)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-200">
                                                    {{ $transfer->fromWallet->name }}
                                                </div>
                                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 my-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                                    </svg>
                                                </div>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-200">
                                                    {{ $transfer->toWallet->name }}
                                                </div>
                                                @if($transfer->description)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">
                                                    {{ Str::limit($transfer->description, 30) }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                            @if($transfer->fromWallet->currency === 'IDR')
                                                Rp {{ number_format($transfer->amount, 0, ',', '.') }}
                                            @else
                                                {{ $transfer->fromWallet->currency }} {{ number_format($transfer->amount, 2, '.', ',') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg shadow-sm
                                            @if($transfer->frequency === 'daily') bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800
                                            @elseif($transfer->frequency === 'weekly') bg-gradient-to-r from-green-100 to-green-200 text-green-800
                                            @elseif($transfer->frequency === 'monthly') bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800
                                            @else bg-gradient-to-r from-orange-100 to-orange-200 text-orange-800
                                            @endif">
                                            {{ ucfirst($transfer->frequency) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700 dark:text-gray-300">
                                        {{ $transfer->start_date->format('d/m/Y') }}
                                        @if($transfer->end_date)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            s/d {{ $transfer->end_date->format('d/m/Y') }}
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-gray-900 dark:text-gray-200">
                                        {{ $transfer->next_execution_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700 dark:text-gray-300">
                                        {{ $transfer->execution_count }}x
                                        @if($transfer->last_executed_at)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $transfer->last_executed_at->diffForHumans() }}
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($transfer->status === 'active')
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gradient-to-r from-green-100 to-green-200 text-green-800 shadow-sm">Aktif</span>
                                        @elseif($transfer->status === 'paused')
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 shadow-sm">Dijeda</span>
                                        @elseif($transfer->status === 'completed')
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 shadow-sm">Selesai</span>
                                        @else
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gradient-to-r from-red-100 to-red-200 text-red-800 shadow-sm">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <div class="flex items-center justify-center space-x-2">
                                            @if($transfer->status === 'active' || $transfer->status === 'paused')
                                            <!-- Execute Button -->
                                            <form action="{{ route('scheduled-transfers.execute', $transfer) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center p-2 text-green-600 hover:text-white bg-green-50 hover:bg-green-600 rounded-lg transition-all duration-200 hover:shadow-md" title="Eksekusi Sekarang">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </button>
                                            </form>

                                            <!-- Toggle Status Button -->
                                            <form action="{{ route('scheduled-transfers.toggle-status', $transfer) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center p-2 text-yellow-600 hover:text-white bg-yellow-50 hover:bg-yellow-600 rounded-lg transition-all duration-200 hover:shadow-md" title="{{ $transfer->status === 'active' ? 'Jeda' : 'Aktifkan' }}">
                                                    @if($transfer->status === 'active')
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    @else
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    @endif
                                                </button>
                                            </form>

                                            <!-- Edit Button -->
                                            <button onclick="editTransfer({{ $transfer->id }}, '{{ $transfer->from_wallet_id }}', '{{ $transfer->to_wallet_id }}', '{{ $transfer->amount }}', '{{ $transfer->frequency }}', '{{ $transfer->start_date->format('Y-m-d') }}', '{{ $transfer->end_date ? $transfer->end_date->format('Y-m-d') : '' }}', '{{ $transfer->description }}', '{{ $transfer->status }}')" class="inline-flex items-center justify-center p-2 text-blue-600 hover:text-white bg-blue-50 hover:bg-blue-600 rounded-lg transition-all duration-200 hover:shadow-md" title="Edit">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            @endif

                                            <!-- Delete Button -->
                                            <form action="{{ route('scheduled-transfers.destroy', $transfer) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transfer terjadwal ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center p-2 text-red-600 hover:text-white bg-red-50 hover:bg-red-600 rounded-lg transition-all duration-200 hover:shadow-md" title="Hapus">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-16 px-4">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900 dark:to-purple-900 rounded-full mb-4">
                            <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum ada transfer terjadwal</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-6">Mulai dengan menambahkan transfer terjadwal otomatis antar wallet Anda.</p>
                        <button onclick="toggleModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 border border-transparent rounded-lg font-semibold text-sm text-white shadow-md hover:from-indigo-600 hover:to-purple-700 hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Transfer Terjadwal
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="transferModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modalTitle">Tambah Transfer Terjadwal</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="transferForm" method="POST" action="{{ route('scheduled-transfers.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Wallet <span class="text-red-500">*</span></label>
                        <select name="from_wallet_id" id="from_wallet_id" required 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih Wallet Asal</option>
                            @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->name }} ({{ $wallet->currency }} {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ke Wallet <span class="text-red-500">*</span></label>
                        <select name="to_wallet_id" id="to_wallet_id" required 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih Wallet Tujuan</option>
                            @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->name }} ({{ $wallet->currency }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" id="amount" required min="0.01" step="0.01"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Frekuensi <span class="text-red-500">*</span></label>
                        <select name="frequency" id="frequency" required 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="daily">Harian</option>
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                            <option value="yearly">Tahunan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="start_date" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Berakhir (Opsional)</label>
                        <input type="date" name="end_date" id="end_date"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div id="statusField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" id="status" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="active">Aktif</option>
                            <option value="paused">Dijeda</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea name="description" id="description" rows="3" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Catatan transfer (opsional)"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg hover:from-indigo-600 hover:to-purple-700 shadow-md hover:shadow-lg transition-all duration-200">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal() {
            document.getElementById('transferModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Tambah Transfer Terjadwal';
            document.getElementById('transferForm').action = '{{ route('scheduled-transfers.store') }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('from_wallet_id').value = '';
            document.getElementById('to_wallet_id').value = '';
            document.getElementById('amount').value = '';
            document.getElementById('frequency').value = 'monthly';
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';
            document.getElementById('description').value = '';
            document.getElementById('statusField').classList.add('hidden');
        }

        function editTransfer(id, fromWalletId, toWalletId, amount, frequency, startDate, endDate, description, status) {
            document.getElementById('transferModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Edit Transfer Terjadwal';
            document.getElementById('transferForm').action = '/scheduled-transfers/' + id;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('from_wallet_id').value = fromWalletId;
            document.getElementById('to_wallet_id').value = toWalletId;
            document.getElementById('amount').value = amount;
            document.getElementById('frequency').value = frequency;
            document.getElementById('start_date').value = startDate;
            document.getElementById('end_date').value = endDate || '';
            document.getElementById('description').value = description || '';
            document.getElementById('status').value = status;
            document.getElementById('statusField').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('transferModal').classList.add('hidden');
        }

        // Set minimum date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('start_date').setAttribute('min', today);
        });
    </script>
</x-app-layout>
