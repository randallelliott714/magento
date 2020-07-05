<p align="center">
<a href="https://travis-ci.org/openmage/magento-lts"><img src="https://travis-ci.org/openmage/magento-lts.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/openmage/magento-lts"><img src="https://poser.pugx.org/openmage/magento-lts/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/openmage/magento-lts"><img src="https://poser.pugx.org/openmage/magento-lts/license.svg" alt="License"></a>
</p>

# Magento - Long Term Support

This repository is the home of an **unofficial** community-driven project. It's goal is to be a dependable alternative
to the Magento CE official releases which integrates improvements directly from the community while maintaining a high
level of backwards compatibility to the official releases.

**Pull requests with unofficial bug fixes and security patches from the community are encouraged and welcome!**

Though Magento does not follow [Semantic Versioning](http://semver.org/) we aim to provide a workable system for
dependency definition. Each Magento `1.<minor>.<revision>` release will get its own branch (named `1.<minor>.<revision>.x`)
that will be independently maintained with upstream patches and community bug fixes for as long as it makes sense
to do so (based on available resources). For example, Magento version `1.9.3.4` was merged into the `1.9.3.x` branch.

Note, the branches older than `1.9.3.x` that were created before this strategy came into practice are **not maintained**.

## Installation

### Using Composer
Download the latest archive and extract it, clone the repo, or add a composer dependency to your existing project like so:

```json
"openmage/magento-lts": "1.9.4.x"
```

### Using Git
Go to `https://github.com/OpenMage/magento-lts` and fork the project. Enter your web directory and start a new git project utilizing `git init`. Set git remote to your forked repo using `git remote add origin https://github.com/<YOUR GIT USERNAME>/magento-lts`. Issue `git pull origin master`. Then add the official LTS repo as an upstream using `git remote add upstream https://github.com/OpenMage/magento-lts`. Issue `git pull upstream v19.4.4` or the latest version you want to pull. Be sure to check for conflicts before merging all in the next step. Dont forget to git commit and add files to your own forked repo.

When using git you have the ability to fix and test your own code in your own repo. In addition you can periodically sync with the official upstream Magento LTS repo.

```
git init
git remote add origin https://github.com/<YOUR GIT USERNAME>/magento-lts
git pull origin master
git remote add upstream https://github.com/OpenMage/magento-lts
git pull upstream v19.4.4
git add -A && git commit
```

[More Information](http://openmage.github.io/magento-lts/install.html)

## Requirements

- PHP 7.0+ (PHP 7.3 and OpenSSL extension strongly recommended)
- MySQL 5.6+ (8.0+ Recommended)

## Translations

There are some new or changed tranlations, if you want add them to your locale pack please check:

- `app/locale/en_US/*_LTS.csv`

## PhpStorm Factory Helper

This repo includes class maps for the core Magento files in `.phpstorm.meta.php`.
This file is generated using the following commands:

```
$ wget https://files.magerun.net/n98-magerun.phar
$ docker run --rm -u $UID -v $PWD:/var/www/html php:7.0-apache php n98-magerun.phar dev:ide:phpstorm:meta
```

You can add additional meta files in this directory to cover your own project files. See
[PhpStorm advanced metadata](https://www.jetbrains.com/help/phpstorm/ide-advanced-metadata.html)
for more information.

## License

[OSL v3.0](http://opensource.org/licenses/OSL-3.0)

## Public Communication and online Community places

* [Discord](https://discord.gg/EV8aNbU) (maintained by Flyingmana)

## Maintainers

* [Lee Saferite](https://github.com/LeeSaferite)
* [David Robinson](https://github.com/drobinson)
* [Daniel Fahlke aka Flyingmana](https://github.com/Flyingmana)
* [Tymoteusz Motylewski](https://github.com/tmotyl)
* [Sven Reichel](https://github.com/sreichel)
* Pull requests are welcome
