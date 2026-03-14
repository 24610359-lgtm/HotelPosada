<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * $2y$12$/c3KlhtuWrYZN7xoZapjUui9MH8wmdXSFNaca0XGp/fY25oKv9ys.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre', 50);
            $table->string('apellidos', 100);
            $table->string('telefono', 10);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('rol', ['cliente', 'admin', 'recepcion', 'limpieza'])
                ->default('cliente');
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->useCurrent();
        });

        Schema::create('tipos_habitacion', function (Blueprint $table) {
            $table->id('id_tipo');
            $table->string('nombre', 50);
            $table->text('descripcion')->nullable();
            $table->decimal('precio_noche', 10, 2);
            $table->integer('capacidad');
        });

        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id('id_habitacion');
            $table->string('numero', 10)->unique();
            $table->foreignId('id_tipo')->constrained('tipos_habitacion', 'id_tipo');
            $table->enum('estado', ['disponible', 'sucia', 'mantenimiento'])
                ->default('disponible');
        });

        Schema::create('servicios', function (Blueprint $table) {
            $table->id('id_servicio');
            $table->string('nombre', 50);
            $table->string('icono', 100)->nullable();
        });

        Schema::create('tipos_habitacion_servicios', function (Blueprint $table) {
            $table->foreignId('id_tipo')->constrained('tipos_habitacion', 'id_tipo');
            $table->foreignId('id_servicio')->constrained('servicios', 'id_servicio');
            $table->primary(['id_tipo', 'id_servicio']);
        });

        Schema::create('reservaciones', function (Blueprint $table) {
            $table->id('id_reservacion');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario');
            $table->foreignId('id_habitacion')->constrained('habitaciones', 'id_habitacion');
            $table->date('fecha_entrada');
            $table->date('fecha_salida');
            $table->enum('estado', ['activa', 'cancelada', 'finalizada'])
                ->default('activa');
            $table->timestamp('fecha_creacion')->useCurrent();
        });

        // Schema::create('cargos_extra', function (Blueprint $table) {
        //     $table->id('id_cargo');
        //     $table->foreignId('id_reservacion')->constrained('reservaciones', 'id_reservacion');
        //     $table->string('area', 50);
        //     $table->string('concepto', 100);
        //     $table->integer('cantidad')->default(1);
        //     $table->decimal('monto', 10, 2);
        //     $table->timestamp('fecha')->useCurrent();
        // });

        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');
            $table->foreignId('id_reservacion')
                ->constrained('reservaciones', 'id_reservacion');
            $table->enum('tipo', ['pago', 'reembolso', 'ajuste']);
            $table->decimal('monto', 10, 2);
            $table->enum('metodo', ['efectivo', 'tarjeta', 'transferencia'])
                ->nullable();
            $table->string('referencia', 100)->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha')->useCurrent();
        });

        Schema::create('limpieza', function (Blueprint $table) {
            $table->id('id_limpieza');
            $table->foreignId('id_habitacion')->constrained('habitaciones', 'id_habitacion');
            $table->enum('estado', ['pendiente', 'en_proceso', 'limpia'])
                ->default('pendiente');
            $table->timestamp('fecha')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('limpieza');
        Schema::dropIfExists('pagos');
        // Schema::dropIfExists('cargos_extra');
        Schema::dropIfExists('reservaciones');
        Schema::dropIfExists('servicios_habitacion');
        Schema::dropIfExists('tipos_habitacion_servicios');
        Schema::dropIfExists('servicios');
        Schema::dropIfExists('habitaciones');
        Schema::dropIfExists('tipos_habitacion');
        Schema::dropIfExists('usuarios');
    }
};
