<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Wallet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

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

            <!-- Stats Overview -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Wallet</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $wallets->count() }}</p>
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
                                <p class="text-sm font-medium text-gray-600">Total Saldo</p>
                                <p class="text-2xl font-semibold text-green-600">Rp {{ number_format($wallets->sum('balance'), 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Wallet Aktif</p>
                                <p class="text-2xl font-semibold text-purple-600">{{ $wallets->where('balance', '>', 0)->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wallet List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Wallet</h3>
                        <button onclick="toggleModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-150">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Wallet
                        </button>
                    </div>

                    @if($wallets->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($wallets as $wallet)
                        <div class="border rounded-lg p-6 hover:shadow-lg transition-shadow">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $wallet->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $wallet->type ?? 'Cash' }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="editWallet({{ $wallet->id }}, '{{ $wallet->name }}', '{{ $wallet->type }}', '{{ $wallet->balance }}', '{{ $wallet->description }}')" 
                                        class="text-blue-600 hover:text-blue-900">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <form action="{{ route('wallets.destroy', $wallet) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus wallet ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-1">Saldo</p>
                                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
                            </div>

                            @if($wallet->description)
                            <p class="text-sm text-gray-600 mb-4">{{ $wallet->description }}</p>
                            @endif

                            <div class="flex space-x-2">
                                <button onclick="showAddBalanceModal({{ $wallet->id }}, '{{ $wallet->name }}')" 
                                    class="flex-1 px-3 py-2 bg-green-100 text-green-700 rounded-md hover:bg-green-200 text-sm font-medium">
                                    + Tambah Saldo
                                </button>
                                <button onclick="showSubtractBalanceModal({{ $wallet->id }}, '{{ $wallet->name }}')" 
                                    class="flex-1 px-3 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 text-sm font-medium">
                                    - Kurangi Saldo
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada wallet</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan wallet pertama Anda.</p>
                        <div class="mt-6">
                            <button onclick="toggleModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah Wallet
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Wallet Modal -->
    <div id="walletModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah Wallet</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="walletForm" method="POST" action="{{ route('wallets.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Wallet <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="wallet_name" required 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Contoh: Cash, BCA, Mandiri">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Wallet</label>
                        <select name="type" id="wallet_type" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="Cash">Cash</option>
                            <option value="Bank">Bank</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Other">Lainnya</option>
                        </select>
                    </div>

                    <div id="balanceField">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Saldo Awal</label>
                        <input type="number" name="balance" id="wallet_balance" min="0" step="0.01" value="0"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" id="wallet_description" rows="3" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Deskripsi wallet (opsional)"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Balance Modal -->
    <div id="addBalanceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Saldo</h3>
                <button onclick="closeAddBalanceModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="addBalanceForm" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="space-y-4">
                    <p class="text-sm text-gray-600">Wallet: <span id="addBalanceWalletName" class="font-semibold"></span></p>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" required min="0" step="0.01"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="0">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeAddBalanceModal()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Tambah Saldo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Subtract Balance Modal -->
    <div id="subtractBalanceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Kurangi Saldo</h3>
                <button onclick="closeSubtractBalanceModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="subtractBalanceForm" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="space-y-4">
                    <p class="text-sm text-gray-600">Wallet: <span id="subtractBalanceWalletName" class="font-semibold"></span></p>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" required min="0" step="0.01"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="0">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeSubtractBalanceModal()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Kurangi Saldo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal() {
            document.getElementById('walletModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Tambah Wallet';
            document.getElementById('walletForm').action = '{{ route('wallets.store') }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('wallet_name').value = '';
            document.getElementById('wallet_type').value = 'Cash';
            document.getElementById('wallet_balance').value = '0';
            document.getElementById('wallet_description').value = '';
            document.getElementById('balanceField').classList.remove('hidden');
        }

        function editWallet(id, name, type, balance, description) {
            document.getElementById('walletModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Edit Wallet';
            document.getElementById('walletForm').action = '/wallets/' + id;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('wallet_name').value = name;
            document.getElementById('wallet_type').value = type || 'Cash';
            document.getElementById('wallet_description').value = description || '';
            document.getElementById('balanceField').classList.add('hidden');
        }

        function closeModal() {
            document.getElementById('walletModal').classList.add('hidden');
        }

        function showAddBalanceModal(id, name) {
            document.getElementById('addBalanceModal').classList.remove('hidden');
            document.getElementById('addBalanceWalletName').textContent = name;
            document.getElementById('addBalanceForm').action = '/wallets/' + id + '/add-balance';
        }

        function closeAddBalanceModal() {
            document.getElementById('addBalanceModal').classList.add('hidden');
        }

        function showSubtractBalanceModal(id, name) {
            document.getElementById('subtractBalanceModal').classList.remove('hidden');
            document.getElementById('subtractBalanceWalletName').textContent = name;
            document.getElementById('subtractBalanceForm').action = '/wallets/' + id + '/subtract-balance';
        }

        function closeSubtractBalanceModal() {
            document.getElementById('subtractBalanceModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
