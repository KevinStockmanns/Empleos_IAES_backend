<?php
namespace App\Services;
use Intervention\Image\ImageManager;
use App\Exceptions\CustomException;
use App\Http\Requests\Usuario\UsuarioImagenRequest;
use Intervention\Image\Drivers\Gd\Driver;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileService{

    private ImageManager $manager;
    private string $imgPath = 'app/public/imagenes';

    public function __construct() {
        $this->manager = new ImageManager(new Driver());
    }

    public function saveImage(UsuarioImagenRequest $req, string|null $fileToDelete=null){
        try {
            if($fileToDelete){
                $this->removeFile($fileToDelete);
            }
            $imagen = $req->file("imagen");
            $fileName = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();

            $img = $this->manager->read($imagen->getRealPath());
            $img->scaleDown(width: 350, height: 350);

            $directorio = storage_path($this->imgPath);
            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }

            $img->save(storage_path($this->imgPath . '/' . $fileName));

            return $fileName;
        } catch (\Throwable $th) {
            throw new CustomException('Ocurrio un error al cargar la imágen', 500);
        }
    }

    public function removeFile(string $fileName){
        $path = storage_path($this->imgPath . '/' . $fileName);
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }

    public function getFile(string $fileName) {
        try {
            $path = storage_path($this->imgPath . '/' . $fileName);
            
            if (!file_exists($path)) {
                throw new CustomException('La imagen no existe', 404);
            }

            return new BinaryFileResponse($path);
        } catch (\Throwable $th) {
            throw new CustomException('Error al obtener la imagen'.$th->getMessage(), 500);
        }
    }
}