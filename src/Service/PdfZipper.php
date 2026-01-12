<?php

namespace Linderp\SuluSwissQRBillBundle\Service;

class PdfZipper
{
    public function zip(array $files): false|string
    {
        $zip = new \ZipArchive();
        $zipFileName = tempnam(sys_get_temp_dir(), 'zip'); // temporary file

        if ($zip->open($zipFileName, \ZipArchive::CREATE) !== true) {
            throw new \RuntimeException('Cannot create zip archive');
        }

        foreach ($files as $name => $content) {
            $zip->addFromString($name, $content);
        }

        $zip->close();
        return $zipFileName;
    }
}