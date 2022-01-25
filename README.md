## Cognacbox with Bedrock (and Sage)

### These were the steps used to create this repo

* Set up a Cognac Box from https://github.com/reddingwebpro/cognacbox 
* `cd` into project folder
* `composer create-project roots/bedrock` https://docs.roots.io/bedrock/master/installation/#getting-started 
* Take files out of bedrock folder
* Update `.env` file with the following (dependant on hostname in your vagrant file for `WP_HOME`)

```
DB_NAME='cognacbox'
DB_USER='root'
DB_PASSWORD='root'

WP_HOME='http://cognacbox' 
```

* Add this to Vagrantfile to get it to point to `/web` instead of `/public`

```
config.vm.provision "shell", inline: <<-SHELL
        # add composer to path
        export PATH="~/.composer/vendor/bin:$PATH"
        # change public to web to comply with Bedrock standards
        mv /var/www/public /var/www/web
        sudo sed -i s,/var/www/public,/var/www/web,g /etc/apache2/sites-available/000-default.conf
        sudo service apache2 restart
    SHELL
```
