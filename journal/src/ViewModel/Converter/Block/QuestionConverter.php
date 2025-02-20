<?php

namespace Microsimulation\Journal\ViewModel\Converter\Block;

use eLife\ApiSdk\Model\Block;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\PatternRenderer;
use Microsimulation\Journal\Patterns\ViewModel;

final class QuestionConverter implements ViewModelConverter
{
    private $viewModelConverter;
    private $patternRenderer;

    public function __construct(ViewModelConverter $viewModelConverter, PatternRenderer $patternRenderer)
    {
        $this->viewModelConverter = $viewModelConverter;
        $this->patternRenderer = $patternRenderer;
    }

    /**
     * @param Block\Question $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $context['level'] = ($context['level'] ?? 1) + 1;

        return ViewModel\ArticleSection::basic(
            $object->getQuestion(),
            $context['level'],
            implode('', array_map(function (Block $block) use ($context) {
                return $this->patternRenderer->render($this->viewModelConverter->convert($block, null, $context));
            }, $object->getAnswer())),
            null,
            null,
            $context['isFirst'] ?? false
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Block\Question;
    }
}
