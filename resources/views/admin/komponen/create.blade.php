@extends('layouts.app')
@section('title', 'Tambah Komponen')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Tambah Komponen</h1>

    <form action="{{ route('admin.komponen.store') }}" method="POST" class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 max-w-xl">
        @csrf
        @include('admin.komponen._form')
    </form>
@endsection