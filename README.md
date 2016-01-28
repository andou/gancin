Gancin
============
A Symfony based application to manage CI from git repositories.
### Abstract
Gancin has been created to manage command-line deploys and CI integration via [Github WebHooks](https://developer.github.com/v3/repos/hooks/).
### Compatibility
To run Gancin you need: php-cli and a linux/unix system with the following commands available: `rsync`,`chown`,`tar`. If you plan to use Gancin in a linux/unix machine chances are that you do not have to worry about.
### Installation
Workin on this, available soon (I know, this is the most important part of a README)
### Command line
You can easily deploy your projects with Gancin from command line.
#### Deployments
##### Deploy a project
```
php bin/console project:deploy <project_name> <:branch> [--grunt]
```
Where `<project_name>` is the name you specified in the config.json file (see below), `<:brach>` is optional and represent the branch you wish to deploy. `--grunt` options tells gancin to run grunt after the deployment in the project working directory.
##### List available projects
```
php bin/console project:list
```
#### Github status check
These command check the Github healt status. If you plan to deploy your projects from Github Code Hosting, you would make sure this is not down.
##### Status
```
php bin/console gitstatus:status
```
Outputs the current status.
##### Last message
```
php bin/console gitstatus:last
```
Outputs the current status along with the message.
##### Last messages
```
php bin/console gitstatus:messages
```
Outputs the last 10 status messages.
### Configuration file
Workin on this, available soon. In the meantime check out `app/Resources/config/config-sample.json`
### Todo
Deploy scheduling, tests, command line project add
