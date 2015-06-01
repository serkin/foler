<?php


class PHPExport implements ExportInterface
{
    public function export($arr, $directory, $language)
    {
        $file = $directory.DIRECTORY_SEPARATOR.$language.'.php';
        //return file_get_contents($file, '<?php return '.var_export($arr, true).';');

        $fp = fopen($file, 'w');
        fwrite($fp, '<?php return '.var_export($arr, true).';');

        fclose($fp);
    }
}
