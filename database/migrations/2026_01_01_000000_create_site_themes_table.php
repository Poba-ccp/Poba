<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_themes', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique();
            $table->string('setting_value');
            $table->string('setting_group')->default('general');
            $table->timestamps();
        });

        // ── Seed default values so the site never breaks if theme page not visited ──
        $defaults = [
            // Typography
            ['setting_key' => 'heading_font',       'setting_value' => 'Poppins',    'setting_group' => 'typography'],
            ['setting_key' => 'body_font',           'setting_value' => 'Roboto',     'setting_group' => 'typography'],
            ['setting_key' => 'nav_font',            'setting_value' => 'Poppins',    'setting_group' => 'typography'],
            ['setting_key' => 'heading_size',        'setting_value' => '2rem',       'setting_group' => 'typography'],
            ['setting_key' => 'subheading_size',     'setting_value' => '1.5rem',     'setting_group' => 'typography'],
            ['setting_key' => 'body_size',           'setting_value' => '14px',       'setting_group' => 'typography'],
            ['setting_key' => 'nav_size',            'setting_value' => '14px',       'setting_group' => 'typography'],
            ['setting_key' => 'heading_weight',      'setting_value' => '700',        'setting_group' => 'typography'],

            // Colors
            ['setting_key' => 'primary_color',      'setting_value' => '#1a7a7a',    'setting_group' => 'colors'],
            ['setting_key' => 'secondary_color',    'setting_value' => '#e87722',    'setting_group' => 'colors'],
            ['setting_key' => 'text_color',         'setting_value' => '#2c3e50',    'setting_group' => 'colors'],
            ['setting_key' => 'bg_color',           'setting_value' => '#ffffff',    'setting_group' => 'colors'],
            ['setting_key' => 'nav_bg_color',       'setting_value' => '#ffffff',    'setting_group' => 'colors'],
            ['setting_key' => 'footer_bg_color',    'setting_value' => '#1a7a7a',    'setting_group' => 'colors'],
            ['setting_key' => 'card_bg_color',      'setting_value' => '#ffffff',    'setting_group' => 'colors'],

            // Layout
            ['setting_key' => 'border_radius',      'setting_value' => '12px',       'setting_group' => 'layout'],
            ['setting_key' => 'button_radius',      'setting_value' => '30px',       'setting_group' => 'layout'],
        ];

        DB::table('site_themes')->insert($defaults);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_themes');
    }
};
