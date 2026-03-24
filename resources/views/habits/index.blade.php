<x-app-layout>

<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">Habit Tracker</h2>

        <button onclick="toggleModal()" 
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg">
            + Tambah Habit
        </button>
    </div>
</x-slot>

<div class="p-6 max-w-7xl mx-auto">

    <!-- NAV BULAN -->
    <div class="flex justify-between items-center mb-6">

        <a href="{{ route('habits.index', ['month' => $month->copy()->subMonth()->format('Y-m')]) }}"
           class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
            ⬅️
        </a>

        <h3 class="text-lg font-semibold">
            {{ $month->format('F Y') }}
        </h3>

        <a href="{{ route('habits.index', ['month' => $month->copy()->addMonth()->format('Y-m')]) }}"
           class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
            ➡️
        </a>

    </div>

    @if($habits->count() > 0)

    @php
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();
        $dates = [];

        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            $dates[] = $date->copy();
        }
    @endphp

    <div class="overflow-x-auto bg-white dark:bg-gray-800 p-4 rounded-2xl shadow">

        <table class="min-w-max text-center">

            <!-- HEADER -->
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700">

                    <th class="p-3 sticky left-0 bg-white dark:bg-gray-800 z-10 text-left">
                        Habit
                    </th>

                    @foreach($dates as $date)
                        <th class="p-2 text-xs 
                            {{ $date->isToday() && $month->isCurrentMonth() ? 'bg-indigo-200' : '' }}">
                            {{ $date->format('d') }}
                        </th>
                    @endforeach

                </tr>
            </thead>

            <!-- BODY -->
            <tbody>
                @foreach($habits as $habit)

                @php
                    $done = $habit->logs->where('status', 1)->count();
                    $total = count($dates);
                @endphp

                <tr class="border-t">

                    <!-- NAMA HABIT -->
                    <td class="p-3 sticky left-0 bg-white dark:bg-gray-800 text-left">

                        <div class="font-medium text-gray-800 dark:text-white">
                            {{ $habit->name }}
                        </div>

                        <div class="text-xs text-gray-400">
                            {{ $done }}/{{ $total }}
                        </div>

                    </td>

                    <!-- TANGGAL -->
                    @foreach($dates as $date)

                        @php
                            $dateStr = $date->format('Y-m-d');
                            $log = $habit->logs->where('date', $dateStr)->first();
                        @endphp

                        <td class="p-2 
                            {{ $date->isToday() && $month->isCurrentMonth() ? 'bg-indigo-100' : '' }}">

                            <form method="POST" action="{{ route('habit.check', $habit->id) }}">
                                @csrf
                                <input type="hidden" name="date" value="{{ $dateStr }}">

                                <input type="checkbox"
                                    onchange="this.form.submit()"
                                    class="w-4 h-4 text-indigo-600 rounded"
                                    {{ $log && $log->status ? 'checked' : '' }}>
                            </form>

                        </td>

                    @endforeach

                </tr>

                @endforeach
            </tbody>

        </table>

    </div>

    @else
        <p class="text-gray-500 text-center">Belum ada habit</p>
    @endif

</div>

<!-- MODAL -->
<div id="habitModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg w-full max-w-md mx-auto mt-20">

        <h3 class="mb-4 font-semibold">Tambah Habit</h3>

        <form method="POST" action="{{ route('habits.store') }}">
            @csrf

            <input type="text" name="name" 
                class="w-full mb-3 p-2 border rounded"
                placeholder="Nama Habit">

            <select name="category" class="w-full mb-3 p-2 border rounded">
                <option value="ibadah">Ibadah</option>
                <option value="kesehatan">Kesehatan</option>
                <option value="kerja">Kerja</option>
            </select>

            <button class="w-full bg-indigo-600 text-white py-2 rounded">
                Simpan
            </button>

        </form>

    </div>
</div>

<!-- JS -->
<script>
function toggleModal() {
    document.getElementById('habitModal').classList.toggle('hidden');
}

// Auto scroll ke hari ini
window.onload = function() {
    setTimeout(() => {
        document.querySelector('.bg-indigo-100')?.scrollIntoView({
            behavior: 'smooth',
            inline: 'center'
        });
    }, 300);
};
</script>

</x-app-layout>