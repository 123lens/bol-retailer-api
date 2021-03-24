<?php
namespace Budgetlens\BolRetailerApi\Resources\Concerns;

trait HasSaveable
{
    protected $fileExt = 'pdf';

    /**
     * Save contents
     * @param $path
     * @return string|null
     */
    public function save(string $path, string $filename = null): ?string
    {
        if (is_null($filename)) {
            $filename = "{$this->id}.{$this->fileExt}";
        }
        if (!@file_put_contents("{$path}/{$filename}", $this->contents)) {
            return null;
        } else {
            return "{$path}/{$filename}";
        }
    }

}
