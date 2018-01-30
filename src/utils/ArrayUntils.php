<?php
class ArrayUntils
{
    /**
     * Check if array is associative or sequential
     * ['a', 'b', 'c'] // false
     * ["0" => 'a', "1" => 'b', "2" => 'c'] // false
     * ["1" => 'a', "0" => 'b', "2" => 'c'] // true
     * ["a" => 'a', "b" => 'b', "c" => 'c'] // true.
     */
    public static function isAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    // Support:
    // name="artist[images][]"
    // name="images[]"
    // name="image"
    public static function normalizeFilesArray($files, array $params = [])
    {
        $remove_empty_uploads = $params['remove_empty_uploads'] ?? true;

        $output = [];
        foreach ($files as $base_key => $file) {
            if (isset($file['name']) and is_array($file['name'])) {
                $file_keys = array_keys($file['name']);
                foreach ($file_keys as $file_key) {
                    if (is_array($file['name'][$file_key])) {
                        $keys = array_keys($file['name'][$file_key]);
                        foreach ($keys as $key) {
                            if ($remove_empty_uploads and $file['error'][$file_key][$key] == 4) {
                                continue;
                            }
                            $output[$base_key][$file_key][$key] = [
                                'name' => $file['name'][$file_key][$key],
                                'type' => $file['type'][$file_key][$key],
                                'tmp_name' => $file['tmp_name'][$file_key][$key],
                                'error' => $file['error'][$file_key][$key],
                                'size' => $file['size'][$file_key][$key],
                            ];
                        }
                    } else {
                        if ($remove_empty_uploads and $file['error'][$file_key] == 4) {
                            continue;
                        }
                        $output[$base_key][$file_key] = [
                            'name' => $file['name'][$file_key],
                            'type' => $file['type'][$file_key],
                            'tmp_name' => $file['tmp_name'][$file_key],
                            'error' => $file['error'][$file_key],
                            'size' => $file['size'][$file_key],
                        ];
                    }
                }
            } else {
                $output[$base_key] = $file;
            }
        }

        return $output;
    }
}
