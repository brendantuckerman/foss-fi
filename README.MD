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
