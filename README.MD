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

```bash
cd foss-fi/
./coldstart.sh
```

To start everything:

```bash
cd foss-fi/
./pullstart.sh
```

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
