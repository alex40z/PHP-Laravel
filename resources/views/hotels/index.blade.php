<x-app-layout>

@php
    if (!request()->get('clear_filter')) {
        $category = request()->get('category');
        $min_price = request()->get('min_price');
        $max_price = request()->get('max_price');
        $facilities = request()->get('facilities');
        if (!isset($facilities)) {
            $facilities = [];
        }
    } else {
        $category = null;
        $min_price = null;
        $max_price = null;
        $facilities = [];
    }
@endphp

    <div class="flex flex-col">
        <div class="text-2xl text-center md:text-center font-bold">Фильтр</div>
        <form method="get" action="{{ url()->current() }}">
            <div class="flex justify-center items-center mr-5 space-x-5">
                <span class="mx-4 text-gray-500">Категория:</span>
                <div class="relative">
                    <input name="category" min="1" max="5" value="{{ $category }}"
                           placeholder="Категория" type="number"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-32 pl-5 p-2.5">
                </div>
                <span class="mx-4 text-gray-500">Цена:</span>
                <div class="relative">
                    <input name="min_price" min="0" max="100000" value="{{ $min_price }}"
                           placeholder="Мин. цена" type="number"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-32 pl-5 p-2.5">
                </div>
                <div class="relative">
                    <input name="max_price" min="0" max="100000" value="{{ $max_price }}"
                           placeholder="Макс. цена" type="number"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-32 pl-5 p-2.5">
                </div>
                <span class="mx-4 text-gray-500">Удобства:</span>
                <div class="relative">
                    <select name="facilities[]" size="3" multiple
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-32 pl-5 p-2.5">
                        @foreach($facilities_list as $facility)
                            @if(in_array($facility->id, $facilities))
                                <option value="{{ $facility->id }}" selected>{{ $facility->name }}</option>
                            @else
                                <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col">
                    <div class="p-1">
                        <x-the-button type="submit" class="w-full">Загрузить отели</x-the-button>
                    </div>
                    <div class="p-1">
                        <x-the-button type="submit" formaction="{{ route('hotels.index') }}" name="clear_filter" value="1" class="w-full">Очистить фильтр</x-the-button>
                    </div>
                </div>
            </div>
            <x-form-validation-errors class="mb-4" :errors="$errors" />
        </form>
    </div>

    <div class="py-14 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($hotels as $hotel)
                <x-hotels.hotel-card :hotel="$hotel"></x-hotels.hotel-card>
            @endforeach
        </div>
    	{{ $hotels->links('vendor.pagination.tailwind') }}
    </div>
</x-app-layout>
