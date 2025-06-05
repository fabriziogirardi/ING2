<div {{ $attributes->merge(['class' => 'flex items-center gap-4 lg:justify-end']) }}>
{{--    <div class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full">--}}
{{--        <span class="font-medium text-gray-600">{{ $person->initials }}</span>--}}
{{--    </div>--}}
    <div class="block font-medium">
        <span class="w-fit overflow-hidden text-ellipsis hidden md:block" title="{{ $person->full_name }}">{{ $person->full_name_ellipsis }}</span>
        <span class="w-fit overflow-hidden text-ellipsis md:hidden" title="{{ $person->full_name }}">{{ $person->full_name }}</span>
        <div class="text-sm text-gray-500 lg:text-end">{{ $userType }}</div>
    </div>
</div>
