<?php

namespace App\Http\Controllers;

use App\DTO\PaginacionDTO;
use App\DTO\Pasantia\PasantiaDetalleDTO;
use App\DTO\Pasantia\PasantiaListadoDTO;
use App\Enums\PasantiaEstadoEnum;
use App\Exceptions\CustomException;
use App\Models\Pasantia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PasantiaController extends Controller
{
    
    public function postPasantia(Request $req){
        $data = $req->validate([
            'idPasantia'=>'nullable|exists:pasantias,id',
            'titulo'=>'required|min:5|max:40',
            'desc'=>'nullable|min:50|max:2000',
            'fechaInicio'=>'nullable|date',
            'fechaFinal'=>'nullable|date|after:fechaInicio',
            'idEmpresa'=>'required|exists:empresas,id',
            'usuarios'=>'array',
            'usuarios.*.id'=>'required|exists:usuarios,id',
            'usuarios.*.nota'=>'nullable|numeric|min:0|max:10',
            'estado'=>[
                'nullable',
                Rule::in(array_column(PasantiaEstadoEnum::cases(), 'value'))
            ]
            
        ],[
            'idPasantia.exists'=>'La pasantía no existe',
            'titulo.required'=>'El título es requerido',
            'titulo.min'=>'El título debe tener al menos :min caracteres',
            'titulo.max'=>'El título no puede tener más de :max caracteres',
            'desc.min'=>'La descripción debe tener al menos :min caracteres',
            'desc.max'=>'La descripción no puede tener más de :max caracteres',
            'fechaInicio.date'=>'La fecha de inicio no es válida',
            'fechaFinal.date'=>'La fecha final no es válida',
            'fechaFinal.after'=>'La fecha final debe ser posterior a la fecha de inicio',
            'idEmpresa.required'=>'El id de la empresa es requerido',
            'idEmpresa.exists'=>'La empresa no existe',
            'usuarios.array'=>'Los usuarios deben ser un arreglo',
            'usuarios.*.id.required'=>'El id del usuario es requerido',
            'usuarios.*.id.exists'=>'El usuario no existe',
            'usuarios.*.nota.numeric'=>'La nota debe ser un número',
            'usuarios.*.nota.min'=>'La nota debe ser mayor o igual a 0',
            'usuarios.*.nota.max'=>'La nota debe ser menor o igual a 10',
            'estado.required'=>'El estado es requerido',
            'estado.in'=>'El estado no es válido. Los valores válidos son: '.implode(', ',array_column(PasantiaEstadoEnum::cases(), 'value'))
        ]);

        $pasantia = null;;


        if(isset($data['idPasantia'])){
            $pasantia = Pasantia::find($data['idPasantia']);

            if($pasantia->estado != PasantiaEstadoEnum::SOLICITADA->value && !auth()->user()->isAdmin()){
                throw new CustomException('Una vez que la pasantia fue aprobada o rechazada no se puede editar.', 403);
            }

            $pasantia->update([
                'titulo'=>$data['titulo'],
                'desc'=>$data['desc'] ?? null,
                'fecha_inicio'=>$data['fechaInicio'],
                'fecha_final'=>$data['fechaFinal'],
                'empresa_id'=>$data['idEmpresa'],
            ]);
        }else{
            $pasantia = Pasantia::create([
                'titulo'=>$data['titulo'],
                'desc'=>$data['desc'],
                'fecha_inicio'=>$data['fechaInicio'],
                'fecha_final'=>$data['fechaFinal'],
                'empresa_id'=>$data['idEmpresa'],
                'estado'=> auth()->user()->isAdmin() ? PasantiaEstadoEnum::APROBADA->value : PasantiaEstadoEnum::SOLICITADA->value,
            ]);
        }

        $usuariosCollection = collect($data['usuarios'] ?? [])->mapWithKeys(function ($usuario) {
            return [
                $usuario['id'] => [  
                    'nota' => $usuario['nota'] ?? null,  
                ]
            ];
        });

        
        $pasantia->usuarios()->sync($usuariosCollection);
    }


    public function responderPasantia(Request $req, $id){
        $data = $req->validate([
            'accion'=>'required|in:APROBAR,RECHAZAR'
        ],[
            'accion.required'=>'La acción es requerida.',
            'accion.in'=> 'Los valores validos para la acción son APROBAR y RECHAZAR.'
        ]);

        $pasantia = Pasantia::find($id);
        if($pasantia){
            if($data['accion'] == 'APROBAR'){
                if(!$pasantia->isComplete()){
                    throw new CustomException('No se puede aprobar la pasantía por que le faltan datos que cargar.' , 400);
                }
                $pasantia->update([
                    'estado'=> PasantiaEstadoEnum::APROBADA->value,
                ]);
            }

            if($data['accion'] == 'RECHAZAR'){
                $pasantia->update([
                    'estado'=> PasantiaEstadoEnum::RECHAZADA->value
                ]);
            }
        }


        return response()->noContent();
    }


    public function deletePasantia(Request $req){
        $req->merge(['id'=> $req->route('id')]);
        $data = $req->validate([
            'id'=>'required|exists:pasantias,id'
        ],[
            'id.required'=>'El id de la pasantía es requerido.',
            'id.exists'=>'No se encontro la pasantía en la base de datos.'
        ]);

        Pasantia::where('id',$data['id'])->first()->delete();

        return response()->json('La pasantía se elimino con éxito');
    }


    public function listarPasantias(Request $req){
        $query = Pasantia::query();
        $size = $req->input('size', 10);
        $page = $req->input('page', 1);
        

        

        $authUser = auth()->user();

        if(!$authUser->isAdmin()){
            $query->whereHas('usuarios', function($userQuery) use ($authUser){
                $userQuery->where('usuarios.id', $authUser->id)
                    ->whereNotNull('pasantias_usuarios.nota');
            });
        }

        


        if ($req->has('fecha')) {
            $fechas = explode('|', $req->fecha);
            $fechaInicio = $fechas[0] ?? null;
            $fechaFinal = $fechas[1] ?? null;
    
            if ($fechaInicio && $fechaFinal) {
                $query->whereBetween('fecha_inicio', [$fechaInicio, $fechaFinal])
                    ->orWhereBetween('fecha_final', [$fechaInicio, $fechaFinal]);
            } elseif ($fechaFinal) {
                $query->where('fecha_inicio', '<=', $fechaFinal)
                    ->orWhere('fecha_final', '<=', $fechaFinal);
            } elseif ($fechaInicio) {
                $query->where('fecha_inicio', '>=', $fechaInicio)
                    ->orWhere('fecha_final', '>=', $fechaInicio);
            }
        }

        if($req->has('estado')){
            $estado = $req->estado;

            if($estado == 'ACTUAL'){
                $query->where('fecha_inicio', '<=', now())
                    // ->where('fecha_final', '>=', now())
                    ->where('estado', PasantiaEstadoEnum::APROBADA->value)
                    ->whereHas('usuarios', function($userQuery){
                        $userQuery->whereNull('pasantias_usuarios.nota');
                    });
            }elseif($estado == 'FINALIZADA'){
                $query
                    ->where('estado', PasantiaEstadoEnum::APROBADA->value)
                    ->whereHas('usuarios', function($userQuery){
                        $userQuery->whereNotNull('pasantias_usuarios.nota');
                    });
            }
            else{
                $query->where('estado', $req->estado);
            }
        }


        if($req->has('usuario')){
            $usuario = $req->get('usuario');
            $query->whereHas('usuarios', function($subq) use ($usuario){
                $subq->where(function($q) use ($usuario) {
                $q->where('nombre', 'like', "%$usuario%")
                  ->orWhere('apellido', 'like', "%$usuario%")
                  ->orWhere('correo', 'like', "%$usuario%")
                  ->orWhere('dni', 'like', "%$usuario%");
            });
            });
        }
        if($req->has('empresa')){
            $empresa = $req->get('empresa');
            $query->whereHas('empresa', function($subq) use ($empresa){
                $subq->where('nombre', 'like', "%$empresa%")
                    ->orWhere('cuil_cuit', 'like', "%$empresa%");
            });
        }


        if($req->has('pendiente') && $req->pendiente){
            $query->whereNull('fecha_inicio')
                ->where('estado', PasantiaEstadoEnum::APROBADA->value)
                ->orWhereDoesntHave('usuarios');
                // ->orWhereHas('usuarios', function($userQuery) {
                //     $userQuery->whereNull('pasantias_usuarios.nota');
                // });;
        }else{
            $query
                ->whereHas('usuarios');
        }

        $query->orderBy('id', 'desc');


        $pasantias = $query->paginate($size, ['*'],'page', $page);
        $pasantias->getCollection()->transform(function ($pasantia) use ($authUser) {
            return new PasantiaListadoDTO($pasantia, $authUser->isAdmin() ? null : $authUser->id);
        });
        
        // Modificar la respuesta para usar PaginacionDTO
        $pasantiasDTO = $pasantias->getCollection()->toArray();
        
        return response()->json(new PaginacionDTO(
            $pasantiasDTO,
            $size,
            $page,
            $pasantias->lastPage(),
            $pasantias->total()
        ));
    }


    public function getPasantia(Request $req){
        $req->merge(['id'=> $req->route('id')]);
        $data = $req->validate([
            'id'=>'required|exists:pasantias,id',
        ],[
            'id.required'=>'El id de la pasantía es requerido.',
            'id.exists'=>'La pasantía no se encontro en la base de datos.'
        ]);

        $pasantia = Pasantia::find($data['id']);

        return response()->json(new PasantiaDetalleDTO($pasantia));
    }
}
