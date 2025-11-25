<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Categories') }}
            </h2>
            <button onclick="toggleModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Add Category</span>
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

            <!-- Info Alert -->
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded relative" role="alert">
                <p class="text-sm">
                    <strong>Note:</strong> System categories (without Edit/Delete buttons) are default categories available to all users. You can create your own custom categories.
                </p>
            </div>

            <!-- Income Categories -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Income Categories</h3>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $incomeCategories->count() }} categories</span>
                    </div>

                    @if($incomeCategories->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($incomeCategories as $category)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition duration-150">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="text-2xl">{{ $category->icon ?? '💰' }}</div>
                                            <div>
                                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $category->name }}</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $categoryStats[$category->id]->transactions_count ?? 0 }} transactions
                                                </p>
                                            </div>
                                        </div>
                                        @if($category->user_id)
                                            <div class="flex space-x-2">
                                                <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->type }}', '{{ $category->icon }}', '{{ $category->color }}')" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500">System</span>
                                        @endif
                                    </div>
                                    @if($category->color)
                                        <div class="mt-2 flex items-center space-x-2">
                                            <span class="w-4 h-4 rounded-full" style="background-color: {{ $category->color }}"></span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $category->color }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No income categories yet</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Expense Categories -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Expense Categories</h3>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $expenseCategories->count() }} categories</span>
                    </div>

                    @if($expenseCategories->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($expenseCategories as $category)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition duration-150">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="text-2xl">{{ $category->icon ?? '💸' }}</div>
                                            <div>
                                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $category->name }}</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $categoryStats[$category->id]->transactions_count ?? 0 }} transactions
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button onclick="setBudget({{ $category->id }}, '{{ $category->name }}')" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Set Budget">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                            @if($category->user_id)
                                                <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->type }}', '{{ $category->icon }}', '{{ $category->color }}')" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400 dark:text-gray-500">System</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($category->color)
                                        <div class="mt-2 flex items-center space-x-2">
                                            <span class="w-4 h-4 rounded-full" style="background-color: {{ $category->color }}"></span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $category->color }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No expense categories yet</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Set Budget Modal -->
    <div id="budgetModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 id="budgetModalTitle" class="text-xl font-semibold text-gray-900 dark:text-white">Set Budget</h3>
                <button onclick="closeBudgetModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="budgetForm" method="POST" class="space-y-4">
                @csrf

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Budget Amount</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium text-sm pointer-events-none">Rp</span>
                        <input type="number" name="amount" step="0.01" min="0.01" class="w-full pl-11 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" placeholder="0" required>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter the maximum amount for this category</p>
                </div>

                <!-- Period -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Period</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer period-option" data-period="monthly">
                            <input type="radio" name="period" value="monthly" class="hidden" checked required onchange="updatePeriodSelection(this)">
                            <div class="period-card border-2 rounded-lg p-4 text-center transition-all duration-200">
                                <svg class="period-icon w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="period-text text-sm font-medium">Monthly</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer period-option" data-period="yearly">
                            <input type="radio" name="period" value="yearly" class="hidden" required onchange="updatePeriodSelection(this)">
                            <div class="period-card border-2 rounded-lg p-4 text-center transition-all duration-200">
                                <svg class="period-icon w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="period-text text-sm font-medium">Yearly</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeBudgetModal()" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150">Set Budget</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add/Edit Category Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-xl font-semibold text-gray-900 dark:text-white">Add Category</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="categoryForm" action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="formMethod" name="_method" value="">
                <input type="hidden" name="_modal" value="category">

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category Name</label>
                    <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white {{ $errors->has('name') ? 'border-red-500' : '' }}" placeholder="e.g., Salary, Food, Transport" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="income" class="peer sr-only" required {{ old('type') == 'income' ? 'checked' : '' }}>
                            <div class="px-4 py-3 text-center border-2 border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900 peer-checked:text-green-700 dark:peer-checked:text-green-200 transition duration-150">
                                <span class="font-medium">Income</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="expense" class="peer sr-only" required {{ old('type') == 'expense' ? 'checked' : '' }}>
                            <div class="px-4 py-3 text-center border-2 border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900 peer-checked:text-red-700 dark:peer-checked:text-red-200 transition duration-150">
                                <span class="font-medium">Expense</span>
                            </div>
                        </label>
                    </div>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Icon (Emoji)</label>
                    <input type="text" name="icon" id="icon" maxlength="10" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="💰 🍔 🚗 ✈️" value="{{ old('icon') }}">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Use emoji to represent this category (optional)</p>
                    @error('icon')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Color</label>
                    <div class="flex items-center space-x-3">
                        <input type="color" name="color" id="color" class="h-10 w-20 border border-gray-300 dark:border-gray-600 rounded cursor-pointer" value="{{ old('color', '#3B82F6') }}">
                        <input type="text" id="colorHex" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" value="{{ old('color', '#3B82F6') }}" readonly>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Used for charts and visualizations (optional)</p>
                    @error('color')
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
            document.getElementById('categoryModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            if (!editMode) {
                document.getElementById('categoryForm').reset();
                document.getElementById('modalTitle').textContent = 'Add Category';
                document.getElementById('categoryForm').action = '{{ route("categories.store") }}';
                document.getElementById('formMethod').value = '';
                document.getElementById('colorHex').value = '#3B82F6';
                document.getElementById('color').value = '#3B82F6';
            }
        }

        function closeModal() {
            document.getElementById('categoryModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            editMode = false;
            editId = null;
        }

        function editCategory(id, name, type, icon, color) {
            editMode = true;
            editId = id;
            
            document.getElementById('modalTitle').textContent = 'Edit Category';
            document.getElementById('categoryForm').action = '/categories/' + id;
            document.getElementById('formMethod').value = 'PUT';
            
            document.getElementById('name').value = name;
            document.querySelector(`input[name="type"][value="${type}"]`).checked = true;
            document.getElementById('icon').value = icon;
            document.getElementById('color').value = color || '#3B82F6';
            document.getElementById('colorHex').value = color || '#3B82F6';
            
            toggleModal();
        }

        // Sync color picker with text input
        document.addEventListener('DOMContentLoaded', function() {
            const colorPicker = document.getElementById('color');
            const colorHex = document.getElementById('colorHex');
            
            colorPicker.addEventListener('input', function() {
                colorHex.value = this.value;
            });
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('categoryModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
                closeBudgetModal();
            }
        });

        // Update period selection styling
        function updatePeriodSelection(radio) {
            // Remove active class from all options
            document.querySelectorAll('.period-option').forEach(option => {
                const card = option.querySelector('.period-card');
                const icon = option.querySelector('.period-icon');
                const text = option.querySelector('.period-text');
                
                card.classList.remove('border-blue-600', 'bg-blue-100', 'dark:bg-blue-900', 'shadow-md');
                card.classList.add('border-gray-300', 'dark:border-gray-600');
                icon.classList.remove('text-blue-600', 'dark:text-blue-400');
                icon.classList.add('text-gray-400');
                text.classList.remove('text-blue-700', 'dark:text-blue-300', 'font-bold');
                text.classList.add('text-gray-700', 'dark:text-gray-300');
            });
            
            // Add active class to selected option
            const selectedOption = radio.closest('.period-option');
            const card = selectedOption.querySelector('.period-card');
            const icon = selectedOption.querySelector('.period-icon');
            const text = selectedOption.querySelector('.period-text');
            
            card.classList.add('border-blue-600', 'bg-blue-100', 'dark:bg-blue-900', 'shadow-md');
            card.classList.remove('border-gray-300', 'dark:border-gray-600');
            icon.classList.add('text-blue-600', 'dark:text-blue-400');
            icon.classList.remove('text-gray-400');
            text.classList.add('text-blue-700', 'dark:text-blue-300', 'font-bold');
            text.classList.remove('text-gray-700', 'dark:text-gray-300');
        }

        // Initialize period selection on page load
        document.addEventListener('DOMContentLoaded', function() {
            const checkedRadio = document.querySelector('input[name="period"]:checked');
            if (checkedRadio) {
                updatePeriodSelection(checkedRadio);
            }
        });

        // Budget modal functions
        function setBudget(categoryId, categoryName) {
            document.getElementById('budgetModalTitle').textContent = 'Set Budget for ' + categoryName;
            document.getElementById('budgetForm').action = '/categories/' + categoryId + '/budget';
            document.getElementById('budgetModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Reset and initialize period selection
            setTimeout(() => {
                const monthlyRadio = document.querySelector('input[name="period"][value="monthly"]');
                if (monthlyRadio) {
                    monthlyRadio.checked = true;
                    updatePeriodSelection(monthlyRadio);
                }
            }, 50);
        }

        function closeBudgetModal() {
            document.getElementById('budgetModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('budgetForm').reset();
        }

        // Close budget modal when clicking outside
        window.onclick = function(event) {
            const categoryModal = document.getElementById('categoryModal');
            const budgetModal = document.getElementById('budgetModal');
            if (event.target == categoryModal) {
                closeModal();
            }
            if (event.target == budgetModal) {
                closeBudgetModal();
            }
        }

        // Auto-open modal if there are validation errors
        @if($errors->any() && old('_modal') == 'category')
            toggleModal();
            @if(old('type'))
                document.querySelector('input[name="type"][value="{{ old('type') }}"]').checked = true;
            @endif
        @endif
    </script>
</x-app-layout>
