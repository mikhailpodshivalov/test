# test
git clone https://github.com/mikhailpodshivalov/test
docker-compose -f ./docker/docker-compose.yml build
php bin/console doctrine:fixtures:load

