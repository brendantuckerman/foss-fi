# FOSS FI

An open source FI/FIRE/Coast Fire calculator, built as a SPA

Author: Brendan Tuckerman

Github: [repo(git@github.com:brendantuckerman/foss-fi.git)]

## Tech Stack

Front end - Vue.js
Back end - Symfony

## Project structure

/foss-fi
├── /api (Symfony)
│ ├── src/
│ ├── config/
│ ├── composer.json
│ └── ...
├── /app (Vue.js)
│ ├── src/
│ ├── package.json
│ └── ...
├── compose.yaml
└── README.MD <- You are here

## Starting the project

To start from scratch (build and install npm packages)

Create a .env file 

```bash
cd foss-fi/
./coldstart.sh
```

Or use the linux version

To start everything in dev:



```bash
cd foss-fi/
./pullstart.sh
```
(mac)

## To build

API:

````bash
cd foss-fi/
docker compose build
```

## To run

API:

```bash
cd foss-fi/
docker compose up -d
````

APP:

```bash
cd foss-fi/
npm run dev
```

## Commands

### Symfony and symfony console

Make your life easier and add these to your .zshrc:

```zsh
alias sc="docker compose --project-directory ~/Projects/foss-fi exec php bin/console"

alias sy="docker compose --project-directory ~/Projects/foss-fi exec php"

```

Then you can use symfony console from root as `sc` and the php container with `sy`, e.g.:

```zsh
  sy composer require some/package  # Composer
  sy php bin/phpunit                # PHPUnit
```

### PSQL Dev

To access the psql command line

```zsh
docker-compose exec database psql app app
```

For NSERTION etc

```zsh
docker-compose exec database psql -U app -d app -c "INSERT INTO..."
```

## Tests

Running tests should be done in the test environment.

The easiest way is via

```bash
make tests
```

If not already done, a DB should be created for that env:

```bash
sc doctrine:database:create --env=test
```

And then migrations added to that db:

```bash
sc doctrine:migrations:migrate -n --env=test
```

Always a good idea to clear the cache:

```bash
sc c:c --env==test
```

And tests can be run with

```bash
 sy php bin/phpunit
```
