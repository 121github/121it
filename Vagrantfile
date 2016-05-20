# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure('2') do |config|

  # Configure virtualbox to allow $ram-MB memory and symlinks
  config.vm.provider :virtualbox do |vb|
    vb.customize ['modifyvm', :id, '--memory', 2048]
    vb.customize ['setextradata', :id, 'VBoxInternal2/SharedFoldersEnableSymlinksCreate/v-root', '1']
  end

  # Use Ubuntu LTS 14.04 x64
  config.vm.box = 'trusty64_121it'
  config.vm.box_url = 'https://cloud-images.ubuntu.com/vagrant/trusty/current/trusty-server-cloudimg-amd64-vagrant-disk1.box'

  # Set up network for HTTP/HTTPS and MySQL
  config.vm.network :forwarded_port, guest: 80, host: 8084
  config.vm.network :forwarded_port, guest: 443, host: 8447
  config.vm.network :forwarded_port, guest: 3306, host: 3309
  config.vm.network :forwarded_port, guest: 1080, host: 1084
  config.vm.network :forwarded_port, guest: 11211, host: 11215

  config.vm.network :private_network, ip: '192.168.111.226'

  config.vm.synced_folder '.', '/vagrant',
                          owner: 'vagrant',
                          group: 'vagrant',
                          :mount_options => ['dmode=775,fmode=664']

  config.vm.synced_folder 'app/cache', '/vagrant/app/cache',
                          owner: 'vagrant',
                          group: 'vagrant',
                          :mount_options => ['dmode=777,fmode=666']

  config.vm.synced_folder 'app/logs', '/vagrant/app/logs',
                          owner: 'vagrant',
                          group: 'vagrant',
                          :mount_options => ['dmode=777,fmode=666']

  config.vm.provision "shell", path: "vagrant/provision.sh"

  #Actions after mount (and provision.sh if is needed)
  #note the run: "always", this will force vagrant to run the provisioner always
  config.vm.provision "shell", path: "vagrant/after-boot.sh", run: "always"

end