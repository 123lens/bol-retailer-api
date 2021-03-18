<?php
namespace Budgetlens\BolRetailerApi\Support;

class Arr
{
    /**
     * Replace column indexes with names.
     * Eg.
     * Input:
     *      Items:
     *      0 => [
     *          0 => 'foo',
     *          1 => 'bar'
     *      ]
     *      Headers:
     *      ['title', 'description']
     * Output:
     *      0 => [
     *          'title' => 'foo',
     *          'description' => 'bar'
     *      ]
     * @param array $items
     * @param array $headers
     * @return array
     */
    public static function replaceKeyWithNames(array $items, array $headers): array
    {
        $return = [];

        foreach ($items as $item) {
            $row = [];
            foreach ($item as $idx => $col) {
                $column = $headers[$idx] ?? $idx;
                $row[$column] = $col;
            }
            $return[] = $row;
        }

        return $return;
    }
}
