
/* global CITY_COORDS, I18N, ALL_DEVELOPERS, ACTIVE_DEVELOPERS, MIN_PRICE, MAX_PRICE, MIN_PRICE_OF_ACTIVE_OFFERS, MAX_PRICE_OF_ACTIVE_OFFERS */

const app = {
  /**
   * @see https://getbootstrap.com/docs/5.3/components/tooltips
   * @see https://getbootstrap.com/docs/5.3/components/tooltips#enable-tooltips
   */
  initializeBsTooltips: _ => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    // noinspection JSUnusedLocalSymbols
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
  },
}

Object.defineProperty(Object.prototype, 'sortByKey', {
  value: function () {
    return Object.keys(this).sort().reduce((object, key) => {
      object[key] = this[key]
      return object
    }, {})
  },
  // enumerable: false, // This is actually the default
})

$(_ => {

  const API_URL = '/api/v1/'

  const $filtersColumn = $('#filters-column')
  const $mapColumn = $('#map-column')
  const $filtersForm = $filtersColumn.find('#filters-form')
  const $searchLoader = $('#search-loader')

  const $housingEstateMissing = $('#housing-estate-missing')

  const $housingEstateDeveloperName = $('#housing-estate-developer-name')
  const $doNotSuggestOtherDevelopersAlert = $('#do-not-suggest-other-developers-alert')
  const $housingEstatePhotos = $('#housing-estate-photos')
  const $housingEstateName = $('#housing-estate-name')
  const $housingEstateOpensIn = $('#housing-estate-opens-in')
  const $housingEstatePromotions = $('#housing-estate-promotions')
  const $housingEstateMetro = $('#housing-estate-metro')
  const $housingEstateTags = $('#housing-estate-tags')
  const $housingEstateInfrastructure = $('#housing-estate-infrastructure')
  // LAPS — Location & Advantages & Payment & Scenario
  const $housingEstateLaps = $('#housing-estate-laps')
  const $housingEstateObjects = $('#housing-estate-objects')

  const $sidebarCollapseToggle = $('#sidebar-collapse-toggle')
  $sidebarCollapseToggle.on('click', e => showHideSidebar(e.target))
  function showHideSidebar(sidebarToggle)
  {
    if (sidebarToggle.classList.contains('sidebar-hidden')) {
      $filtersColumn.removeClass('d-none')
      $mapColumn.removeClass('col-9')
      $mapColumn.addClass('col-7')
      sidebarToggle.classList.remove('sidebar-hidden')
      sidebarToggle.textContent = '«'
      localStorage.removeItem('lidofon_sidebarHidden')
    } else {
      $filtersColumn.addClass('d-none')
      $mapColumn.removeClass('col-7')
      $mapColumn.addClass('col-9')
      sidebarToggle.classList.add('sidebar-hidden')
      sidebarToggle.textContent = '»'
      localStorage.setItem('lidofon_sidebarHidden', '1')
    }
  }
  if (localStorage.getItem('lidofon_sidebarHidden')) {
    $sidebarCollapseToggle.trigger('click')
  }

  $filtersForm.find('select').SumoSelect({ // https://hemantnegi.github.io/jquery.sumoselect
    captionFormat: '{0} ' + I18N['selected'],
    captionFormatAllSelected: null,
    clearAll: true,
    csvDispCount: 2,
    locale: [I18N['OK'], I18N['Cancel'], I18N['Select All'], I18N['Clear all']],
    placeholder: I18N['All'],
    selectAll: true,
  })

  $('#city-select').on('change', e => {
    Cookies.set('lidofon_city', e.target.value)
    location.reload()
  })

/*
 * =====================================================================================================================
 * FILTERS
 * =====================================================================================================================
 */

  $filtersForm.on('change', search)

  // Developers

  const $developerSelect = $filtersForm.find('[name="developers[]"]')

  // Price

  const $filterPriceRange = $('#filter-price-range')
  const $filterPriceMinField = $('#filter-price-min-field')
  const $filterPriceMaxField = $('#filter-price-max-field')

  const minPrice = +$filterPriceMinField.val()
  const maxPrice = +$filterPriceMaxField.val()

  $filterPriceMinField.on('keydown', preventPlusMinusListener)
  $filterPriceMaxField.on('keydown', preventPlusMinusListener)
  function preventPlusMinusListener(e)
  {
    if ([107, 109, 187, 189].includes(e.keyCode)) {
      e.preventDefault()
    }
  }

  $filterPriceMinField.on('input', function () {
    $filterPriceRange.slider('values', 0, this.value)
  })
  $filterPriceMaxField.on('input', function () {
    $filterPriceRange.slider('values', 1, this.value)
  })

  $filterPriceRange.slider({
    range: true,
    min: minPrice,
    max: maxPrice,
    values: [minPrice, maxPrice],
    slide: (e, ui) => {
      $filterPriceMinField.val(ui.values[0])
      $filterPriceMaxField.val(ui.values[1])
    },
    change: _ => $filtersForm.change(),
  })

  // "Active" & "Inactive"

  const $disabledCheckbox = $filtersForm.find('[name="disabled"]')
  $disabledCheckbox.on('change', _ => {
    const checked = $disabledCheckbox.prop('checked')
    // Price: [!] DON'T PLACE AFTER DEVELOPERS, BECAUSE OF RAISING EXTRA "change" EVENT
    const minPrice = checked ? MIN_PRICE : MIN_PRICE_OF_ACTIVE_OFFERS
    const maxPrice = checked ? MAX_PRICE : MAX_PRICE_OF_ACTIVE_OFFERS
    $filterPriceMinField.attr('min', minPrice)
    $filterPriceMinField.attr('max', maxPrice)
    $filterPriceMinField.val(minPrice)
    $filterPriceMaxField.attr('min', minPrice)
    $filterPriceMaxField.attr('max', maxPrice)
    $filterPriceMaxField.val(maxPrice)
    $filterPriceRange.slider('values', 0, minPrice)
    $filterPriceRange.slider('values', 1, maxPrice)
    // Developers
    const options = checked ? ALL_DEVELOPERS : ACTIVE_DEVELOPERS
    $developerSelect[0].sumo.unSelectAll()
    $developerSelect[0].sumo.removeAll()
    options.forEach((developer, index) => $developerSelect[0].sumo.add(developer.id, developer.name, index))
  })

/*
 * =====================================================================================================================
 * MAP
 * =====================================================================================================================
 */

  // https://yandex.ru/dev/jsapi30/doc/en

  let MAP

  ymaps3.ready.then(() => { // https://yandex.ru/dev/jsapi30/doc/en/dg/concepts/load#api-readiness
    // https://yandex.ru/dev/jsapi30/doc/ru/ref/#suggest
    // ymaps3.getDefaultConfig().setApikeys({suggest: YOUR_SUGGEST_API_KEY})
    MAP = new ymaps3.YMap(document.getElementById('map'), {
      location: {
        center: [CITY_COORDS.longitude, CITY_COORDS.latitude],
        zoom: 10, // From 0 (all world) to 21
      },
    })
    MAP.addChild(new ymaps3.YMapDefaultSchemeLayer())
    MAP.addChild(new ymaps3.YMapDefaultFeaturesLayer())
    search()
    setInterval(search, 180000)
  })

  const $map = $('#map')
  const $searchInput = $('#map-search-input')
  const $suggestResults = $('#map-suggest-results')
  const $searchResults = $('#map-search-results')
  const CURRENT_SEARCH_MARKERS = []

  $searchInput.on('input focus', e => {
    $searchResults.addClass('d-none')
    if (e.target.value) {
      ymaps3.suggest({ // https://yandex.ru/dev/jsapi30/doc/ru/ref/#SuggestOptions
        center: [CITY_COORDS.longitude, CITY_COORDS.latitude],
        span: [],
        text: e.target.value,
      }).then(result => {
        let lis = ''
        let repeatedResults = []
        result.forEach(item => {
          if (item.subtitle && -1 === repeatedResults.indexOf(item.subtitle.text)) {
            repeatedResults.push(item.subtitle.text)
            lis += '<div>' + item.subtitle.text + '</div>'
          }
        })
        $suggestResults.empty()
        $suggestResults.append(lis)
        $suggestResults.removeClass('d-none')
      })
    } else {
      $suggestResults.addClass('d-none')
    }
  })

  $suggestResults.on('click', 'div', e => yandexSearch(e))
  $searchResults.on('click', 'div', e => searchMarker(e.target.getAttribute('data-coords').split(',')))

  $searchInput.on('keydown', e => {
    navigateListWithUpDownArrowKeys(e, $suggestResults[0].getAttribute('id'), yandexSearch)
  })

  $map.on('click', _ => {
    $suggestResults.addClass('d-none')
    $searchResults.addClass('d-none')
  })

  function yandexSearch(e)
  {
    ymaps3.search({ // https://yandex.ru/dev/jsapi30/doc/ru/ref/#SearchOptions
      center: [CITY_COORDS.longitude, CITY_COORDS.latitude],
      span: [],
      text: e.target.textContent,
      type: ['toponyms'],
    }).then(result => {
      if (result.length !== 1) {
        $suggestResults.addClass('d-none')
        let lis = ''
        result.forEach(item => {
          lis +=
            '<div data-coords="' + item.geometry.coordinates + '">' +
              item.properties.name + (item.properties.description ? ', ' + item.properties.description : '') +
            '</div>'
        })
        $searchResults.empty()
        $searchResults.append(lis)
        $searchResults.removeClass('d-none')
      } else {
        $searchResults.addClass('d-none')
        searchMarker([result[0].geometry.coordinates[0], result[0].geometry.coordinates[1]])
      }
    })
  }

  function searchMarker(coords)
  {
    $searchResults.addClass('d-none')
    clearSearchMarkers()
    const markerElement = document.createElement('div')
    markerElement.classList.add('search-marker')
    const marker = new ymaps3.YMapMarker(
      {coordinates: coords},
      markerElement
    )
    MAP.addChild(marker)
    CURRENT_SEARCH_MARKERS.push(marker)
    MAP.setLocation({center: coords, zoom: 16})
    document.activeElement.blur()
  }

  function clearSearchMarkers()
  {
    CURRENT_SEARCH_MARKERS.forEach(marker => MAP.removeChild(marker))
  }

  $('#phone-filter-input').on('input', e => {
    const phoneNumber = e.target.value.replace(/[^0-9]/g, '')
    if (phoneNumber.length === 11) {
      $filtersForm.append('<input type="hidden" name="phoneNumber" value="' + phoneNumber + '">')
      search()
    } else {
      $filtersForm.find('[name="phoneNumber"]').remove()
    }
    if (phoneNumber.length === 0) {
      search()
    }
  })

/*
 * =====================================================================================================================
 * SEARCH & DISPLAY
 * =====================================================================================================================
 */

  const BASE_URL = API_URL + 'objects/search'
  let currentMarkers = []

  let currentRequestedUrl = BASE_URL

  function search()
  {
    let requestUrl = BASE_URL

    let query = $filtersForm.serializeArray()
    query = query.filter(item => item.value !== '')
    if (query.length) {
      requestUrl += '?'
      query.forEach(item => {
        requestUrl += item.name + '=' + item.value + '&'
      })
      requestUrl = requestUrl.slice(0, -1)
    }

    if (currentRequestedUrl === requestUrl) {
      return
    }
    currentRequestedUrl = requestUrl

    $searchLoader.LoadingOverlay('show')

    $.ajax(requestUrl, {
      success: housingEstates =>
      {
        let selectedHousingEstateId

        currentMarkers.forEach(marker => {
          if (marker.element.classList.contains('housing-estate-selected')) {
            selectedHousingEstateId = +marker.element.getAttribute('data-housing-estate-id')
          }
          MAP.removeChild(marker)
        })

        currentMarkers = []

        housingEstates.forEach(housingEstate => {
          const markerElement = document.createElement('div')
          markerElement.classList.add('housing-estate-marker')
          if (housingEstate.id === selectedHousingEstateId) {
            markerElement.classList.add('housing-estate-selected')
          }
          markerElement.dataset.housingEstateId = housingEstate.id
          markerElement.classList.add(getHasObjectTypeClass(housingEstate))
          const priceUpToClass = getHighlightPriceUpToClass(housingEstate)
          if (priceUpToClass) {
            markerElement.classList.add(priceUpToClass)
          }
          if (housingEstate.is_region) {
            markerElement.classList.add('is-region')
          }

          const markerTextElement = document.createElement('span')
          markerTextElement.className = 'housing-estate-marker-text'
          markerTextElement.textContent = housingEstate.name

          markerElement.appendChild(getMarkerCircle(housingEstate))
          markerElement.appendChild(markerTextElement)

          const marker = new ymaps3.YMapMarker(
            {
              coordinates: [housingEstate.longitude, housingEstate.latitude],
              onClick: markerClick,
            },
            markerElement
          )
          MAP.addChild(marker)
          currentMarkers.push(marker)
        })
      },
      complete: _ =>
      {
        $searchLoader.LoadingOverlay('hide')
        $searchLoader.text(currentMarkers.length)
        currentRequestedUrl = BASE_URL
      },
    })
  }

  function getHasObjectTypeClass(housingEstate)
  {
    const hasApartments = housingEstateHasApartments(housingEstate)
    const hasFlats = housingEstateHasFlats(housingEstate)
    let hasRealEstateTypeClass
    if (hasApartments && hasFlats) {
      hasRealEstateTypeClass = 'has-apartments-and-flats'
    } else if (hasApartments) {
      hasRealEstateTypeClass = 'has-apartments'
    } else if (hasFlats) {
      hasRealEstateTypeClass = 'has-flats'
    } else {
      hasRealEstateTypeClass = 'has-no-objects'
    }
    return hasRealEstateTypeClass
  }

  function getHighlightPriceUpToClass(housingEstate)
  {
    let className
    let offers = []
    if (housingEstate.offers.length) {
      offers = housingEstate.offers
    } else if (housingEstate.developer) {
      offers = housingEstate.developer.offers
    }
    offers.some(offer => {
      if (offer.active) {
        if (Math.round(offer.price * offer.operator_award) <= SETTINGS['highlight_prices_up_to']) {
          return className = 'price-up-to'
        }
      }
    })
    return className
  }

  function getMarkerCircle(housingEstate)
  {
    const markerCircleTextElement = document.createElement('div')
    markerCircleTextElement.classList.add('housing-estate-marker-circle-text')
    if (housingEstateHasDoneRealEstate(housingEstate)) {
      markerCircleTextElement.textContent = '★'
    } else {
      markerCircleTextElement.textContent = getNearDeadlineQuarter(housingEstate)
    }

    const markerCircleElement = document.createElement('div')
    markerCircleElement.classList.add('housing-estate-marker-circle')
    markerCircleElement.appendChild(markerCircleTextElement)

    return markerCircleElement
  }

  function housingEstateHasApartments(housingEstate)
  {
    for (let i in housingEstate.objects) {
      // noinspection JSUnresolvedReference
      if (housingEstate.objects[i].real_estate_type === ENUM_RealEstateType_Apartments) {
        return true
      }
    }
    return false
  }

  function housingEstateHasFlats(housingEstate)
  {
    for (let i in housingEstate.objects) {
      // noinspection JSUnresolvedReference
      if (housingEstate.objects[i].real_estate_type === ENUM_RealEstateType_Flat) {
        return true
      }
    }
    return false
  }

  function housingEstateHasDoneRealEstate(housingEstate)
  {
    for (let i in housingEstate.objects) {
      // noinspection JSUnresolvedReference
      if (housingEstate.objects[i].done) {
        return true
      }
    }
    return false
  }

  function getCurrYearQuarter()
  {
    const currDate = new Date
    const currYear = currDate.getFullYear()
    const currMonthNumber = currDate.getMonth()
    let currQuarter = 4
    if (currMonthNumber < 3) {
      currQuarter = 1
    } else if (currMonthNumber < 6) {
      currQuarter = 2
    } else if (currMonthNumber < 9) {
      currQuarter = 3
    }
    return {year: currYear, quarter: currQuarter}
  }

  const currYearQuarter = getCurrYearQuarter()

  function getNearDeadlineQuarter(housingEstate)
  {
    let currYear = currYearQuarter.year
    let currQuarter = currYearQuarter.quarter
    for (let i = 1; i <= 4; i++) {
      for (let j in housingEstate.objects) {
        if (!housingEstate.objects[j].done) {
          // noinspection JSUnresolvedReference
          if (
            housingEstate.objects[j].deadline_year && housingEstate.objects[j].deadline_quarter
            && housingEstate.objects[j].deadline_year === currYear
            && housingEstate.objects[j].deadline_quarter === currQuarter
          ) {
            return currQuarter
          }
        }
      }
      currQuarter++
      if (currQuarter > 4) {
        currYear++
        currQuarter = 1
      }
    }
    return ''
  }

  function markerClick(e)
  {
    $('.housing-estate-selected').removeClass('housing-estate-selected')

    const $marker = $(e.target).closest('.housing-estate-marker')

    const housingEstateId = $marker.data('housingEstateId')
    if (!Number.isInteger(housingEstateId)) {
      return alert(I18N['Failed to determine the housing estate ID'])
    }

    $marker.addClass('housing-estate-selected')

    const data = {}
    if ($filtersForm.find('[name="disabled"]').prop('checked')) {
      data.disabled = 'on'
    }

    $.ajax(API_URL + 'objects/get/' + housingEstateId, {
      data: data,
      success: housingEstate =>
      {
        $housingEstateMissing.hide()
        clearHousingEstateData()

        // Developer name & "Do not suggest other developers" alert ----------------------------------------

        $housingEstateDeveloperName.append('<span>' + housingEstate.developer.name + '</span>')

        let offer

        const $marketplacesSelect = $filtersForm.find('[name="marketplaces[]"]')
        if ($marketplacesSelect.length) {
          const marketplaces = $marketplacesSelect.val()
          housingEstate.offers.some(item => {
            if (marketplaces.includes('' + item.marketplace.id)) {
              return offer = item
            }
          })
        }

        if (!offer) {
          housingEstate.offers.forEach(function (item) {
            if (!offer) {
              offer = item
            } else if (offer.priority < item.priority) {
              offer = item
            }
          })
        }

        if (offer) { // For housing estates without offer
          $housingEstateDeveloperName.append(
            '<span>' +
            '<span><img src="/img/icons/icons8-today-64.png">' +
            offer.uniqueness_period + ' ' + I18N['days'] +
            '</span>' +
            '<span><img src="/img/icons/icons8-wallet-64.png">' +
            getOperatorAward(offer) + ' ' + '₽' +
            '</span>' +
            '</span>'
          )
          if (offer.other_developers || offer.marketplace.other_developers) {
            $doNotSuggestOtherDevelopersAlert.removeClass('d-none')
          } else {
            $doNotSuggestOtherDevelopersAlert.addClass('d-none')
          }
        }

        // Photos (https://fotorama.io/docs/4/initialization) ----------------------------------------------

        let photos = housingEstate.images
          ? JSON.parse(housingEstate.images)
          : housingEstate.media.map(item => item.original_url);

        let fotoramaHtml = '<div data-loop="true" data-nav="false" data-allowfullscreen="native">'
        let fotoramaItems = ''
        // https://fotorama.io/docs/4/lazy-load
        photos.forEach(url => fotoramaItems += '<a href="' + url + '"></a>')
        fotoramaHtml += fotoramaItems + '</div>'
        const $fotorama = $(fotoramaHtml)
        $housingEstatePhotos.append($fotorama)
        $fotorama.fotorama()

        // Name --------------------------------------------------------------------------------------------

        $housingEstateName.append('<span>' + housingEstate.name + '</span>')

        if (housingEstate.site) {
          $housingEstateName.append('<a href="' + housingEstate.site + '" target="_blank"></a>')
        }

        // Working hours -----------------------------------------------------------------------------------

        // Расписание показывается тогда, когда фильтр "Не рабочее время" включён и оффер активный

        if (
          offer && // For housing estates without offer
          $filtersForm.find('[name="notWorkingHours"]').prop('checked')
          && offer.active
        ) {
            $housingEstateOpensIn.html(getWorkingHours(offer.working_hours))
        }

        // Promotions --------------------------------------------------------------------------------------

        housingEstate.promotions.forEach(promotion =>
          $housingEstatePromotions.append(
            '<div><a href="' + promotion.link + '" target="_blank">' + promotion.name + '</a></div>'
          )
        )

        // Metro -------------------------------------------------------------------------------------------

        housingEstate.metro_stations.forEach(metroStation =>
          $housingEstateMetro.append(
            '<span class="housing-estate-metro-item">' +
              '<span class="housing-estate-metro-line" style="background: ' +
                metroStation.metro_line.color +
              ';" title="' + metroStation.metro_line.name + '">' + (metroStation.metro_line.designation || '') + '</span>' +
              '<span>' + metroStation.name + '</span>' +
              (
                metroStation.pivot.time_on_foot
                  ? '<span class="time-on-foot">' + metroStation.pivot.time_on_foot + '</span>'
                  : ''
              ) +
              (
                metroStation.pivot.time_by_car
                  ? '<span class="time-by-car">' + metroStation.pivot.time_by_car + '</span>'
                  : ''
              ) +
            '</span>'
          )
        )

        // Tags --------------------------------------------------------------------------------------------

        housingEstate.tags.forEach(tag =>
          $housingEstateTags.append('<span>' + tag.name + '</span>')
        )

        // Infrastructure ----------------------------------------------------------------------------------

        housingEstate.infrastructure.forEach(infrastructure =>
          $housingEstateInfrastructure.append(
            '<span class="housing-estate-infrastructure-item">' +
              (
                infrastructure.icon_filename
                  ? `<img src="/storage/${infrastructure.icon_filename}" title="${infrastructure.designation}">`
                  : ''
              ) +
              '<span>' + (infrastructure.pivot.name || '?') + '</span>' +
              (
                infrastructure.pivot.time_on_foot
                  ? '<span class="time-on-foot">' + infrastructure.pivot.time_on_foot + '</span>'
                  : ''
              ) +
              (
                infrastructure.pivot.time_by_car
                  ? '<span class="time-by-car">' + infrastructure.pivot.time_by_car + '</span>'
                  : ''
              ) +
            '</span>'
          )
        )

        // LAPS — Location & Advantages & Payment & Scenario -----------------------------------------------

        const paymentMethods = housingEstate.payment_methods || housingEstate.developer.payment_methods

        $housingEstateLaps.append(
          '<div id="housing-estate-laps-tabs">' +
            '<ul>' +
              '<li><a href="#housing-estate-laps-tab-l">' + I18N['Location'] + '</a></li>' +
              (
                housingEstate.advantages
                  ? '<li><a href="#housing-estate-laps-tab-a">' + I18N['Advantages'] + '</a></li>'
                  : ''
              ) +
              (
                housingEstate.payment || paymentMethods.length
                  ? '<li><a href="#housing-estate-laps-tab-p">' + I18N['Payment'] + '</a></li>'
                  : ''
              ) +
              (
                offer && // For housing estates without offer
                offer.active
                  ? '<li><a href="#housing-estate-laps-tab-s">' + I18N['Scenario'] + '</a></li>'
                  : ''
              ) +
            '</ul>' +
            '<div id="housing-estate-laps-tab-l">' + housingEstate.location + '</div>' +
            (
              housingEstate.advantages
                ? '<div id="housing-estate-laps-tab-a">' + housingEstate.advantages + '</div>'
                : ''
            ) +
            (
              housingEstate.payment || paymentMethods.length
                ? '<div id="housing-estate-laps-tab-p">' +
                    getPayment(paymentMethods, housingEstate.payment) +
                  '</div>'
                : ''
            ) +
            (
              offer && // For housing estates without offer
              offer.active
                ? '<div id="housing-estate-laps-tab-s">' + offer.scenario.scenario + '</div>'
                : ''
            ) +
          '</div>'
        )
        $('#housing-estate-laps-tabs').tabs({
          active: false,
          collapsible: true,
        })

        // Objects -----------------------------------------------------------------------------------------

        let objects = sortObjectsByDeadline(housingEstate.objects)
        objects = sortObjectsByRealEstateType(objects)
        objects = sortObjectsByFinishing(objects)

        // Done

        if (objects.done) {
          for (let realEstateType in objects.done) {
            for (let finishing in objects.done[realEstateType]) {
              let header = I18N['Done'] + ' / ' + ENUM_RealEstateType[realEstateType] + ' / ' + ENUM_Finishing[finishing]
              $housingEstateObjects.append('<div class="housing-estate-objects-header">' + header + '</div>')
              $housingEstateObjects.append(getObjectsTable(objects.done[realEstateType][finishing]))
            }
          }
          delete objects.done
        }

        // Future deadline

        for (let deadline in objects) {
          const yearQuarter = deadline.split('-')
          let headerDeadline = yearQuarter[1] + ' ' + I18N['qr.'] + ' ' + yearQuarter[0]
          for (let realEstateType in objects[deadline]) {
            for (let finishing in objects[deadline][realEstateType]) {
              let header = headerDeadline
              header += ' / ' + ENUM_RealEstateType[realEstateType] + ' / ' + ENUM_Finishing[finishing]
              $housingEstateObjects.append('<div class="housing-estate-objects-header">' + header + '</div>')
              $housingEstateObjects.append(getObjectsTable(objects[deadline][realEstateType][finishing]))
            }
          }
        }
      },
    })
  }

  function getOperatorAward(offer)
  {
    return Math.round(offer.price * offer.operator_award)
  }

  function clearHousingEstateData()
  {
    $housingEstateDeveloperName.empty()
    $housingEstatePhotos.empty()
    $housingEstateName.empty()
    $housingEstateOpensIn.empty()
    $housingEstatePromotions.empty()
    $housingEstateMetro.empty()
    $housingEstateTags.empty()
    $housingEstateInfrastructure.empty()
    $housingEstateLaps.empty()
    $housingEstateObjects.empty()
  }

  function getWorkingHours(workingHours)
  {
    let result = ''
    workingHours.forEach(item => {
      result += ENUM_DaysOfWeek[item.day_of_week] + ': ' + item.start_time + '-' + item.end_time + '<br>'
    })
    return result
  }

  function getPayment(paymentMethods, payment)
  {
    if (!paymentMethods.length) {
      return payment
    }
    let result = ''
    paymentMethods.forEach(item => result += '- ' + item.name + '<br>')
    if (payment) {
      result += '---<br>' + payment
    }
    return result
  }

  function sortObjectsByDeadline(objects)
  {
    const result = {}
    objects.forEach(item => {
      if (item.done) {
        if (!result['done']) {
          result['done'] = []
        }
        result['done'].push(item)
      } else if (item.deadline_year && item.deadline_quarter) {
        let deadline = item.deadline_year + '-' + item.deadline_quarter
        if (!result[deadline]) {
          result[deadline] =[]
        }
        result[deadline].push(item)
      }
    })
    result.sortByKey()
    return result
  }

  function sortObjectsByRealEstateType(objects)
  {
    const result = {}
    for (let deadline in objects) {
      result[deadline] = {}
      objects[deadline].forEach(object => {
        if (!result[deadline][object.real_estate_type]) {
          result[deadline][object.real_estate_type] = []
        }
        result[deadline][object.real_estate_type].push(object)
      })
    }
    return result
  }

  function sortObjectsByFinishing(objects)
  {
    const result = {}
    for (let deadline in objects) {
      for (let realEstateType in objects[deadline]) {
        if (!result[deadline]) {
          result[deadline] = {}
        }
        result[deadline][realEstateType] = {}
        objects[deadline][realEstateType].forEach(object => {
          if (!result[deadline][realEstateType][object.finishing]) {
            result[deadline][realEstateType][object.finishing] = []
          }
          result[deadline][realEstateType][object.finishing].push(object)
        })
      }
    }
    return result
  }

  function getObjectsTable(objects)
  {
    let table = '<table>'
    objects.forEach(item => {
      // noinspection JSNonASCIINames
      table +=
        '<tr>' +
          '<td>' + ENUM_Roominess[item.roominess] + '</td>' +
          '<td>' +
            I18N['from'] + ' ' + formatPrice(item.price) + ' ₽' +
          '</td>' +
          '<td title="' + formatPrice(Math.round(item.price / item.square_meters)) + ' ₽/' + I18N['m²'] + '">' +
            I18N['from'] + ' ' + item.square_meters + ' ' + I18N['m²'] +
          '</td>' +
        '</tr>'
    })
    table += '</table>'
    return table
  }

  /**
   * @see https://stackoverflow.com/a/2901298/4223982
   */
  function formatPrice(price)
  {
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
  }

  /**
   * @see https://jsfiddle.net/Lm1jz8h0
   */
  function navigateListWithUpDownArrowKeys(keyboardEvent, listId, pressEnterHandler)
  {
    if (![13, 38, 40].includes(keyboardEvent.keyCode)) {
      return
    }
    const list = document.getElementById(listId)
    const currentItem = list.querySelector('.w3l-current-item')
    if (keyboardEvent.keyCode === 13 && pressEnterHandler) {
      return pressEnterHandler(currentItem)
    }
    const listAsArray = Array.from(list.children)
    let index = 0
    if (keyboardEvent.keyCode === 38) { // Arrow up
      index = listAsArray.length - 1
    }
    if (currentItem) {
      currentItem.classList.remove('w3l-current-item')
      let currentItemIndex = listAsArray.indexOf(currentItem)
      if (keyboardEvent.keyCode === 38) { // Arrow up
        index = (--currentItemIndex === -1) ? listAsArray.length - 1 : currentItemIndex
      } else if (keyboardEvent.keyCode === 40) { // Arrow down
        index = (++currentItemIndex === listAsArray.length) ? 0 : currentItemIndex
      }
    }
    listAsArray[index].classList.add('w3l-current-item')
  }

  $(window).on('load', _ => { // We need to wait for `ymaps3` to load
    $.ajax(API_URL + 'metro/stations/under-construction', {
      success: metroStations => {
        metroStations.forEach(metroStation => {
          if (!metroStation.latitude || !metroStation.longitude) {
            return
          }

          const markerElement = document.createElement('div')

          /* https://getbootstrap.com/docs/5.3/components/tooltips */
          markerElement.setAttribute('data-bs-toggle', 'tooltip')
          markerElement.setAttribute('data-bs-placement', 'right')
          markerElement.setAttribute('data-bs-title', metroStation.name)
          markerElement.setAttribute('data-bs-custom-class', 'metro-stations-under-construction-tooltip')

          markerElement.classList.add('metro-stations-under-construction')
          markerElement.textContent = 'M'
          const marker = new ymaps3.YMapMarker(
            {coordinates: [metroStation.longitude, metroStation.latitude]},
            markerElement
          )
          MAP.addChild(marker)
        })
      },
      complete: _ => app.initializeBsTooltips(),
    })
  })
})
