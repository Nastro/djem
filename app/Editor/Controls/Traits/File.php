<?php

namespace DJEM\Editor\Controls\Traits;

use InvalidArgumentException;

trait File
{
    private function getFilePath($fileName)
    {
        $url = parse_url($fileName);
        if (isset($url['scheme']) && isset($url['path'])) {
            $newFileName = uniqid().'.'.basename($url['path']);

            $file = file_get_contents($fileName, false, stream_context_create(
                [
                    'http' => ['ignore_errors' => true],
                    'ssl' => ['verify_peer' => false],
                ]
            ));
            if (empty($file)) {
                return false;
            }
            if (! file_put_contents(sys_get_temp_dir().'/'.$newFileName, $file)) {
                return false;
            }
            $fileName = $newFileName;
        }

        $file = realpath(sys_get_temp_dir().'/'.$fileName);
        if (substr($file, 0, strlen(sys_get_temp_dir())) !== sys_get_temp_dir()) {
            // какой-то неправильный файл, игнорируем
            throw new InvalidArgumentException('Invalid filename ['.$fileName.'].');
        }

        return $file;
    }

    private static function normalizeImageExtension($filePath)
    {
        // проверим тип файла по mime:
        $extension = false;
        switch (mime_content_type($filePath)) {
            case 'image/jpeg':
                $extension = 'jpg';
                break;

            case 'image/gif':
                $extension = 'gif';
                break;

            case 'image/png':
                $extension = 'png';
                break;

            case 'image/x-icon':
                $extension = 'ico';
                break;

            case 'image/bmp':
                $extension = 'bmp';
                break;

            default:
                // это не картинка!
                throw new \InvalidArgumentException('Unsupported file: '.$filePath);
        }
        if ($extension) {
            $newFile = substr($filePath, 0, -strlen(pathinfo($filePath, PATHINFO_EXTENSION))).$extension;
            if ($newFile != $filePath) {
                rename($filePath, $newFile);
                $filePath = $newFile;
            }
        }

        return $filePath;
    }
}
