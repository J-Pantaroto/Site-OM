namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieController extends Controller
{
    public function criarCookie()
    {
        $cookie = cookie('meu_cookie', 'meu_valor', 60);
        return response('Cookie criado!')->cookie($cookie);
    }

    public function lerCookie(Request $request)
    {
        $valor = $request->cookie('meu_cookie');
        return response("Valor do cookie: $valor");
    }

    public function excluirCookie()
    {
        return response('Cookie excluído!')->withCookie(Cookie::forget('meu_cookie'));
    }
}
