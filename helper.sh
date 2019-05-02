#!/usr/bin/env bash

set -euo pipefail

deploy_to_env () {
    local env=$1
    echo Deploying to ${env}
    ssh -o "StrictHostKeyChecking no" -t ec2-user@13.239.37.152 "rm -rf /ecs/cp3402-$env-storage/wp-content/plugins"
    ssh -o "StrictHostKeyChecking no" -t ec2-user@13.239.37.152 "rm -rf /ecs/cp3402-$env-storage/wp-content/theme/custom_theme"
    scp -r -o "StrictHostKeyChecking no" ./wp-content/plugins/. ec2-user@13.239.37.152:/ecs/cp3402-${env}-storage/wp-content/plugins/
    scp -r -o "StrictHostKeyChecking no" ./wp-content/themes/custom_theme/. ec2-user@13.239.37.152:/ecs/cp3402-${env}-storage/wp-content/themes/custom_theme/
}

pull_prod_locally () {
    docker exec -it a2-cp-3402-2019-team01_db_1 sh -c "mysqldump -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} --password=${REMOTE_DB_PASSWORD} ${REMOTE_DB_NAME} > db_prod_dump.sql"
    docker exec -it a2-cp-3402-2019-team01_db_1 sh -c "mysql -h localhost -u root -p\$MYSQL_ROOT_PASSWORD \$MYSQL_DATABASE < db_prod_dump.sql"
}

eval $@
