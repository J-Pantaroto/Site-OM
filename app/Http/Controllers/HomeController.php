<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Grupo;
use App\Models\SubGrupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProdutoDisponivel;
class HomeController extends Controller
{
    public function index()
    {
        $exibirPreco    = config('config.config.exibir_preco') === 'S';
        $validarEstoque = config('config.config.validar_estoque') === 'S';
        $produtosQuery = Produto::query();
        if ($exibirPreco) {
            $produtosQuery->whereNotNull('preco')->where('preco', '>', 0);
        }

        $produtos = $produtosQuery->paginate(12);
        $gruposQuery = Grupo::query();
        $gruposQuery->whereExists(function ($query) use ($exibirPreco, $validarEstoque) {
            $query->select(DB::raw(1))
                ->from('produtos')
                ->whereColumn('produtos.grupo', 'grupos.codigo');

            if ($exibirPreco) {
                $query->whereNotNull('produtos.preco')->where('produtos.preco', '>', 0);
            }
        });


        $grupos = $gruposQuery->get();

        // Separação de grupos
        $gruposPrincipais = $grupos->take(15);
        $gruposRestantes  = $grupos->skip(15);

        $nenhumProdutoComPreco = $exibirPreco && $produtos->isEmpty();
        $isAdmin = Auth::check() && (Auth::user()->is_admin || Auth::user()->isSuperVisor());
        $mostrarBotaoVerMais  = $produtos->total() > $produtos->perPage();

        return view('home', compact(
            'gruposPrincipais',
            'gruposRestantes',
            'produtos',
            'nenhumProdutoComPreco',
            'isAdmin',
            'mostrarBotaoVerMais'
        ));
    }

    public function aviseMe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'produto_id' => 'required|exists:produtos,id'
        ]);

        // Salvar a solicitação de aviso
        $produto = Produto::find($request->produto_id);
        $email = $request->email;

        // Simulação do envio de e-mail quando o produto estiver disponível
        Mail::to($email)->send(new ProdutoDisponivel($produto));

        return response()->json(['status' => 'sucesso', 'mensagem' => 'Você será avisado quando o produto estiver disponível.']);
    }

    public function buscarSubgrupos(Request $request)
    {
        $grupo = $request->input('grupo');
        $exibirPreco = config('config.config.exibir_preco') === 'S';
        $validarEstoque = config('config.config.validar_estoque') === 'S';
        $subgrupos = Subgrupo::whereHas('produtos', function ($query) use ($grupo, $exibirPreco, $validarEstoque) {
            $query->where('grupo', $grupo);
            if ($exibirPreco) {
                $query->whereNotNull('preco')->where('preco', '>', 0);
            }
            if ($validarEstoque) {
                $query->whereNotNull('quantidade')->where('quantidade', '>', 0);
            }
        })->get(['codigo', 'descricao']);

        Log::info('Subgrupos retornados:', ['subgrupos' => $subgrupos]);

        return response()->json([
            'status' => 'sucesso',
            'subgrupos' => $subgrupos,
        ]);
    }
    public function buscarProduto(Request $request)
    {

        $exibirPreco = config('config.config.exibir_preco') === 'S';
        $validarEstoque = config('config.config.validar_estoque') === 'S';
        $exibirPreco = config('config.config.exibir_preco') === 'S';
        $pesquisa = $request->input('pesquisa');
        $grupo = $request->input('grupo');
        $subgrupo = $request->input('subgrupo');
        $limite = $request->input('limite', 12);
        $offset = $request->input('offset', 0);
        $escopo = $request->input('escopo');

        $query = Produto::query();


        if ($exibirPreco) {
            $query->whereNotNull('preco')->where('preco', '>', 0);
        }


        if ($grupo && $escopo != 'todos') {
            $query->where('grupo', $grupo);
        }
        if ($subgrupo && $escopo != 'todos') {
            $query->where('subgrupo', $subgrupo);
        }

        if ($pesquisa) {
            $query->where('nome', 'like', '%' . $pesquisa . '%');
        }

        $totalProdutos = $query->count();

        $produtos = $query->offset($offset)
            ->limit($limite)
            ->get()
            ->transform(function ($produto) {
                $produto->imagem = asset('storage/' . $produto->imagem);
                return $produto;
            });

        $subgrupos = [];
        if ($grupo) {
            $subgrupos = Subgrupo::whereHas('produtos', function ($query) use ($grupo, $exibirPreco, $validarEstoque) {
                $query->where('grupo', $grupo);
                if ($exibirPreco) {
                    $query->whereNotNull('preco')->where('preco', '>', 0);
                }

            })->get(['codigo', 'descricao']);
        }

        return response()->json([
            'status' => 'sucesso',
            'quantidade' => $produtos->count(),
            'totalProdutos' => $totalProdutos,
            'produtos' => $produtos,
            'subgrupos' => $subgrupos,
        ]);
    }

    public function adicionarProdutoCarrinho(Request $request)
    {
        $id = $request->input('produto_id');
        $nome = $request->input('nome');
        $preco = $request->input('preco');
    }
}
