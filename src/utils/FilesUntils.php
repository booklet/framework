<?php
class FilesUntils
{
    public static function getListFilesPathFromDirectoryAndSubfolders($dir)
    {
        $di = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = [];
        foreach (new RecursiveIteratorIterator($di) as $fileName => $fileInfo) {
            $path = (string) $fileInfo;
            $files[] = $path;
        }

        return $files;
    }

    /**
     * Filter array of files paths to grab only test files.
     */
    public static function getTestsFiles(array $files_paths)
    {
        $files = [];
        foreach ($files_paths as $file_path) {
            if (substr($file_path, -8) == 'Test.php') {
                $files[] = $file_path;
            }
        }

        return $files;
    }

    public static function deleteAllFilesInDirectory($path)
    {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public static function getFilesFromDirectory($directory)
    {
        return array_filter(glob($directory . '/*'), 'is_file');
    }

    // USE WITH CAUTION!
    public static function deleteDirectoryAndEverythingIn($directory)
    {
        if (is_dir($directory)) {
            $objects = scandir($directory);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (filetype($directory . '/' . $object) == 'dir') {
                        self::deleteDirectoryAndEverythingIn($directory . '/' . $object);
                    } else {
                        unlink($directory . '/' . $object);
                    }
                }
            }
            reset($objects);
            rmdir($directory);
        }
    }

    /**
     * To resolve problem with to many files in one folder
     * we group files in wrappers folders 000, 001, 002 by 1000 items
     * Folder base on item id, 0-999 => 000, 1000-1999 => 001.
     */
    public static function getWrapperFolderById($id)
    {
        $wrapper_folder_id = floor($id / 1000);

        return sprintf('%03d', $wrapper_folder_id);
    }

    // 12 => 000/000/012/
    public static function idToPath($id)
    {
        $id_str = str_pad($id, 9, '0', STR_PAD_LEFT);

        return chunk_split($id_str, 3, '/');
    }

    public static function objectIdToPath($object)
    {
        if (!isset($object->id)) {
            throw new Exception('Object does not have id property.');
        }

        return self::idToPath($object->id);
    }

    public static function getPathDirname($file_path)
    {
        return pathinfo($file_path)['dirname'];
    }

    public static function getFileBasename($file_path_or_name)
    {
        return pathinfo($file_path_or_name)['basename'];
    }

    public static function getFileExtension($file_path_or_name)
    {
        return pathinfo($file_path_or_name)['extension'];
    }

    public static function getFileName($file_path_or_name)
    {
        return pathinfo($file_path_or_name)['filename'];
    }

    public static function isImage($file)
    {
        $type = mime_content_type($file);
        $types = ['image/jpg', 'image/jpeg', 'image/bmp', 'image/gif', 'image/tiff', 'application/pdf', 'image/png'];

        return in_array($type, $types);
    }

    public static function humanFilesize($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            ++$i;
        }

        return round($size, $precision) . ' ' . $units[$i];
    }

    public static function makeDirectory($dir_path, $mode = 0777)
    {
        return is_dir($dir_path) || mkdir($dir_path, $mode, true);
    }
}
