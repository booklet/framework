<?php
// Get dimensions from graphics files
// Sizes for 300 dpi
class MeasurementFileForPrinting
{
    const ALLOWED_CONTENT_TYPE = [
        'application/pdf' => ['pdf'],
        'application/postscript' => ['ai', 'eps', 'ps'],
        'image/svg+xml' => ['svg', 'svgz'],
        'image/gif' => ['gif'],
        'image/jpeg' => ['jpeg', 'jpg', 'jpe'],
        'image/png' => ['png'],
        'image/tiff' => ['tiff', 'tif'],
        'image/bmp' => ['bmp'],
    ];

    private $file_path;
    private $sizes;

    public function __construct(string $file_path, array $params = [])
    {
        $this->file_path = $file_path;

        $this->checkContentType();
    }

    public function getWidthInPt()
    {
        $this->cacheSizes();

        return $this->sizes['width_in_pt'] ?? UnitConverterUntils::pxToPt($this->sizes['width_in_px'], ['precision' => 2]);
    }

    public function getHeightInPt()
    {
        $this->cacheSizes();

        return $this->sizes['height_in_pt'] ?? UnitConverterUntils::pxToPt($this->sizes['height_in_px'], ['precision' => 2]);
    }

    public function getWidthInPx()
    {
        $this->cacheSizes();

        return $this->sizes['width_in_px'] ?? UnitConverterUntils::ptToPx($this->sizes['width_in_pt'], ['precision' => 2]);
    }

    public function getHeightInPx(array $params = [])
    {
        $this->cacheSizes();

        return $this->sizes['height_in_px'] ?? UnitConverterUntils::ptToPx($this->sizes['height_in_pt'], ['precision' => 2]);
    }

    public function getWidthInMm()
    {
        if (!empty($this->sizes['width_in_px'])) {
            return UnitConverterUntils::pxToMm($this->getWidthInPx(), ['precision' => 2]);
        } else {
            return UnitConverterUntils::ptToMm($this->getWidthInPt(), ['precision' => 2]);
        }
    }

    public function getHeightInMm()
    {
        if (!empty($this->sizes['height_in_px'])) {
            return UnitConverterUntils::pxToMm($this->getHeightInPx(), ['precision' => 2]);
        } else {
            return UnitConverterUntils::ptToMm($this->getHeightInPt(), ['precision' => 2]);
        }
    }

    private function cacheSizes()
    {
        if (empty($this->sizes)) {
            $this->setSizes();
        }
    }

    private function setSizes()
    {
        $sizes = [];
        if ($this->isPostscript()) {
            // Get pt
            if ($this->isPdfFile() || $this->isAiFile()) {
                $fmpdf = new FileMeasurementPDF($this->file_path);
                $sizes['width_in_pt'] = $fmpdf->widthInPt();
                $sizes['height_in_pt'] = $fmpdf->heightInPt();
            } else {
                $measurement = new IdentifyMeasurementFile($this->file_path);
                $sizes['width_in_pt'] = $measurement->getWidth();
                $sizes['height_in_pt'] = $measurement->getHeight();
            }
        } else {
            // Get px
            list($sizes['width_in_px'], $sizes['height_in_px']) = getimagesize($this->file_path);
        }

        $this->sizes = $sizes;
    }

    private function checkContentType()
    {
    }

    private function isPostscript()
    {
        $type = mime_content_type($this->file_path);
        $allowed_types = ['application/pdf', 'application/postscript', 'image/svg+xml'];
        $ext = strtolower(FilesUntils::getFileExtension($this->file_path));
        $allowed_exts = ['pdf', 'eps', 'svg'];

        return in_array($type, $allowed_types) || in_array($ext, $allowed_exts);
    }

    private function isEpsFile()
    {
        return strtolower(FilesUntils::getFileExtension($this->file_path)) == 'eps';
    }

    private function isPdfFile()
    {
        return strtolower(FilesUntils::getFileExtension($this->file_path)) == 'pdf';
    }

    private function isAiFile()
    {
        return strtolower(FilesUntils::getFileExtension($this->file_path)) == 'ai';
    }
}
