<?php
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\LocationController;

    Route::get('/lokasi', [LocationController::class, 'api']);
