YAML := ./etc/infrastructure/dev/docker-compose.yaml

.DEFAULT_GOAL := help

.PHONY: help
help:
	@awk 'BEGIN {FS = ":.*?## "}; /^[a-zA-Z-]+:.*?## .*$$/ {printf "\033[32m%-15s\033[0m %s\n", $$1, $$2}' Makefile | sort

.PHONY: ps
ps: ## PS docker-compose
	@docker-compose -f $(YAML) ps

.PHONY: start
start: ## Start docker-compose
	@docker-compose -f $(YAML) up -d

.PHONY: stop
stop: ## Stop docker-compose
	@docker-compose -f $(YAML) down

.PHONY: rr-ash
rr-ash: ## RR ash
	@docker-compose -f $(YAML) exec rr ash

.PHONY: rr-logs
rr-logs: ## RR logs
	@docker-compose -f $(YAML) logs rr -f
