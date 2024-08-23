<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ config('app.name', 'Bukuku Businesss') }}</title>
    <!-- Gilroy Fonts -->
    <link href="https://fonts.cdnfonts.com/css/gilroy-bold" rel="stylesheet"/>

    <link href="{{ asset('report-template/base.css') }}" rel="stylesheet"/>
</head>
<body>

<!-- Header -->
<div class="root-padding row" style="justify-content: flex-start">
    {{ $header }}
</div>

<div class="divider-thick"></div>

<div class="root-padding">
    @foreach($components as $component)
        {{ $component }}
    @endforeach
</div>
</body>
</html>
