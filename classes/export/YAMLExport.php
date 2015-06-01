<?php


class YAMLExport implements ExportInterface
{
    public function export($arr, $directory, $language)
    {
        $file = $directory . DIRECTORY_SEPARATOR . $language . '.yml';

        $fp = fopen($file, 'w');
        fwrite($fp, spyc_dump($arr));
        fclose($fp);

        return file_exists($file);
    }
}
