<?php

$locale = app()->getLocale();

$translations = file_get_contents(base_path('lang/' . $locale . '.json'));

?>
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? __('Lidofon') }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css">
  <script>
    const I18N = {!! $translations !!};
  </script>
  @vite(['resources/css/lidofon.css', 'resources/js/app.js'])
  {{ $header ?? '' }}
</head>
<body>
  {{ $slot }}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sumoselect@3.4.9/jquery.sumoselect.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/dist/js.cookie.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
  {{ $bodyEnd ?? '' }}
</body>
</html>
