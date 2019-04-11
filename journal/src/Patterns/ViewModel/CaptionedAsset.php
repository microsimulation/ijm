<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;
use InvalidArgumentException;

final class CaptionedAsset implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $captionText;
    private $picture;
    private $video;
    private $table;
    private $image;
    private $iframe;
    private $doi;
    private $inline;

    public function __construct(
        IsCaptioned $figure,
        CaptionText $captionText = null,
        Doi $doi = null,
        bool $inline = false
    ) {
        $this->captionText = $captionText;
        $this->setFigure($figure);
        if (null !== $doi) {
            $doi = FlexibleViewModel::fromViewModel($doi);
            $this->doi = $doi->withProperty('variant', Doi::ASSET);
        }
        if ($inline) {
            $this->inline = $inline;
        }
    }

    private function setFigure($figure)
    {
        // Reverse switch (i.e. which evaluates to true)
        switch (true) {
            case $figure instanceof Image:
                $this->image = $figure;
                break;

            case $figure instanceof Picture:
                $this->picture = $figure;
                break;

            case $figure instanceof Video:
                $this->video = $figure;
                break;

            case $figure instanceof Table:
                $this->table = $figure;
                break;

            case $figure instanceof IFrame:
                $this->iframe = $figure;
                break;

            default:
                throw new InvalidArgumentException('Unknown figure type '.get_class($figure));
        }
    }

    public function getTemplateName() : string
    {
        return 'patterns/captioned-asset.mustache';
    }
}
