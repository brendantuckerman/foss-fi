# Builds, then starts both the API dev server and the APP dev server. Mac only
DIR="$(cd "$(dirname "$0")" && pwd)"

echo "Building project"
docker compose build

echo "Starting Symfony api server"
docker compose up -d
docker compose exec php composer install

echo "Running database migrations"
docker compose exec php bin/console doctrine:migrations:migrate -n

echo "Opening Symfony logs in a new terminal window"
osascript -e "tell application \"Terminal\" to do script \"cd $DIR && docker compose logs -f\""

echo "Starting Vue app server in a new terminal window"
osascript -e "tell application \"Terminal\" to do script \"cd $DIR/app && npm i && npm run dev\""
