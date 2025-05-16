<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">PDF Usage History</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('admin.history.export') }}"
               class="mb-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-500 transition">
                📥 Export CSV
            </a>

            <div class="bg-gray-800 shadow-md rounded-lg overflow-x-auto">
                <table class="w-full text-left text-sm text-white">
                    <thead class="bg-gray-700 uppercase text-xs">
                    <tr>
                        <th class="p-4">User ID</th>
                        <th class="p-4">Action</th>
                        <th class="p-4">Used From</th>
                        <th class="p-4">City</th>
                        <th class="p-4">State</th>
                        <th class="p-4">Timestamp</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($logs as $log)
                        <tr class="border-b border-gray-700 hover:bg-gray-700">
                            <td class="p-4">{{ $log->user_id }}</td>
                            <td class="p-4">{{ ucfirst($log->action) }}</td>
                            <td class="p-4">{{ ucfirst($log->used_from) }}</td>
                            <td class="p-4">{{ $log->location_city }}</td>
                            <td class="p-4">{{ $log->location_state }}</td>
                            <td class="p-4">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
