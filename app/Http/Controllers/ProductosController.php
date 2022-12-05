<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Image;

use App\Models\Producto;

class ProductosController extends Controller
{
    public function index()
    {
        return view('productos');
    }

    public function all(Request $request)
    {
        $productos = \DB::table('productos')
                    ->select('productos.*')
                    ->orderBy('id','DESC')
                    ->get();

                    return response(json_encode($productos),200)->header('Content-type','text/plain');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nombre'=>'required|min:3|max:30',
            'img'=>'required|image|mimes:jpg,jpeg,png,gif,svg|max:2038',
            'stock'=>'required',
            'codigo'=>'required'
        ]);
        if($validator->fails()){
           return back()
           ->withInput()
           ->with('ErrorInsert', 'Favor de llenar todos los campos')
           ->withErrors($validator);
        }else{
            $imagen = $request -> file('img');
            $nombre = time().'.'.$imagen->getClientOriginalExtension();
            $destino = public_path('img/productos');
            $request -> img -> move($destino, $nombre);
            $red = Image::make($destino.'/'.$nombre);
            $red -> resize(200, null, function($constraint){
                $constraint->aspectRatio();
            });
            $red -> save($destino.'/thumbs/'.$nombre);
            $marca_agua = Image::make($destino.'/'.$nombre);
            $logo = Image::make(public_path('img/Shoppingp.png'));
            $marca_agua -> insert($logo, 'bottom-right', 10,10);
            $marca_agua -> save();
            $producto = Producto::create([
                'nombre'=>$request->nombre,
                'img'=>$nombre,
                'stock'=>$request->stock,
                'codigo'=>$request->codigo
            ]);
            return back()->with('Listo', 'Se ha insertado correctamente');
        }
    }
}
