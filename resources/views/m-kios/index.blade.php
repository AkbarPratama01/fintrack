<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('M-KIOS - Pulsa, E-Wallet & Token Listrik') }}
        </h2>
    </x-slot>

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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Transaksi</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalTransactions, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Profit</p>
                                <p class="text-2xl font-semibold text-green-600">Rp {{ number_format($totalProfit, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Modal</p>
                                <p class="text-2xl font-semibold text-red-600">Rp {{ number_format($totalBalanceDeducted, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Penjualan</p>
                                <p class="text-2xl font-semibold text-purple-600">Rp {{ number_format($totalCashReceived, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Transaction Button & Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Riwayat Transaksi</h3>
                        <button onclick="toggleModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-150">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Transaksi
                        </button>
                    </div>

                    @if($transactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wallet</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Modal</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Uang Diterima</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->transaction_date->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($transaction->transaction_type === 'pulsa')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Pulsa</span>
                                        @elseif($transaction->transaction_type === 'dana')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">DANA</span>
                                        @elseif($transaction->transaction_type === 'gopay')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">GoPay</span>
                                        @elseif($transaction->transaction_type === 'token_listrik')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Token Listrik</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($transaction->transaction_type === 'token_listrik')
                                        <div class="text-sm font-medium text-gray-900">{{ $transaction->customer_id ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">ID Pelanggan</div>
                                        @else
                                        <div class="text-sm font-medium text-gray-900">{{ $transaction->phone_number ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $transaction->provider ?? 'Nomor HP' }}</div>
                                        @endif
                                        @if($transaction->product_code)
                                        <div class="text-xs text-blue-600 mt-1">{{ $transaction->product_code }}</div>
                                        @endif
                                        @if($transaction->notes)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($transaction->notes, 30) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $transaction->wallet ? $transaction->wallet->name : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">
                                        Rp {{ number_format($transaction->balance_deducted, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">
                                        Rp {{ number_format($transaction->cash_received, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-blue-600">
                                        Rp {{ number_format($transaction->profit, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($transaction->status === 'completed')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                        @elseif($transaction->status === 'pending')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Gagal</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <form action="{{ route('m-kios.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
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
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada transaksi</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan transaksi pulsa, e-wallet, atau token listrik pertama Anda.</p>
                        <div class="mt-6">
                            <button onclick="toggleModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah Transaksi
                            </button>
                        </div>
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
    </script>
</x-app-layout>
