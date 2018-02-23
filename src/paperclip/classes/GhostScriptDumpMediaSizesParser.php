<?php
// Parser for outputs data from ghostscript toolbin_pdf_info.ps script

class GhostScriptDumpMediaSizesParser
{
    const REGEX = [
        'media_box' => '/MediaBox: \[.*?\]/',
        'bleed_box' => '/BleedBox: \[.*?\]/',
        'crop_box' => '/CropBox: \[.*?\]/',
        'trim_box' => '/TrimBox: \[.*?\]/',
        'art_box' => '/ArtBox: \[.*?\]/',
        // MediaBox: [8.94069672e-06 -5.0 96.3786 153.069] => matches ['0.0', '-5.0', '96.3786', '153.069']
        'sizes' => '/-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/',
        'rotate' => '/Rotate = (\d*)?/',
        'split_pages_count' => '/through (\d*)?./',
    ];

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function parse()
    {
        $pages_sizes = [];
        $lines = explode("\n", $this->data);
        foreach ($lines as $line) {
            if (StringUntils::areStartsWith($line, 'Page ')) {
                $pages_sizes[] = $this->extractSizesFormLine($line);
            }
        }

        if (empty($pages_sizes)) {
            throw new Exception('GhostScript dump media size input data error.');
        }

        return $pages_sizes;
    }

    private function extractSizesFormLine($line)
    {
        $size = [
            'media_box' => $this->getSizes('media_box', $line),
            'bleed_box' => $this->getSizes('bleed_box', $line),
            'crop_box' => $this->getSizes('crop_box', $line),
            'trim_box' => $this->getSizes('trim_box', $line),
            'art_box' => $this->getSizes('art_box', $line),
        ];

        // If page has rotated param, we need to "rotate" sizes of boxs
        $rotate = $this->getRotate($line);
        if ($rotate) {
            $size = $this->rotateBoxs($size, $rotate);
        }

        return $size;
    }

    // String "MediaBox: [0.0 0.0 96.3786 153.069]"
    // to array [0.0, 0.0, 96.3786, 153.069]
    public function getSizes($type, $line)
    {
        preg_match_all(self::REGEX[$type], $line, $matches, 0);
        $match = $matches[0][0] ?? null;

        if (!$match) {
            return null;
        }

        preg_match_all(self::REGEX['sizes'], $match, $sizes_matches);

        return array_map('floatval', $sizes_matches[0]);
    }

    // String "Page 1 MediaBox: [-2.0 0.0 351.0 238.0]  Rotate = 270  Page contains Annotations"
    // to init 270
    public function getRotate($line)
    {
        preg_match_all(self::REGEX['rotate'], $line, $matches, 0);
        $match = $matches[1][0] ?? null;

        if ($match == null) {
            return null;
        }

        return intval($match);
    }

    public function rotateBoxs($size, $rotate)
    {
        if (!in_array($rotate, [90, 270])) {
            return $size;
        }

        foreach ($size as $box_key => $vals) {
            if ($vals) {
                $size[$box_key] = [$vals[1], $vals[0], $vals[3], $vals[2]];
            }
        }

        return $size;
    }

    public function getPagesFromSplitResults()
    {
        // If 1 page pdf, ghostscript when split not return information about pages:
        // "Processing pages 1 through 2."
        preg_match_all(self::REGEX['split_pages_count'], $this->data, $matches);
        $match = $matches[1][0] ?? 1;

        return intval($match);
    }
}
