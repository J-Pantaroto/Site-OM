<?php
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\ConfiguracoesController;
use App\Http\Controllers\CitiesStatesController;
use App\Http\Middleware\CheckAddressComplete;
use App\Http\Middleware\IsAdmin;

use Illuminate\Support\Facades\Route;
Route::post('/buscar', [HomeController::class, 'buscarProduto']); //rota para fetch
Route::post('/limpar/carrinho', [CarrinhoController::class, 'limparCarrinho']);
Route::post('/remover/produto/carrinho', [CarrinhoController::class, 'removerProdutoCookie']);
Route::post('/atualizar/carrinho', [CarrinhoController::class, 'atualizarCarrinho']);
Route::post('/pesquisar/produtos', [ProdutoController::class, 'pesquisarProdutos'])->name('produtos.pes') ->middleware(['auth', 'verified', 'admin']);
Route::get('/pesquisar/produto/{slug}', [ProdutoController::class, 'pesquisaProduto'])->name('produto/');
Route::post('/pesquisar/usuarios', [ProfileController::class, 'pesquisarUsuarios'])->name('usuarios.pes') ->middleware(['auth', 'verified', 'admin']);
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produtos', [ProdutoController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('produtos');

Route::get('/usuarios', [ProfileController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('usuarios');

Route::delete('usuarios/{id}', [ProfileController::class, 'destroyUser'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('usuarios.destroy');

Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy'])->middleware(['auth', 'verified', 'admin'])->name('produtos.destroy');
Route::get('/produtos/{id}/edit', [ProdutoController::class, 'edit'])->middleware(['auth', 'verified', 'admin'])->name('produtos.edit');
Route::put('/produtos/{id}', [ProdutoController::class, 'update'])->middleware(['auth', 'verified', 'admin'])->name('produtos.update');
Route::delete('/produtos/imagens/{imagem}', [ProdutoController::class, 'destroyImagem'])->middleware(['auth', 'verified', 'admin'])->name('produtos.imagens.destroy');

Route::get('/configuracoes', [ConfiguracoesController::class, 'index'])->middleware(['auth', 'verified', 'supervisor'])->name('configuracoes');
Route::get('configuracoes/{configuracao}/edit',[ConfiguracoesController::class, 'edit'])->middleware(['auth', 'verified', 'supervisor'])->name('configuracoes.edit');
Route::put('/configuracoes/{configuracao}', [ConfiguracoesController::class, 'update'])->middleware(['auth', 'verified', 'supervisor'])->name('configuracoes.update');


Route::post('/registrar/venda', [VendaController::class, 'registrarVenda'])
->middleware(['auth', 'verified','CheckAddress'])
->name('registrar.venda');
Route::get('/dashboard', [VendaController::class, 'listarComprasCliente'])
    ->middleware(['auth', 'verified','user.approved'])
    ->name('dashboard');


Route::middleware(['auth', 'user.approved'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/profile/{usuario}/definir', [ProfileController::class, 'editRole'])->name('profile.definir');
Route::put('/profile/{usuario}/role', [ProfileController::class, 'updateRole'])->name('profile.updateRole');


Route::get('/cities/{stateAbbreviation}', [CitiesStatesController::class, 'getCitiesByState'])->middleware(['auth', 'verified', 'user.approved']);

Route::delete('usuarios/{id}', [ProfileController::class, 'destroyUser'])
    ->middleware(['auth', 'verified', 'admin','user.approved'])
    ->name('usuarios.destroy');
require __DIR__ . '/auth.php';

Route::post('/buscar-subgrupos', [HomeController::class, 'buscarSubgrupos']);
Route::post('/buscar-produtos-por-subgrupo', [HomeController::class, 'buscarProdutosPorSubgrupo']);