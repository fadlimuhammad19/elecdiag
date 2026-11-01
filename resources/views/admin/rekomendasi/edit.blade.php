@extends('layouts.app')
@section('title', 'Edit Rekomendasi')

@section('content')
    <form action="{{ route('admin.rekomendasi.update', $rekomendasi) }}" method="POST"
          class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 max-w-xl">
        @csrf
        @method('PUT')
        @include('admin.rekomendasi._form')
    </form>
@endsection