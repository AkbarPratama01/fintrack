<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Habit Tracker
            </h2>

            <button onclick="toggleModal()" 
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center space-x-2">
                <span>+ Tambah Habit</span>
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alert -->
            @if(session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Habit List -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                <div class="p-6">

                    <div class="flex justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Habit Hari Ini
                        </h3>
                        <span class="text-sm text-gray-500">
                            {{ $habits->count() }} habits
                        </span>
                    </div>

                    @if($habits->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                            @foreach($habits as $habit)
                                <div class="border rounded-lg p-4 hover:shadow-md">

                                    <div class="flex justify-between items-center">

                                        <!-- Info -->
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">
                                                {{ $habit->name }}
                                            </h4>
                                            <p class="text-xs text-gray-500">
                                                {{ ucfirst($habit->category) }}
                                            </p>
                                        </div>

                                        <!-- Checkbox -->
                                        <form action="{{ route('habit.check', $habit->id) }}" method="POST">
                                            @csrf
                                            <input type="checkbox"
                                                onchange="this.form.submit()"
                                                {{ optional($habit->todayLog)->status ? 'checked' : '' }}>
                                        </form>
                                    </div>

                                    <!-- Action -->
                                    <div class="flex justify-end space-x-2 mt-3">
                                        <button onclick="editHabit({{ $habit->id }}, '{{ $habit->name }}', '{{ $habit->category }}')" 
                                            class="text-indigo-600">Edit</button>

                                        <form action="{{ route('habits.destroy', $habit->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600">Hapus</button>
                                        </form>
                                    </div>

                                </div>
                            @endforeach

                        </div>
                    @else
                        <p class="text-gray-500 text-center">Belum ada habit</p>
                    @endif

                </div>
            </div>

        </div>
    </div>

    <!-- Modal Add/Edit -->
    <div id="habitModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md mx-auto mt-20">

            <h3 id="modalTitle" class="text-lg font-semibold mb-4">Tambah Habit</h3>

            <form id="habitForm" method="POST" action="{{ route('habits.store') }}">
                @csrf
                <input type="hidden" id="formMethod" name="_method">

                <div class="mb-3">
                    <label>Nama Habit</label>
                    <input type="text" name="name" id="name" class="w-full border rounded p-2">
                </div>

                <div class="mb-3">
                    <label>Kategori</label>
                    <select name="category" id="category" class="w-full border rounded p-2">
                        <option value="ibadah">Ibadah</option>
                        <option value="kesehatan">Kesehatan</option>
                        <option value="kerja">Kerja</option>
                    </select>
                </div>

                <div class="flex space-x-2">
                    <button type="button" onclick="closeModal()" class="w-full bg-gray-400 text-white p-2 rounded">
                        Batal
                    </button>
                    <button class="w-full bg-indigo-600 text-white p-2 rounded">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function toggleModal() {
            document.getElementById('habitModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('habitModal').classList.add('hidden');
        }

        function editHabit(id, name, category) {
            document.getElementById('modalTitle').innerText = 'Edit Habit';
            document.getElementById('habitForm').action = '/habits/' + id;
            document.getElementById('formMethod').value = 'PUT';

            document.getElementById('name').value = name;
            document.getElementById('category').value = category;

            toggleModal();
        }
    </script>

</x-app-layout>