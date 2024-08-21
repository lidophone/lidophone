<?php

use App\Models\YandexRealty;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yandex_realty', function (Blueprint $table) {
            $table->comment('https://is.gd/qS4fkE');

            $table->id();

            $table->string('internal_id')
                ->comment('У элемента offer есть обязательный атрибут internal-id, который должен быть уникальным для каждого объявления. Internal-id может состоять из цифр, латинских и кириллических букв, знаков. Мы рекомендуем заполнять его номером из вашей базы данных.');

            $table->string('organization')->comment('Название организации. https://is.gd/wY3Spe');

            $table->integer('yandex_building_id')
                ->comment('Идентификатор жилого комплекса в базе Яндекса. https://is.gd/yJRRTh');

            $table->string('building_name')->comment('Название жилого комплекса. https://is.gd/hUT3hs');

            $table->unsignedSmallInteger('built_year')->comment('Год сдачи (год постройки). https://is.gd/98Puy5');
            $table->unsignedTinyInteger('ready_quarter')->comment('Квартал сдачи (квартал постройки)');

            $table->enum('building_state', YandexRealty::BUILDING_STATES)
                ->comment('Статус строительства: built, hand-over, unfinished');

            $table->string('apartments')->nullable()
                ->comment('Апартаменты. Строго ограниченные значения: «да»/«нет», «true»/«false», «1»/«0», «+»/«-». https://is.gd/Q1zXE6');

            $table->unsignedTinyInteger('rooms')->nullable()
                ->comment('Общее количество комнат в квартире. При свободной планировке укажите количество комнат по паспорту объекта. Не обязательно для частных домов. Не указывайте для студий. https://is.gd/oY24fQ');
            $table->enum('studio', ['да', 'true', '1', '+'])->nullable()
                ->comment('Студия. Элемент используется только для объявлений о продаже и аренде квартиры. Строго ограниченные значения: «да», «true», «1», «+». Элемент не используется для объектов со свободной планировкой. https://is.gd/I8Cm8s');

            $table->string('renovation')
                ->comment('Ремонт. Возможные значения: «евроремонт», «косметический», «дизайнерский», «требует ремонта». (в фиде встречается значение «черновая отделка»). https://is.gd/FFJ5eY');

            $table->double('area_value')->comment('Общая площадь. https://is.gd/hNLJus');
            $table->string('area_unit')->comment('Единица измерения площади. https://is.gd/hNLJus');

            $table->integer('price_value')->comment('Цена. https://is.gd/La0cUD');
            $table->string('price_currency')->comment('Валюта. https://is.gd/La0cUD');

            // <location>
            $table->string('region')->nullable()->comment('Название субъекта РФ. https://is.gd/5garz4');
            $table->string('locality_name')->comment('Название населенного пункта. https://is.gd/1tsCRM');
            $table->json('metro')->comment('Ближайшая станция метро. (может быть несколько) https://is.gd/3d0pRf');
            $table->double('latitude')->comment('Географическая широта. https://is.gd/cBO58u');
            $table->double('longitude')->comment('Географическая долгота. https://is.gd/71CkEc');
            // </location>

            $table->json('images')->comment('Фотография. https://is.gd/3XQT9P');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yandex_realty');
    }
};
