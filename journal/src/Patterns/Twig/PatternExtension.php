<?php

namespace Microsimulation\Journal\Patterns\Twig;

use Microsimulation\Journal\Patterns\PatternRenderer;
use Microsimulation\Journal\Patterns\ViewModel;
use Twig_Extension;
use Twig_Function;

final class PatternExtension extends Twig_Extension
{
    private $renderer;

    public function __construct(PatternRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions()
    {
        return [
            new Twig_Function(
                'render_pattern',
                [$this, 'renderPattern'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function renderPattern(ViewModel $viewModel) : string
    {
        return $this->renderer->render($viewModel);
    }
}
