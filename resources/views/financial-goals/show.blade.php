<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $financialGoal->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('financial-goals.edit', $financialGoal) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('financial-goals.index') }}" class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 font-bold py-2 px-4 rounded-lg transition duration-150">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Goal Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Goal Overview Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                        <div class="p-6" style="border-left: 4px solid {{ $financialGoal->color }}">
                            <div class="flex items-start gap-4 mb-6">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-3xl" style="background-color: {{ $financialGoal->color }}">
                                    {{ $financialGoal->icon ?? '🎯' }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $financialGoal->name }}</h3>
                                    @if($financialGoal->category)
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                            {{ ucfirst(str_replace('_', ' ', $financialGoal->category)) }}
                                        </span>
                                    @endif
                                    <span class="ml-2 px-3 py-1 text-xs font-semibold rounded-full 
                                        {{ $financialGoal->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                        {{ $financialGoal->status === 'active' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                        {{ $financialGoal->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                        {{ ucfirst($financialGoal->status) }}
                                    </span>
                                </div>
                            </div>

                            @if($financialGoal->description)
                                <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $financialGoal->description }}</p>
                            @endif

                            <!-- Progress Bar -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progress</span>
                                    <span class="text-lg font-bold" style="color: {{ $financialGoal->color }}">
                                        {{ number_format($financialGoal->progress_percentage, 1) }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                                    <div class="h-4 rounded-full transition-all duration-500" 
                                         style="width: {{ min($financialGoal->progress_percentage, 100) }}%; background-color: {{ $financialGoal->color }}"
                                    ></div>
                                </div>
                            </div>

                            <!-- Amount Stats -->
                            <div class="grid grid-cols-3 gap-4 mb-6">
                                <div class="text-center p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Current</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $financialGoal->formatted_current_amount }}</p>
                                </div>
                                <div class="text-center p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Target</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $financialGoal->formatted_target_amount }}</p>
                                </div>
                                <div class="text-center p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Remaining</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $financialGoal->formatted_remaining_amount }}</p>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Target Date</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $financialGoal->target_date->format('d M Y') }}</p>
                                    <p class="text-xs {{ $financialGoal->is_overdue ? 'text-red-600' : 'text-gray-500 dark:text-gray-400' }}">
                                        {{ $financialGoal->days_remaining > 0 ? $financialGoal->days_remaining . ' days remaining' : 'Overdue' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Monthly Required</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Rp {{ number_format($financialGoal->monthly_required, 0, ',', '.') }}</p>
                                    @if($financialGoal->monthly_target)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Target: Rp {{ number_format($financialGoal->monthly_target, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            </div>

                            @if($financialGoal->notes)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Notes</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $financialGoal->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contributions History -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contribution History</h3>
                            
                            @if($financialGoal->contributions->count() > 0)
                                <div class="space-y-3">
                                    @foreach($financialGoal->contributions as $contribution)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $contribution->amount >= 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                                    @if($contribution->amount >= 0)
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                        {{ $contribution->amount >= 0 ? 'Contribution' : 'Withdrawal' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $contribution->contribution_date->format('d M Y') }}
                                                        @if($contribution->source)
                                                            · {{ ucfirst($contribution->source) }}
                                                        @endif
                                                    </p>
                                                    @if($contribution->notes)
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $contribution->notes }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold {{ $contribution->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $contribution->amount >= 0 ? '+' : '' }}{{ $contribution->formatted_amount }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No contributions yet. Start by adding your first contribution!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Actions -->
                <div class="space-y-6">
                    <!-- Add Contribution Form -->
                    @if($financialGoal->status === 'active')
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Add Contribution</h3>
                                <form method="POST" action="{{ route('financial-goals.contribute', $financialGoal) }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Amount (Rp) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="amount" id="amount" 
                                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" 
                                               placeholder="100000" min="0.01" step="0.01" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="contribution_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Date <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" name="contribution_date" id="contribution_date" value="{{ date('Y-m-d') }}"
                                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" 
                                               required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="source" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Source
                                        </label>
                                        <input type="text" name="source" id="source" 
                                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" 
                                               placeholder="e.g., Salary, Bonus">
                                    </div>
                                    <div class="mb-4">
                                        <label for="contrib_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Notes
                                        </label>
                                        <textarea name="notes" id="contrib_notes" rows="2" 
                                                  class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition">
                                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add Contribution
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Withdraw Form -->
                        @if($financialGoal->current_amount > 0)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Withdraw</h3>
                                    <form method="POST" action="{{ route('financial-goals.withdraw', $financialGoal) }}" 
                                          onsubmit="return confirm('Are you sure you want to withdraw from this goal?');">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="withdraw_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Amount (Rp) <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" name="amount" id="withdraw_amount" 
                                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" 
                                                   placeholder="50000" min="0.01" max="{{ $financialGoal->current_amount }}" step="0.01" required>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max: {{ $financialGoal->formatted_current_amount }}</p>
                                        </div>
                                        <div class="mb-4">
                                            <label for="withdraw_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Date <span class="text-red-500">*</span>
                                            </label>
                                            <input type="date" name="contribution_date" id="withdraw_date" value="{{ date('Y-m-d') }}"
                                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" 
                                                   required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="withdraw_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Reason
                                            </label>
                                            <textarea name="notes" id="withdraw_notes" rows="2" 
                                                      class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" 
                                                      placeholder="Reason for withdrawal..."></textarea>
                                        </div>
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition">
                                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                            Withdraw
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Delete Goal -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Danger Zone</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Once you delete a goal, there is no going back. Please be certain.</p>
                            <form method="POST" action="{{ route('financial-goals.destroy', $financialGoal) }}" 
                                  onsubmit="return confirm('Are you sure you want to delete this financial goal? This action cannot be undone!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition">
                                    Delete Goal
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
