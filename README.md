# Find A Pitch

An API which conceptualises the search for a pitch and a slot within that pitch.

## Dependencies

 - [Yarn](https://yarnpkg.com/lang/en/docs/install/#mac-stable)
 - [Symfony Server](https://symfony.com/doc/current/setup/symfony_server.html)
 - [Docker](https://docs.docker.com/docker-for-mac/install/)

## Setup

Run the `startup.sh` file in the root directory to;

- Spin up a development MySQL instance
- Run a symfony package security check
- Install required Composer packages
- Install required Yarn packages
- Build the frontend using Yarn
- Add an initial test database
- Run PHPUnit tests
- Serve the application using the Symfony Server.

## Setup

You can find API documentation, once the server is up by adding `api/doc` to the base url.

 - e.g https://127.0.0.1:8000/api/doc