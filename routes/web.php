<?php
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
Route::post('/buscar', [HomeController::class, 'buscarProduto']); //rota para fetch
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produtos', [ProdutoController::class, 'index'])->middleware(['auth', 'verified'])->name('produtos');
Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');
Route::get('/produtos/{id}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit');
Route::put('/produtos/{id}', [ProdutoController::class, 'update'])->name('produtos.update');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
