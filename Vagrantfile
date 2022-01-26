# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    config.vm.box = "reddingwebpro/cognacbox"
    config.vm.hostname = "cognacbox"
	config.vm.box_version = "2.3"
    config.vm.network "forwarded_port", guest: 80, host: 8080
    config.vm.network "private_network", ip: "192.168.33.10"
    config.vm.synced_folder ".", "/var/www", :mount_options => ["dmode=777", "fmode=777"]
    config.vm.provider "virtualbox" do |v|
        v.memory = 4096
        v.cpus = 4
    config.vm.provision "shell", inline: <<-SHELL
        # add composer to path
        export PATH="~/.composer/vendor/bin:$PATH"
        # change public to web to comply with Bedrock standards
        mv /var/www/public /var/www/web
        sudo sed -i s,/var/www/public,/var/www/web,g /etc/apache2/sites-available/000-default.conf
        sudo service apache2 restart
    SHELL
    end
end