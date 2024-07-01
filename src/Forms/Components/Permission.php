<?php

namespace Hexters\HexaLite\Forms\Components;

use Filament\Facades\Filament;
use Filament\Forms\Components\Field;
use Hexters\HexaLite\Helpers\Can;
use Illuminate\Support\Str;

class Permission extends Field
{
    protected string $view = 'filament-hexa::forms.components.permission';

    public $pages = [];
    public $resources = [];
    public $widgets = [];
    public $clusters = [];
    public $clusterComponents = [];
    public $additionals = [];

    public static function make(string $name): static
    {
        $make = parent::make($name);
        $panel = Filament::getCurrentPanel();

        $pages = collect(self::getClusterComponents($panel))->filter(fn ($item) => Str::of($item)->contains('\\Pages\\'));
        $resources = collect(self::getClusterComponents($panel))->filter(fn ($item) => Str::of($item)->contains('\\Resources\\'));

        $make->pages = collect($panel->getPages())
            ->filter(fn ($item) => method_exists(app($item), 'getPermissionId') && !in_array($item, $pages->toArray()))
            ->map(fn ($item) => [
                'id' => app($item)->getPermissionId(),
                'name' => app($item)->getTitlePermission(),
                'description' => app($item)->getDescriptionPermission(),
                'subs' => app($item)->getSubPermissions(),
            ]);


        $make->resources = collect($panel->getResources())
            ->filter(fn ($item) => method_exists(app($item), 'getPermissionId') && !in_array($item, $resources->toArray()))
            ->map(fn ($item) => [
                'id' => app($item)->getPermissionId(),
                'name' => app($item)->getTitlePermission(),
                'description' => app($item)->getDescriptionPermission(),
                'subs' => app($item)->getSubPermissions(),
            ]);

            
        return $make;
    }

    protected static function getClusterComponents($panel)
    {
        $components = collect();
        foreach ($panel->getClusteredComponents() as $cluster => $items) {
            $components->push($cluster);
            foreach ($items as $item) {
                $components->push($item);
            }
        }

        return $components;
    }

    public function getAdditioinal()
    {
        return $this->evaluate($this->additionals);
    }

    public function getPages()
    {
        return $this->evaluate($this->pages);
    }

    public function getClusters()
    {
        return $this->evaluate($this->clusters);
    }

    public function getResources()
    {
        return $this->evaluate($this->resources);
    }

    public function getWidgets()
    {
        return $this->evaluate($this->widgets);
    }
}
