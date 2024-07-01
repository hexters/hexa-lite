<?php

namespace Hexters\HexaLite\Helpers;

use Illuminate\Support\Str;

class Can
{
    protected $gateName;
    protected $gateDesc;

    public function __construct(
        protected string $id
    ) {
    }

    public static function make($id)
    {
        return app(self::class, ['id' => $id]);
    }

    public function name($name)
    {
        $this->gateName = $name;

        return $this;
    }

    public function description($description)
    {
        $this->gateDesc = $description;

        return $this;
    }

    public function get()
    {
        return [
            'id' => $this->id,
            'name' => $this->gateName ?? Str::of($this->id)->replace('.', ' ')->headline()->value(),
            'description' => $this->gateDesc,
            'subs' => []
        ];
    }
}
