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

    // filter array of files paths to grab only test files
    public static function getTestsFiles(Array $files_paths)
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
}
