<?php

namespace Microsimulation\Journal\Controller;

use eLife\ApiSdk\Collection\ArraySequence;
use eLife\ApiSdk\Collection\EmptySequence;
use eLife\ApiSdk\Collection\PromiseSequence;
use eLife\ApiSdk\Collection\Sequence;
use eLife\ApiSdk\Model\Person;
use eLife\ApiSdk\Model\Subject;
use eLife\Journal\Exception\EarlyResponse;
use eLife\Journal\Helper\Callback;
use Microsimulation\Journal\Patterns\ViewModel\AboutProfile;
use Microsimulation\Journal\Patterns\ViewModel\AboutProfiles;
use Microsimulation\Journal\Patterns\ViewModel\ArticleSection;
use Microsimulation\Journal\Patterns\ViewModel\Button;
use Microsimulation\Journal\Patterns\ViewModel\ContentHeader;
use Microsimulation\Journal\Patterns\ViewModel\DefinitionList;
use Microsimulation\Journal\Patterns\ViewModel\FlexibleViewModel;
use Microsimulation\Journal\Patterns\ViewModel\FormLabel;
use Microsimulation\Journal\Patterns\ViewModel\IFrame;
use Microsimulation\Journal\Patterns\ViewModel\Link;
use Microsimulation\Journal\Patterns\ViewModel\ListHeading;
use Microsimulation\Journal\Patterns\ViewModel\Listing;
use Microsimulation\Journal\Patterns\ViewModel\Paragraph;
use Microsimulation\Journal\Patterns\ViewModel\SectionListing;
use Microsimulation\Journal\Patterns\ViewModel\SectionListingLink;
use Microsimulation\Journal\Patterns\ViewModel\SeeMoreLink;
use Microsimulation\Journal\Patterns\ViewModel\Select;
use Microsimulation\Journal\Patterns\ViewModel\SelectNav;
use Microsimulation\Journal\Patterns\ViewModel\SelectOption;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function GuzzleHttp\Promise\all;
use function GuzzleHttp\Promise\promise_for;

final class AboutController extends Controller
{
    public function aboutAction(Request $request) : Response
    {
        $arguments = $this->aboutPageArguments($request);

        $arguments['title'] = 'Aims and scope';

        $arguments['contentHeader'] = new ContentHeader($arguments['title']);

        $arguments['body'] = [
            new Paragraph('The International Journal of Microsimulation (IJM) is the official online peer-reviewed journal of the <a href="https://microsimulation.org">International Microsimulation Association</a>.'),
            new Paragraph('The IJM covers research in all aspects of microsimulation modelling. It publishes high quality contributions making use of microsimulation models to address specific research questions in all scientific areas, as well as methodological and technical issues.'),
            new Paragraph('In particular, the IJM invites submission of five types of contributions: research articles, research notes, data watch, book reviews, and software reviews.'),
            new Paragraph('<strong>Research articles</strong> of interest to the IJM concern:'),
            Listing::unordered([
                'the description, validation, benchmarking and replication of microsimulation models;',
                'results coming from microsimulation models, in particular policy evaluation and counterfactual analysis;',
                'technical or methodological aspect of microsimulation modelling;',
                'reviews of models and results, as well as of technical or methodological issues.',
            ], 'bullet'),
            new Paragraph('<strong>Research notes</strong> concern:'),
            Listing::unordered([
                'specific technical aspects of microsimulation modelling,',
                'short case-studies illustrating the application of microsimulation models and their impacts on policy-making;',
                'examples of good practice in microsimulation modelling.',
            ], 'bullet'),
            new Paragraph('<strong>Data watch</strong> refers to short research notes that describe (newly) available datasets and how they can be exploited for microsimulation studies.'),
            new Paragraph('<strong>Book reviews</strong> offer a discussion of recent books that might be of interest to the microsimulation community, or present a critical assessment in retrospect of the impact of "classic" contributions.'),
            new Paragraph('<strong>Software reviews</strong> are short contributions that describe advances in software development that are likely to be of interest to the journal readership, with a particular attention to open source software.'),
            new Paragraph('If in doubt concerning the suitability of a particular manuscript, or if interested in editing a Special thematic issue, please 
            <a href="mailto:matteo.richiardi@essex.ac.uk">contact the editor</a> for further advice'),
            Listing::unordered([
                'The IJM is listed in EBSCOhost, EconLit, RePEc, Scopus.',
                'The ISSN of the journal is 1747-5864.',
                'The IDEAS/RePEc journal page can be accessed from <a href="https://ideas.repec.org/s/ijm/journl.html">here</a>.',
                'The IDEAS/RePEc impact factor of the journal is 3.15 (December 2019).',
                'The journal ranking page can be accessed from <a href="https://ideas.repec.org/top/top.series.simple.html#repec:ijm:journl">here</a>.',
            ], 'bullet'),
        ];

        return new Response($this->get('templating')->render('::about.html.twig', $arguments));
    }

    public function boardAction(Request $request) : Response
    {
        $arguments = $this->aboutPageArguments($request);

        $arguments['title'] = 'Editorial Board';

        $arguments['contentHeader'] = new ContentHeader($arguments['title'], null,
            '');
        $arguments['body'] = [
            new Paragraph('The editors, associate editors and editorial board of the International Journal of Microsimulation are appointed through the auspices of the International Microsimulation Association. All are appointed to serve for a two-year period, during which they commit themselves to (i) seeking out and encouraging submission of work likely to be of interest to the journal readership; (ii) undertaking reviews of submitted manuscripts; (iii) providing guidance to the editor on future directions for the journal. In addition, Associate Editors take responsibility for facilitating the review and editing of submitted manuscripts falling within their area of subject specialism.  Nominations for all of these posts are sought in the run-up to the biennial IMA General Conference via the IMA-NEWS email discussion list.'),
            new Paragraph('<strong>Chief Editor</strong> - Prof Matteo Richiardi (University of Essex, UK), <a href="mailto:matteo.richiardi@essex.ac.uk">matteo.richiardi@essex.ac.uk</a>'),
            new Paragraph('<strong>Assistant Editors</strong>'),
            Listing::unordered([
                'Patryk Bronka (University of Essex, UK)',
                'Dr Melanie Tomintz (University of Canterbury, New Zealand)',
            ], 'bullet'),
            new Paragraph('<strong>Book Review Editor</strong> - Dr Gijs Dekkers (Federal Planning Bureau, Belgium)'),
            new Paragraph('<strong>Associate Editors</strong>'),
            Listing::unordered([
                'Prof Francesco Figari (Università dell\'Insubria, Italy)',
                'Dr Jakob Grazzini (Università di Pavia, Italy)',
                'Dr Philipp Harting (Bielefeld University)',
                'Dr Sophie Pennec (Institut National d\'Etudes Démographiques, France)',
                'Dr Azizur Rahman (Charles Sturt University, Australia)',
                'Prof Luc Savard (Universit&eacute de Sherbrooke, Canada)',
                'Prof Deborah Schofield (University of Sydney, Australia)',
                'Dr Sven Stöwhase (FIT, Germany)',
                'Dr Gerlinde Verbist (Antwerp University, Belgium)',
                'Jürgen Wiemers (IAB, Germany)',
            ], 'bullet'),
            new Paragraph('<strong>Scientific Committee</strong>'),
            Listing::unordered([
                'Prof Rolf Aaberge (Statistics Norway)',
                'Prof Jakub Bijak (Univesity of Southampton, UK)',
                'Prof Francesco Billari (University of Oxford, UK)',
                'Prof Ugo Colombino (University of Torino, Italy)',
                'Prof John Creedy (University of Melbourne, Australia)',
                'Prof André Decoster (University of Leuven, Belgium)',
                'Dr Gijs Dekkers (Federal Panning Bureau, Belgium)',
                'Prof Lennart Flood (University of Gothenburg, Sweden)',
                'Prof Cathal O\'Donoghue (Teagasc, Ireland)',
                'Prof Andreas Peichl (University of Mannheim, Germany)',
                'Prof Nicole Saam (Erlangen University, Germany)',
                'Prof Holly Sutherland (University of Essex, UK)',
                'Prof Leigh Tesfatsion (Iowa State University, USA)',
                'Prof Michael Wolfson (University of Ottawa, Canada)',
            ], 'bullet'),
        ];

        return new Response($this->get('templating')->render('::about.html.twig', $arguments));
    }

    public function submissionPolicyAction(Request $request) : Response
    {
        $arguments = $this->aboutPageArguments($request);

        $arguments['title'] = 'Editorial Policy';

        $arguments['contentHeader'] = new ContentHeader($arguments['title']);

        $arguments['body'] = [
            ArticleSection::basic('Submissions', 2,
                $this->render(
                    new Paragraph('Information about the types of contributions of interest to the journal is contained in the <a href="'.$this->get('router')->generate('about').'">Aims and scope</a> page. If in doubt concerning the suitability of a particular manuscript, please contact the editor for further advice.'),
                    new Paragraph('All submitted research notes and research articles will be subject to peer review by at least two independent referees appointed by the Editor. Other items will be accepted for publication subject to review by at least two members of the Editorial board.'),
                    new Paragraph('Submission implies that the work described has not been published previously (except in the form of an abstract or as part of a published lecture or academic thesis or as an electronic preprint or working paper), that it is not under consideration for publication elsewhere, that its publication is approved by all authors and tacitly or explicitly by the responsible authorities where the work was carried out, and that, if accepted, it will not be published elsewhere in the same form, in English or in any other language, including electronically without the written consent of the copyright-holder.'),
                    new Paragraph('<strong>Submission guidelines</strong>'),
                    Listing::unordered([
                        'Submissions must be in English',
                        'We strongly recommend that research articles do not exceed 15,000 words, and that research notes do not exceed 5,000 words. The suggested length includes reference lists, figures, and tables. Excessively long submissions may be summarily rejected, or authors requested to cut them down to the suggested length.',
                        'All manuscript should be 1.5 spaced and use 12-point Times New Roman or similar font. Margins should be 1.5 inches on the top, bottom, and sides.',
                        'Research articles should containt an abstract of no more than 250 words, while the abstract for research notes should not exceed 100 words.',
                        'All manuscripts should be submitted in PDF format.', 
                    ], 'bullet')       
                )
            ),
            ArticleSection::basic('Fees', 2,
                $this->render(
                    new Paragraph('The International Journal of Microsimulation is open access.'),
                    new Paragraph('It is the current policy of the International Microsimulation Association to waive any submission or publication fees.')
                )
            ),
            ArticleSection::basic('Ethics in publishing', 2,
                $this->render(new Paragraph('The IJM supports the ethical principles set out by the <a href="http://publicationethics.org/resources/guidelines">Committee on Publication Ethics (COPE).</a>')
                )
            ),
            ArticleSection::basic('Conflict of interest', 2,
                $this->render(new Paragraph('All Authors are requested to disclose any actual or potential conflict of interest. Further information and can be found in COPE author guidelines.')
                )
            ),
            ArticleSection::basic('Data and code availability', 2,
                $this->render(
                    new Paragraph('It is the policy of the IJM to publish papers only if the data and code used in the analysis are clearly and precisely documented and access to the data and code is non-exclusive to the authors.'),
                    new Paragraph('Authors of accepted papers that contain empirical work, simulations, or experimental work must provide, prior to acceptance, information about the data, programs, and other details of the computations sufficient to permit replication, as well as information about access to data and programs.
                    In particular, authors are required to report, for any data they use, which is the source and whether the data is:'),
                    Listing::ordered([
                        'publicly available (specifying how the data can be accessed);',
                        'available for scientific research only upon registration;',
                        'proprietary (specifying the nature of the data and the user agreement which they benefited from).',
                    ], 'number'),
                    new Paragraph('If the paper is model-based, authors are also required to specify whether the code is:'),
                    Listing::ordered([
                        'open-source;',
                        'proprietary, with executable available;',
                        'proprietary, with executable also not available.',
                    ], 'number'),
                    new Paragraph('If data or programs cannot be published in an openly accessible trusted data repository, authors must commit to preserving data and code for a period of no less than five years following publication of the manuscript, and to providing reasonable assistance to requests for clarification and replication.'),
                    new Paragraph('The journal encourages the use of open-source software and the publication of the source code.')
                )
            ),     
            ArticleSection::basic('Participants and participant consent', 2,
                $this->render(
                    new Paragraph('All submitted manuscripts containing research which involves human participants and/or animal experimentation - however unlikely this is given the scope of the journal - must include a statement confirming that the research was carried out in accordance with the principles embodied in the <a href="https://www.wma.net/policies-post/wma-declaration-of-helsinki-ethical-principles-for-medical-research-involving-human-subjects/"> Declaration of Helsinki</a> and all relevant guidelines and institutional policies, giving the name of the institutional and/or national research ethics committee that approved the research, along with the approval number/ID.'),
                    new Paragraph('All submitted manuscripts containing research which involves identifiable human subjects must include a statement confirming that consent was obtained for all identifiable individuals and that any identifiable individuals are aware of intended publication. In order to protect participant anonymity, authors do not need to send proof of this consent to the IJM.')
                )
            ),            
            ArticleSection::basic('Copyright', 2,
                $this->render(new Paragraph('All IJM articles, unless otherwise stated, are published under the terms of the Creative Commons Attribution (CC BY) License which permits use, distribution and reproduction in any medium, provided the work is properly attributed back to the original author and publisher. Copyright on any research article in the International Journal ofMicrosimulation(IJM) is retained by the Authors. Authors grant IJM a license to publish the article and identify itself as the original publisher. Authors cannot revoke these freedoms as long as the Journal follows the license terms. Authors should not submit any paper unless they agree with this policy. The full text of the CC BY 4.0 license can be found here. Special exemptions and other licensing arrangement can be made on a case by case basis, by writing a motivated request to the Editor.')
                )
            ),
            ArticleSection::basic('Authors rights', 2,
                $this->render(new Paragraph('Upon publication, contributors will retain the rights including but not limited to the following, as permitted by the CC BY license:')
                )
            ),
            Listing::unordered([
                'The rights to reproduce, distribute, publicly perform, and publicly display the Contribution in any medium for non-commercial purposes.',
                'The right to prepare derivative works from the Contribution, including reuse parts of the Contribution (e.g. figures and excerpts from an article) so long as the Authors receives credit as authors and the IJM is appropriately cited as the source of first publication.',
                'Patent and trademark rights and rights to any process or procedure described in the Contribution.',
                'The right to proper attribution and credit for the published work.',
            ], 'bullet'),
            ArticleSection::basic('Disclaimer', 2,
                $this->render(new Paragraph('The International Microsimulation Association (IMA) and the International Journal of Microsimulation (IJM) and make every effort to ensure the accuracy of all the information contained in our publications. It however, makes no representations or warranties whatsoever as to the accuracy, completeness, or suitability for any purpose of the published work. Any opinions and views expressed in this publication are the opinions and views of the Authors, and are not necessarily the view of the Editors or the Journal.')
                )
            ),
            ArticleSection::basic('Contact information', 2,
                $this->render(new Paragraph('For questions, please contact the Editor Matteo Richiardi at <a href="mailto:matteo.richiardi@essex.ac.uk">matteo.richiardi@essex.ac.uk</a>') 
                )
            ),            
        ];
        return new Response($this->get('templating')->render('::about.html.twig', $arguments));
    }

    public function reviewerNotesAction(Request $request) : Response
    {
        $arguments = $this->aboutPageArguments($request);

        $arguments['title'] = 'Notes for reviewers';

        $arguments['contentHeader'] = new ContentHeader($arguments['title'], null,
            '');

        $arguments['body'] = [
            new Paragraph('The primary purpose of the review process is to ensure that papers accepted for publication by the journal meet the highest academic standards of clarity, rigour and replicability. A secondary purpose of the review process is to provide constructive feedback for authors whose papers fall short of this mark.'),
            new Paragraph('The general expectation is that all reviewer feedback will be passed on, in an anonymised form, to the submitting author(s). Reviewers are therefore asked to ensure that their comments are suitably constructive, and to highlight any comments that they would prefer remained confidential to the editor.'),
            new Paragraph('In providing feedback on a paper, reviewers are asked make a clear overall recommendation (publish; publish subject to minor revision; publish subject to major revision; revise and resubmit; reject). This recommendation should be followed by a justification for the recommendation made, including at least brief reference to each of:'),
            Listing::unordered([
                'Originality',
                'Validity of methods, results and interpretations',
                'Relevance to journal readership',
                'Clarity and structure of narrative',
                'Quality and appropriateness of any tables or figures'
            ], 'bullet'),
            new Paragraph('If revision prior to publication or resubmission is recommended, reviewers are asked to provide a list of points that the submitting author(s) should be asked to address.'),
            new Paragraph('In order to allow for timely publication, reviewers are asked to provide comments on submitted items within the agreed review deadline (normally four weeks after receipt of item).'),

        ];

        return new Response($this->get('templating')->render('::about.html.twig', $arguments));
    }
    
    public function authorNotesAction(Request $request) : Response
    {
        $arguments = $this->aboutPageArguments($request);
        
        $arguments['title'] = 'Special issues';
        
        $arguments['contentHeader'] = new ContentHeader($arguments['title']);
        $arguments['body'] = [
            ArticleSection::basic('Special issue on Covid-19 microsimulation research', 2, $this->render(
                new Paragraph('The IJM will publish a special issue of the journal on Covid-19 microsimulation research, based on the <a href="https://www.microsimulation.org/events/2020_online_covid/">Microsimulation modelling of policy responses to COVID-19</a> workshop organised by the International Microsimulation Association on December 2-3, 2020.')
                )),
            ArticleSection::basic('Special issue on WEHIA2021 - Workshops on Economics with Heterogeneous Interacting Agents', 2, $this->render(
                new Paragraph('The IJM will also publish a special issue with selected articles presented at the <a href="https://centridiricerca.unicatt.it/complexity-the-complexity-lab-in-economics-cle-wehia-2017#content">WEHIA2021 workshops</a> (28-29 June 2021, 10 September 2021, 15 October 2021, 19 November 2021)'), 
                new Paragraph('For more information, or if you are interested in editing a special issue, please <a href="mailto:matteo.richiardi@essex.ac.uk">contact the editor</a>.')
                )),
        ];      
        
        return new Response($this->get('templating')->render('::about.html.twig', $arguments));
    }
    
    private function aboutPageArguments(Request $request) : array
    {
        $arguments = $this->defaultPageArguments($request);

        $arguments['menuLink'] = new SectionListingLink('All sections', 'sections');

        $menuItems = [
            'Aims and scope' => $this->get('router')->generate('about'),
            'Editorial board' => $this->get('router')->generate('about-board'),
            'Editorial policy' => $this->get('router')->generate('about-submission'),
            'Notes for reviewers' => $this->get('router')->generate('about-reviewers'),
            'Call for papers' => $this->get('router')->generate('about-authors'),
        ];

        $currentPath = $this->get('router')->generate($request->attributes->get('_route'), $request->attributes->get('_route_params'));

        $menuItems = array_map(function (string $text, string $path) use ($currentPath) {
            return new Link($text, $path, $path === $currentPath);
        }, array_keys($menuItems), array_values($menuItems));

        $arguments['menu'] = new SectionListing('sections', $menuItems, new ListHeading('About sections'), true);

        return $arguments;
    }
}
