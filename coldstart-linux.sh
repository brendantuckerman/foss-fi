#!/bin/bash
# Builds, then starts both the API dev server and the APP dev server. Linux only
DIR="$(cd "$(dirname "$0")" && pwd)"

echo "Building project"
docker compose build

echo "Starting Symfony api server"
docker compose up -d
docker compose exec php composer install

echo "Running database migrations"
docker compose exec php bin/console doctrine:migrations:migrate -n

echo "Opening Symfony logs in a new terminal window"
if command -v gnome-terminal &> /dev/null; then
    gnome-terminal -- bash -c "cd $DIR && docker compose logs -f; exec bash"
elif command -v xterm &> /dev/null; then
    xterm -e "bash -c 'cd $DIR && docker compose logs -f; exec bash'" &
else
    echo "No supported terminal emulator found. Tailing Symfony logs in background..."
    docker compose logs -f &
fi

echo "Starting Vue app server in a new terminal window"
if command -v gnome-terminal &> /dev/null; then
    gnome-terminal -- bash -c "cd $DIR/app && npm i && npm run dev; exec bash"
elif command -v xterm &> /dev/null; then
    xterm -e "bash -c 'cd $DIR/app && npm i && npm run dev; exec bash'" &
else
    echo "No supported terminal emulator found. Starting Vue app in background..."
    cd "$DIR/app" && npm i && npm run dev &
fi
