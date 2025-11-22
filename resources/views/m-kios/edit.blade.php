<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Transaksi M-KIOS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <!-- Back Button -->
                    <div class="mb-6">
                        <a href="{{ route('m-kios.index') }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Daftar Transaksi
                        </a>
                    </div>

                    <!-- Error Messages -->
                    @if($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Error!</strong>
                        <ul class="mt-2 ml-4 list-disc">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Edit Transaksi M-KIOS</h3>

                    <form action="{{ route('m-kios.update', $mkiosTransaction) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-4">
                            <!-- Jenis Transaksi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Transaksi <span class="text-red-500">*</span></label>
                                <select name="transaction_type" id="transaction_type" required onchange="toggleTransactionFields()" 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('transaction_type') border-red-500 @enderror">
                                    <option value="">Pilih Jenis Transaksi</option>
                                    <option value="pulsa" {{ old('transaction_type', $mkiosTransaction->transaction_type) == 'pulsa' ? 'selected' : '' }}>Pulsa</option>
                                    <option value="dana" {{ old('transaction_type', $mkiosTransaction->transaction_type) == 'dana' ? 'selected' : '' }}>DANA</option>
                                    <option value="gopay" {{ old('transaction_type', $mkiosTransaction->transaction_type) == 'gopay' ? 'selected' : '' }}>GoPay</option>
                                    <option value="token_listrik" {{ old('transaction_type', $mkiosTransaction->transaction_type) == 'token_listrik' ? 'selected' : '' }}>Token Listrik</option>
                                </select>
                                @error('transaction_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Field untuk Pulsa, DANA, GoPay -->
                            <div id="phone_number_field" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor HP <span class="text-red-500">*</span></label>
                                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $mkiosTransaction->phone_number) }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('phone_number') border-red-500 @enderror"
                                    placeholder="08xxxxxxxxxx">
                                @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Field untuk Token Listrik -->
                            <div id="pln_customer_id_field" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor ID PLN / Meter <span class="text-red-500">*</span></label>
                                <input type="text" name="pln_customer_id" id="pln_customer_id" value="{{ old('pln_customer_id', $mkiosTransaction->pln_customer_id) }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('pln_customer_id') border-red-500 @enderror"
                                    placeholder="Contoh: 123456789012">
                                @error('pln_customer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Customer Selection (Optional for all types) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Customer 
                                    <span class="text-gray-500 text-xs">(Optional)</span>
                                    <a href="{{ route('customers.create') }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-xs ml-2">
                                        + Add New
                                    </a>
                                </label>
                                <select name="customer_id" id="customer_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('customer_id') border-red-500 @enderror">
                                    <option value="">-- Select Customer (Optional) --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id', $mkiosTransaction->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                            @if($customer->phone) - {{ $customer->phone }}@endif
                                            @if($customer->company_name) ({{ $customer->company_name }})@endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Link this transaction to a customer for better tracking</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Provider untuk Pulsa -->
                                <div id="provider_field" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Provider</label>
                                    <select name="provider" id="provider" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Pilih Provider</option>
                                        <option value="Telkomsel" {{ old('provider', $mkiosTransaction->provider) == 'Telkomsel' ? 'selected' : '' }}>Telkomsel</option>
                                        <option value="Indosat" {{ old('provider', $mkiosTransaction->provider) == 'Indosat' ? 'selected' : '' }}>Indosat</option>
                                        <option value="XL" {{ old('provider', $mkiosTransaction->provider) == 'XL' ? 'selected' : '' }}>XL</option>
                                        <option value="Tri" {{ old('provider', $mkiosTransaction->provider) == 'Tri' ? 'selected' : '' }}>Tri</option>
                                        <option value="Smartfren" {{ old('provider', $mkiosTransaction->provider) == 'Smartfren' ? 'selected' : '' }}>Smartfren</option>
                                        <option value="Axis" {{ old('provider', $mkiosTransaction->provider) == 'Axis' ? 'selected' : '' }}>Axis</option>
                                    </select>
                                </div>

                                <!-- Nominal/Kode Produk -->
                                <div id="product_code_field" class="hidden col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nominal Produk</label>
                                    <input type="text" name="product_code" id="product_code" value="{{ old('product_code', $mkiosTransaction->product_code) }}"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Contoh: 10000, 20000, 50000">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Modal (Saldo Terpotong) <span class="text-red-500">*</span></label>
                                    <input type="number" name="balance_deducted" required min="0" step="0.01" value="{{ old('balance_deducted', $mkiosTransaction->balance_deducted) }}"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('balance_deducted') border-red-500 @enderror"
                                        placeholder="0">
                                    @error('balance_deducted')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Uang Diterima <span class="text-red-500">*</span></label>
                                    <input type="number" name="cash_received" required min="0" step="0.01" value="{{ old('cash_received', $mkiosTransaction->cash_received) }}"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('cash_received') border-red-500 @enderror"
                                        placeholder="0">
                                    @error('cash_received')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Wallet <span class="text-red-500">*</span></label>
                                <select name="wallet_id" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('wallet_id') border-red-500 @enderror">
                                    <option value="">Pilih Wallet</option>
                                    @foreach($wallets as $wallet)
                                    <option value="{{ $wallet->id }}" {{ old('wallet_id', $mkiosTransaction->wallet_id) == $wallet->id ? 'selected' : '' }}>
                                        {{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('wallet_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
                                <select name="status" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                                    <option value="completed" {{ old('status', $mkiosTransaction->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="pending" {{ old('status', $mkiosTransaction->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="failed" {{ old('status', $mkiosTransaction->status) == 'failed' ? 'selected' : '' }}>Gagal</option>
                                </select>
                                @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                                <textarea name="notes" rows="3" 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Catatan tambahan (opsional)">{{ old('notes', $mkiosTransaction->notes) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Transaksi</label>
                                <input type="datetime-local" name="transaction_date"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    value="{{ old('transaction_date', $mkiosTransaction->transaction_date->format('Y-m-d\TH:i')) }}">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('m-kios.index') }}" 
                                class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500">
                                Batal
                            </a>
                            <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Update Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize fields based on transaction type
        document.addEventListener('DOMContentLoaded', function() {
            toggleTransactionFields();
        });

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
                document.getElementById('pln_customer_id_field').classList.remove('hidden');
                document.getElementById('pln_customer_id').required = true;
                document.getElementById('product_code_field').classList.remove('hidden');
            }
        }

        function hideAllFields() {
            // Hide all conditional fields
            document.getElementById('phone_number_field').classList.add('hidden');
            document.getElementById('pln_customer_id_field').classList.add('hidden');
            document.getElementById('provider_field').classList.add('hidden');
            document.getElementById('product_code_field').classList.add('hidden');
            
            // Remove required attributes
            document.getElementById('phone_number').required = false;
            document.getElementById('pln_customer_id').required = false;
        }
    </script>
</x-app-layout>
