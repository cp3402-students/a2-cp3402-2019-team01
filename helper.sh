#!/usr/bin/env sh

#set -euo pipefail

deploy_to_env () {
    local env=$1
    echo Deploying to ${env}
    rsync -avz -e "ssh -o StrictHostKeyChecking=no" --delete $(pwd)/wp-content/plugins/ ec2-user@13.239.37.152:/ecs/cp3402-${env}-storage/wp-content/plugins
    rsync -avz -e "ssh -o StrictHostKeyChecking=no" --delete $(pwd)/wp-content/themes/arabusta/ ec2-user@13.239.37.152:/ecs/cp3402-${env}-storage/wp-content/themes/arabusta
}

pull_prod_locally () {
    docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysqldump -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} --password=${REMOTE_DB_PASSWORD} prod > db_prod_dump.sql"
    docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h localhost -u root -p\$MYSQL_ROOT_PASSWORD \$MYSQL_DATABASE < db_prod_dump.sql"
    docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h localhost -u root -p\$MYSQL_ROOT_PASSWORD \$MYSQL_DATABASE --execute=\"UPDATE wp_posts SET guid = replace(guid, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', 'http://localhost:8000');\""
    docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h localhost -u root -p\$MYSQL_ROOT_PASSWORD \$MYSQL_DATABASE --execute=\"UPDATE wp_posts SET post_content = replace(post_content, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', 'http://localhost:8000');\""
    docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h localhost -u root -p\$MYSQL_ROOT_PASSWORD \$MYSQL_DATABASE --execute=\"UPDATE wp_postmeta SET meta_value = replace(meta_value, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', 'http://localhost:8000');\""
    docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h localhost -u root -p\$MYSQL_ROOT_PASSWORD \$MYSQL_DATABASE --execute=\"UPDATE wp_options SET option_value = replace(option_value, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', 'http://localhost:8000');\""
}

pull_prod_locally_win () {
    winpty docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysqldump -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} --password=${REMOTE_DB_PASSWORD} prod > db_prod_dump.sql"
    winpty docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h localhost -u root -p\$MYSQL_ROOT_PASSWORD \$MYSQL_DATABASE < db_prod_dump.sql"
    winpty docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h localhost -u root -p\$MYSQL_ROOT_PASSWORD \$MYSQL_DATABASE --execute=\"UPDATE wp_posts SET guid = replace(guid, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', 'http://localhost:8000');\""
    winpty docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h localhost -u root -p\$MYSQL_ROOT_PASSWORD \$MYSQL_DATABASE --execute=\"UPDATE wp_posts SET post_content = replace(post_content, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', 'http://localhost:8000');\""
    winpty docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h localhost -u root -p\$MYSQL_ROOT_PASSWORD \$MYSQL_DATABASE --execute=\"UPDATE wp_postmeta SET meta_value = replace(meta_value, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', 'http://localhost:8000');\""
    winpty docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h localhost -u root -p\$MYSQL_ROOT_PASSWORD \$MYSQL_DATABASE --execute=\"UPDATE wp_options SET option_value = replace(option_value, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', 'http://localhost:8000');\""
}

pull_prod_into_staging () {
    ssh ec2-user@13.239.37.152 -o StrictHostKeyChecking=no "rsync -va -O --delete /ecs/cp3402-prod-storage/wp-content/plugins/ /ecs/cp3402-stage-storage/wp-content/plugins"
    ssh ec2-user@13.239.37.152 -o StrictHostKeyChecking=no "rsync -va -O --delete /ecs/cp3402-prod-storage/wp-content/uploads/ /ecs/cp3402-stage-storage/wp-content/uploads"
    docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysqldump -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} --password=${REMOTE_DB_PASSWORD} prod > db_prod_dump.sql"
    docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -f -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} -p${REMOTE_DB_PASSWORD} stage < db_prod_dump.sql"
    docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} -p${REMOTE_DB_PASSWORD} stage --execute=\"UPDATE wp_posts SET guid = replace(guid, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', '${REMOTE_STAGING_URL}');\""
    docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} -p${REMOTE_DB_PASSWORD} stage --execute=\"UPDATE wp_posts SET post_content = replace(post_content, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', '${REMOTE_STAGING_URL}');\""
    docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} -p${REMOTE_DB_PASSWORD} stage --execute=\"UPDATE wp_postmeta SET meta_value = replace(meta_value, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', '${REMOTE_STAGING_URL}');\""
    docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} -p${REMOTE_DB_PASSWORD} stage --execute=\"UPDATE wp_options SET option_value = replace(option_value, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', '${REMOTE_STAGING_URL}');\""
}

pull_prod_into_staging_win () {
    ssh ec2-user@13.239.37.152 -o StrictHostKeyChecking=no "rsync -va -O --delete /ecs/cp3402-prod-storage/wp-content/plugins/ /ecs/cp3402-stage-storage/wp-content/plugins"
    ssh ec2-user@13.239.37.152 -o StrictHostKeyChecking=no "rsync -va -O --delete /ecs/cp3402-prod-storage/wp-content/uploads/ /ecs/cp3402-stage-storage/wp-content/uploads"
    winpty docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysqldump -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} --password=${REMOTE_DB_PASSWORD} prod > db_prod_dump.sql"
    winpty docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -f -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} -p${REMOTE_DB_PASSWORD} stage < db_prod_dump.sql"
    winpty docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} -p${REMOTE_DB_PASSWORD} stage --execute=\"UPDATE wp_posts SET guid = replace(guid, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', '${REMOTE_STAGING_URL}');\""
    winpty docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} -p${REMOTE_DB_PASSWORD} stage --execute=\"UPDATE wp_posts SET post_content = replace(post_content, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', '${REMOTE_STAGING_URL}');\""
    winpty docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} -p${REMOTE_DB_PASSWORD} stage --execute=\"UPDATE wp_postmeta SET meta_value = replace(meta_value, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', '${REMOTE_STAGING_URL}');\""
    winpty docker exec -it a2-cp3402-2019-team01_db_1 sh -c "mysql -h ${REMOTE_DB_HOST} -u ${REMOTE_DB_USERNAME} -p${REMOTE_DB_PASSWORD} stage --execute=\"UPDATE wp_options SET option_value = replace(option_value, 'http://cp3402-alb-1118752971.ap-southeast-2.elb.amazonaws.com', '${REMOTE_STAGING_URL}');\""
}
eval $@
