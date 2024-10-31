<?php

namespace Oaattia\WoocommerceGenerator\Utils;

use Intervention\Image\Image;
use Oaattia\WoocommerceGenerator\Admin\MetaBox;

class FileSaver
{
    /**
     * @var Image
     */
    private $image;
    /**
     * @var string
     */
    private $filename;


    public function __construct($image, $filename)
    {
        $this->image = $image;
        $this->filename = $filename;
    }

    /**
     * Save generated image and save the path to the product meta table
     *
     * @param int $productId
     * @return string|null
     */
    public function saveGeneratedImage($productId)
    {
        $uploadDirectory = wp_upload_dir();

        if(!isset($uploadDirectory['path']) && isset($uploadDirectory['error'])) {
            throw new \RuntimeException($uploadDirectory['error']);
        }

        if (strpos($this->filename, '.') !== false) {
            $fileName = $productId .'-' . $this->filename;

            if($filePath = get_post_meta($productId, MetaBox::META_KEY_IMAGE, true)) {
                wp_delete_file($uploadDirectory['path'].DIRECTORY_SEPARATOR.$filePath);
            }

            $this->image->save(
                $savePath = $uploadDirectory['path'].DIRECTORY_SEPARATOR. $fileName
            );

            update_post_meta($productId, MetaBox::META_KEY_IMAGE, $savedPath = strstr($savePath, 'wp-content'));

            return $savedPath;
        }

        return null;
    }
}