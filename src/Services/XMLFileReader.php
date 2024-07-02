<?php

namespace App\Services;

class XMLFileReader
{
    public function readFile($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception('File not found: ' . $filePath);
        }

        $xmlContent = file_get_contents($filePath);
        if ($xmlContent === false) {
            throw new \Exception('Failed to read file');
        }

        $xml = simplexml_load_string($xmlContent);
        if ($xml === false) {
            throw new \Exception('Failed to parse XML file');
        }

        $items = [];
        foreach ($xml->item as $item) {
            $items[] = [
                'entity_id' => (int)$item->entity_id,
                'category_name' => (string)$item->CategoryName,
                'sku' => (string)$item->sku,
                'name' => (string)$item->name,
                'shortdesc' => (string)$item->shortdesc,
                'price' => (float)$item->price,
                'link' => (string)$item->link,
                'image' => (string)$item->image,
                'brand' => (string)$item->Brand,
                'rating' => (int)$item->Rating,
                'caffeine_type' => (string)$item->CaffeineType,
                'count' => (int)$item->Count,
                'flavored' => (string)$item->Flavored,
                'seasonal' => (string)$item->Seasonal,
                'instock' => (string)$item->Instock,
                'facebook' => (int)$item->Facebook,
                'is_kcup' => (int)$item->IsKCup,
            ];
        }

        return $items;
    }
}
