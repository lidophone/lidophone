<?php

/**
 * @see \App\Http\Controllers\MainController::dailyCompetition()
 *
 * @var \App\Services\DailyCompetition $dailyCompetition
 */

$tableForYesterday = $dailyCompetition->getTableForYesterday();

$settings = array_column($dailyCompetition->settings, 'value', 'key');

?>
<x-lidofon.layout>
  <x-slot name="title">
    {{ __('Daily competition') }}
  </x-slot>
  <x-slot name="header">
    @vite('resources/js/daily-competition.js')
  </x-slot>
  <div class="container-fluid mt-4 mb-4">
    <div class="row justify-content-center text-center">
      <h1>{{ __('Daily competition') }}</h1>
    </div>
    <div class="text-center mt-2">
      <h2>{{ __('Company record') }}</h2>
      <ul class="mt-4" id="company-record">
        <li>🏆 {{ $settings['company_record_manager'] }}</li>
        <li>
          📅 {{ $settings['company_record_date'] }}
          📞 {{ $settings['company_record_number_of_transfers'] . ' ' . __('transfers')}}
        </li>
        <li>💰 {!! __('Instant payment') !!} {{ $settings['company_record_instant_payment'] }}₽</li>
      </ul>
    </div>
    <div class="row justify-content-center text-center">
      <div class="col-auto">
        <h2>{{ __('Today') }}</h2>
        <table class="table table-bordered mt-4">
          <thead>
          <tr>
            <th>{{ __('Transfers needed') }}</th>
            <th>{{ __('Award') }}</th>
            <th>{{ __('Manager') }}</th>
          </tr>
          </thead>
          <tbody id="daily-competition-table-body-today"></tbody>
        </table>
      </div>
    </div>
    <div class="row justify-content-center text-center">
      <div class="col-auto">
        <h2 class="mt-3">{{ __('Yesterday') }}</h2>
        <table class="table table-bordered mt-4">
          <thead>
          <tr>
            <th>{{ __('Transfers needed') }}</th>
            <th>{{ __('Award') }}</th>
            <th>{{ __('Manager') }}</th>
          </tr>
          </thead>
          <tbody>
          {!! $tableForYesterday !!}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-lidofon.layout>
