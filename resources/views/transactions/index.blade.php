<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Income & Expense') }}
            </h2>
            <button onclick="toggleModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Add Transaction</span>
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Income -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900 px-2 py-1 rounded">All Time</span>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Income</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Total Expense -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900 px-2 py-1 rounded">All Time</span>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Expense</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Monthly Income -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900 px-2 py-1 rounded">This Month</span>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Monthly Income</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Monthly Expense -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900 px-2 py-1 rounded">This Month</span>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Monthly Expense</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Transactions</h3>
                        <div class="flex space-x-2">
                            <button onclick="filterTransactions('all')" class="filter-btn active px-3 py-1 rounded-lg text-sm font-medium transition duration-150">All</button>
                            <button onclick="filterTransactions('income')" class="filter-btn px-3 py-1 rounded-lg text-sm font-medium transition duration-150">Income</button>
                            <button onclick="filterTransactions('expense')" class="filter-btn px-3 py-1 rounded-lg text-sm font-medium transition duration-150">Expense</button>
                        </div>
                    </div>

                    @if($transactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Wallet</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($transactions as $transaction)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 transaction-row" data-type="{{ $transaction->type }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $transaction->date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type == 'income' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $transaction->category->icon }} {{ $transaction->category->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $transaction->wallet->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex items-center gap-2">
                                                    @if($transaction->receipt_image)
                                                        <button onclick="showImageModal('{{ $transaction->receipt_image_url }}')" class="flex-shrink-0 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800" title="View receipt">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                    <span>{{ $transaction->description ? Str::limit($transaction->description, 30) : '-' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold {{ $transaction->type == 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $transaction->type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button onclick="editTransaction({{ $transaction->id }}, '{{ $transaction->type }}', {{ $transaction->category_id }}, {{ $transaction->wallet_id }}, {{ $transaction->amount }}, '{{ $transaction->date->format('Y-m-d') }}', '{{ addslashes($transaction->description ?? '') }}')" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                                    Edit
                                                </button>
                                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No transactions yet</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Start by adding your first income or expense</p>
                            <button onclick="toggleModal()" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150">
                                Add Transaction
                            </button>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Image Viewer Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden overflow-y-auto h-full w-full z-50 flex items-center justify-center" onclick="closeImageModal()">
        <div class="relative max-w-4xl mx-auto p-4" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()" class="absolute -top-2 -right-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-full p-2 hover:bg-gray-100 dark:hover:bg-gray-700 z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalImage" src="" alt="Receipt" class="max-w-full max-h-screen rounded-lg shadow-2xl">
        </div>
    </div>

    <!-- Add/Edit Transaction Modal -->
    <div id="transactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-xl font-semibold text-gray-900 dark:text-white">Add Transaction</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="transactionForm" action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" id="formMethod" name="_method" value="">
                <input type="hidden" name="_modal" value="transaction">

                <!-- Type Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="income" class="peer sr-only" required {{ old('type') == 'income' ? 'checked' : '' }} onchange="updateCategoryOptions()">
                            <div class="px-4 py-3 text-center border-2 border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900 peer-checked:text-green-700 dark:peer-checked:text-green-200 transition duration-150">
                                <span class="font-medium">Income</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="expense" class="peer sr-only" required {{ old('type') == 'expense' ? 'checked' : '' }} onchange="updateCategoryOptions()">
                            <div class="px-4 py-3 text-center border-2 border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900 peer-checked:text-red-700 dark:peer-checked:text-red-200 transition duration-150">
                                <span class="font-medium">Expense</span>
                            </div>
                        </label>
                    </div>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount</label>
                    <input type="number" name="amount" id="amount" step="0.01" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white {{ $errors->has('amount') ? 'border-red-500' : '' }}" placeholder="0" value="{{ old('amount') }}" required>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                    <select name="category_id" id="category_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white {{ $errors->has('category_id') ? 'border-red-500' : '' }}" required>
                        <option value="">Select Category</option>
                        <optgroup label="Income" id="income-categories" style="display: none;">
                            @foreach($incomeCategories as $category)
                                <option value="{{ $category->id }}" data-type="income" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->icon }} {{ $category->name }}
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Expense" id="expense-categories" style="display: none;">
                            @foreach($expenseCategories as $category)
                                <option value="{{ $category->id }}" data-type="expense" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->icon }} {{ $category->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Wallet -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Wallet</label>
                    <select name="wallet_id" id="wallet_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white {{ $errors->has('wallet_id') ? 'border-red-500' : '' }}" required>
                        <option value="">Select Wallet</option>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}" {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}>
                                {{ $wallet->name }} - {{ $wallet->formatted_balance }}
                            </option>
                        @endforeach
                    </select>
                    @error('wallet_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date</label>
                    <input type="date" name="date" id="date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white {{ $errors->has('date') ? 'border-red-500' : '' }}" value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description (Optional)</label>
                    <textarea name="description" id="description" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" rows="3" placeholder="Add notes...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Receipt Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Receipt Image (Optional)</label>
                    <div class="flex items-center space-x-3">
                        <label class="flex-1 cursor-pointer">
                            <div class="flex items-center justify-center w-full px-4 py-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-indigo-500 dark:hover:border-indigo-400 transition duration-150">
                                <div class="text-center">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="mt-2 text-xs text-gray-500 dark:text-gray-400" id="file-name">Click to upload image</span>
                                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                            <input type="file" name="receipt_image" id="receipt_image" class="hidden" accept="image/*" onchange="displayFileName(this)">
                        </label>
                    </div>
                    <div id="image-preview" class="mt-3 hidden">
                        <img src="" alt="Preview" class="max-w-full h-32 rounded-lg object-cover border-2 border-gray-200 dark:border-gray-600">
                    </div>
                    @error('receipt_image')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition duration-150">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let editMode = false;
        let editId = null;

        function toggleModal() {
            document.getElementById('transactionModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Reset form
            if (!editMode) {
                document.getElementById('transactionForm').reset();
                document.getElementById('modalTitle').textContent = 'Add Transaction';
                document.getElementById('transactionForm').action = '{{ route("transactions.store") }}';
                document.getElementById('formMethod').value = '';
            }
            
            updateCategoryOptions();
        }

        function closeModal() {
            document.getElementById('transactionModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            editMode = false;
            editId = null;
        }

        function editTransaction(id, type, categoryId, walletId, amount, date, description) {
            editMode = true;
            editId = id;
            
            document.getElementById('modalTitle').textContent = 'Edit Transaction';
            document.getElementById('transactionForm').action = '/transactions/' + id;
            document.getElementById('formMethod').value = 'PUT';
            
            // Set form values
            document.querySelector(`input[name="type"][value="${type}"]`).checked = true;
            document.getElementById('amount').value = amount;
            document.getElementById('wallet_id').value = walletId;
            document.getElementById('date').value = date;
            document.getElementById('description').value = description;
            
            updateCategoryOptions();
            document.getElementById('category_id').value = categoryId;
            
            toggleModal();
        }

        function updateCategoryOptions() {
            const incomeRadio = document.querySelector('input[name="type"][value="income"]');
            const expenseRadio = document.querySelector('input[name="type"][value="expense"]');
            const incomeCategories = document.getElementById('income-categories');
            const expenseCategories = document.getElementById('expense-categories');
            const categorySelect = document.getElementById('category_id');
            
            // Reset select
            categorySelect.value = '';
            
            if (incomeRadio.checked) {
                incomeCategories.style.display = 'block';
                expenseCategories.style.display = 'none';
            } else if (expenseRadio.checked) {
                incomeCategories.style.display = 'none';
                expenseCategories.style.display = 'block';
            }
        }

        function filterTransactions(type) {
            const rows = document.querySelectorAll('.transaction-row');
            const buttons = document.querySelectorAll('.filter-btn');
            
            buttons.forEach(btn => {
                btn.classList.remove('active', 'bg-indigo-600', 'text-white');
                btn.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
            });
            
            event.target.classList.add('active', 'bg-indigo-600', 'text-white');
            event.target.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
            
            rows.forEach(row => {
                if (type === 'all' || row.dataset.type === type) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Initialize category options on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCategoryOptions();
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('transactionModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Auto-open modal if there are validation errors
        @if($errors->any() && old('_modal') == 'transaction')
            toggleModal();
            @if(old('type'))
                document.querySelector('input[name="type"][value="{{ old('type') }}"]').checked = true;
                updateCategoryOptions();
            @endif
        @endif

        // Initialize filter buttons style
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => {
                if (!btn.classList.contains('active')) {
                    btn.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
                } else {
                    btn.classList.add('bg-indigo-600', 'text-white');
                }
            });
        });

        // Preview image before upload
        function displayFileName(input) {
            const fileNameSpan = document.getElementById('file-name');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = imagePreview.querySelector('img');
            
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                fileNameSpan.textContent = fileName;
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                fileNameSpan.textContent = 'Click to upload image';
                imagePreview.classList.add('hidden');
            }
        }

        // Show receipt image in modal
        function showImageModal(imageUrl) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Close image modal
        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close image modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>

    <style>
        .filter-btn.active {
            background-color: rgb(79 70 229);
            color: white;
        }
    </style>
</x-app-layout>
