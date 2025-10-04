<?php
?>
<div
    class="flex items-center p-4 bg-white border border-{{ $color }}-200 rounded-xl shadow-md hover:shadow-lg transition duration-300 transform hover:scale-[1.02]">
    <div class="flex-shrink-0 p-3 bg-{{ $color }}-500 rounded-full text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
        </svg>
    </div>
    <div class="ml-4">
        <p class="text-sm font-medium text-gray-500">{{ $title }} Bulan Ini</p>
        <p class="text-xl font-bold text-{{ $color }}-700">{{ $value }}</p>
    </div>
</div>
<?php ?>
