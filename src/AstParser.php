<?php

namespace Osbre\FabricSchema;

class AstParser
{
    /** @var array<string, string> */
    protected array $objects;

    /** @var string[] */
    protected array $typesMapping = [
        'Number'  => 'int',
        'String'  => 'string',
        'Object'  => 'array',
        'Array'   => 'array',
        'Boolean' => 'bool',
        'any'     => '',
    ];

    public function parse(array $astData): array
    {
        $fileNames = $this->getFileNames();

        foreach ($astData as $item) {
            if ($item->kind !== 'member') {
                continue;
            }

            if ($item->scope !== 'instance') {
                continue;
            }

            // Underscored properties are private. We don't need to include them.
            if (str_starts_with($item->name, '_')) {
                continue;
            }

            $fileName = $item->meta?->filename;

            if (!in_array($fileName, $fileNames, true)) {
                continue;
            }

            $type = $item->type->names[0] ?? '';

            $this->objects[$fileName][$item->name] = $this->transformType($type);
        }

        return $this->objects;
    }

    /** @return string[] */
    protected function getFileNames(): array
    {
        return [
            'itext.class.js',
            'image.class.js',
            'polyline.class.js',
            'polygon.class.js',
            'object.class.js',
            'path.class.js',
            'line.class.js',
            'rect.class.js',
            'text.class.js',
            'triangle.class.js',
            'textbox.class.js',
            'ellipse.class.js',
            'group.class.js',
            'circle.class.js',
        ];
    }

    protected function transformType(string $type): ?string
    {
        return match ($type) {
            'Number' => 'int',
            'String' => 'string',
            'Object', 'Array' => 'array',
            'Boolean' => 'bool',
            default => null,
        };
    }
}
