<?php

namespace App\Services\ReportTemplate\Components;

use Exception;

class Divider extends Component
{
    protected string $view = 'report-templates.components.divider';

    protected array $availableTypes = ['thick', 'thin'];

    /**
     * @param string $type Available types: thick, thin
     * @throws Exception
     */
    public function __construct(string $type)
    {
        if (!in_array($type, $this->availableTypes)) {
            throw new Exception('Divider type must be one of ' . implode(', ', $this->availableTypes));
        }

        $this->data = [
            'type' => $type,
        ];
    }

    public static function thick(): static
    {
        return new static('thick');
    }

    public static function thin(): static
    {
        return new static('thin');
    }
}
