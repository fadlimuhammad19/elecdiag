@extends('layouts.app')
@section('title', 'Edit Kategori')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Kategori</h1>

    <form action="{{ route('admin.kategori.update', $kategori) }}" method="POST" class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 max-w-xl">
        @csrf
        @method('PUT')
        @include('admin.kategori._form')
    </form>
@endsection