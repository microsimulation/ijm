The International Journal of Microsimulation (IJM) is the official online peer-reviewed journal of the International Microsimulation Association and available at https://microsimulation.org/ijm/

IJM has been selected as the first journal to use the open-source Libero Publisher platform of services and applications to help scholarly publishers do more with everything they publish. More information about Libero can be found at https://libero.pub

This repository serves as a central store of issues, documents, scripts and resources relating to the IJM implentation of Libero Publisher.

- [Create a new Feature Issue](https://github.com/microsimulation/ijm/issues/new?template=feature_ticket.md&labels=feature-ticket)
- [Create a new Technical Issue](https://github.com/microsimulation/ijm/issues/new?template=task.md&labels=technical-ticket)
- [Create a new Bug Issue](https://github.com/microsimulation/ijm/issues/new?template=bug.md&labels=bug)

Development
-------------

### Running site locally

1. `make dev`
2. Open `http://localhost:8080` in your browser.

### Running tests locally / reproducing CI failures

1. `make test`
2. open `./tests/reports/report.html` in your browser

Editing the About Page content
------------------------------

Editing text for the about pages can be done by cloning the repository and using the commands above to preview the site locally, then committing and deploying the changes as you would for any other code change.

### Using The GitHub web interface
However, for smaller text edits it is also possible to edit the text directly in GitHub without having to run the site locally. This is useful for administrators who have the skills to edit the text via the web interface but do not want to install all the dependencies and set up a development environment.

See [Instructions on how to edit the About Pages via the GitHub interface here](how-to-edit-the-about-pages.md).

Redirects for doi urls pointing at microsimulation.org
------------------------------------------------------

- configured in `journal/.docker/nginx.conf`
  - most have per manuscript redirect using an `url_map`
  - some issues redirect using a rewrite regex as they follow a consistent pattern
- test the deployed rules for a subset of doi's with `./tests/redirects.sh`


