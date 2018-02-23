<?php
class ImagickUntils
{
    public static function resize($input_path, $output_path, $width, $height)
    {
        $imagick = self::setUpImage($input_path);
        $imagick->resizeImage($width, $height, imagick::FILTER_LANCZOS, 1, true);
        self::writeImage($imagick, $output_path);
    }

    // Upscale with pixel based resize (Nearest-Neighbor),
    // not blure image when upsacle
    public static function scale($input_path, $output_path, $width, $height)
    {
        $imagick = self::setUpImage($input_path);
        $imagick->scaleImage($width, $height, true);
        self::writeImage($imagick, $output_path);
    }

    public static function resizeIfLarger($input_path, $output_path, $width, $height)
    {
        $imagick = self::setUpImage($input_path);
        $d = $imagick->getImageGeometry();
        if (self::imageBiggerThanTarget($imagick, $width, $height)) {
            $imagick->resizeImage($width, $height, imagick::FILTER_LANCZOS, 1, true);
        }
        self::writeImage($imagick, $output_path);
    }

    public static function scaleIfLarger($input_path, $output_path, $width, $height)
    {
        $imagick = self::setUpImage($input_path);
        $d = $imagick->getImageGeometry();
        if (self::imageBiggerThanTarget($imagick, $width, $height)) {
            $imagick->scaleImage($width, $height, true);
        }
        self::writeImage($imagick, $output_path);
    }

    public static function crop($input_path, $output_path, $width, $height)
    {
        $imagick = self::setUpImage($input_path);
        $imagick->cropThumbnailImage($width, $height);
        self::writeImage($imagick, $output_path);
    }

    private static function setUpImage($input_path)
    {
        $imagick = new Imagick($input_path);
        $imagick->setImageBackgroundColor('white');
        $imagick = $imagick->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

        return $imagick;
    }

    private static function writeImage(&$imagick, $output_path)
    {
        $imagick->writeImages($output_path, false);
        $imagick->clear();
        $imagick->destroy();
    }

    private static function imageBiggerThanTarget($imagick, $width, $height)
    {
        $d = $imagick->getImageGeometry();

        return $d['width'] > $width or $d['height'] > $height;
    }
}
