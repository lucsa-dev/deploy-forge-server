# Deploy forge server

Este projeto solicita o deploy de todos os sites de um serviço Laravel Forge a partir de um hebhook

## Configuração
- Adicione o token do forge no arquivo .env na variável FORGE_API_TOKEN
- Sirva a aplicação em um servidor
- Configure o webhook do seu repositório para a rota /api/deploy


## Features
- [x] Obedecer o limit Rate de 60 requisições por minuto no Forge.
- [x] Erros: Excessões são lançadas caso ocorra erro na reuqisição forge ou se a variável de ambiente não estiver configurada.
- [x] Teste de integração - Testa a rota /api/deploy.
- [x] Retorna um array success contendo os nomes dos sites que tiveram o deploy feito com sucesso!
- [x] Retorna um array errors contendo os nomes dos sites que tiveram erro no deploy e o motivo!
