The International Journal of Microsimulation (IJM) is the official online peer-reviewed journal of the International Microsimulation Association and available at https://microsimulation.org/ijm/

IJM has been selected as the first journal to use the open-source Libero Publisher platform of services and applications to help scholarly publishers do more with everything they publish. More information about Libero can be found at https://libero.pub

This repository serves as a central store of issues, documents, scripts and resources relating to the IJM implentation of Libero Publisher.

- [Create a new Feature Issue](https://github.com/microsimulation/ijm/issues/new?template=feature_ticket.md&labels=feature-ticket)
- [Create a new Technical Issue](https://github.com/microsimulation/ijm/issues/new?template=task.md&labels=technical-ticket)
- [Create a new Bug Issue](https://github.com/microsimulation/ijm/issues/new?template=bug.md&labels=bug)

Dependencies
------------

* [Composer](https://getcomposer.org/)
* [npm](https://www.npmjs.com/)
* PHP 7

Installation
-------------

1. Create `app/config/parameters.yml` from `app/config/parameters.yml.dist`
2. `npm install`
3. `composer install`
4. `node_modules/.bin/gulp`
5. `bin/console assets:install --symlink`

Running the site locally
------------------------

1. `docker-compose down --volumes --remove-orphans && docker-compose up --build`
2. Open `http://localhost:8080` in your browser.

### Changing configuration

When running the site locally via Docker, the parameters are supplied by `/.docker/parameters.yaml`.

To change configuration that is supplied by an environment variable, pass in the environment variable at start up. For example, to change the API URL:
`docker-compose down --volumes --remove-orphans && API_URL=https://prod--gateway.elifesciences.org docker-compose up --build`.

See `/.env` for the list of environment variables that can be passed in this way.  

Editing the About Page content
------------------------------

Editing text for the about pages can be done by cloning the repository and using the commands above to preview the site locally, then committing and deploying the changes as you would for any other code change.

### Using The GitHub web interface
However, for smaller text edits it is also possible to edit the text directly in GitHub without having to run the site locally. This is useful for administrators who have the skills to edit the text via the web interface but do not want to install all the dependencies and set up a development environment.

See [Instructions on how to edit the About Pages via the GitHub interface here](how-to-edit-the-about-pages.md).


Regenerating critical CSS
-------------------------

`docker-compose run critical_css`

Running the tests
-----------------

`docker-compose run app vendor/bin/phpunit`

Running Behat
-------------

Behat needs the `ci` image to run, so it needs to build an additional image and use the ci configuration:

```
docker-compose -f docker-compose.yml -f docker-compose.ci.yml up --build --detach
```

To run all scenarios:

```
docker-compose -f docker-compose.yml -f docker-compose.ci.yml run ci .ci/behat
```

To run a single scenario:

```
docker-compose -f docker-compose.yml -f docker-compose.ci.yml run ci vendor/bin/behat features/article.feature
```

If you have made changes to the code and want to re-run a test then you will need to rebuild your docker containers:

```
docker-compose -f docker-compose.yml -f docker-compose.ci.yml down && docker-compose -f docker-compose.yml -f docker-compose.ci.yml up --build --detach
```

Reproduce a ci failure
----------------------

```
docker-compose -f docker-compose.yml -f docker-compose.ci.yml down -v
SELENIUM_IMAGE_SUFFIX=-debug docker-compose -f docker-compose.yml -f docker-compose.ci.yml up --build
docker-compose -f docker-compose.yml -f docker-compose.ci.yml run ci .ci/behat
```

Redirects for doi urls pointing at microsimulation.org
------------------------------------------------------

- configured in `journal/.docker/nginx.conf`
  - most have per manuscript redirect using an `url_map`
  - some issues redirect using a rewrite regex as they follow a consistent pattern
- test the deployed rules for a subset of doi's with `./tests/redirects.sh`
