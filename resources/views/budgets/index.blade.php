<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Budget Tracking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Budget Overview -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Budget Overview</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Track your spending against budgets</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <form action="{{ route('budgets.reset') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to reset all budgets to the current period? This will update all budget periods.');">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition duration-150 flex items-center space-x-2" title="Reset all budgets to current period">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span>Reset Budgets</span>
                                </button>
                            </form>
                            <button onclick="toggleBudgetModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Set New Budget</span>
                            </button>
                        </div>
                    </div>

                    @if($budgetData->count() > 0)
                        <div class="space-y-4">
                            @foreach($budgetData as $budget)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="text-2xl">{{ $budget['category']->icon ?? '💸' }}</div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $budget['category']->name }}</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ $budget['period'] }} budget</p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button onclick="editBudget({{ $budget['id'] }}, {{ $budget['category']->id }}, '{{ $budget['category']->name }}', '{{ $budget['category']->icon }}', {{ $budget['amount'] }}, '{{ $budget['period'] }}')" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="Edit Budget">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <form action="{{ route('budgets.destroy', $budget['id']) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this budget?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete Budget">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mb-3">
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600 dark:text-gray-400">Spent: Rp {{ number_format($budget['spending'], 0, ',', '.') }}</span>
                                            <span class="text-gray-600 dark:text-gray-400">Budget: Rp {{ number_format($budget['amount'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                                            <div class="h-full transition-all duration-300 {{ $budget['status'] === 'exceeded' ? 'bg-red-600' : ($budget['status'] === 'warning' ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                                 style="width: {{ min($budget['percentage'], 100) }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Status Info -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            @if($budget['status'] === 'exceeded')
                                                <span class="px-3 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 text-xs font-semibold rounded-full">
                                                    Over Budget
                                                </span>
                                                <span class="text-sm text-red-600 dark:text-red-400">
                                                    Rp {{ number_format($budget['spending'] - $budget['amount'], 0, ',', '.') }} over
                                                </span>
                                            @elseif($budget['status'] === 'warning')
                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 text-xs font-semibold rounded-full">
                                                    Warning
                                                </span>
                                                <span class="text-sm text-yellow-600 dark:text-yellow-400">
                                                    Rp {{ number_format($budget['remaining'], 0, ',', '.') }} remaining
                                                </span>
                                            @else
                                                <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 text-xs font-semibold rounded-full">
                                                    On Track
                                                </span>
                                                <span class="text-sm text-green-600 dark:text-green-400">
                                                    Rp {{ number_format($budget['remaining'], 0, ',', '.') }} remaining
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-lg font-bold {{ $budget['is_exceeded'] ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                                            {{ number_format($budget['percentage'], 1) }}%
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Budgets Set</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">Start tracking your spending by setting budgets for your expense categories</p>
                            <button onclick="toggleBudgetModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Set Your First Budget
                            </button>
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
                <h3 id="budgetModalTitle" class="text-xl font-semibold text-gray-900 dark:text-white">Set New Budget</h3>
                <button onclick="closeBudgetModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="budgetForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="formMethod" name="_method" value="">

                <!-- Category Selection -->
                <div id="categorySelectDiv">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                    <select name="category" id="category" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required onchange="updateBudgetFormAction()">
                        <option value="">Select Category</option>
                        @foreach($expenseCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->icon }} {{ $category->name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Select an expense category to set budget</p>
                </div>

                <!-- Category Display (for edit mode) -->
                <div id="categoryDisplayDiv" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                    <div class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                        <span id="categoryDisplay" class="text-gray-900 dark:text-white"></span>
                    </div>
                </div>

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

    <script>
        let editMode = false;
        let editBudgetId = null;

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

        function toggleBudgetModal() {
            editMode = false;
            editBudgetId = null;
            document.getElementById('budgetModalTitle').textContent = 'Set New Budget';
            document.getElementById('formMethod').value = '';
            document.getElementById('categorySelectDiv').classList.remove('hidden');
            document.getElementById('categoryDisplayDiv').classList.add('hidden');
            document.getElementById('budgetForm').reset();
            document.getElementById('budgetModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Reset period selection to monthly
            setTimeout(() => {
                const monthlyRadio = document.querySelector('input[name="period"][value="monthly"]');
                if (monthlyRadio) {
                    monthlyRadio.checked = true;
                    updatePeriodSelection(monthlyRadio);
                }
            }, 50);
        }

        function editBudget(budgetId, categoryId, categoryName, categoryIcon, amount, period) {
            editMode = true;
            editBudgetId = budgetId;
            
            document.getElementById('budgetModalTitle').textContent = 'Edit Budget';
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('budgetForm').action = '/budgets/' + budgetId;
            
            // Hide category select, show category display
            document.getElementById('categorySelectDiv').classList.add('hidden');
            document.getElementById('categoryDisplayDiv').classList.remove('hidden');
            document.getElementById('categoryDisplay').textContent = categoryIcon + ' ' + categoryName;
            
            // Fill form
            document.querySelector('input[name="amount"]').value = amount;
            
            // Set period and update styling
            const periodRadio = document.querySelector(`input[name="period"][value="${period}"]`);
            if (periodRadio) {
                periodRadio.checked = true;
                updatePeriodSelection(periodRadio);
            }
            
            document.getElementById('budgetModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeBudgetModal() {
            document.getElementById('budgetModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('budgetForm').reset();
            editMode = false;
            editBudgetId = null;
        }

        function updateBudgetFormAction() {
            if (!editMode) {
                const categoryId = document.getElementById('category').value;
                if (categoryId) {
                    document.getElementById('budgetForm').action = '/categories/' + categoryId + '/budget';
                }
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('budgetModal');
            if (event.target == modal) {
                closeBudgetModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeBudgetModal();
            }
        });
    </script>
</x-app-layout>
