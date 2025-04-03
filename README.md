# Pasquali Solutions
Microserviço Pasquali usando as seguintes tecnologias:
- **PHP**: 8.3
- **Laravel**: 12
- **MySQL**: 5.7

## Instalação
Passos para a instalação:

### **Passo 1** - Clone o repositório
```sh
git clone https://github.com/dhikernel/pasqualisolution.git pasquali
cd pasquali

### Passo 2
Substitua substituir o ID que nomeia os cotainers pela "nomeclatura" que julgar mais adequeda para os seguintes arquivos:
 - docker-compose.yml
 - docker/nginx/laravel.conf
 - .env

### Passo 3
Rode o seguinte comandos no terminal:
```
esse comando constrói e inicia os containers em segundo plano:

docker-compose up -d --build --force-recreate
```
Dica: Se houver erro na conexão com MySQL, talvez o serviço ainda não esteja pronto. Aguarde alguns segundos ou reinicie os containers:

docker compose restart
```
### Passo 4
execute os seguintes comandos:
```
docker exec -it pasquali_site composer install
docker exec -it pasquali_site cp .env.example .env
docker exec -it pasquali_site php artisan key:generate
docker exec -it pasquali_site php artisan jwt:secret

Dica: Se for rodar no Windows, pode precisar usar winpty antes do docker exec:

winpty docker exec -it pasquali_site php artisan key:generate
```
### Passo 5

setar as variáveis de ambiente para o banco de dados no arquivo .env:
```
DB_CONNECTION=mysql
DB_HOST=pasquali_db
DB_PORT=3306
DB_DATABASE=pasquali
DB_USERNAME=usuario
DB_PASSWORD=password
```
Dica: Confirme se o nome do container do banco é realmente pasquali_db. Caso tenha alterado no Passo 2, esse nome precisa ser ajustado aqui também.

### Passo 6:
docker exec -it pasquali_site php artisan migrate --seed
```
Se precisar recriar o banco (apagar tudo e recriar):
docker exec -it pasquali_site php artisan migrate:fresh --seed
```
O migrate:fresh --seed deleta todas as tabelas e recria do zero.

Dica: Se houver erro no MySQL, pode ser necessário esperar o banco estar pronto antes de rodar as migrações. Para verificar:

docker logs pasquali_db
```
Se ainda der erro de conexão, tente reiniciar o container:
docker compose restart pasquali_db
```
### Passo 7
 - Acesse o site na URL: 
 # Abra no navegador
http://localhost

Se estiver em um ambiente remoto, substitua localhost pelo IP do servidor.

📌 Dica: Se o site não carregar, verifique os logs do Nginx para possíveis erros:

```
docker logs pasquali_nginx
