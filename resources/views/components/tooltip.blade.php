@props(['slot_item', 'slot_tooltip','position' => 'top','class' => ''])
<div class="relative inline-block group {{ $class }}">
        {{ $slot_item }}

        <div
            class="absolute invisible opacity-0 group-hover:visible group-hover:opacity-100
            @if($position === 'top') bottom-full left-1/2 -translate-x-1/2 mb-3 @endif
            @if($position === 'bottom') top-full left-1/2 -translate-x-1/2 mt-3 @endif
 transition-all duration-300 ease-out transform group-hover:translate-y-0 translate-y-2 z-100
            @if($position === 'left') right-full -bottom-1/2 mr-3 @endif
            @if($position === 'right') left-full -bottom-1/2 ml-3 @endif
            ">

            <div
                class="relative p-2 rounded-lg border shadow-md
            bg-white border-gray-200 text-gray-800
            dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                {{ $slot_tooltip }}
            </div>
            <div
                class="absolute w-3 h-3 bg-white border-gray-200 dark:bg-gray-800 dark:border-gray-600 transform rotate-45
                @if($position === 'top') bottom-[-6px] border-r border-b left-1/2 -translate-x-1/2 @endif
                @if($position === 'bottom') top-[-6px] border-l border-t left-1/2 -translate-x-1/2 @endif
                @if($position === 'left') right-[-6px] border-t border-r top-1/2 -translate-y-1/2 @endif
                @if($position === 'right') left-[-6px] border-b border-l top-1/2 -translate-y-1/2 @endif">
            </div>
        </div>
    </div>
