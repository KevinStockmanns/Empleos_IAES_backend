<?php

namespace App\Services;


use App\DTO\Usuario\UsuarioPerfilCompletoDTO;
use App\Enums\AccionCrudEnum;
use App\Enums\EstadoUsuarioEnum;
use App\Enums\RolEnum;
use App\Exceptions\CustomException;
use App\Http\Requests\Usuario\RegistrarUsuarioRequest;
use App\Http\Requests\Usuario\UsuarioCVRequest;
use App\Http\Requests\Usuario\UsuarioImagenRequest;
use App\Models\Empresa;
use App\Models\ExperienciaLaboral;
use App\Models\LicenciaConducir;
use App\Models\PerfilProfesional;
use App\Models\Rol;
use App\Models\Titulo;
use App\Models\TituloDetalle;
use App\Models\Usuario;
use App\Service\FileServices;
use Carbon\Carbon;
use Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsuarioService
{
    private $ubicacionService;
    private $contactoService;
    private $habilidadService;
    private $tituloService;
    private $fileService;

    public function __construct(UbicacionService $ubicacionService,
        ContactoService $contactoService,
        HabilidadService $habilidadService,
        FileService $fileService,
        TituloService $tituloService
    ){
        $this->ubicacionService = $ubicacionService;
        $this->contactoService = $contactoService;
        $this->habilidadService = $habilidadService;
        $this->tituloService = $tituloService;
        $this->fileService = $fileService;
    }
    public function registrar(RegistrarUsuarioRequest $request, Usuario|null $admin)
    {
        $isAdmin = $admin !=null && $admin->isAdmin();
        $data = $request->validated();
        $rol = Rol::firstOrCreate([
            'nombre' => $data['rol'],
        ]);
        $direccion = null;
        if(isset($data['ubicacion'])){
            $direccion = $this->ubicacionService->registrarOrBuscar($data['ubicacion']);
        }
        $usuario = new Usuario([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'correo' => $data['correo'],
            'clave' => $isAdmin
                ? Hash::make($data['dni'])
                : Hash::make($data['clave']),
            'dni' => $data['dni'],
            'estado' => $isAdmin
                ? EstadoUsuarioEnum::ALTA->value
                : EstadoUsuarioEnum::SOLICITADO->value,
            'fecha_nacimiento' => isset($data['fechaNacimiento'])
                ? Carbon::createFromFormat('Y-m-d', $data['fechaNacimiento'])
                : null,
            'direccion_id' => $direccion->id ?? null,
            'estado_civil'=>$data['estado_civil'],
            'genero'=>$data['genero'],
        ]);
        $usuario->rol_id = $rol->id;

        $usuario->save();

        $claims = [
            'rol' => $usuario->rol->nombre,
            'username' => $usuario->correo,
            'exp' => now()->addHours(4)->timestamp
        ];

        $token = JWTAuth::claims($claims)->fromUser($usuario);

        return [
            'usuario'=>$usuario,
            'token'=> $token
        ];
    }

    public function obtenerById($id)
    {
        $usuario = Usuario::find($id);
        if(!$usuario){
            throw new CustomException('El usuario no fue encontrado en la base de datos', 404);
        }
        return $usuario;
    }

    public function login($username, $clave)
    {
        $usuario = Usuario::withTrashed()->where('correo', $username)->first();
        if (!$usuario) {
            throw new CustomException('Credenciales inválidas', 403);
        }
        if (!$this->verifyPassword($clave, $usuario->clave)) {
            throw new CustomException('Credenciales inválidas', 403);
        }
        if($usuario->estado == EstadoUsuarioEnum::BLOQUEADO->value){
            throw new CustomException('Tu cuenta ha sido suspendida. Por favor comunícate con administración.', 403);
        }
        if($usuario->estado == EstadoUsuarioEnum::RECHAZADO->value){
            throw new CustomException('Tu cuenta ha sido rechazada. Por favor comunícate con administración.', 403);
        }
        if($usuario->trashed()){
            throw new CustomException('Tu cuenta ha sido dada de baja. Por favor comunícate con administración.', 403);
        }

        if($usuario->estado == EstadoUsuarioEnum::ALTA->value){
            $usuario->estado = EstadoUsuarioEnum::PRIVADO->value;
        }
        
        $usuario->ultimo_inicio = now();
        $usuario->save();


        $claims = [
            'rol' => $usuario->rol->nombre,
            'username' => $usuario->correo,
            'exp' => now()->addHours(4)->timestamp
        ];

        $token = JWTAuth::claims($claims)->fromUser($usuario);

        return [
            'usuario' => $usuario,
            'token' => $token,
        ];
    }

    public function completarData($data)
    {
        if (isset($data['ubicacion'])) {

        }
    }

    public function cargarUbicacion($idUsuario, $data)
    {
        $direccion = $this->ubicacionService->registrarOrBuscar($data['ubicacion']);
        $usuario = Usuario::find($idUsuario);

        if (!$usuario) {
            throw new CustomException('no se enontro el usuario con el id ingresado', 404);
        }
        $usuario->direccion_id = $direccion->id;
        $usuario->save();
        return $direccion;
    }
    public function actualizarUsuario($idUsuario, $data)
    {
        $usuario = Usuario::find($idUsuario);
        if (isset($data['nombre'])) {
            $usuario->nombre = $data['nombre'];
        }
        if (isset($data['apellido'])) {
            $usuario->apellido = $data['apellido'];
        }
        if (isset($data['correo'])) {
            $usuario->correo = $data['correo'];
        }
        if (isset($data['fechaNacimiento'])) {
            $usuario->fecha_nacimiento = Carbon::createFromFormat('Y-m-d', $data['fechaNacimiento']);
        }
        if (isset($data['dni'])) {
            $usuario->dni = $data['dni'];
        }
        if (isset($data['estado_civil'])) {
            $usuario->estado_civil = $data['estado_civil'];
        }
        if (isset($data['genero'])) {
            $usuario->genero = $data['genero'];
        }
        if (isset($data['rol'])) {
            $rol = Rol::firstOrCreate([
                'nombre' => $data['rol']
            ]);
            $usuario->rol_id = $rol->id;
        }

        $usuario->save();
        return $usuario;
    }

    public function cargarPerfilProfesional($idUsuario, $data){
        $usuario = Usuario::find($idUsuario);
        if (!$usuario){
            throw new CustomException('no se econtro el ususario en la base de datos',404);
        }
        $perfilP = $usuario->perfilProfesional;
        if($perfilP){
            if(isset($data['cargo'])){
                $perfilP->cargo = $data['cargo'];
            }
            if(isset($data['cartaPresentacion'])){
                $perfilP->carta_presentacion = $data['cartaPresentacion'];
            }
            if(isset($data['disponibilidad'])){
                $perfilP->disponibilidad = $data['disponibilidad'];
            }
            if(isset($data['disponibilidadMudanza'])){
                $perfilP->disponibilidad_mudanza = $data['disponibilidadMudanza'];
            }
        }else{
            $perfilP = new PerfilProfesional([
                'cargo' => $data['cargo'],
                'carta_presentacion'=> $data['cartaPresentacion'] ?? null,
                'disponibilidad'=>$data['disponibilidad'],
                'disponibilidad_mudanza'=> $data['disponibilidadMudanza']
            ]);
            $perfilP->usuario_id = $usuario->id;
        }
        $perfilP->save();
        return $perfilP;
    }

    public function listarUsuarios($size, $page, $rol){
        $rolId = Rol::where('nombre', $rol)->first()->id;

        return Usuario::where('rol_id', $rolId)
            ->paginate($size, ['*'], 'page', $page);
    }

    public function cargarContacto($data, $idUsuario){
        $usuario = Usuario::find($idUsuario);
        if(!$usuario){
            throw new CustomException('No se encontro el usuario con el id '. $idUsuario, 404);
        }

        $contacto = $usuario->contacto;
        if($contacto){
            $contacto= $this->contactoService->actualizarContacto($contacto, $data['contacto']);
        }else{
            $contacto= $this->contactoService->crearContacto($data['contacto']);
            $usuario->contacto_id = $contacto->id;
            $usuario->save();
        }

        return $contacto;
    }

    public function cargarHabilidades($idUsuario, $data): array{
        $usuario = Usuario::find($idUsuario);
        if(!$usuario){
            throw new CustomException('No se econtro un usuario con el id ingresado', 404);
        }
        $habilidades = $this->habilidadService->buscarOCrear($data);

        $usuario->habilidades()->sync(
            collect($habilidades)->pluck('id')->toArray()
        );

        return $habilidades;
    }


    public function calcularPerfilCompletado($usuarioOrId){
        $usuario=null;
        if($usuarioOrId instanceof Usuario){
            $usuario = $usuarioOrId;
        }else{
            $usuario = $this->obtenerById($usuarioOrId);
        }

        $datos = [
            ['Foto de Perfil', $usuario->foto_perfil == true],
            ['Contacto', $usuario->contacto()->exists()],
            ['Información Profesional', $usuario->perfilProfesional ? (($usuario->perfilProfesional->cargo || $usuario->perfilProfesional->carta_presentacion || $usuario->perfilProfesional->disponibilidad || $usuario->perfilProfesional->disponibilidad_mudanza) ?? false) : false],
            ['Habilidades', $usuario->habilidades()->exists()],
            ['Ubicación',$usuario->direccion()->exists()],
            ['Educación',$usuario->tituloDetalles()->exists()],
            ['Experiencia Laboral',$usuario->experienciasLaborales()->exists()],
            ['Currículum', $usuario->perfilProfesional->cv ?? false],
        ];

        $datosTotales = count($datos);
        $datosCompletos = count(array_filter($datos, function($d){
            return $d[1];
        }));
        $porcentaje = $datosCompletos * 100 / $datosTotales;

        return new UsuarioPerfilCompletoDTO($porcentaje, $datos);
    }

    public function postPerfilImagen(UsuarioImagenRequest $req){
        $usuario = $this->obtenerById($req->route('id'));
        $fileName = $this->fileService->saveImage($req, $usuario->foto_perfil);

        $usuario->foto_perfil = $fileName;
        $usuario->save();

        return $fileName;
    }

    public function getFotoPerfil($imageName){
        return $this->fileService->getFile($imageName);
    }

    public function postCV(UsuarioCVRequest $req){
        $usuario = $this->obtenerById($req->route('id'));
        $fileName = $this->fileService->saveCV($req, $usuario->perfilProfesional->cv ?? null);

        $perfilP = $usuario->perfilProfesional;
        if($perfilP){
            $perfilP->cv = $fileName;
            $perfilP->save();
        }else{
            PerfilProfesional::create([
                'cv'=> $fileName,
                'usuario_id'=>$usuario->id
            ]);
        }

        return $fileName;
    }
    public function getCV($imageName){
        return $this->fileService->getFile($imageName, false);
    }

    public function cargarEducacion($data){
        $usuario = request()->attributes->get('usuarioValidado');
        $tituloDetalles = $this->tituloService->registrarTitulo($data, $usuario);
        return $tituloDetalles;
    }


    public function cargarExperienciaLaboral($data){
        $usuario = request()->attributes->get('usuarioValidado');
        
        $experienciasLaborales = $usuario->experienciasLaborales;
        foreach($data['experienciaLaboral'] as $key => $expDto){
            $empresa = null;
            if(isset($expDto['idEmpresa'])){
                $empresa = Empresa::find($expDto['idEmpresa']);
            }
            if($expDto['accion'] == AccionCrudEnum::AGREGAR->value){
                $expLab = ExperienciaLaboral::create([
                    'puesto'=> $expDto['puesto'],
                    'empresa'=> $expDto['empresa'] ?? $empresa->nombre,
                    'fecha_inicio'=> $expDto['fechaInicio'],
                    'fecha_terminacion'=> $expDto['fechaTerminacion'] ?? null,
                    'descripcion'=> $expDto['descripcion'] ?? null,
                    'usuario_id'=> $usuario->id,
                    'empresa_id'=>$empresa->id ?? null
                ]);
                $experienciasLaborales->push($expLab);
            }else if($expDto['accion'] == AccionCrudEnum::ACTUALIZAR->value){
                $expLab = $experienciasLaborales->firstWhere('id', $expDto['id']);
                if(!$expLab){
                    throw new CustomException("No se ha encontrado la experiencia laboral con el id " . $expDto['id'] ." para actualizar",404, "experienciaLaboral.$key.id");
                }

                if(isset($expDto['puesto'])){
                    $expLab->puesto = $expDto['puesto'];
                }
                if(isset($expDto['empresa'])){
                    $expLab->empresa = $expDto['empresa'];
                }
                if(isset($expDto['fechaInicio'])){
                    $expLab->fecha_inicio = $expDto['fechaInicio'];
                }
                if(isset($expDto['fechaTerminacion'])){
                    $expLab->fecha_terminacion = $expDto['fechaTerminacion'];
                }
                if(isset($expDto['descripcion'])){
                    $expLab->descripcion = $expDto['descripcion'];
                }
                if(isset($expDto['idEmpresa'])){
                    $expLab->empresa_id = $empresa->id;
                    $expLab->empresa = $empresa->nombre;
                }
                $expLab->save();
                logger('update exp: ' . $expLab);
            }else if($expDto['accion'] == AccionCrudEnum::ELIMINAR->value){
                $exp = $experienciasLaborales->firstWhere('id', $expDto['id']);
                if(!$exp){
                    throw new CustomException("No se ha encontrado la experiencia laboral con el id " . $expDto['id'] . " para eliminar", 404, "experienciaLaboral.$key.id");
                }
                $exp->delete();
                $experienciasLaborales = $experienciasLaborales->filter(function($exp) use ($expDto) {
                    return $exp->id != $expDto['id'];
                });
            }
        }

        return $experienciasLaborales;
    }


    public function cargarLicenciaConducir($data, $idUser): LicenciaConducir|null{
        $lic = null;
        if(isset($data['id'])){
            $lic = LicenciaConducir::find($data['id']);

            if(isset($data['categoria']) && $data['categoria']){
                $lic->update([
                    'categoria' => $data['categoria'],
                    'vehiculo_propio' => $data['vehiculoPropio'] ?? false,
                ]);
            }else{
                $lic->delete();
            }
        }else{
            if(isset($data['categoria']) && $data['categoria']){
                $lic = LicenciaConducir::create([
                    'categoria' => $data['categoria'],
                    'vehiculo_propio' => $data['vehiculoPropio'] ?? false,
                    'usuario_id' => $idUser, 
                ]);
            }
        }


        return $lic;
    }


    public function encryptPassword($password)
    {
        return Hash::make($password);
    }

    public function verifyPassword($password, $hash)
    {
        return Hash::check($password, $hash);
    }

}