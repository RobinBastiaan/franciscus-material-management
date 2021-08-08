# Description

The Franciscus Material Management is an application build with the <strong>Symfony Framework</strong> that allows the users to manage the loaning of materials. At our Scouting group, the materials are shared between multiple teams. This management tool primarily makes sure the materials are not used at the same time, while also providing features to allow for easy management in general.  

# Installation

1. This project is set up with PHP version 7.4 in combination with Composer. So make sure they are installed.
1. When the project is downloaded, first don't forget to run `composer install` to download all dependencies.
1. Then you can start a local server to run the project with `symfony server:start`.
1. The next step is to create and populate the database, which can be done with `php bin/console doctrine:database:create` and then `php bin/console doctrine:migrations:migrate -n`.
1. Then you can load the CSV data into the database with the own implemented command `php bin/console import-material`.
1. Finally, to easily open the website you can use `symfony open:local`. 

# Screenshots

Further down the development of this tool, we will provide some screenshots to give you a sense of what it looks like.

# Visualization of the codebase

![Visualization of the codebase](./diagram.svg)
