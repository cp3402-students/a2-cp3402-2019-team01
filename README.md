# The Coffee Can Website Repository

## Dependencies
To develop for the wordpress site or the infrastructure it is installed on, a few dependencies are required to be installed on your machine.

**For wordpress development:**
* [Docker](https://docs.docker.com/install/)
* [Docker Compose](https://docs.docker.com/compose/install/)
* [Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)


**For infrastructure development:**
* [Terraform](https://learn.hashicorp.com/terraform/getting-started/install.html)
* [Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)


## Developing for Wordress
Changes to wordpress can be broken down into two categories that are db changes and static file changes. Changes to these two categories are handled differently. 


Db changes are changes to the websites content or configuration stored in the database. To make these changes to production, make them in the production wordpress admin panel. Given the nature of these changes, it is not neccessary first replicate them locally or in staging as you can generally test them in an unpublished state in wordpress that will not effect end users. 


Static file changes if tested in production would effect end users and therefore we cannot accept testing these in this manner. To make static file changes you develop them locally and push to staging for a review where end users are unaffected. When these changes are approved, you can then "publish" them by pushing them to production. The following instructions explain how to do this.

1. Clone this repository: `git clone https://github.com/cp3402-students/a2-cp3402-2019-team01`
1. Check out a working branch using the associated [Trello card number](https://chrome.google.com/webstore/detail/trello-card-numbers/kadpkdielickimifpinkknemjdipghaf?hl=en) prefixed with `cc-`:  `git checkout -b cc-1`
1. Run the command `docker-compose up` from within the repository to start wordpress locally at `http://localhost:8000`
1. Make your desired changes to `wp-content/themes/custom_theme/` or install plugins through the the admin console.
1. Test these changes are working at `http://localhost:8000`
1. Stage, commit and push your changes 
    1. `git add .`
    1. `git commit -m "<Descriptive commit message>"`
    1. `git push -u origin cc-1`
1. In Github, create a pull request for your changes with staging as the base branch and have it reviewed by at least one peer. After approval you can merge this pull request.
1. When staging is stable create a pull request in staging with master as the base branch and have it reviewed by at least one peer. After approval you can merge this pull request.


## Deploying wordpress
Deploying changes to the wordpress theme or plugins is currently a manual process. The branches `master` and `staging` should be a mirror of their respective environment. This is to say that changes in master should also be in production and the two should never be out of sync unless an error has occurred. The same is to be said for staging.


**Deploy to production:**
1. From within the repo, checkout master and update master: 
    1. `git checkout master`
    1. `git fetch origin master`
1. Push your changes using the helper script: `./helper.sh deploy_to_env prod


**Deploy to staging:**
1. From within the repo, checkout staging and update staging: 
    1. `git checkout staging`
    1. `git fetch origin staging`
1. Push your changes using the helper script: `./helper.sh deploy_to_env stage


## Developing & Deploying Infrastructure
TODO
