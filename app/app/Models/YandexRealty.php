<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\YandexRealty
 *
 * @property int $id
 * @property string $internal_id У элемента offer есть обязательный атрибут internal-id, который должен быть уникальным для каждого объявления. Internal-id может состоять из цифр, латинских и кириллических букв, знаков. Мы рекомендуем заполнять его номером из вашей базы данных.
 * @property string $organization Название организации. https://is.gd/wY3Spe
 * @property int $yandex_building_id Идентификатор жилого комплекса в базе Яндекса. https://is.gd/yJRRTh
 * @property string $building_name Название жилого комплекса. https://is.gd/hUT3hs
 * @property int $built_year Год сдачи (год постройки). https://is.gd/98Puy5
 * @property int $ready_quarter Квартал сдачи (квартал постройки)
 * @property string|null $building_state
 * @property string|null $apartments Апартаменты. Строго ограниченные значения: «да»/«нет», «true»/«false», «1»/«0», «+»/«-». https://is.gd/Q1zXE6
 * @property int|null $rooms Общее количество комнат в квартире. При свободной планировке укажите количество комнат по паспорту объекта. Не обязательно для частных домов. Не указывайте для студий. https://is.gd/oY24fQ
 * @property string|null $studio Студия. Элемент используется только для объявлений о продаже и аренде квартиры. Строго ограниченные значения: «да», «true», «1», «+». Элемент не используется для объектов со свободной планировкой. https://is.gd/I8Cm8s
 * @property string|null $renovation Ремонт. Возможные значения: «евроремонт», «косметический», «дизайнерский», «требует ремонта». (в фиде встречается значение «черновая отделка»). https://is.gd/FFJ5eY
 * @property float $area_value Общая площадь. https://is.gd/hNLJus
 * @property string $area_unit Единица измерения площади. https://is.gd/hNLJus
 * @property int $price_value Цена. https://is.gd/La0cUD
 * @property string $price_currency Валюта. https://is.gd/La0cUD
 * @property string|null $region Название субъекта РФ. https://is.gd/5garz4
 * @property string $locality_name Название населенного пункта. https://is.gd/1tsCRM
 * @property mixed $metro Ближайшая станция метро. (может быть несколько) https://is.gd/3d0pRf
 * @property float $latitude Географическая широта. https://is.gd/cBO58u
 * @property float $longitude Географическая долгота. https://is.gd/71CkEc
 * @property mixed $images Фотография. https://is.gd/3XQT9P
 * @property string $description
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty query()
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereApartments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereAreaUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereAreaValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereBuildingName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereBuildingState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereBuiltYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereInternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereLocalityName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereMetro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereOrganization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty wherePriceCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty wherePriceValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereReadyQuarter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereRenovation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereRooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereStudio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexRealty whereYandexBuildingId($value)
 * @mixin \Eloquent
 */
class YandexRealty extends Model
{
    use HasFactory;

    protected $table = 'yandex_realty';

    public const BUILDING_STATE_BUILT = 'built';
    public const BUILDING_STATE_FINISHED = 'finished';
    public const BUILDING_STATE_HAND_OVER = 'hand-over';
    public const BUILDING_STATE_READY = 'ready';
    public const BUILDING_STATE_UNFINISHED = 'unfinished';

    public const BUILDING_STATES = [
        self::BUILDING_STATE_BUILT,
        self::BUILDING_STATE_FINISHED,
        self::BUILDING_STATE_HAND_OVER,
        self::BUILDING_STATE_READY,
        self::BUILDING_STATE_UNFINISHED,
    ];

    public const BUILDING_STATES_READY = [
        self::BUILDING_STATE_BUILT,
        self::BUILDING_STATE_FINISHED,
        self::BUILDING_STATE_HAND_OVER,
        self::BUILDING_STATE_READY,
    ];
}
