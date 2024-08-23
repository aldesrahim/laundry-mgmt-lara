@if($logo)
    <div class="logo-wrapper" style="{{ $logo->getStyle() }}">
        <img src="{{ $logo->getLogo() }}"/>
    </div>
@endif

<div class="ps-2" style="width: 100%">
    <p class="title text-center my-0">{{ $title }}</p>

    @foreach($subtitles as $subtitle)
        <p class="text-center my-0">{{ $subtitle }}</p>
    @endforeach
</div>
