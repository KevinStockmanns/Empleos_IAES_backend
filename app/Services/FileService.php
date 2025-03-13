<?php
namespace App\Services;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use App\Exceptions\CustomException;
use App\Http\Requests\Usuario\UsuarioImagenRequest;
use Intervention\Image\Drivers\Gd\Driver;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileService{

    private ImageManager $manager;
    private string $imgPath = 'files/usuarios/imagenes';
    private string $cvPath = 'files/usuarios/cv';

    public function __construct() {
        $this->manager = new ImageManager(new Driver());
    }

    public function saveCV($req, string|null $fileToDelete = null): string{
    try {
        if ($fileToDelete) {
            $this->removeFile($fileToDelete, $this->cvPath);
        }
        $file = $req->file('cv');

        if ($file->getClientOriginalExtension() !== 'pdf') {
            throw new CustomException('El archivo debe estar en formato PDF', 400);
        }

        $fileName = time() . '_' . uniqid() . '.pdf';

        $directory = storage_path($this->cvPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $file->move($directory, $fileName);

        return $fileName;
    } catch (\Throwable $th) {
        throw new CustomException('Ocurrió un error al cargar el CV: ' . $th->getMessage(), 500);
    }
}


    // public function saveImage(UsuarioImagenRequest $req, string|null $fileToDelete=null){
    //     try {
    //         if($fileToDelete){
    //             $this->removeFile($fileToDelete, $this->imgPath);
    //         }
    //         $imagen = $req->file("imagen");
    //         $fileName = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();

    //         $img = $this->manager->read($imagen->getRealPath());
    //         $img->scaleDown(width: 350, height: 350);

    //         $directorio = storage_path($this->imgPath);
    //         if (!file_exists($directorio)) {
    //             mkdir($directorio, 0777, true);
    //         }

    //         $img->save(storage_path($this->imgPath . '/' . $fileName));

    //         return $fileName;
    //     } catch (\Throwable $th) {
    //         throw new CustomException('Ocurrio un error al cargar la imágen', 500);
    //     }
    // }


public function saveImage(UsuarioImagenRequest $req, string|null $fileToDelete = null) {
    try {
        if ($fileToDelete) {
            $this->removeFile($fileToDelete, $this->imgPath);
        }

        $imagen = $req->file("imagen");
        $originalExtension = $imagen->getClientOriginalExtension();
        $webpFileName = time() . '_' . uniqid() . '.webp';

        $img = $this->manager->read($imagen->getRealPath());
        $img->scaleDown(width: 350, height: 350);

        $directorio = storage_path($this->imgPath);
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }

        // Intentar guardar como WebP
        try {
            $encodedWebp = $img->encode(new WebpEncoder(quality: 80));
            file_put_contents($directorio . '/' . $webpFileName, $encodedWebp);
            return $webpFileName;
        } catch (\Throwable $th) {
            // Si falla, guardar en formato original
            $fallbackFileName = time() . '_' . uniqid() . '.' . $originalExtension;
            $img->save($directorio . '/' . $fallbackFileName);
            return $fallbackFileName;
        }

    } catch (\Throwable $th) {
        throw new CustomException('Ocurrió un error al cargar la imagen', 500);
    }
}


    public function removeFile(string $fileName, string $path): bool {
        $filePath = storage_path($path . '/' . $fileName);
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    public function getFile(string $fileName, bool $img = true) {
        try {
            $path = storage_path(($img ? $this->imgPath : $this->cvPath) . '/' . $fileName);
            
            if (!file_exists($path)) {
                throw new CustomException('La imagen no existe', 404);
            }

            return new BinaryFileResponse($path);
        } catch (\Throwable $th) {
            throw new CustomException('Error al obtener la imagen'.$th->getMessage(), 500);
        }
    }
}