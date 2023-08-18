setup-local:
	./bin/setup-local.sh

compose-test:
	docker compose exec app ./vendor/bin/paratest
