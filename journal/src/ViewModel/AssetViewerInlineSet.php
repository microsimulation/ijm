<?php

namespace Microsimulation\Journal\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\AssetViewerInline;

final class AssetViewerInlineSet implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $viewers;

    public function __construct(AssetViewerInline ...$viewers)
    {
        $this->viewers = $viewers;
    }

    public function getTemplateName() : string
    {
        return 'patterns/asset-viewer-inline-set.mustache';
    }
}
