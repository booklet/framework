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
    * Filter array of files paths to grab only test files
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
    /**
    * To resolve problem with to many files in one folder
    * we group files in wrappers folders 000, 001, 002 by 1000 items
    * Folder base on item id, 0-999 => 000, 1000-1999 => 001
    */
    public static function getWrapperFolderById($id)
    {
        $wrapper_folder_id = floor($id / 1000);
        return sprintf('%03d', $wrapper_folder_id);
    }
}
