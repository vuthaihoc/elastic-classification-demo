# Text Classification made easy with Elasticsearch

Tutorial https://www.elastic.co/blog/text-classification-made-easy-with-elasticsearch

# How to run this code

- Clone this repo
- Go to project root
- Get vendors `composer install`
- Create empty database `touch database.sqlite`
- Migrate database `php artisan migrate`
- Setup elasticsearch (localhost:9200)
- Migrate elastic `php artisan elastic:migrate`
- Import copus from dataset `php artisan import -s`
- Test fulltext search `php artisan text:search "something"`
- Test classifier `php artisan text:search "something" -c`

