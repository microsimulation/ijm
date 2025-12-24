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
            new Paragraph('The journal is published 3 times per year, with a Spring, Summer and Winter issues.'),
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
			new Paragraph('The journal also publishes thematic special issues containing historically relevant work for the microsimulation community, previously unpublished in journal or book form, such as documentation of landmark models. Occasionally, the journal might also republish relevant chapters from edited volumes, with permission from the original publisher, to facilitate access to important early microsimulation work. The original source of publication is always clearly indicated in the published articles.'),
			new Paragraph('If in doubt concerning the suitability of a particular manuscript, or if interested in editing a Special thematic issue, please <a href="mailto:ijm-editor@microsimulation.org">contact the editor</a> for further advice.'),
            Listing::unordered([
                'The IJM is listed in EBSCOhost, EconLit, RePEc, Scopus.',
                'The ISSN of the journal is 1747-5864.',
                'The IDEAS/RePEc journal page can be accessed from <a href="https://ideas.repec.org/s/ijm/journl.html">here</a>.',
                'The IDEAS/RePEc impact factor of the journal is 3.9 (December 2025).',
                'The journal ranking page can be accessed from <a href="https://ideas.repec.org/top/top.series.simple.html#repec:ijm:journl">here</a>.',
            ], 'bullet'),
            new Paragraph('---'),
            new Paragraph('<strong>Publisher:</strong>'),
            new Paragraph('International Microsimulation Association'),
            new Paragraph('11 Porte Des Sciences, Esch-Sur-Alzette L-4366, Luxembourg.'),
            new Paragraph('<a href="https://www.microsimulation.org/contact/">Contact information</a>'),
			new Paragraph('For enquiries about the journal, write to <a href="mailto:ijm-enquiries@microsimulation.org">ijm-enquiries@microsimulation.org</a>.'),
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
            new Paragraph('<strong>Chief Editor</strong> - Prof Matteo Richiardi (University of Essex, UK), <a href="mailto:ijm-editor@microsimulation.org">ijm-editor@microsimulation.org</a>'),
            new Paragraph('<strong>Managing Editor</strong> - Prof Michael Christl (Universidad Loyola Andalucia, Spain), <a href="mailto:ijm-managing@microsimulation.org">ijm-managing@microsimulation.org</a>'),
            new Paragraph('<strong>Assistant Editor</strong>'),
            Listing::unordered([
                'Natasha Brooks (University of Essex, UK)'
            ], 'bullet'),
            new Paragraph('<strong>Book Review Editor</strong> - Dr Gijs Dekkers (Federal Planning Bureau, Belgium)'),
            new Paragraph('<strong>Associate Editors</strong>'),
            Listing::unordered([
                'Prof Federico Belotti (University of Rome Tor Vergata, Italy)',
                'Dr Corinna Elsembroich (University of Glasgow, United Kingdom)',
                'Prof Francesco Figari (Università dell\'Insubria, Italy)',
                'Prof Amadeo Fuenmayor (University of Valencia, Spain)',
                'Dr Benedikt Goderis (SCP - Netherlands Institute for Social Research, The Netherlands)',
                'Dr Jakob Grazzini (Università di Pavia, Italy)',
                'Dr Philipp Harting (Bielefeld University, Germany)',
                'Prof Nicolas Hérault (Bordeaux School of Economics, France)',
                'Dr Jason Hilton (University of Southampton, United Kingdom)',
                'Prof Seiichi Inagaki (International University of Health and Welfare, Japan)',
                'Prof Giulia Iori (City University, United Kingdom)',
                'Dr Tanja Kirn (University of Liechtenstein, Liechtenstein)',
                'Dr Daniel Kopasker (Glasgow University, United Kingdom)',
                'Prof Nik Lomax (University of Leeds, United Kingdom)',
                'Dr Chrysa Leventi (JRC - Joint Research Centre, Spain)',
                'Dr Sophie Pennec (Institut National d\'Etudes Démographiques, France)',
                'Dr Azizur Rahman (Charles Sturt University, Australia)',
                'Dr Mary Ryan (Teagasc - Agriculture and Food Development Authority, Ireland)',
                'Prof Luc Savard (Universit&eacute de Sherbrooke, Canada)',
                'Prof Rupendra Shresta (Macquarie University, Australia)',
                'Dr Agathe Simon (ESRI - Economic and Social Research Institute, Ireland)',
                'Dr Eric Silverman (University of Glasgow, United Kingdom)',
                'Dr Sven Stöwhase (Fraunhofer FIT, Germany)',
                'Prof Javier Torres Gomez (Universidad del Pacífico, Peru)',
                'Prof Bryan Tysinger (University of Southern California, United States)',
                'Dr Gerlinde Verbist (Antwerp University, Belgium)',
                'Jürgen Wiemers (IAB - Institute for Employment Research, Germany)',
                'Dr Jesse Wiki (University of Auckland, New Zeland)',
            ], 'bullet'),
            new Paragraph('<strong>Scientific Committee</strong>'),
            Listing::unordered([
                'Prof Rolf Aaberge (Statistics Norway)',
                'Prof Jakub Bijak (Univesity of Southampton, United Kingdom)',
                'Prof Ugo Colombino (University of Torino, Italy)',
                'Prof John Creedy (University of Wellington, New Zealand)',
                'Prof André Decoster (University of Leuven, Belgium)',
                'Dr Gijs Dekkers (Federal Panning Bureau, Belgium)',
                'Prof Lennart Flood (University of Gothenburg, Sweden)',
                'Prof Nora Lustig (University of Tulane, United States)',
                'Prof Cathal O\'Donoghue (Teagasc, Ireland)',
                'Prof Deborah Schofield (Macquarie University, Australia)',
                'Prof Leigh Tesfatsion (Iowa State University, United States)',
            ], 'bullet'),
        ];

        return new Response($this->get('templating')->render('::about.html.twig', $arguments));
    }

    public function editorialPolicyAction(Request $request) : Response
    {
        $arguments = $this->aboutPageArguments($request);

        $arguments['title'] = 'Editorial Policy';

        $arguments['contentHeader'] = new ContentHeader($arguments['title']);

        $arguments['body'] = [
            ArticleSection::basic('Submissions', 2,
                $this->render(
                    new Paragraph('Information about the types of contributions of interest to the journal is contained in the <a href="'.$this->get('router')->generate('about').'">Aims and scope</a> page. If in doubt concerning the suitability of a particular manuscript, please contact the editor for further advice.'),
                    new Paragraph('Submission implies that the work described has not been published previously (except in the form of an abstract or as part of a published lecture or academic thesis or as an electronic preprint or working paper), that it is not under consideration for publication elsewhere, that its publication is approved by all authors and tacitly or explicitly by the responsible authorities where the work was carried out, and that, if accepted, it will not be published elsewhere in the same form, in English or in any other language, including electronically without citing the IJM as the original source of publication (see <a href="#authors_rights">Authors rights</a> below).'),
                    new Paragraph('We strongly encourage recent PhD graduates to submit their work to the International Journal of Microsimulation. Our policy is to take into account the fact that recent graduates are less experienced in the process of writing and submitting papers.'),
                    new Paragraph('<strong>Submission guidelines</strong>'),
                    Listing::unordered([
                        '<strong>Language and style.</strong> Submissions must be in English, with a style adequate to a scientific journal. A submission may be rejected solely because typographical errors and imprecise definitions make it hard to understand.',
                        '<strong>Length.</strong> Article types are listed in the <a href="'.$this->get('router')->generate('about').'">Aims and scope</a> page. We strongly recommend that research articles do not exceed 15,000 words, and that research notes do not exceed 5,000 words, excluding tables, figures, algorithms, and references, but including appendixes. Excessively long submissions may be summarily rejected, or authors requested to adjust them to the suggested length.',
                        '<strong>Abstract.</strong> Research articles should contain an abstract of no more than 250 words, while the abstract for research notes should not exceed 100 words.',
                        '<strong>Keywords.</strong> A maximum of four keywords, separated by comma, should be presented below the abstract, preceded by one blank line (or equivalent spacing).',
                        '<strong>Font, spacing, margins.</strong> All manuscript should be 1.5 spaced and use Times New Roman font. Font size should be either 11 or 12 points. Margins should be 1.5 inches on the top, bottom, and sides.',
                        '<strong>Structure.</strong> A standard structure for research articles is suggested but not enforced, with sections covering introduction, methods, data, results, discussion, and conclusions.',
                        '<strong>Sections.</strong> Use a maximum of three orders of headings, consecutively numbered, with levels separated by dots (e.g. ‘1’, ‘1.1’, and 1.1.1’). Sections should be referred to in the text with a capital letter and the section number.',
                        '<strong>Appendixes.</strong> Appendixes should be numbered as sections, but preceded by an "A" (e.g. A.1, A.2). Format title and text should as standard sections; continue page numbering; place at the end of the paper, after the section “References”.',
                        '<strong>Footnotes.</strong> Footnotes should use the same font as the text, size 10. Endnotes should be avoided.',
                        '<strong>Equations.</strong> Equations should be centre-aligned, consecutively numbered and referred to in the text as “Equation” with capital letter, followed by the equation number. Numbers should appear in parenthesis (brackets) on the right-hand margin.',
                        '<strong>References.</strong> References should follow the APA refence style.',
                        '<strong>Format.</strong> All manuscripts should be submitted in PDF format. Source code will be requested should the article be accepted for publication.',
                    ], 'bullet'),
                    new Paragraph('<strong>Tables, Figures and Algorithms</strong>'),
                    Listing::unordered([
                        'Tables, figures and algorithms are the only accepted floats, to be referred as such in the text, with capital letter.',
                        'Tables, figures and algorithms should be preceded by one blank line (or equivalent spacing).',
                        'Labels should commence with word "Table/Figure/Algorithm", followed by a sequential number (separate for the three floats), a colon, and the title. Labels should be placed on the line immediately preceding the float.',
                        'Text and numbers in tables, figures, and algorithms - including axes labels and axes titles in figures - should use font Arial or similar, size 10.',
                        'Tables, figures and algorithms should fit within specified page margins; if extra width required, present landscape.',
                        'Avoid borders around tables, figures and algorithms.',
                        'Colours can be used in figures and algorithms, but check before how they look like when printed in black-and-white or greyscale. Colours should be avoided for tables.',
                        'Sources and notes for tables, figures and algorithms should be placed immediately below the float, using font Times New Roman or similar, size 10.  The notes should make the float self-explanatory, without need to refer to the text.',
                        'Tables, figures and algorithms should be followed by one blank line (or equivalent spacing).',
                    ], 'bullet')
                )
            ),
            ArticleSection::basic('Fees', 2,
                $this->render(
                    new Paragraph('The International Journal of Microsimulation is open access. It is the current policy of the International Microsimulation Association to waive any submission or publication fees.')
                )
            ),
            ArticleSection::basic('Review process', 2,
                $this->render(
                    new Paragraph('Submissions should be made using the <a href="https://www.epress.ac.uk/ijm/webforms/author.php">online submission management system</a>. Authors will receive an acknowledgement of their submission.'),
					new Paragraph('Regular submissions are either managed directly by the Chief Editor, or assigned by the Chief Editor to an Associate Editor, provided that the Associate Editor is not in conflict of interest. Whenever the Chief Editor is in conflict of interest regarding a specific submission (for instance being an author, or a close collaborator of one of the authors), he or she will not manage the submission directly, but assign it to an Associate Editor who is not in conflict of interest.'),
					new Paragraph('Submissions for special issues are managed by the Guest Editors of the special issue. Whenever any of the Guest Editors is in conflict of interest regarding a specific submission (for instance being an author, or a close collaborator of one of the authors), the submission will be managed by the Chief Editor, following the rules described above.'),
					new Paragraph('As a general rule, submitted research notes and research articles will be subject to peer review by at least two independent reviewers appointed by the Editor in charge. Other items will be accepted for publication subject to review by at least two members of the editorial board. The identity of the reviewers and the handling editors will not be communicated to authors. On the other hand, reviewers will be communicated the names and affiliations of the authors (single-blind review process).'),
                    new Paragraph('However, some contributions might be desk-rejected by the Editor in charge without providing detailed reports. This is intended to save authors from waiting for an extended period, especially when it is apparent that the contribution is unsuitable, and to conserve the scarce resource of reviewers. Desk-rejection may occur when the contribution has evident errors or is too poorly written to assess its accuracy. Additionally, it may happen when the contribution falls outside the scope of the journal, or is clearly not innovative enough for the standards of the journal.'),
                    new Paragraph('In addition to guaranteeing the novelty, significance, and accuracy of the published work, the editorial team endeavours to minimize the time taken to publish it. Usually, we aim to send a first decision to authors within 2-3 months from submission, although some papers may take longer. Desk rejections are generally communicated to authors in a matter of days from submission.'),
                    new Paragraph('Manuscripts may be rejected, returned for revisions (major or minor), or accepted. Articles rarely go beyond three rounds of revisions. Unless otherwise stated by the Editor, conditional acceptance should not be presumed. An invitation to revise a paper remains valid for 12 months following the decision. The Editor may grant extensions to authors, but such requests must be made within the initial 12-month period. The decision letter sent to authors regarding revisions contains as much information as the Editor can provide. It is advisable for authors to refrain from contacting the Editor for additional guidance, except if seeking clarification on the decision letter.'),
                    new Paragraph('It is a policy of the International Journal of Microsimulation to always give authors detailed advice when providing feedback. We are grateful to our <a href="https://microsimulation.pub/about/editorial-board">international board of editors </a> and the many reviewers who support the journal for their constructive and timely reviews, thereby helping authors to improve their work and encouraging submissions of the highest quality.')
                )
            ),
            ArticleSection::basic('Accessibility of editorial files', 2,
                $this->render(
                    new Paragraph('Reviewers’ reports and cover letters will be retained in the editorial system, but non-handling Associate Editors will not have access to them. Editors and Associate Editors are obligated to keep all reports and cover letters confidential. The names of reviewers and cover letters are never shared with anyone, including authors, other reviewers, or non-handling Associate Editors. Additionally, any submission and related records are inaccessible to Editors or Associate Editors who have a conflict of interest.')
                )
            ),
            ArticleSection::basic('Ethics in publishing', 2,
                $this->render(
                    new Paragraph('The IJM supports the ethical principles set out by the <a href="http://publicationethics.org/resources/guidelines">Committee on Publication Ethics (COPE)</a> and the <a href="https://www.icmje.org/index.html">International Committee of Medical Journal Editors (ICMJE)</a>.'),
                    new Paragraph('<strong>Authorship:</strong> The journal adheres to the principles of responsible authorship as outlined by COPE. All authors are expected to have made substantial contributions to the research presented in their manuscript and to have approved the final version of the manuscript prior to submission. In addition, all authors must disclose any conflicts of interest and financial support related to the research presented in their manuscript. Anyone who made major contributions to the writing of the manuscript should be listed as an author (e.g. "ghost writing" is prohibited by the Journal). Any other individuals who made less substantive contributions to the experiment or the writing of the manuscript should be listed in the acknowledgement section. Any change in authorship (including author order) after the initial manuscript submission must be approved in writing by all authors.'),
                    new Paragraph('<strong>Authorship and "Umbrella" groups:</strong> Collaborative studies sometimes use a group name to represent all participants, and it is mandatory for each article to have at least one named author. If the authors want to acknowledge the umbrella group from which the work originate, they should first list the author(s) of the article and then add "on behalf of the GROUP NAME." If required, the names of the participants can be included in the Acknowledgements section.'),
                    new Paragraph('<strong>Plagiarism:</strong> The journal takes plagiarism very seriously and follows the guidelines for handling plagiarism outlined by COPE. All submitted manuscripts are screened for plagiarism using appropriate software. If plagiarism is detected during the review process, the manuscript may be rejected. If plagiarism is detected in published work, a formal correction or retraction may be required.'),
                    new Paragraph('<strong>Post-Publication Corrections:</strong> The journal recognizes the importance of ensuring the accuracy and integrity of the scientific record. If errors or inaccuracies are discovered after publication, the journal will issue a correction or clarification, as appropriate. Authors are encouraged to notify the journal of any errors or inaccuracies as soon as possible.'),
                    new Paragraph('<strong>Retractions:</strong> In case of evidence of scientific misconduct or fraudulent behaviour, violation of ethical guidelines, copyright infringement, or significant errors that cannot be resolved through correction or clarification, the International Microsimulation Association holds the authority to retract articles. A panel consisting of the Editor, two Associate Editors, and the current President of the Association will be formed to evaluate and decide on the proposed retraction. Additional details regarding COPE retraction guidelines can be obtained <a href="https://publicationethics.org/files/retraction-guidelines-cope.pdf">here</a>. Any retracted articles will be prominently marked and removed from the journal`s website and other databases.'),
                    new Paragraph('Overall, the journal is committed to upholding high ethical standards and promoting responsible scientific conduct.')
                )
            ),
            ArticleSection::basic('Conflict of interest', 2,
                $this->render(
                    new Paragraph('All authors are requested to disclose any actual or potential conflict of interest. In particular, every author must disclose any financial interests or support, any in kind support – such as providing access to data – and any connection, - direct or indirect - that could potentially create bias in the reported work or the stated opinions, conclusions, or implications. This includes relevant commercial or funding sources for the authors, their associated departments or organizations, personal relationships, or direct academic competition. This also includes support or pressures of any kind by any interested party, defined as any individual, group, or organization that has a financial, ideological, or political stake related to the article. If the support in question comes with a non-disclosure obligation, that fact should be stated, along with as much information as the obligation permits.'),
                    new Paragraph('In order to determine whether or not a conflict of interest should be declared, authors should apply the following test: “Is there any arrangement that would be embarrassing for any of the authors if it were to come to light after publication and had not been disclosed?”'),
                    new Paragraph('A disclosure statement for each of the authors should be added at the end of the submitted manuscript. If authors fail to disclose pertinent information during the submission process, the acceptance decision may be reversed. In the event that the article has already been published, the journal retains the right to publish a notice on their website informing readers that the authors have violated the journal`s policy regarding disclosure. Further information and can be found in the <a href="https://publicationethics.org/guidance/Guidelines">COPE author guidelines</a>.')
                )
            ),
            ArticleSection::basic('Data and code availability', 2,
                $this->render(
                    new Paragraph('It is the policy of the IJM to publish papers only if the data and code used in the analysis are clearly and precisely documented and access to the data and code is non-exclusive to the authors.'),
                    new Paragraph('Authors of accepted papers that contain empirical work, simulations, or experimental work must provide, prior to acceptance, information about the data, programs, and other details of the computations sufficient to permit replication, as well as information about access to data and programs. In particular, authors are required to report, for any data they use, which is the source and whether the data is:'),
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
			ArticleSection::basic('Reprinted material', 2,
			    $this->render(
			        new Paragraph('Occasionally, the journal might publish thematic special issues containing historically relevant work for the microsimulation community, previously unpublished in journal or book form, such as documentation of landmark models (see the <a href="https://microsimulation.pub/about">Aims and Scope</a> section). The choice of such articles always rests on a decision by the Chief Editor, subject to the acquisition of the relevant publishing rights. Republished articles are clearly identified in the Acknowledgements section, with the original source of publication duly reported.')
			    )
			),
            ArticleSection::basic('Copyright', 2,
                $this->render(new Paragraph('All IJM articles, unless otherwise stated, are published under the terms of the Creative Commons Attribution (CC BY) License which permits use, distribution and reproduction in any medium, provided the work is properly attributed back to the original author and publisher. Copyright on any research article in the International Journal ofMicrosimulation(IJM) is retained by the Authors. Authors grant IJM a license to publish the article and identify itself as the original publisher. Authors cannot revoke these freedoms as long as the Journal follows the license terms. Authors should not submit any paper unless they agree with this policy. Submission implies full acceptance of the above terms by all co-authors, with the corresponding author responsible for providing a written acknowledgement signed by all co-authors prior to publication. The full text of the CC BY 4.0 license can be found <a href="https://creativecommons.org/licenses/by/4.0/">here</a>. Special exemptions and other licensing arrangement can be made on a case by case basis, by writing a motivated request to the Editor.')
                )
            ),
            ArticleSection::basic('Authors rights <div id="authors_rights"></div>', 2,
                $this->render(new Paragraph('Upon publication, Authors will retain the rights to their Contribution, including but not limited to the following, as permitted by the CC BY license:')
                )
            ),
            Listing::unordered([
                'The rights to reproduce, distribute, publicly perform, and publicly display the Contribution in any medium for non-commercial purposes.',
                'The right to prepare derivative works from the Contribution, including reuse parts of the Contribution (e.g. figures and excerpts from an article) so long as the Authors receives credit as authors and the IJM is appropriately cited as the source of first publication.',
                'Patent and trademark rights and rights to any process or procedure described in the Contribution.',
                'The right to proper attribution and credit for the published work.',
            ], 'bullet'),
			ArticleSection::basic('Publication schedule', 2,
			    $this->render(new Paragraph('Issues are finalised on April 30 (Spring issue), August 31 (Summer issue) and December 31 (Winter issue) of each year. Issues may be listed on the journal website in advance of their scheduled date, containing articles assigned to those issues that have already completed the editorial process (forthcoming). New articles are added to the forthcoming issues as they become available, until the issue is finalised on the scheduled date.')
			    )
			),
            ArticleSection::basic('Disclaimer', 2,
                $this->render(new Paragraph('The International Microsimulation Association (IMA) and the International Journal of Microsimulation (IJM) and make every effort to ensure the accuracy of all the information contained in our publications. It however, makes no representations or warranties whatsoever as to the accuracy, completeness, or suitability for any purpose of the published work. Any opinions and views expressed in this publication are the opinions and views of the Authors, and are not necessarily the view of the Editors or the Journal.')
                )
            ),
            ArticleSection::basic('Contact information', 2,
                $this->render(new Paragraph('For questions, please write to <a href="mailto:ijm-enquiries@microsimulation.org">ijm-enquiries@microsimulation.org.</a>')
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

    public function callForPapersAction(Request $request) : Response
    {
        $arguments = $this->aboutPageArguments($request);

        $arguments['title'] = 'Special issues';

        $arguments['contentHeader'] = new ContentHeader($arguments['title']);
        $arguments['body'] = [
            ArticleSection::basic('Forthcoming special issues', 2, $this->render(
                new Paragraph('For more information, or if you are interested in editing a special issue, please <a href="mailto:matteo.richiardi@essex.ac.uk">contact the Editor.</a>')
                )),         
			ArticleSection::basic('"Microsimulation in Government"', 2, $this->render(
			    new Paragraph('Guest editor: Dave Pankhurst, Grant Tregonning')
			    ))
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
            'Editorial policy' => $this->get('router')->generate('about-editorial-policy'),
            'Notes for reviewers' => $this->get('router')->generate('about-reviewers'),
            'Call for papers' => $this->get('router')->generate('call-for-papers'),
        ];

        $currentPath = $this->get('router')->generate($request->attributes->get('_route'), $request->attributes->get('_route_params'));

        $menuItems = array_map(function (string $text, string $path) use ($currentPath) {
            return new Link($text, $path, $path === $currentPath);
        }, array_keys($menuItems), array_values($menuItems));

        $arguments['menu'] = new SectionListing('sections', $menuItems, new ListHeading('About sections'), true);

        return $arguments;
    }
}
