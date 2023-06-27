# Deploy forge serve

Este projeto solicita o deploy de todos os sites de um serviço Laravel Forge a partir de um hebhook

## Configuração
- Adicione o token do forge no arquivo .env na variável FORGE_API_TOKEN
- Sirva a aplicação em um servidor
- Configure o webhook do seu repositório para a rota /api/deploy
