@php
    $startDate = request()->get('start_date');
    if (isset($startDate)) {
        $startDate = strtotime($startDate);
    } else {
        $startDate = strtotime(\Carbon\Carbon::now()->format('Y-m-d'));
    }
    $endDate = request()->get('end_date');
    if (isset($endDate)) {
        $endDate = strtotime($endDate);
    } else {
        $endDate = strtotime(\Carbon\Carbon::now()->addDay()->format('Y-m-d'));
    }
    if ($endDate <= $startDate) {
        $startDate = strtotime(\Carbon\Carbon::now()->format('Y-m-d'));
        $endDate = strtotime(\Carbon\Carbon::now()->addDay()->format('Y-m-d'));
    }
    $days = floor(($endDate - $startDate) / 3600 / 24);
    $startDate = date('Y-m-d', $startDate);
    $endDate = date('Y-m-d', $endDate);
@endphp

<x-app-layout>
    <div class="py-14 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">
        <div class="flex flex-wrap mb-12">
            <div class="w-full flex justify-start md:w-1/3 mb-8 md:mb-0">
                <img class="h-full rounded-l-sm" src="/storage/{{ $hotel->poster_url }}" alt="Hotel Image">
            </div>
            <div class="w-full md:w-2/3 px-4">
                <div class="text-2xl font-bold">{{ $hotel->name }}</div>
                <div class="text-xs">
                    {{ $hotel->category }}*
                </div>
                <div class="flex items-center">
                    {{ $hotel->address }}
                </div>
                @if($hotel->facilities->isNotEmpty())
                    <div class="flex items-center py-2">
                        @foreach($hotel->facilities as $facility)
                            <div class="pr-2 text-xs">
                                <span>•</span> {{ $facility->name }}
                            </div>
                        @endforeach
                    </div>
                @endif
                <div>{{ $hotel->description }}</div>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="text-2xl text-center md:text-start font-bold">Забронировать комнату</div>
            <form method="get" action="{{ url()->current() }}">
                <div class="flex my-6">
                    <div class="flex items-center mr-5">
                        <div class="relative">
                            <input name="start_date" min="{{ date('Y-m-d') }}" value="{{ $startDate }}"
                                   placeholder="Дата заезда" type="date"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5">
                        </div>
                        <span class="mx-4 text-gray-500">по</span>
                        <div class="relative">
                            <input name="end_date" type="date" min="{{ date('Y-m-d') }}" value="{{ $endDate }}"
                                   placeholder="Дата выезда"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5">
                        </div>
                    </div>
                    <div>
                        <x-the-button type="submit" class=" h-full w-full">Загрузить номера</x-the-button>
                    </div>
                </div>
                <x-form-validation-errors class="mb-4" :errors="$errors" />
            </form>
            @if($startDate && $endDate)
                <div class="flex flex-col w-full lg:w-4/5">
                    @if($rooms->isNotEmpty())
                        @foreach($rooms as $room)
                            <x-rooms.room-list-item :room="$room" :days="$days" class="mb-4"/>
                      @endforeach
                    @else
                        <h1 class="text-lg md:text-xl font-semibold text-gray-800">На выбранные даты нет свободных номеров</h1>
                    @endif
                </div>
            @else
                <div></div>
            @endif
        </div>
    </div>
</x-app-layout>
