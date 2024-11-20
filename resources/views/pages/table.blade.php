<div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">

    @foreach($records as $item)
    <div
        class="plugins group relative min-h-full rounded-xl bg-[#fafafa] px-6 py-12 transition duration-300 hover:-translate-y-1 md:rounded-3xl text-gray-600">
        <a class="btn btn-icon absolute top-[8%] right-[8%] border-gray-400 border rounded-full" href="#?"
            target="_self"><span class="icon-wrapper">
                <span class="icon">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em"
                        width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z">
                        </path>
                    </svg>
                </span>
                <span class="icon" aria-hidden="true">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em"
                        width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z">
                        </path>
                    </svg>
                </span>
            </span>
        </a>
        <img onerror="this.onerror=null; this.src='{{ route('plugins.logo', ['plugin' => $item['identifier']]) }}'"
            src="{{ route('plugins.logo', ['plugin' => $item['identifier']]) }}" loading="lazy" decoding="async"
            class="mb-3 w-28" width="120" height="120">
        <div class="mb-2 flex flex-row items-center">
            <h3 class="text-2xl font-medium">{{ json_decode($item['name'])->{app()->getLocale()} }}</h3>
            <span class="inline-block bg-[#013b8b] text-white text-xs font-semibold px-2 rounded h-5 leading-5 ml-4">{{
                $item['version'] }}
            </span>

        </div>

        <p class="text-sm"> {{ json_decode($item['description'])->{app()->getLocale()} }}</p>
        <ul class="mt-4 flex flex-wrap gap-2 text-gray-500">
            @if($item['type'] !== 'lib')
            @if((bool)config('filament-plugins.allow_generator'))
            <x-filament::icon-button :tooltip="trans('filament-plugins::messages.plugins.actions.generate')" tag="a"
                href="{{route('filament.'.filament()->getCurrentPanel()->getId().'.resources.tables.index', ['module'=>$item->module_name])}}">
                <x-slot name="icon">
                    <x-heroicon-s-cog class="w-5 h-5" />
                </x-slot>
            </x-filament::icon-button>
            @endif
            @if((bool)config('filament-plugins.allow_toggle'))
            @if($item->active)
            {{ ($this->disableAction)(['item' => $item]) }}
            @else
            {{ ($this->activeAction)(['item' => $item]) }}
            @endif

            @endif
            @if((bool)config('filament-plugins.allow_destroy'))
            {{ ($this->deleteAction)(['item' => $item])}}
            @endif
            @endif

            <div class="w-full flex justify-end gap-4">
                @if($item->github)
                <x-filament::icon-button :tooltip="trans('filament-plugins::messages.plugins.actions.github')"
                    href="{{$item->github}}" target="_blank" tag="a">
                    <x-slot name="icon">
                        <x-heroicon-s-globe-asia-australia class="w-5 h-5" />
                    </x-slot>
                </x-filament::icon-button>
                @endif
                @if($item->docs)
                <x-filament::icon-button :tooltip="trans('filament-plugins::messages.plugins.actions.docs')"
                    href="{{$item->docs}}" target="_blank" tag="a">
                    <x-slot name="icon">
                        <x-heroicon-s-document-text class="w-5 h-5" />
                    </x-slot>
                </x-filament::icon-button>
                @endif
            </div>
        </ul>
    </div>
    @endforeach
</div>