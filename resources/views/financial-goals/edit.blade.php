<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Financial Goal') }}
            </h2>
            <a href="{{ route('financial-goals.show', $financialGoal) }}" class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 font-bold py-2 px-4 rounded-lg transition duration-150">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('financial-goals.update', $financialGoal) }}">
                        @csrf
                        @method('PUT')

                        <!-- Goal Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Goal Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $financialGoal->name) }}" 
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" 
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="3" 
                                      class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $financialGoal->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Amount (Read-only, showing current progress) -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Current Progress
                            </label>
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Current Amount</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $financialGoal->formatted_current_amount }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-2">
                                    <div class="h-3 rounded-full transition-all" 
                                         style="width: {{ min($financialGoal->progress_percentage, 100) }}%; background-color: {{ $financialGoal->color }}"
                                    ></div>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($financialGoal->progress_percentage, 1) }}% Complete</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Target: {{ $financialGoal->formatted_target_amount }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Target Amount -->
                        <div class="mb-6">
                            <label for="target_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Target Amount (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="target_amount" id="target_amount" value="{{ old('target_amount', $financialGoal->target_amount) }}" 
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" 
                                   min="0" step="0.01" required>
                            @error('target_amount')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Date & Category -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="target_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Target Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="target_date" id="target_date" value="{{ old('target_date', $financialGoal->target_date->format('Y-m-d')) }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" 
                                       required>
                                @error('target_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Category
                                </label>
                                <select name="category" id="category" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Select Category --</option>
                                    <option value="savings" {{ old('category', $financialGoal->category) == 'savings' ? 'selected' : '' }}>💰 Savings</option>
                                    <option value="investment" {{ old('category', $financialGoal->category) == 'investment' ? 'selected' : '' }}>📈 Investment</option>
                                    <option value="debt_payment" {{ old('category', $financialGoal->category) == 'debt_payment' ? 'selected' : '' }}>💳 Debt Payment</option>
                                    <option value="purchase" {{ old('category', $financialGoal->category) == 'purchase' ? 'selected' : '' }}>🛍️ Purchase</option>
                                    <option value="emergency_fund" {{ old('category', $financialGoal->category) == 'emergency_fund' ? 'selected' : '' }}>🚨 Emergency Fund</option>
                                    <option value="other" {{ old('category', $financialGoal->category) == 'other' ? 'selected' : '' }}>📋 Other</option>
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Icon & Color -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Icon (Emoji)
                                </label>
                                <input type="text" name="icon" id="icon" value="{{ old('icon', $financialGoal->icon) }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" 
                                       maxlength="10">
                                @error('icon')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Color
                                </label>
                                <div class="flex gap-2">
                                    <input type="color" name="color" id="color" value="{{ old('color', $financialGoal->color) }}" 
                                           class="h-10 w-20 rounded-md border-gray-300 dark:border-gray-700">
                                    <input type="text" id="color_display" value="{{ old('color', $financialGoal->color) }}" 
                                           class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" 
                                           readonly>
                                </div>
                                @error('color')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Monthly Target & Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="monthly_target" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Monthly Savings Target
                                </label>
                                <input type="number" name="monthly_target" id="monthly_target" value="{{ old('monthly_target', $financialGoal->monthly_target) }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" 
                                       min="0" step="0.01">
                                @error('monthly_target')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="active" {{ old('status', $financialGoal->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="completed" {{ old('status', $financialGoal->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $financialGoal->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notes
                            </label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $financialGoal->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Auto Save Checkbox -->
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="auto_save" value="1" {{ old('auto_save', $financialGoal->auto_save) ? 'checked' : '' }}
                                       class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    Enable auto-save
                                </span>
                            </label>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150">
                                Update Financial Goal
                            </button>
                            <a href="{{ route('financial-goals.show', $financialGoal) }}" class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 font-bold py-3 px-6 rounded-lg transition duration-150">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('color').addEventListener('input', function(e) {
            document.getElementById('color_display').value = e.target.value;
        });
    </script>
</x-app-layout>
