# Builds, then starts both the API dev server and the APP dev server
DIR="$(cd "$(dirname "$0")" && pwd)"

echo "Building project"
docker compose build

echo "Starting Symfony api server"
docker compose up -d

echo "Starting Vue app server in a new terminal window"
osascript -e "tell application \"Terminal\" to do script \"cd $DIR/app && npm i && npm run dev\""
