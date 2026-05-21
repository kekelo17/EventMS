@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-4xl font-bold mb-8">Users Management</h1>

    <div class="bg-white rounded-3xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-8 py-5 text-left">User</th>
                    <th class="px-8 py-5 text-left">Role</th>
                    <th class="px-8 py-5 text-left">Tickets</th>
                    <th class="px-8 py-5 text-left">Events</th>
                    <th class="px-8 py-5 text-left">Status</th>
                    <th class="px-8 py-5 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-8 py-6">
                        <div class="font-medium">{{ $user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="capitalize px-4 py-1 rounded-full text-sm 
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 
                               ($user->role === 'organiser' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-8 py-6">{{ $user->tickets_count ?? 0 }}</td>
                    <td class="px-8 py-6">{{ $user->events_count ?? 0 }}</td>
                    <td class="px-8 py-6">
                        @if($user->is_active)
                            <span class="text-emerald-600">Active</span>
                        @else
                            <span class="text-red-600">Disabled</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-center">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:underline">View</a>
                        @if($user->role !== 'admin')
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline ml-4">
                                @csrf
                                <button type="submit" class="text-sm {{ $user->is_active ? 'text-red-600' : 'text-emerald-600' }}">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection