setup-local:
	./bin/setup-local.sh

compose-test:
	docker compose exec app make test
