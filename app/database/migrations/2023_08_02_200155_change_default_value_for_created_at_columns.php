<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
DB::unprepared(<<<SQL
    alter table cities modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table developers modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table housing_estates modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table housing_estates_infrastructure modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table housing_estates_metro_stations modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table housing_estates_payment_methods modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table housing_estates_promotions modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table housing_estates_tags modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table infrastructure modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table metro_lines modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table metro_stations modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table objects modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table offers modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table payment_methods modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table promotions modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table scenarios modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table tags modify created_at timestamp default CURRENT_TIMESTAMP not null;
    alter table working_hours modify created_at timestamp default CURRENT_TIMESTAMP not null;
SQL);
    }
};
