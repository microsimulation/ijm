<?php

namespace Microsimulation\Journal\Controller;

use Microsimulation\Journal\Patterns\ViewModel\ContentHeader;
use Microsimulation\Journal\Patterns\ViewModel\Paragraph;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AboutController extends Controller
{
    public function aboutAction(Request $request) : Response
    {
        $arguments = $this->defaultPageArguments($request);

        $arguments['title'] = 'About';

        $arguments['contentHeader'] = new ContentHeader('About');

        $arguments['body'] = [
            new Paragraph(
                'The International Journal of Microsimulation (IJM) is the official online peer-reviewed journal of the International Microsimulation Association.'
            ),
            new Paragraph(
                'The IJM covers research in all aspects of microsimulation modelling. It publishes high quality contributions making use of microsimulation models to address specific research questions in all scientific areas, as well as methodological and technical issues.'
            ),
            new Paragraph(
                'In particular, the IJM invites submission of five types of contributions: research articles, research notes, data watch, book reviews, and software reviews.'
            ),
        ];

        return new Response($this->get('templating')->render('::about.html.twig', $arguments));
    }
}
