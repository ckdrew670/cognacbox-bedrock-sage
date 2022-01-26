## Cognacbox with Bedrock (and Sage)

## These were the steps used to create this repo

### Cognacbox
* Set up a Cognacbox from https://github.com/reddingwebpro/cognacbox 
  * Cognacbox uses PHP 8. If you need PHP 7 (7.4), there is an earlier version (2.3) of Cognacbox you can use. Just replace the Vagrantfile config with this https://github.com/reddingwebpro/cognacbox/blob/v2.3/Vagrantfile

### Bedrock setup
* In the project folder run `composer create-project roots/bedrock` (See https://docs.roots.io/bedrock/master/installation/#getting-started)
* This will create a `bedrock` folder inside you project. Moves the contents of this `bedrock` folder directly into the project folder
* Update the `.env` file with your local env, hostname, DB information (make sure DB name matches the one in the Vagrantfile)
* Add the following to the Vagrantfile to get it to point to `/web` instead of `/public`

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

* `composer install` and `vagrant up`. 

*NB: You may encounter an error when you provision the box in future re: being unable to find the public folder but this can be ignored*

* Go to the admin area and run through the WP installation steps

### Sage/theme setup
* You can then set up a fresh Sage theme inf the following ways: 
  * Sage 9 https://docs.roots.io/sage/9.x/installation/
  * Sage 10 https://docs.roots.io/sage/10.x/installation/#installation-2
  * or add in an existing theme by copying it over

*NB: if you are using an existing theme and want to import assets, copy them over from the existing project (plugins/uploads) and place any DB dump SQL files in the `/web` directory.*
