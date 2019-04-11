<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class DownloadLink implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $name;
    private $url;
    private $fileName;

    private function __construct(string $name, string $url, string $fileName = null)
    {
        $this->name = $name;
        $this->url = $url;
        $this->fileName = $fileName;
    }

    public static function fromLink(Link $link, string $fileName = null)
    {
        return new static($link['name'], $link['url'], $fileName);
    }
}
