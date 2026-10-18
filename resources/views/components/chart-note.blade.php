@props(['text' => 'Loading the chart…'])

{{-- The chart loading note: rendered here, re-shown and cleared by the
     window.chartNotes helper while a chart's series are in flight. --}}
<p {{ $attributes->merge(['class' => 'text-[13px] text-[#64748B]', 'role' => 'status']) }} data-loading>{{ $text }}</p>
