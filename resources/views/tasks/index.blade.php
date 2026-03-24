<x-app-layout>
<x-slot name="header">
    <h2 class="text-xl font-semibold">To-Do List</h2>
</x-slot>

<div class="p-6">

    <form method="POST" action="{{ route('tasks.store') }}" 
      class="mb-6 flex items-center gap-3 bg-white dark:bg-gray-800 p-4 rounded-2xl shadow">

        @csrf

        <input type="text" 
              name="title"
              placeholder="Tambah task hari ini..."
              class="flex-1 px-4 py-3 rounded-xl border border-gray-300 
                      focus:ring-2 focus:ring-indigo-500 focus:outline-none 
                      dark:bg-gray-700 dark:text-white">

        <select name="priority" 
                class="px-3 py-3 rounded-xl border border-gray-300 dark:bg-gray-700 dark:text-white">
            <option value="low">🟢 Low</option>
            <option value="medium" selected>🟡 Medium</option>
            <option value="high">🔴 High</option>
        </select>

        <button class="px-5 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
            +
        </button>

    </form>

    <div class="space-y-4">

      @foreach($tasks as $task)
      <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-4 rounded-2xl shadow hover:shadow-md transition">

          <!-- LEFT -->
          <div class="flex items-center gap-4">

              <!-- Checkbox -->
              <form method="POST" action="{{ route('tasks.toggle', $task->id) }}">
                  @csrf
                  <input type="checkbox"
                      onchange="this.form.submit()"
                      class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500"
                      {{ $task->status == 'done' ? 'checked' : '' }}>
              </form>

              <!-- Content -->
              <div>
                  <p class="text-lg font-medium 
                      {{ $task->status == 'done' ? 'line-through text-gray-400' : 'text-gray-800 dark:text-white' }}">
                      {{ $task->title }}
                  </p>

                  <!-- Info kecil -->
                  <div class="flex items-center gap-2 mt-1">

                      <!-- Priority -->
                      <span class="text-xs px-2 py-1 rounded-full
                          @if($task->priority == 'high') bg-red-100 text-red-600
                          @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-600
                          @else bg-green-100 text-green-600
                          @endif">
                          {{ ucfirst($task->priority) }}
                      </span>

                      <!-- Due Date -->
                      @if($task->due_date)
                      <span class="text-xs text-gray-400">
                          📅 {{ $task->due_date }}
                      </span>
                      @endif

                  </div>
              </div>
          </div>

          <!-- RIGHT ACTION -->
          <div class="flex items-center gap-3">

              <!-- Status badge -->
              <span class="text-xs px-3 py-1 rounded-full
                  {{ $task->status == 'done' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                  {{ $task->status }}
              </span>

              <form id="delete-form-{{ $task->id }}" method="POST" action="{{ route('tasks.destroy', $task->id) }}">
                  @csrf
                  @method('DELETE')

                  <button type="button" onclick="confirmDelete({{ $task->id }})"
                      class="text-red-500 hover:text-red-700 text-lg">
                      ✕
                  </button>
              </form>

          </div>

      </div>
      @endforeach

      </div>

</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Yakin hapus?',
        text: "Task akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '{{ session('success') }}',
    timer: 2000,
    showConfirmButton: false
});
</script>
@endif
</x-app-layout>