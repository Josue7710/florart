<?php
/** Subida segura de imágenes de producto (JPG, PNG o WEBP, máx. 2 MB). */
class ImageUpload {
    private const MAX_BYTES = 2097152;
    private const TYPES = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    /** @return array [ruta relativa|null, mensaje de error|null] */
    public static function save(?array $file): array {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return [null, null];
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [null, 'No se pudo subir la imagen. Prueba con una de menos de 2 MB.'];
        }
        if ($file['tamano'] > self::MAX_BYTES) {
            return [null, 'La imagen pesa más de 2 MB.'];
        }
        if (!is_uploaded_file($file['tmp_name'])) {
            return [null, 'Archivo de imagen no válido.'];
        }
        $info = @getimagesize($file['tmp_name']);
        if (!$info || !isset(self::TYPES[$info['mime']])) {
            return [null, 'La imagen debe ser JPG, PNG o WEBP.'];
        }
        $dir = ROOT_PATH . '/uploads';
        if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
            return [null, 'No se pudo crear la carpeta uploads/ en el servidor.'];
        }
        $name = 'p_' . bin2hex(random_bytes(8)) . '.' . self::TYPES[$info['mime']];
        if (!@move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
            return [null, 'No se pudo guardar la imagen (revisa los permisos de la carpeta uploads/).'];
        }
        return ['uploads/' . $name, null];
    }

    /** Borra una imagen subida por el admin (nunca las ilustraciones de assets/). */
    public static function delete(?string $path): void {
        if ($path && preg_match('#^uploads/p_[a-f0-9]{16}\.(jpg|png|webp)$#', $path)) {
            @unlink(ROOT_PATH . '/' . $path);
        }
    }
}
