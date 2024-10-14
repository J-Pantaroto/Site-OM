<?php
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VendaController;
use Illuminate\Support\Facades\Route;
Route::post('/buscar', [HomeController::class, 'buscarProduto']); //rota para fetch
Route::post('/limpar/carrinho', [CarrinhoController::class, 'limparCarrinho']);
Route::post('/remover/produto/carrinho', [CarrinhoController::class, 'removerProdutoCookie']);
Route::post('/atualizar/carrinho', [CarrinhoController::class, 'atualizarCarrinho']);
Route::post('/pesquisar/produtos', [ProdutoController::class, 'pesquisarProdutos'])->name('produtos.pes');
Route::get('/pesquisar/produto/{nome}', [ProdutoController::class, 'pesquisaProduto'])->name('produto/');
Route::post('/pesquisar/usuarios', [ProfileController::class, 'pesquisarUsuarios'])->name('usuarios.pes');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produtos', [ProdutoController::class, 'index'])->middleware(['auth', 'verified'])->name('produtos');
Route::get('/usuarios', [ProfileController::class, 'index'])->middleware(['auth', 'verified'])->name('usuarios');
Route::delete('/usuarios/{id}', [ProfileController::class, 'destroyUser'])->middleware(['auth', 'verified'])->name('usuarios.destroy');
Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy'])->middleware(['auth', 'verified'])->name('produtos.destroy');
Route::get('/produtos/{id}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit');
Route::put('/produtos/{id}', [ProdutoController::class, 'update'])->name('produtos.update');
Route::post('/registrar/venda', [VendaController::class, 'registrarVenda']);
Route::get('/dashboard', [VendaController::class, 'listarComprasCliente'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard'); */




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
