@extends('layouts.admin')

@include('components.admin.sidebar')

@section('title', 'Edit Akun Kurir')

@section('content')
<div class="absolute top-32 left-0 right-0 px-4">
    <div class="max-w-[90rem] mx-auto bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('admin.edit_kurir.submit') }}" method="POST">
            @csrf

            @php
                $fields = [
                    ['id' => 'nama', 'label' => 'Nama', 'type' => 'text'],
                    ['id' => 'email', 'label' => 'Email', 'type' => 'email'],
                    ['id' => 'no_hp', 'label' => 'No. HP', 'type' => 'text'],
                    ['id' => 'alamat', 'label' => 'Alamat', 'type' => 'text'],
                    ['id' => 'username', 'label' => 'Nama User', 'type' => 'text'],
                    ['id' => 'password', 'label' => 'Sandi', 'type' => 'password'],
                ];
            @endphp

            @foreach ($fields as $field)
            <div class="mb-4 grid grid-cols-12 items-center gap-2">
                <label for="{{ $field['id'] }}" class="col-span-2 font-medium text-left">
                    {{ $field['label'] }}
                </label>
                <span class="col-span-1 text-center">:</span>
                <input type="{{ $field['type'] }}" id="{{ $field['id'] }}" name="{{ $field['id'] }}"
                    class="col-span-9 border shadow-md shadow-gray-250 px-3 py-2 rounded focus:outline-none focus:ring focus:ring-blue-300">
            </div>
            @endforeach

            <div class="text-left mt-6">
                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded shadow-md shadow-gray-700">
                    Edit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
