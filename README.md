Gancin
============
A Symfony based application to manage CI from git repositories.
### Abstract
Gancin has been created to manage command-line deploys and CI integration via [Github WebHooks](https://developer.github.com/v3/repos/hooks/).
### Command line
```
php bin/console project:list
```
```
php bin/console project:deploy <project_name> <branch> [--grunt]
```
### Todo
Deploy scheduling, tests, command line project add
