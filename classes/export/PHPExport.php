<?php

class PHPExport implements ExportInterface
{
    public function export($arr, $directory, $language)
    {
        $file = $directory . DIRECTORY_SEPARATOR . $language . '.php';

        $fp = fopen($file, 'w');
        fwrite($fp, '<?php return ' . var_export($arr, true) . ';');

        fclose($fp);
    }
}
