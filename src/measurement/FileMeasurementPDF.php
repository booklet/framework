<?php
class FileMeasurementPDF
{
    private $file_path;
    private $pages_sizes;

    public function __construct($pdf_file_path)
    {
        $this->file_path = $pdf_file_path;
    }

    public function pagesCount()
    {
        $pages_count = shell_exec('gs -q -dNODISPLAY -c "(' . $this->file_path . ') (r) file runpdfbegin pdfpagecount = quit"');

        return intval($pages_count);
    }

    public function widthInPt(array $params = [])
    {
        return $this->getPtSize('media_box', 'with', $params);
    }

    public function widthInMm(array $params = [])
    {
        $width_in_mm = UnitConverterUntils::ptToMm($this->widthInPt($params));

        return round($width_in_mm, 2);
    }

    public function widthInPx(array $params = [])
    {
        return UnitConverterUntils::ptToPx($this->widthInPt($params), $params);
    }

    public function heightInPt(array $params = [])
    {
        return $this->getPtSize('media_box', 'height', $params);
    }

    public function heightInMm(array $params = [])
    {
        $height_in_mm = UnitConverterUntils::ptToMm($this->heightInPt($params));

        return round($height_in_mm, 2);
    }

    public function heightInPx(array $params = [])
    {
        return UnitConverterUntils::ptToPx($this->heightInPt($params), $params);
    }

    private function getPtSize($box_type, $orient, $params)
    {
        $page = $params['page'] ?? 1;

        if (!$this->pages_sizes) {
            $this->setPagesSizes();
        }

        $sizes = $this->pages_sizes[$page - 1][$box_type];

        if ($orient == 'with') {
            $size = $sizes[2] - $sizes[0]; // index 0 (x1) and 1 (y1) can be negative
        } elseif ($orient == 'height') {
            $size = $sizes[3] - $sizes[1];
        }

        return round($size, 2);
    }

    public function setPagesSizes()
    {
        // When run tests in framework we need this path
        // but when we run this in system (api, fotobum) we need full path
        $toolbin_pdf_info_path = 'lib/ghost_script/toolbin_pdf_info.ps';
        if (!file_exists($toolbin_pdf_info_path)) {
            $toolbin_pdf_info_path = 'vendor/boooklet/framework/lib/ghost_script/toolbin_pdf_info.ps';
        }

        $pdf_info_data = shell_exec('gs -dNODISPLAY -q -sFile="' . $this->file_path . '"' .
            ' -dDumpMediaSizes ' . $toolbin_pdf_info_path);
        $gs_parser = new GhostScriptDumpMediaSizesParser($pdf_info_data);

        $this->pages_sizes = $gs_parser->parse();
    }
}
