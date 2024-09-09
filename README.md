How to run?

If make is not installed:
sudo apt-get install build-essential

1. cp docker/.env docker/.env.local (Configure ports, if necessary)
2. cp .env .env.local
3. cp docker-compose.yml docker-compose-override.yml
4. make build
5. make up
6. make enter
7. composer i

Run tests:
1. cp .env.test .env.test.local
2. make test-db-refresh
3. make test
