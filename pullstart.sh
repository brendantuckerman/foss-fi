DIR="$(cd "$(dirname "$0")" && pwd)"

echo "Starting Symfony api server"
docker compose up -d

echo "Starting Vue app server in a new terminal window"
osascript -e "tell application \"Terminal\" to do script \"cd $DIR/app && npm run dev\""
