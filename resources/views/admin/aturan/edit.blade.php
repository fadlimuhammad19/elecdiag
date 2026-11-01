@extends('layouts.app')
@section('title', 'Edit Aturan')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Aturan</h1>

    <form action="{{ route('admin.aturan.update', $aturan) }}" method="POST" class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 max-w-xl">
        @csrf
        @method('PUT')
        @include('admin.aturan._form')
    </form>
@endsection