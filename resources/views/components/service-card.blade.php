@props(['title', 'description', 'button', 'icon' , 'link'])

<div class="card bg-white shadow-md hover:shadow-lg transition duration-300 p-5 rounded-lg text-center w-full">
    <div class="flex justify-center mb-4">
        <img src="{{ $icon }}" alt="{{ $title }}" class="w-24 md:w-28">
    </div>

    <h3 class="text-base md:text-lg font-bold mb-2">{{ $title }}</h3>
    <p class="text-sm text-gray-600 mb-4">
        {{ $description }}
    </p>

    <a href="{{ $link }}" class="btn bg-yellow-400 hover:bg-yellow-500 text-black shadow-md font-semibold">
    {{ $button }}
</a>
</div>
