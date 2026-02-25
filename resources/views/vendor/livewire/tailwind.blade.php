@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

@if ($paginator->hasPages())
<div class="flex items-center justify-between w-full h-16 px-3 border-t border-neutral-200">

    <!-- Showing Info -->
    <p class="pl-2 text-sm text-gray-700">
        Showing
        <span class="font-medium">{{ $paginator->firstItem() }}</span>
        to
        <span class="font-medium">{{ $paginator->lastItem() }}</span>
        of
        <span class="font-medium">{{ $paginator->total() }}</span>
        results
    </p>

    <!-- Pagination -->
    <nav>
        <ul class="flex items-center text-sm leading-tight bg-white border divide-x rounded h-9 text-neutral-500 divide-neutral-200 border-neutral-200">

            <!-- Previous -->
            <li class="h-full">
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center h-full px-3 rounded-l text-neutral-400 cursor-not-allowed">
                        Previous
                    </span>
                @else
                    <button type="button"
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            class="relative inline-flex items-center h-full px-3 rounded-l hover:text-neutral-900">
                        Previous
                    </button>
                @endif
            </li>

            <!-- Page Numbers -->
            @foreach ($elements as $element)

                {{-- Dots --}}
                @if (is_string($element))
                    <li class="hidden md:block h-full">
                        <span class="relative inline-flex items-center h-full px-2.5">
                            {{ $element }}
                        </span>
                    </li>
                @endif

                {{-- Pages --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="hidden md:block h-full" wire:key="page-{{ $page }}">
                            @if ($page == $paginator->currentPage())
                                <span class="relative inline-flex items-center h-full px-3 text-neutral-900 bg-gray-50">
                                    {{ $page }}
                                    <span class="absolute bottom-0 left-0 w-full h-px bg-neutral-900"></span>
                                </span>
                            @else
                                <button type="button"
                                        wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                        class="relative inline-flex items-center h-full px-3 hover:text-neutral-900 group">
                                    {{ $page }}
                                    <span class="absolute bottom-0 left-1/2 w-0 h-px bg-neutral-900 transition-all duration-200 group-hover:left-0 group-hover:w-full"></span>
                                </button>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <!-- Next -->
            <li class="h-full">
                @if ($paginator->hasMorePages())
                    <button type="button"
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            class="relative inline-flex items-center h-full px-3 rounded-r hover:text-neutral-900">
                        Next
                    </button>
                @else
                    <span class="relative inline-flex items-center h-full px-3 rounded-r text-neutral-400 cursor-not-allowed">
                        Next
                    </span>
                @endif
            </li>

        </ul>
    </nav>

</div>
@endif