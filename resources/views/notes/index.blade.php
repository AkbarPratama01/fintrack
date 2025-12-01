<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Notes') }}
            </h2>
            <a href="{{ route('notes.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New Note</span>
            </a>
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

            <!-- Notes Grid -->
            @if($notes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($notes as $note)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition duration-300 overflow-hidden border-l-4 {{ $note->priority === 'high' ? 'border-red-500' : ($note->priority === 'medium' ? 'border-yellow-500' : 'border-green-500') }}">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex-1 line-clamp-2">
                                        {{ $note->title }}
                                        @if($note->is_pinned)
                                            <svg class="w-4 h-4 inline text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 2a.75.75 0 01.75.75v7.5h7.5a.75.75 0 010 1.5h-7.5v7.5a.75.75 0 01-1.5 0v-7.5h-7.5a.75.75 0 010-1.5h7.5v-7.5A.75.75 0 0110 2z"/>
                                            </svg>
                                        @endif
                                    </h3>
                                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $note->priority === 'high' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : ($note->priority === 'medium' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200') }}">
                                        {{ ucfirst($note->priority) }}
                                    </span>
                                </div>

                                @if($note->content)
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">{{ $note->content }}</p>
                                @endif

                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-4">
                                    <span>{{ $note->created_at->diffForHumans() }}</span>
                                </div>

                                <div class="flex items-center justify-between space-x-2">
                                    <form action="{{ route('notes.toggle-pin', $note) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 {{ $note->is_pinned ? 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-400' }} rounded transition" title="{{ $note->is_pinned ? 'Unpin note' : 'Pin note' }}">
                                            @if($note->is_pinned)
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M16 12V4H17V2H7V4H8V12L6 14V16H11.2V22H12.8V16H18V14L16 12Z" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12V4H17V2H7V4H8V12L6 14V16H11.2V22H12.8V16H18V14L16 12Z" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('notes.edit', $note) }}" class="px-3 py-1 text-sm bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-200 rounded transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('notes.destroy', $note) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this note?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 text-sm bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900 dark:text-red-200 rounded transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $notes->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No notes yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Create your first note to get started!</p>
                    <a href="{{ route('notes.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Note
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
