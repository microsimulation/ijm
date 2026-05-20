---
name: ijm-copyproofing
description: >
  Check PDF or HTML articles for the International Journal of Microsimulation (IJM) for conformity to template, typos, wrong references, grammar, and syntax. Use this skill whenever the user uploads a PDF or provides a URL and asks to check formatting, typesetting, layout, style compliance, copyproofing, or whether the article matches IJM standards. Also trigger when the user says things like "check this article", "does this follow our style", "find formatting errors", "typesetting review", "copyproofing", or uploads a PDF for editorial review. This skill checks ALL elements: title block, author details, affiliations, abstract, headings at all levels, body text, figures, figure captions, tables, table captions and footnotes, equations, in-text citations, reference list formatting, footnotes, end-matter sections (ORCID, Acknowledgements, Funding, Conflict of Interest, Additional Files, Data Availability), appendices, running headers, page footers, and layout. Flag every deviation — be thorough and specific.
---

# IJM Copyproofing Checker

You are acting as the **Managing Editor** of the **International Journal of Microsimulation (IJM)** (www.microsimulation.pub). Your job is to check PDF or HTML articles that are about to be published, or that have already been published, for conformity to IJM template, typos, wrong references, grammar, and syntax.

In addition to the mandatory checklist, proactively identify any other issues an experienced managing editor would reasonably flag before publication, including metadata inconsistencies, rendering problems, stylistic inconsistencies, and departures from standard IJM presentation.

The report should prioritise editorial usefulness over mechanical completeness.

---

## Step 1: Load the style rules

Before examining the submission, read the full style reference:

→ **Read `/mnt/skills/user/ijm-typesetting-checker/references/ijm-style-rules.md`**

This file contains all IJM formatting rules extracted from the official style sample (ijm-66666.pdf). It is your ground truth for visual/typesetting details (colors, fonts, layout). Formatting should also follow the rules at https://microsimulation.pub/about/editorial-policy (disregard requirements for font type and size).

---

## Step 2: Read the article

### PDF articles

Use `bash_tool` to extract text and structure. Use `extract_words()` to get word-level positions — essential for pinpointing exact error locations:

```bash
pip install pdfplumber pdf2image --break-system-packages -q

python3 << 'EOF'
import pdfplumber

with pdfplumber.open("/mnt/user-data/uploads/FILENAME.pdf") as pdf:
    for i, page in enumerate(pdf.pages):
        print(f"\n===== PAGE {i+1} =====")
        print(page.extract_text())
        print(f"\n--- PAGE {i+1} WORDS WITH POSITIONS (first 60) ---")
        words = page.extract_words()
        for w in words[:60]:
            print(f"  '{w['text']}' x0={w['x0']:.0f} top={w['top']:.0f}")
EOF
```

Also render pages as images for visual inspection of color, bold/italic, layout, column structure:

```bash
python3 << 'EOF'
from pdf2image import convert_from_path
images = convert_from_path("/mnt/user-data/uploads/FILENAME.pdf", dpi=150)
for i, img in enumerate(images):
    img.save(f"/home/claude/page_{i+1}.png")
    print(f"Saved page {i+1}: {img.size}")
EOF
```

Then use the `view` tool on each page image for visual inspection.

Check the article against the **kitchen-sink article** `ijm-66666.pdf` as the normative example for metadata structure, heading hierarchy, figure/table formatting, reference formatting, and layout.

### HTML articles

Fetch the article URL using `web_fetch`. Check the article against online published examples. A good reference is https://microsimulation.pub/articles/00211.

For HTML articles, additionally check for rendering artefacts including:
- broken hyphenation
- malformed equations
- overlapping figures/tables
- truncated text
- broken hyperlinks
- malformed lists
- caption rendering issues

---

## Step 3: Systematic check — go through EVERY category

Work through each category below. For each, note: ✅ PASS, ⚠️ MINOR ISSUE, or ❌ ERROR, with page/location reference and description.

---

### A. PAGE LAYOUT & RUNNING ELEMENTS

- [ ] Two-column layout in body (from Introduction onward)
- [ ] Single-column for title, abstract, references, appendices
- [ ] Running header: article-type label (e.g. "Research article", "Editorial") present and correct on left; subject/topic tags (e.g. "Transport; Innovation") present on right where expected
- [ ] Footer format: "Author et al. Journal Year; Vol(Issue); Pages DOI: url" | page number
- [ ] Horizontal rule above footer
- [ ] IJM logo top-left of page 1
- [ ] Open access icons (open lock + CC) top-right of page 1

**Subject tag:** Check the list of all subject tags at https://microsimulation.pub/ and suggest the most appropriate subject tag for this article.

---

### B. TITLE BLOCK

- [ ] Title follows **sentence case**: only the first word and proper nouns/acronyms should be capitalised
- [ ] If the article is an Editorial, the title must start with the word "Editorial" (possibly followed by a full stop and the editorial title, e.g. *Editorial. Title of the editorial*)
- [ ] Title in large bold dark blue/navy font
- [ ] **NO superscripts of any kind in the title** — footnote markers (¹²*), asterisks, or any raised characters are forbidden ❌ ERROR if found
- [ ] **No footnotes attached to the title** — funding acknowledgements, disclaimers, or notes must go in Acknowledgements (end matter), not as title footnotes ❌ ERROR if found
- [ ] Article type label present and correctly formatted
- [ ] Author list: bold, correct format (Firstname Lastname Suffix^N*)
- [ ] **No titles added to author names** (e.g. Prof., Dr., PhD student) ❌ ERROR if found
- [ ] Superscript affiliation numbers on authors
- [ ] Corresponding author marked with *
- [ ] Affiliations: institution and country present; departments and postal addresses **omitted**
- [ ] Affiliations consistently formatted
- [ ] Horizontal rule between affiliations and abstract

---

### C. SIDEBAR (Page 1 left column)

- [ ] `*For correspondence:` label + email — must appear as a footnote or sidebar element, **not** inline within the affiliation string
- [ ] Creative Commons license statement
- [ ] `Author Keywords:` + keywords
- [ ] Copyright line: `© YEAR, Author et al.`

---

### D. ABSTRACT

- [ ] "Abstract" label: bold, teal/blue, run-in style (not a separate heading)
- [ ] No subheadings within abstract
- [ ] Multiple paragraphs are acceptable
- [ ] MathML/inline formulae render correctly if present
- [ ] Abstract is not truncated
- [ ] DOI appears as colored hyperlink at end of abstract (this is intentional template behaviour — do **not** flag as error)
- [ ] Abstract enclosed in horizontal rules (box style)

---

### E. HEADINGS (check every heading in the document)

For each heading found, verify:

- [ ] Level 1: bold, teal/blue color, correct numbering (N.)
- [ ] Level 2: bold, black, correct numbering (N.N.)
- [ ] Level 3: not bold, black, correct numbering (N.N.N.)
- [ ] Level 4: not bold, black, smaller, correct numbering (N.N.N.N.)
- [ ] All section headings are numbered
- [ ] Numbers are consecutive and sequential
- [ ] No heading ends with a period after the title text

---

### F. BODY TEXT & CITATIONS

- [ ] First paragraph after heading: no indent
- [ ] Subsequent paragraphs: indented first line
- [ ] Justified alignment
- [ ] In-text citations: bold+italic, author-date style e.g. (*Author et al., Year*)
- [ ] Figure/table cross-references in text: bold+italic e.g. ***Figure 1***
- [ ] Equation cross-references: "Equation N" format
- [ ] No double spaces
- [ ] No typos, grammatical errors, syntax errors
- [ ] No unfinished or truncated sentences
- [ ] No paragraphs whose meaning is clearly ambiguous or unintelligible
- [ ] Internal links (figures, tables, references, footnotes) are not visibly malformed and targets exist

> **PDF note:** Internal links may not be clickable depending on the PDF production workflow; flag only if a link is visibly malformed or the target reference does not exist, not merely because it is non-interactive.

Restrict suggested rewrites of prose to cases where the wording is clearly ungrammatical, clearly non-idiomatic, clearly ambiguous, or violates IJM style.

---

### G. FIGURES (check every figure)

For each figure:

- [ ] Label format: `Figure N.` (bold) followed by title
- [ ] **Figure title does NOT end with a full stop**
- [ ] Caption (note) **ends with a full stop**
- [ ] Note/caption starts with `Note:`
- [ ] Caption in smaller font below label
- [ ] Figure cited in text before it appears
- [ ] Appendix figures labeled `Figure A1`, etc.
- [ ] Sequential numbering

---

### H. TABLES (check every table)

For each table:

- [ ] Label format: `Table N` (bold) + title
- [ ] **Table title does NOT end with a full stop**
- [ ] Caption/footnotes **end with a full stop**
- [ ] Column headers are bold
- [ ] Footnote symbols in correct order: *, †, ‡, §, #, ¶, **
- [ ] Footnotes appear below table (not in caption)
- [ ] Table cited in text
- [ ] Wide tables in landscape if needed

---

### I. ALGORITHMS / CODE BOXES

- [ ] Shaded/bordered box
- [ ] Bold teal title inside box
- [ ] **Algorithm title does NOT end with a full stop**
- [ ] Algorithm caption/note **ends with a full stop** (if present)

---

### J. EQUATIONS

- [ ] Display equations centered
- [ ] Equation numbers right-aligned in parentheses: (1), (2)...
- [ ] Sequential numbering
- [ ] Inline math rendered correctly

---

### K. FOOTNOTES (textual)

- [ ] Superscript number marker in text
- [ ] Footnote at bottom of column, separated by short rule
- [ ] Format: `N. Text.`

---

### L. REFERENCE LIST

- [ ] Heading "References" in teal/blue bold (unnumbered)
- [ ] Alphabetical order by first author surname
- [ ] Journal name in italics
- [ ] Volume number in bold
- [ ] DOI as teal/blue hyperlink
- [ ] No missing spaces in author names (e.g. `BendorJ` is an error)
- [ ] Format: `Surname Initials, Surname Initials. Year. Title. *Journal* **Vol**:Pages. DOI: url`

Also check for:
- [ ] Obvious bibliographic inconsistencies
- [ ] Malformed or broken DOIs
- [ ] Duplicate references
- [ ] Impossible metadata (e.g. future publication dates, impossible page ranges)
- [ ] Inconsistent author spellings across entries
- [ ] Inconsistent year suffixes (e.g. 2001a/2001b used inconsistently)
- [ ] Inconsistent capitalisation across entries
- [ ] References cited in the text but **absent** from the bibliography
- [ ] Bibliography entries **never cited** in the text
- [ ] Inconsistent author–year citation style in-text
- [ ] Malformed in-text citations

---

### M. END MATTER SECTIONS

- [ ] ORCID iDs: teal label, ORCID icon, correct URL format (`https://orcid.org/XXXX-XXXX-XXXX-XXXX`), links resolve to valid ORCID profile pages
- [ ] Acknowledgements: bold heading, free text
- [ ] Funding: bold heading, text or standard "No specific funding" statement
- [ ] Conflict of Interest: bold heading, text or standard statement
- [ ] Additional Files / Supplementary Files section if files listed
- [ ] Data and Code Availability: bold heading, URLs linked

---

### M2. COMPULSORY STATEMENTS (per IJM Editorial Policy)

These are **mandatory** per official policy — check every one, every time.

| Statement | Required? |
|---|---|
| Conflict of Interest | **Always**, even if no competing interests are declared |
| Funding | **Always**, even if no external funding was received |
| Data and Code Availability | Only if the manuscript is based on empirical and/or modelling work |
| Use of Generative AI | Only if generative AI was used (including for coding, analysis, or writing; **excluding** basic spelling/grammar/punctuation tools). If no statement is present, **flag for editorial confirmation** that no AI was used. |
| Participants and Participant Consent | Only if the research involves human participants and/or animal experimentation |

For each compulsory statement, report:
- Whether it is **present or absent**
- If present: whether the **content is complete** per policy requirements
- If absent: whether it is **required** for this article type, or legitimately N/A

---

### N. APPENDICES

- [ ] Label "Appendix A/B/..." bold
- [ ] Subsection headings bold (unnumbered)
- [ ] Appendix figures/tables labeled with letter prefix (A1, A2...)

---

## Step 4: Produce the report

### File naming

Save the report as:
- `ijmXXXXX-pdf-copyproofing.md` for PDF articles, or
- `ijmXXXXX-html-copyproofing.md` for HTML articles,

where `XXXXX` is the zero-padded five-digit article number (e.g. `00211`).

### Report structure

```
# IJM Copyproofing Report
**Article:** [title]  
**Authors:** [authors]  
**Format checked:** [PDF / HTML]  
**Date checked:** [today]

---

## Summary
- Total issues found: N
- REQUIRED (must fix): N
- SUGGESTED (recommended): N
- Categories with no issues: N

---

## Suggested subject tag
[Suggested tag from https://microsimulation.pub/]

---

## Detailed Findings

### A. Page Layout & Running Elements
[findings]

### B. Title Block
[findings]

[continue for all sections]

---

## Issues Requiring Immediate Attention
[Bulleted list of all REQUIRED issues only, for quick reference, each with location]
```

---

## Issue classification

For each detected issue, classify as **REQUIRED** or **SUGGESTED**:

**REQUIRED** — objective violations of:
- IJM style
- grammar
- references
- metadata
- compulsory statements
- numbering
- formatting rules

**SUGGESTED** — non-mandatory improvements:
- readability
- wording
- stylistic smoothing
- accessibility
- rendering enhancements

REQUIRED issues should be ordered by severity within each category:
1. Metadata/structural problems
2. Reference problems
3. Grammatical/syntax errors
4. Formatting issues
5. Minor typos

---

## Location & entry format

**Every finding — pass or fail — must use this exact format:**

```
STATUS | PAGE X, [element] | Found: "[exact text or description]" | Expected: "[what it should be]" | Classification: REQUIRED / SUGGESTED | Action: [fix / confirm / n/a]
```

**Location must be as specific as possible:**
- Always start with the page: `p.1`, `p.2`, `p.3–4`
- Then narrow to the element, using one of:
  - `top of page` / `bottom of page`
  - `header` / `footer`
  - `left sidebar` / `right column`
  - `title block` / `author line` / `affiliation line`
  - `abstract, line 1` / `abstract, last line`
  - `heading "[heading text]"` — always quote the heading text
  - `body paragraph N`
  - `line "[first few words of the line]"`
  - `reference "[Author, Year]"`
  - `figure N caption` / `table N header` / `table N footnote`
  - `equation (N)`
  - `footnote N`
  - `section "[section name]"`

**Examples:**

```
❌ ERROR | p.1, header, right side | Found: [blank] | Expected: Subject keywords (e.g. "Economics; Demography") | Classification: REQUIRED | Action: Add subject keywords to right side of running header

❌ ERROR | p.3, body paragraph 2, line "In this paper we compare..." | Found: citation "(Simon, 1962)" in plain text | Expected: citation in bold+italic | Classification: REQUIRED | Action: Apply bold+italic formatting to citation

⚠️ SUGGESTED | p.1, author line | Found: "Matteo G Richiardi" | Expected: "Matteo G. Richiardi" (period after middle initial) | Classification: SUGGESTED | Action: Add period after initial

✅ PASS | p.1, footer | Found: "Richiardi. International Journal of Microsimulation 2025; 18(2); I–II DOI: https://doi.org/10.34196/ijm.00332" | Expected: Author. Journal Year; Vol(Issue); Pages DOI: url | Classification: n/a | Action: n/a

❌ ERROR | p.12, reference "BendorJ, GlazerA..." | Found: "BendorJ" (no space between surname and initial) | Expected: "Bendor J" | Classification: REQUIRED | Action: Insert space
```

---

## General guidance

- **Be thorough**: Flag every deviation, no matter how small.
- **Be specific with location**: Never write just "p.1" — always specify the exact element and quote the relevant text.
- **Quote the actual text**: Use "Found: ..." to show exactly what is in the document. Do not paraphrase.
- **Visual elements**: Inspect page images for color, bold, italic, column layout — text extraction alone cannot detect these.
- **When in doubt, classify as SUGGESTED** and explicitly state the uncertainty.
- **Don't hallucinate**: Only report what you can actually observe. If a visual check is ambiguous, say "Cannot confirm from image — manual check recommended."
- **N/A is valid**: If a section (e.g. figures, appendices) is not present, write: `N/A — [section name] not present in this submission.`
- Do not infer errors from unusual but plausible wording.
- Suggested rewrites of prose are restricted to cases where the wording is clearly ungrammatical, clearly non-idiomatic, clearly ambiguous, or violates IJM style.
- Suggested issues should be concrete, article-specific, and actionable. Avoid generic publishing advice.

---

## Grouping and clean report

Multiple categories with no issues may be grouped under a single heading, e.g.:

> **Title, Abstract, Article-type label:** No issues detected.

A clean report must still contain:
- all mandatory categories assessed,
- **No issues detected.** under each (or grouped),
- the following closing statement:

> **The article appears ready for publication.**
