<?php


namespace Macedonia\Alex\Utils;


use ZipArchive;

class Archive
{
    /**
     * @var ZipArchive
     */
    private $zipArchive;

    /**
     * Archive constructor.
     */
    public function __construct()
    {
        $this->zipArchive = new ZipArchive();
    }

    /**
     * @param string $source
     * @param string $destination
     * @return bool
     */
    public function unzip(string $source, string $destination): bool
    {
        $resource = $this->zipArchive->open($source);
        if ($resource) {
            $this->zipArchive->extractTo($destination);
            $this->zipArchive->close();
            return true;
        }
        return false;
    }
}
