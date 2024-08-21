<?php

use App\Enums\Finishing;
use App\Enums\RealEstateType;
use App\Enums\Roominess;
use App\Enums\DayOfWeek;
use App\Helpers\AssetHelper;
use App\Helpers\FormatHelper;
use App\Models\City;
use App\Models\Developer;
use App\Models\Marketplace;
use App\Models\Objects;
use App\Models\PaymentMethod;
use App\Models\Settings;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;

/** @var $cities City[]|\Illuminate\Database\Eloquent\Collection */
$cities = City::all()->keyBy('id');
$currentCityId = (int) request()->cookie('lidofon_city');
/** @var $currentCity City */
$currentCity = $currentCityId ? $cities->get($currentCityId) : $cities->get(1);

$settings = Settings::select(['key', 'value'])->pluck('value', 'key')->toArray();

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Sumoselect
 * ---------------------------------------------------------------------------------------------------------------------
 */

/** @var $tags Tag[] */
$tags = Tag::orderBy('name')->get();

$allDevelopers = Developer::getSelectOptionsAsAssocArray();
$activeDevelopers = Developer::getActive();

/** @var $paymentMethods PaymentMethod[] */
$paymentMethods = PaymentMethod::orderBy('name')->get();

$deadlinesInFuture = Objects::whereHas(
    'housingEstate.offers',
    static fn(Builder $offers) => $offers->where('active', 1)
)
    ->select(['deadline_year', 'deadline_quarter'])
    ->whereNotNull(['deadline_year', 'deadline_quarter'])
    ->orderBy('deadline_year')
    ->orderBy('deadline_quarter')
    ->groupBy(['deadline_year', 'deadline_quarter'])
    ->get()
    ->toArray();

/** @var $finishing Finishing[] */
$finishing = Finishing::getSelectOptions();

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Price
 * ---------------------------------------------------------------------------------------------------------------------
 */

$minPrice = FormatHelper::trimNDigits(Objects::min('price'), FormatHelper::FIXED_NUMBER_OF_ZEROS);
$maxPrice = FormatHelper::trimNDigits(Objects::max('price'), FormatHelper::FIXED_NUMBER_OF_ZEROS) + 1;

$minMaxPrices = Objects::getMinMaxPriceOfActiveOffers();
$minPriceOfActiveOffers = FormatHelper::trimNDigits($minMaxPrices['minPrice'], FormatHelper::FIXED_NUMBER_OF_ZEROS);
$maxPriceOfActiveOffers = FormatHelper::trimNDigits($minMaxPrices['maxPrice'], FormatHelper::FIXED_NUMBER_OF_ZEROS) + 1;

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Marketplaces
 * ---------------------------------------------------------------------------------------------------------------------
 */

if (User::isAdmin() || User::isExpertMode()) {
    $marketplaces = Marketplace::orderBy('name')->pluck('name', 'id')->toArray();
}

?>
<x-lidofon.layout>
  <x-slot name="header">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sumoselect@3.4.9/sumoselect.min.css">
    <script>
      const SETTINGS = {!! json_encode($settings) !!};
      const YANDEX_MAPS_API_KEY = '{{ YANDEX_MAPS_API_KEY }}';
      const CITY_COORDS = {{ '{latitude:' . $currentCity->latitude . ',longitude:' . $currentCity->longitude . '}' }};
      const ENUM_DaysOfWeek = {!! json_encode(DayOfWeek::getSelectOptions()) !!};
      const ENUM_Finishing = {!! json_encode(Finishing::getSelectOptions()) !!};
      const ENUM_RealEstateType = {!! json_encode(RealEstateType::getSelectOptions()) !!};
      const ENUM_RealEstateType_Apartments = {{ RealEstateType::Apartments }};
      const ENUM_RealEstateType_Flat = {{ RealEstateType::Flat }};
      const ENUM_Roominess = {!! json_encode(Roominess::getSelectOptions()) !!};
      const FIXED_NUMBER_OF_ZEROS = {!! FormatHelper::FIXED_NUMBER_OF_ZEROS !!};
      const ALL_DEVELOPERS = {!! json_encode($allDevelopers) !!};
      const ACTIVE_DEVELOPERS = {!! json_encode($activeDevelopers) !!};
      const MIN_PRICE = {!! $minPrice !!};
      const MAX_PRICE = {!! $maxPrice !!};
      const MIN_PRICE_OF_ACTIVE_OFFERS = {!! $minPriceOfActiveOffers !!};
      const MAX_PRICE_OF_ACTIVE_OFFERS = {!! $maxPriceOfActiveOffers !!};
    </script>
  </x-slot>
  <div class="container-fluid overflow-hidden">
    <div class="row" id="map-bootstrap-row">
      <div class="col-2" id="filters-column">
        <form class="mb-3" id="filters-form">
          <select name="city" id="city-select">
            @foreach ($cities as $city)
              <option value="{{ $city->id }}"{{ $city->id === $currentCityId ? ' selected' : '' }}>{{ $city->name }}</option>
            @endforeach
          </select>
          <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" name="clientIsOutOfTown" class="form-check-input">
              {{ __('Client is out of town') }}
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" name="lookingNotForHimself" class="form-check-input">
              {{ __('Looking not for himself') }}
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" name="notWorkingHours" class="form-check-input">
              {{ __('Not working hours') }}
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" name="disabled" class="form-check-input">
              {{ __('Disabled') }}
            </label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" name="moreExpensive" value="{{ $settings['highlight_prices_up_to'] }}" class="form-check-input">
              {{ __('More expensive') . ' ' . $settings['highlight_prices_up_to'] . ' ₽' }}
            </label>
          </div>
          <hr>
          <table id="roominess-table">
            @foreach (Roominess::getSelectOptions() as $value => $name)
              @if ($value % 2 !== 0)
                {!! '<tr>' !!}
              @endif
              <td>
                <div class="form-check form-check-inline">
                  <label class="form-check-label">
                    <input type="checkbox" name="roominess[]" value="{{ $value }}" class="form-check-input"> {{ $name }}
                  </label>
                </div>
              </td>
              @if ($value === Roominess::TYPE_5K->value)
                <td>
                  <div class="form-check form-check-inline">
                    <label class="form-check-label">
                      <input type="checkbox" name="duplex" class="form-check-input"> {{ __('DPLX') }}
                    </label>
                  </div>
                </td>
                @break
              @endif
            @endforeach
          </table>
          <hr>
          <h6>{{ __('Tags') }}</h6>
          <select name="tags[]" multiple>
            @foreach ($tags as $tag)
              <option value="{{ $tag->id }}">{{ $tag->name }}</option>
            @endforeach
          </select>
          <h6>{{ __('Developers') }}</h6>
          <select name="developers[]" multiple>
            @foreach ($activeDevelopers as $developer)
              <option value="{{ $developer['id'] }}">{{ $developer['name'] }}</option>
            @endforeach
          </select>
          <h6>{{ __('Payment methods') }}</h6>
          <select name="paymentMethods[]" multiple>
            @foreach ($paymentMethods as $paymentMethod)
              <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
            @endforeach
          </select>
          <h6>{{ __('Deadline') }}</h6>
          <select name="deadline">
            <option value="">{{ __('All') }}</option>
            <option value="done">{{ __('Done') }}</option>
            <option value="thisYear">{{ __('This year') }}</option>
            @foreach ($deadlinesInFuture as $deadline)
              <option value="{{ $deadline['deadline_year'] . '-' . $deadline['deadline_quarter'] }}">
                {{ __('Until') . ' ' . $deadline['deadline_quarter'] . ' ' . __('qr.') . ' ' . $deadline['deadline_year'] }}
              </option>
            @endforeach
          </select>
          <h6>{{ __('Finishing') }}</h6>
          <select name="finishing[]" multiple>
            @foreach ($finishing as $value => $name)
              <option value="{{ $value }}">{{ $name }}</option>
            @endforeach
          </select>
          <h6>{{ __('Price') }}</h6>
          <div id="filter-price-range-box">
            <div class="d-flex justify-content-between">
              <label>
                <input type="number" min="<?= $minPriceOfActiveOffers ?>" max="<?= $maxPriceOfActiveOffers - 1 ?>" name="priceFrom" value="<?= $minPriceOfActiveOffers ?>" class="form-control" id="filter-price-min-field">{{ __('mln') }}
              </label>
              <label>
                <input type="number" min="<?= $minPriceOfActiveOffers ?>" max="<?= $maxPriceOfActiveOffers ?>" name="priceTo" value="<?= $maxPriceOfActiveOffers ?>" class="form-control" id="filter-price-max-field">{{ __('mln') }}
              </label>
            </div>
            <div class="mt-3" id="filter-price-range"></div>
          </div>
          @if (User::isAdmin() || User::isExpertMode())
            <h6>{{ __('Marketplace') }}</h6>
            <select name="marketplaces[]" multiple>
              @foreach ($marketplaces as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
              @endforeach
            </select>
          @endif
        </form>
      </div>
      <div class="col-7" id="map-column">
        <div id="map-search-input-box">
          <input type="search" id="map-search-input">
          <div class="d-none" id="map-suggest-results"></div>
          <div class="d-none" id="map-search-results"></div>
        </div>
        <div id="sidebar-collapse-toggle">«</div>
        <div id="map"></div>
        <div id="phone-filter-box">
          <input type="search" id="phone-filter-input">
        </div>
      </div>
      <div class="col-3" id="housing-estate-column">
        <div class="text-center" id="housing-estate-missing">
          <h6>{!! __('To display information,<br>click on the marker on the map') !!}</h6>
          <h6>{!! __('If there are no markers,<br>adjust filters') !!}</h6>
        </div>
        <div id="housing-estate-developer-name"></div>
        <div id="do-not-suggest-other-developers-alert" class="d-none">{{ __('Do not suggest other developers') }}</div>
        <div id="housing-estate-photos"></div>
        <div id="housing-estate-name"></div>
        <div id="housing-estate-opens-in"></div>
        <div id="housing-estate-promotions"></div>
        <div id="housing-estate-metro"></div>
        <div id="housing-estate-tags"></div>
        <div id="housing-estate-infrastructure"></div>
        {{-- LAPS — Location & Advantages & Payment & Scenario --}}
        <div id="housing-estate-laps"></div>
        <div id="housing-estate-objects"></div>
      </div>
    </div>
  </div>
  <div class="col-2" id="user-panel">
    <form method="post" action="{{ route('logout') }}">
      @csrf
      <button class="btn btn-link">{{ __('Log out') }}</button>
    </form>
    <div id="search-loader"></div>
  </div>
  @if (User::isAdmin())
    <div class="col-3" id="admin-panel">
      <form method="post" action="{{ route('enableExpertMode') }}">
        @csrf
        <button class="btn btn-link">{{ __(User::isExpertMode() ? 'Normal mode' : 'Expert mode') }}</button>
      </form>
      <a href="{{ env('APP_DEBUG') ? '/nova' : 'https://admin.lidofon.ru' }}" target="_blank" class="btn btn-link">
        {{ __('Admin') }}
      </a>
    </div>
  @endif
  <script src="https://api-maps.yandex.ru/3.0/?apikey={{ YANDEX_MAPS_API_KEY }}&lang={{ config('settings.locale_LOCALE') }}"></script>
  <x-slot name="bodyEnd">
    <script src="{{ AssetHelper::addFilemtime('/js/index.js') }}"></script>
  </x-slot>
</x-lidofon.layout>
