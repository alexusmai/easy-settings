<?php

namespace Alexusmai\EasySettings\Traits;


trait SchemaService
{
    /**
     * Create schema array
     *
     * @param $data
     *
     * @return array
     */
    public function createSchemaArray($data)
    {
        $schema = [];
        $count = count($data['name']);

        for ($i = 0; $count > $i; $i++) {
            $schema[] = [
                'name'        => $data['name'][$i],
                'description' => $data['description'][$i],
                'type'        => $data['type'][$i],
                'rules'       => $data['rules'][$i] ? $data['rules'][$i] : null,
            ];
        }

        return $schema;
    }


    /**
     * Create rules array for validation settings
     *
     * @param $group
     *
     * @return array
     */
    public function createRulesArray($group)
    {
        $rules = [];

        foreach ($group->schema as $item) {
            // if rules exist
            if ($item['rules']) {
                // lang type - create array of rules
                if ($item['type'] === 'lang') {
                    $rules[$item['name'].'.*'] = $item['rules'];
                } else {
                    $rules[$item['name']] = $item['rules'];
                }
            }
        }

        return $rules;
    }
}
