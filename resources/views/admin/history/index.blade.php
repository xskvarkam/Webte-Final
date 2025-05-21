<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">{{ __('messages.view_history') }}</h2>
    </x-slot>

    <style>
        td {
            text-align: center;
        }
    </style>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="display:flex;justify-content: space-between">
            <a href="{{ route('admin.history.export') }}"
               class="mb-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-500 transition">
                📥 {{ __('messages.download') }} CSV
            </a>
            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(auth()->user()->role === 'admin')
                <form action="{{ route('admin.history.clear') }}" method="POST" onsubmit="return confirm('{{__('messages.clear_confirm')}}');" class="mb-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('messages.Clear_All_History') }}
                    </button>
                </form>
            @endif
            </div>
            <div class="bg-gray-800 shadow-md rounded-lg overflow-x-auto">
                <table class="w-full text-left text-sm text-white">
                    <thead class="bg-gray-700 uppercase text-xs">
                    <tr>
                        <th class="p-4">{{ __('messages.user') }}</th>
                        <th class="p-4">{{ __('messages.action') }}</th>
                        <th class="p-4">{{ __('messages.source') }}</th>
                        <th class="p-4">{{ __('messages.city') }}</th>
                        <th class="p-4">{{ __('messages.state') }}</th>
                        <th class="p-4">{{ __('messages.timestamp') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($logs as $log)
                        <tr class="border-b border-gray-700 hover:bg-gray-700">
                            <td class="p-4">
                                <span class="text-sm text-gray-400">{{ $log->user->email ?? 'N/A' }}</span>
                            </td>
                            <td class="p-4">{{ ucfirst(__("messages.{$log->action}")) }}</td>
                            <td class="p-4">{{ ucfirst(__('messages.' . $log->used_from)) }}</td>
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
