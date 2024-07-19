docker run \
    -e SERVER_NAME=':80' \
    -e MERCURE_PUBLISHER_JWT_KEY='!ChangeThisMercureHubJWTSecretKey!' \
    -e MERCURE_SUBSCRIBER_JWT_KEY='!ChangeThisMercureHubJWTSecretKey!' \
    -p 8800:80 \
    --name mercure \
    dunglas/mercure \
    caddy run --config /etc/caddy/dev.Caddyfile