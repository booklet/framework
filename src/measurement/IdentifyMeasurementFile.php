<?php
class IdentifyMeasurementFile
{
    private $file_path;
    private $width;
    private $height;

    public function __construct(string $file_path)
    {
        $this->file_path = $file_path;
    }

    public function getWidth()
    {
        $this->cacheSizes();

        return $this->width;
    }

    public function getHeight()
    {
        $this->cacheSizes();

        return $this->height;
    }

    private function cacheSizes()
    {
        if (empty($this->width) or empty($this->height)) {
            $this->setSizes();
        }
    }

    private function setSizes()
    {
        $file_info_data = shell_exec($this->getIdentifyPath() . ' -ping ' . $this->file_path . ' 2>&1');

        if (StringUntils::isInclude($file_info_data, 'command not found')) {
            throw new Exception('Missing imagemagick identify library.');
        }

        preg_match("/ \d+x\d+ /", $file_info_data, $output_array);
        $this->width = floatval(explode('x', $output_array[0])[0]);
        $this->height = floatval(explode('x', $output_array[0])[1]);
    }

    private function getIdentifyPath()
    {
        return Config::get('custom_identify_bin_path') ?? 'identify';
    }
}
