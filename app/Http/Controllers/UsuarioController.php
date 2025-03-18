<?php

namespace App\Http\Controllers;

use App\DTO\Contacto\ContactoRespuestaDTO;
use App\DTO\ExperienciaLaboral\ExperienciaLaboralRespuestaDTO;
use App\DTO\Habilidad\HabilidadRespuestaDTO;
use App\DTO\PaginacionDTO;
use App\DTO\PerfilProfesional\PerfilProfesionalRespuestaDTO;
use App\DTO\Titulo\TituloRespuestaDTO;
use App\DTO\Ubicacion\UbicacionRespuestaDTO;
use App\DTO\Usuario\UsuarioDetalleDTO;
use App\DTO\Usuario\UsuarioListadoDTO;
use App\DTO\Usuario\UsuarioRespuestaDTO;
use App\Enums\AccionCrudEnum;
use App\Enums\CategoriaLicenciaConducirEnum;
use App\Enums\EstadoUsuarioEnum;
use App\Enums\RolEnum;
use App\Exceptions\CustomException;
use App\Http\Requests\Contacto\ContactoRequest;
use App\Http\Requests\ExperienciaLaboral\ExpLaboralRegistrarRequest;
use App\Http\Requests\Habilidad\HabilidadRegistrarRequest;
use App\Http\Requests\Titulos\TituloRegistrarRequest;
use App\Http\Requests\UbicacionRequest;
use App\Http\Requests\Usuario\RegistrarUsuarioRequest;
use App\Http\Requests\Usuario\UsuarioActualizarRequest;
use App\Http\Requests\Usuario\UsuarioCompletarRequest;
use App\Http\Requests\Usuario\UsuarioCVRequest;
use App\Http\Requests\Usuario\UsuarioDetalleRequest;
use App\Http\Requests\Usuario\UsuarioImagenRequest;
use App\Http\Requests\Usuario\UsuarioLoginRequest;
use App\Http\Requests\Usuario\UsuarioPerfilProfesionalRequest;
use App\Models\Empresa;
use App\Models\ExperienciaLaboral;
use App\Models\Usuario;
use App\Services\UsuarioService;
use Auth;
use Cache;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\Image;

class UsuarioController extends Controller
{
    protected $usuarioService;
    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }


    public function registrarUsuario(RegistrarUsuarioRequest $request)
    {
        $admin = null;
        if (auth()->check()) {
            $admin = auth()->user();
        }
        $usuario = $this->usuarioService->registrar($request, $admin);
        return response()->json(new UsuarioRespuestaDTO($usuario['usuario'], $usuario['token']));
    }

    public function putUsuario(UsuarioActualizarRequest $req)
    {
        $data = $req->validated();
        $id = $req->route('id');
        $usuario = $this->usuarioService->actualizarUsuario($id, $data);

        return response()->json(new UsuarioRespuestaDTO($usuario));
    }

    public function obtenerUsuario($id)
    {
        // if (!auth()->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        $usuario = $this->usuarioService->obtenerById($id);

        if (!$usuario) {
            throw new CustomException('Usuario no encontrado en la base de datos', 404);
        }

        return response()->json(new UsuarioRespuestaDTO($usuario));
    }


    public function restorePassword(Request $req, $id=null){
        if($id){
            $usuario = Usuario::withTrashed()->find($id);
            if($usuario){
                $usuario->update([
                    'clave'=> Hash::make(($usuario->dni ?? '123456789'))
                ]);
                return response()->noContent();
            }
        }

        throw new CustomException('No se pudo restablecer la clave.', 400);
    }

    public function changePassword(Request $req, $id = null){
        $data = $req->validate([
            'claveActual'=>'required',
            'clave'=>'required|min:8|max:20|regex:/^[a-zA-ZñÑ\-_0-9]+$/'
        ], [
            'claveActual.required' => 'La clave actual es requerida',
            'clave.required' => 'La clave es requerida',
            'clave.min' => 'La clave debe tener al menos :min caracteres',
            'clave.max' => 'La clave debe tener hasta :max caracteres',
            'clave.regex' => 'La clave puede tener letras, números y estos caracteres: - _',
        ]);





        if($id){

            if($id != auth()->user()->id){
                throw new CustomException('No puedes cambiar la clave de otro usuario.', 403);
            }

            
            $usuario = Usuario::withTrashed()->find($id);
            if($usuario){
                if(!Hash::check($data['claveActual'], $usuario->clave)){
                    throw new CustomException('La clave actual no coincide.', 403);
                }
                if($data['claveActual'] == $data['clave']){
                    throw new CustomException('La clave debe ser diferente a la actual.', 400);
                }

                $usuario->update([
                    'clave'=> Hash::make($data['clave']),
                ]);
                return response()->noContent();
            }
        }

        throw new CustomException('No se pudo cambiar la clave.', 400);
    }


    public function listarUsuarios(Request $req){
        $page = $req->get('page', 1);
        $size = $req->get('size', 15);

        $query = Usuario::query();

        if (!is_numeric($page) || !is_numeric($size)) {
            throw new CustomException('Los parametros deben ser númericos', 400);
        }
        $size = (int) $size;
        if ($size < 5 || $size > 15) {
            throw new CustomException('El parametro "size" debe ser entre 5 y 15', 400);
        }
        $page = (int) $page;
        if ($page <= 0) {
            throw new CustomException('el parametro "page" debe ser mayor a 0', 400);
        }

        if($req->has('nombre')){
            $nombre = $req->get('nombre');
            $query->where(function($q) use ($nombre) {
                $q->where('nombre', 'like', "%$nombre%")
                  ->orWhere('apellido', 'like', "%$nombre%")
                  ->orWhere('id', $nombre);
            });
        }
        if($req->has('dni')){
            $dni = $req->get('dni');
            $query->where(function($q) use ($dni) {
                $q->where('dni', 'like', "%$dni%");
            });
        }
        if($req->has('cargo')){
            $cargo = $req->get('cargo');

            $query->whereHas('experienciasLaborales', function($exp) use($cargo){
                $exp->where('puesto', 'like', "%$cargo%");
            })
                ->orWhereHas('perfilProfesional', function($perfil) use($cargo){
                    $perfil->where('cargo', 'like', "%$cargo%");
                });
        }
        if($req->has('educacion')){
            $educacion = $req->get('educacion');

            $query->whereHas('tituloDetalles.titulo', function($tit) use($educacion){
                $tit->where('nombre', 'like', "%$educacion%");
            });
        }
        if($req->has('habilidad')){
            $habilidad = $req->get('habilidad');
            $query->whereHas('habilidades', function($hab) use($habilidad){
                $hab->where('nombre', 'like', "%$habilidad%");
            });
        }
        if ($req->has('edad')) {
            $edad = $req->get('edad');
            $rangos = explode('-', $edad);
            $hoy = now(); // Fecha actual
        
            if (count($rangos) == 2) {
                $desde = trim($rangos[0]) !== '' ? intval($rangos[0]) : null;
                $hasta = trim($rangos[1]) !== '' ? intval($rangos[1]) : null;
        
                if ($desde !== null && $hasta !== null) {
                    // Filtrar entre ambas edades
                    $query->whereRaw("TIMESTAMPDIFF(YEAR, fecha_nacimiento, ?) BETWEEN ? AND ?", [$hoy, $desde, $hasta]);
                } elseif ($desde !== null) {
                    // Filtrar desde una edad específica
                    $query->whereRaw("TIMESTAMPDIFF(YEAR, fecha_nacimiento, ?) >= ?", [$hoy, $desde]);
                } elseif ($hasta !== null) {
                    // Filtrar hasta una edad específica
                    $query->whereRaw("TIMESTAMPDIFF(YEAR, fecha_nacimiento, ?) <= ?", [$hoy, $hasta]);
                }
            } else {
                // Caso en que solo venga una edad sin guión
                $edadExacta = intval($edad);
                $query->whereRaw("TIMESTAMPDIFF(YEAR, fecha_nacimiento, ?) = ?", [$hoy, $edadExacta]);
            }
        }
        if($req->has('licencia')){
            $lic = $req->get('licencia');
            if($lic == 'SI'){
                $query->whereHas('licenciaConducir');
            }else{
                $query->whereDoesntHave('licenciaConducir');
            }
        }
        if($req->has('correo')){
            $correo = $req->get('correo');
            $query->where('correo', 'like', "%$correo%");
        }
        if ($req->has('estado')) {
            $estado = explode(',', $req->get('estado')); 

            if(in_array('BAJA', $estado)){
                $query->withTrashed();
            }
            
            $query->whereIn('estado', $estado); 
        }else{
            $query->where('estado', '!=', 'BAJA')
                ->where('estado', '!=', 'BLOQUEADO')
                ->where('estado', '!=', 'SOLICITADO');
        }
        
        if ($req->has('rol')) {
            $roles = explode(',', $req->get('rol')); 
        
            $validRoles = array_map(fn($role) => $role->value, RolEnum::cases());
            $invalidRoles = array_diff($roles, $validRoles);
        
            if (!empty($invalidRoles)) {
                $validRolesList = implode(', ', $validRoles);
                throw new CustomException('Los roles enviados no son válidos. Los roles válidos son: ' . $validRolesList, 400);
            }
        
            $query->whereHas('rol', function($q) use ($roles) {
                $q->whereIn('nombre', $roles); 
            });
        }
        // Excluir siempre a los usuarios DEV
        $query->whereHas('rol', function ($q) {
            $q->where('nombre', '!=', 'DEV');
        });
        
        // dd($query->get());

        $orden = $req->get('order', 'ASCENDENTE');
        $orderBy = $req->get('orderBy', 'APELLIDO');

        if($orderBy == 'NOMBRE'){
            $orderBy = 'APELLIDO';
        }

        if ($orderBy == 'EDAD') {
            $query->orderByRaw("TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) " . ($orden == 'ASCENDENTE' ? 'ASC' : 'DESC'));
        } else {
            $query->orderBy($orderBy, $orden == 'ASCENDENTE' ? 'asc' : 'desc');
        }
        

        $usuarios = $query->paginate($size, ['*'],'page', $page);



        $usuariosDTO = [];
        foreach ($usuarios->items() as $usuario) {
            $usuariosDTO[] = new UsuarioListadoDTO($usuario, $this->usuarioService->calcularPerfilCompletado($usuario));
        }


        $solicitudUsuarios = Usuario::where('estado', EstadoUsuarioEnum::SOLICITADO->value)->count();

        return response()->json(new PaginacionDTO(
            $usuariosDTO,
            $size,
            $page,
            $usuarios->lastPage(),
            $usuarios->total(),
            [
                'solicitudUsuarios'=> $solicitudUsuarios
            ]
        ));
    }


    public function loginUsuario(UsuarioLoginRequest $r)
    {
        $data = $r->validated();

        $loginData = $this->usuarioService->login($data['username'], $data['clave']);

        return response()->json(new UsuarioRespuestaDTO($loginData['usuario'], $loginData['token']));
    }

    public function completarDatos(UsuarioCompletarRequest $r)
    {
        $data = $r->validated();


    }

    public function postUbicacion(UbicacionRequest $r)
    {
        $id = $r->route('id');
        $data = $r->validated();
        $direccion = $this->usuarioService->cargarUbicacion($id, $data);

        return response()->json(new UbicacionRespuestaDTO($direccion));
    }

    public function postPerfilProfesional(UsuarioPerfilProfesionalRequest $req)
    {
        $data = $req->validated();
        $id = $req->route('id');
        $perfil = $this->usuarioService->cargarPerfilProfesional($id, $data['perfilProfesional']);

        return response()->json(new PerfilProfesionalRespuestaDTO($perfil));
    }

    public function getRoles()
    {
        $roles = array_filter(
            RolEnum::cases(),
            fn($role) => $role->value !== "DEV"
        );

        return response()->json([
            "roles" => array_column($roles, 'value')
        ]);
    }
    
    public function postContacto(ContactoRequest $req){
        $data = $req->validated();
        $idUsuario = $req->route('id');
        $contacto = $this->usuarioService->cargarContacto($data, $idUsuario);

        return response()->json(new ContactoRespuestaDTO($contacto));
    }

    public function postHabilidades(HabilidadRegistrarRequest $req){
        $idUsuario = $req->route('id');
        $data = $req->validated();
        $habilidades = $this->usuarioService->cargarHabilidades($idUsuario, $data);

        return response()->json([
            'habilidades' => array_map(function($habilidad){
                return new HabilidadRespuestaDTO($habilidad);
            }, $habilidades)
        ]);
    
    }
    

    public function getDetalleUsuario(UsuarioDetalleRequest $request){
        $authUser = auth()->user();
        $id = $request->validated()['id'];
        $usuario = $this->usuarioService->obtenerById($id);

        if($authUser->isAlumn() && $id != $authUser->id){
            if($authUser->estado == EstadoUsuarioEnum::PRIVADO->value){
                throw new CustomException('Para ver otros perfiles debes tener tu perfil público.', 403);
            }
            if($usuario->estado != EstadoUsuarioEnum::PUBLICO->value){
                throw new CustomException('El perfil del usuario no es público.', 403);
            }
        }

        return response()->json(new UsuarioDetalleDTO($usuario));
    }

    public function getPerfilCompletado(Request $req){
        $idUsuario = $req->route('id');
        $data = $this->usuarioService->calcularPerfilCompletado($idUsuario);
        return response()->json($data);
    }


    public function postPerfilImage(UsuarioImagenRequest $req){
        $data = $req->validated();
        // return response()->json(storage_path());
        return response()->json($this->usuarioService->postPerfilImagen($req));
    }

    public function getFotoPerfil(Request $req){
        $imageName = $req->route('image');
        $imagen = $this->usuarioService->getFotoPerfil($imageName);
        return $imagen;
    }

    public function postCV(UsuarioCVRequest $req){
        $req->validated();
        $fileName = $this->usuarioService->postCV($req);

        return response()->json($fileName);
    }

    public function getCV(Request $req){
        $imageName = $req->route('cv');
        $imagen = $this->usuarioService->getCV($imageName);
        return $imagen;
    }


    public function postEducacion(TituloRegistrarRequest $req){
        $data=$req->validated();
        $tituloDetalles = $this->usuarioService->cargarEducacion($data);

        return response()->json([
            'titulos' => $tituloDetalles->map(fn($detalle) => new TituloRespuestaDTO($detalle))
        ]);
    }

    public function postExperienciaLaboral(ExpLaboralRegistrarRequest $req){
        $data = $req->validated();
        
        $expLaborales = $this->usuarioService->cargarExperienciaLaboral($data);

        return response()->json([
            'experienciasLaborales'=> $expLaborales->map(function($exp){
                return new ExperienciaLaboralRespuestaDTO($exp);
            })
        ]);
    }



    public function postLicenciaConducir(Request $req){
        $idUser = $req->route('id');
        $data = $req->validate([
            'categoria'=> [
                Rule::in(array_merge(array_column(CategoriaLicenciaConducirEnum::cases(), 'value'), ['']))
            ],
            'vehiculoPropio'=> 'nullable|boolean',
            'id'=>'nullable|exists:licencias_conducir,id'
        ],[
            'categoria.required'=>'La categoría es requerida.',
            'categoria.in'=>"La categoría no es valida.",
            'vehiculoPropio.boolean'=>'El dato de vehículo propio debe ser booleano.',
            'id.exists'=>'No se encontro la licencia en la base de datos.'
        ]);


        $lic = $this->usuarioService->cargarLicenciaConducir($data, $idUser);


        return response()->json($lic);
    }


    public function postEstadoUsuario(Request $req, $id){
        $data = $req->validate([
            'accion'=>'required|in:BLOQUEAR,BAJA,ALTA,RECHAZAR',
        ],[
            'accion.required'=>'La acción es requerida.',
            'accion.in'=>'La acción es solo acepta BLOQUEAR, ALTA, BAJA o RECHAZAR.',
        ]);


        if($id == auth()->user()->id){
            throw new CustomException('No puedes cambiar el estado de tu cuenta desde tu cuenta.', 403);
        }

        $usuario = Usuario::withTrashed()->find($id);

        
        if($usuario){
            if($data['accion'] == 'BLOQUEAR'){
                $usuario->update([
                    'estado'=> EstadoUsuarioEnum::BLOQUEADO->value,
                    'deleted_at'=>null
                ]);
            }
            if($data['accion'] == 'BAJA'){
                $usuario->update([
                    'estado'=> EstadoUsuarioEnum::BAJA->value,
                    'deleted_at'=> now()
                ]);
            }
            if($data['accion'] == 'ALTA'){
                $usuario->update([
                    'estado'=> EstadoUsuarioEnum::ALTA->value,
                    'deleted_at'=>null
                ]);
            }
            if($data['accion'] == 'RECHAZAR'){
                $usuario->update([
                    'estado'=> EstadoUsuarioEnum::RECHAZADO->value,
                    'deleted_at'=>null
                ]);
            }
        }

        return response()->noContent();
    }


    public function postEstadoPrivacidad(){
        $user = auth()->user();
        $estado = $user->estado;

        if($estado == EstadoUsuarioEnum::PRIVADO->value){
            $completado = $this->usuarioService->calcularPerfilCompletado($user);

            if($completado->completo!=100){
                throw new CustomException('Para publicar el perfil el estado de la cuenta debe estar al 100%.', 403);
            }
        }

        $user->update([
            'estado' => $estado == EstadoUsuarioEnum::PRIVADO->value ? EstadoUsuarioEnum::PUBLICO->value : EstadoUsuarioEnum::PRIVADO->value
        ]);


        return response()->json($user->estado);
    }

}
