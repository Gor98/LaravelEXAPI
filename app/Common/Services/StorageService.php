<?php

namespace App\Common\Services;

use App\Common\Constant\Settings;
use Illuminate\Filesystem\FilesystemManager;

class StorageService
{
    /**
     * @var FilesystemManager
     */
    private FilesystemManager $storage;

    /**
     * StorageService constructor.
     * @param FilesystemManager $storage
     */
    public function __construct(FilesystemManager $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param $path
     * @return string
     */
    public function get($path)
    {
        if (!$path) {
            return null;
        }

        $adapter = $this->storage
            ->disk(Settings::STORAGE)
            ->getDriver()
            ->getAdapter();
        $command = $adapter->getClient()->getCommand('GetObject', [
            'Bucket' => $adapter->getBucket(),
            'Key' => $path,
        ]);
        $request = $adapter->getClient()->createPresignedRequest($command, Settings::ACTIVE_PERIOD);

        return (string) $request->getUri();
    }
    /**
     * @param $path
     * @param $content
     */
    public function add($path, $content)
    {
        return $this->storage->disk(Settings::STORAGE)->put($path, $content);
    }

    /**
     * @param $path
     */
    public function remove($path)
    {
        $this->storage->disk(Settings::STORAGE)->delete($path);
    }
}
