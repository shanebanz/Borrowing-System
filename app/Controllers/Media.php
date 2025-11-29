<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Media extends BaseController
{
    /**
     * Serve uploaded files from writable directory
     */
    public function serve($type, $subfolder = null, $filename = null)
    {
        // Handle both 2 and 3 parameter cases
        if ($filename === null) {
            $filename = $subfolder;
            $subfolder = null;
        }

        // Build the file path
        $basePath = WRITEPATH . 'uploads/';
        
        if ($subfolder) {
            $filePath = $basePath . $type . '/' . $subfolder . '/' . $filename;
        } else {
            $filePath = $basePath . $type . '/' . $filename;
        }

        // Check if file exists
        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        // Get mime type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        // Set headers and output file
        $this->response->setHeader('Content-Type', $mimeType);
        $this->response->setHeader('Content-Length', filesize($filePath));
        
        // Cache for 1 week
        $this->response->setHeader('Cache-Control', 'max-age=604800, public');
        $this->response->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + 604800) . ' GMT');

        return $this->response->setBody(file_get_contents($filePath));
    }
}
