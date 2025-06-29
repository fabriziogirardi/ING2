<?php

use App\Facades\GoogleMaps;
use App\Http\Controllers\BinanceController;
use App\Http\Controllers\Catalog\CatalogController;
use App\Http\Controllers\Customer\Auth\LoginController as CustomerLoginController;
use App\Http\Controllers\Customer\RecoverPasswordController;
use App\Http\Controllers\Customer\ResetPasswordController;
use App\Http\Controllers\Employee\Auth\LoginController as EmployeeLoginController;
use App\Http\Controllers\Employee\RegisterCustomer;
use App\Http\Controllers\Employee\RetiredReservationController;
use App\Http\Controllers\Forum\ForumController;
use App\Http\Controllers\Forum\ForumDiscussionController;
use App\Http\Controllers\Forum\ForumReplyController;
use App\Http\Controllers\Manager\Auth\LoginController as ManagerLoginController;
use App\Http\Controllers\Manager\Branches\BranchController;
use App\Http\Controllers\Manager\Brand\BrandController;
use App\Http\Controllers\Manager\Employee\EmployeeController;
use App\Http\Controllers\Manager\ForumSections\ForumSectionController;
use App\Http\Controllers\Manager\Model\ModelController;
use App\Http\Controllers\Manager\Product\ProductController;
use App\Http\Controllers\Payment\MercadoPagoController;
use App\Http\Controllers\Reservation\ReservationController;
use App\Http\Controllers\Wishlist\WishlistController;
use App\Http\Controllers\Wishlist\WishlistProductController;
use App\Http\Controllers\Wishlist\WishlistSublistController;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductAvailabilityService;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return view('components.navigation.landing')->with([
        'branches' => Branch::all(),
    ]);
})->name('home');

Route::get('/la', function () {
    $service  = new ProductAvailabilityService('2025-06-15', '2025-06-20');
    $products = $service->getProductsWithAvailability();

    foreach ($products as $entry) {
        echo 'Producto: '.$entry['product']->name.'<br>';
        echo '¿Disponible?: '.($entry['has_stock'] ? '✅' : '❌').'<br>';

        foreach ($entry['branches_with_stock'] as $branch) {
            echo " - Sucursal con stock: {$branch['branch_name']}, disponible: {$branch['available']}".'<br>';
        }

        foreach ($entry['branches_without_stock'] as $branch) {
            echo " - Sucursal sin stock: {$branch['branch_name']}".'<br>';
        }

        echo '<br>';
    }
});

Route::get('/newcatalog', function () {
    return view('catalog.index', ['products' => \App\Models\Product::all()]);
})->name('catalog.new');

Route::middleware('auth:customer')->group(function () {
    Route::get('/a/a', static function () {
        return 'hola a';
    })->name('a.a');
    Route::get('/a/d', static function () {
        return 'hola d';
    })->name('a.d');

    Route::get('/a/c', static function () {
        Auth::guard('customer')->logout();

        return 'Hola c ';
    })->name('a.c');
});

Route::get('/cat', static function () {
    dd(Category::query()->leaves()->toArray());
});

Route::get('/a/login', static function () {
    $res = Auth::guard('customer')->attempt(['email' => 'gerard33@example.net', 'password' => 'password']);

    return 'Hola login '.Auth::guard('customer')->user()->person->name;
})->name('a.login');

Route::get('/a/test', static function () {
    $a = Auth::guard('customer')->check();
    $b = Auth::guard('manager')->check();
    $c = Auth::guard('employee')->check();

    return 'Hola test';
});

Route::get('/map', static function () {
    $data       = GoogleMaps::searchGoogleMapsAutocomplete('16 1428');
    $details    = GoogleMaps::searchGoogleMapsPlaceDetails($data['predictions'][0]['place_id']);
    $components = GoogleMaps::getAddressCoordinates($details);

    dd($data, $details, $components);
});

Route::get('cat/{cat:slug}', static function (Category $cat) {
    dd(Product::get_all_by_category($cat)->get()->toArray());
})->name('category.index');

Route::get('/manager/login', [ManagerLoginController::class, 'showLoginForm'])
    ->name('filament.manager.auth.login')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);

// region Rutas del manager
Route::group(['prefix' => 'manager', 'as' => 'manager.'], static function () {
    Route::get('/loginForm', [ManagerLoginController::class, 'showLoginForm'])
        ->name('login')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);
    Route::post('/login', [ManagerLoginController::class, 'loginAttempt'])
        ->name('login.post')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);

    Route::get('/verify-token/{manager}', [ManagerLoginController::class, 'showTokenForm'])
        ->name('verify-token')->middleware(['guest:customer', 'guest:employee', 'guest:manager', 'signed']);
    Route::post('/verify-token/{manager}', [ManagerLoginController::class, 'tokenAttempt'])
        ->name('verify-token.post')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);

    Route::group(['middleware' => 'auth:manager'], static function () {
        Route::get('/dashboard', static function () {
            return view('manager.dashboard');
        })->name('dashboard');

        Route::get('/logout', [ManagerLoginController::class, 'logout'])->name('logout');

        // Esta ruta no va acá
        // Route::get('/viewBranches', [BranchesListing::class, '__invoke'])->name('branches.index');
        Route::resource('employee', EmployeeController::class);
        Route::post('employee/{id}/restore', [EmployeeController::class, 'restore'])->name('employee.restore');
        Route::resource('brand', BrandController::class);
        Route::get('brand/{id}/models', [BrandController::class, 'restore'])->name('brand.restore');
        Route::resource('model', ModelController::class);
        Route::get('model/{id}/restore', [ModelController::class, 'restore'])->name('model.restore');
        Route::resource('branch', BranchController::class);
        Route::resource('product', ProductController::class);
        Route::resource('sections', ForumSectionController::class);
        Route::post('sections/{id}/restore', [ForumSectionController::class, 'restore'])->name('sections.restore');
    });
});
// endregion

// region Rutas del empleado
Route::group(['prefix' => 'employee', 'as' => 'employee.'], static function () {
    Route::get('/login', [EmployeeLoginController::class, 'showLoginForm'])
        ->name('login')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);
    Route::post('/login', [EmployeeLoginController::class, 'loginAttempt'])
        ->name('login.post')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);

    Route::group(['middleware' => 'auth:employee'], static function () {
        Route::get('/logout', [EmployeeLoginController::class, 'logout'])->name('logout');

        Route::get('/reservation/retire', [RetiredReservationController::class, 'show'])
            ->name('reservation.retire');

        Route::post('/reservation/retire', [RetiredReservationController::class, 'store'])
            ->name('reservation.retire.post');

        Route::get('/customer', [RegisterCustomer::class, 'create'])->name('register_customer');
        Route::post('/customer', [RegisterCustomer::class, 'store']);

        Route::get('/payment', [BinanceController::class, 'showPaymentForm'])->name('payment');
        Route::get('/payment/confirm', [BinanceController::class, 'confirmPayment'])->name('payment_confirm');

    });
});
// endregion

// region Rutas del cliente
Route::group(['prefix' => 'customer', 'as' => 'customer.'], static function () {
    Route::get('/login', [CustomerLoginController::class, 'showLoginForm'])
        ->name('login')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);
    Route::post('/login', [CustomerLoginController::class, 'loginAttempt'])
        ->name('login.post')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);
    Route::get('/recover-password', [RecoverPasswordController::class, 'show'])
        ->name('recover-password')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);
    Route::post('/recover-password', [RecoverPasswordController::class, 'store'])
        ->name('recover-password.post')->middleware(['guest:customer', 'guest:employee', 'guest:manager']);

    Route::group(['middleware' => 'auth:customer'], static function () {
        Route::get('/payment/test', function () {
            return view('payment.test-payment');
        });

        Route::get('/reset-password', [ResetPasswordController::class, 'show'])
            ->name('password.reset');

        Route::post('/reset-password', [ResetPasswordController::class, 'store'])
            ->name('password.reset.post');

        Route::get('/payment', [MercadoPagoController::class, 'show'])->name('payment');

        Route::resource('reservation', ReservationController::class)->except(['store']);

        Route::get('/reservations/success/{branch_product_id}/{customer_id}/{start_date}/{end_date}/{code}/{total_amount}', [ReservationController::class, 'store'])
            ->name('reservation.store');

        Route::get('/reservations/failure', function (Request $request) {
            return view('payment.failure');
        })->name('reservations.failure');

        // Wishlist Routes
        Route::resource('wishlist', WishlistController::class);
        Route::resource('wishlist-product', WishlistProductController::class)->except(['index']);
        Route::get('/wishlist/{wishlist}', [WishlistProductController::class, 'index'])->name('productslist');
        Route::post('/wishlist-product', [WishlistProductController::class, 'store'])
            ->name('wishlist-product.store');

        Route::get('/logout', [CustomerLoginController::class, 'logout'])->name('logout');
        Route::view('/list-reservations', 'customer.list-reservations')->name('list-reservations');
    });
});

// endregion

// region Catálogo
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{product}', [CatalogController::class, 'show'])->name('catalog.show');
// endregion

// region Foro
Route::group(['middleware' => 'auth:customer,employee,manager'], static function () {
    Route::group(['prefix' => 'forum', 'as' => 'forum.'], static function () {
        Route::get('/', [ForumController::class, 'index'])->name('index');

        Route::resource('discussions', ForumDiscussionController::class)->except(['index']);

        Route::resource('reply', ForumReplyController::class)->except(['index', 'show']);
    });
});
// endregion
