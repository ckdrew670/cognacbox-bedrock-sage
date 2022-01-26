## Cognacbox with Bedrock (and Sage)

### These were the steps used to create this repo

* Set up a Cognac Box from https://github.com/reddingwebpro/cognacbox 
* `cd` into project folder
* `composer create-project roots/bedrock` https://docs.roots.io/bedrock/master/installation/#getting-started 
* Take files out of Bedrock folder and move directly into project folder
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

* If you need PHP7 (7.4) rather than 8, there is an earlier version (2.3) of Cognacbox using 7.4. Just replace the Vagrantfile config with this https://github.com/reddingwebpro/cognacbox/blob/v2.3/Vagrantfile . Anything earlier and a Scotchbox may be best.
* `composer install` and `vagrant up`
* You can then set up a fresh Sage theme: 
- Sage 9 https://docs.roots.io/sage/9.x/installation/
- Sage 10 https://docs.roots.io/sage/10.x/installation/#installation-2
- or add in an existing theme

* if you are using an existing theme and want to import assets, copy them over from the existing project (plugins/uploads) and place any DB dump SQL files in the `/web` directory.
