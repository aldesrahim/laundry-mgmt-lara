<?php

namespace App\Services\ReportTemplate\Components;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

abstract class Component implements Htmlable
{
    protected ?string $id = null;

    protected string $view;

    protected array $data = [];

    /**
     * @throws Throwable
     */
    public function toHtml(): string
    {
        return $this->getView()->render();
    }

    public function getView(): View
    {
        return view($this->view, $this->getData());
    }

    public function getData(): array
    {
        if (!isset($this->data['id'])) {
            $this->data['id'] = $this->getId();
        }

        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getId(): string
    {
        if (blank($this->id)) {
            $className = static::class;
            $this->id = strtolower(Str::of($className)->remove('\\')->kebab()) . '-' . Str::random(6);
        }

        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }
}
