# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
  #https://app.vagrantup.com/ubuntu/boxes/focal64
  config.vm.box = "ubuntu/focal64"
  config.vm.hostname = "public.local"

  config.ssh.insert_key = false

  config.vm.network "forwarded_port", guest: 80, host: 8083
  config.vm.network "private_network", ip: "192.168.50.5"
  config.vm.synced_folder ".", "/var/www/html", {:mount_options => ['dmode=777','fmode=777']}
  config.vm.provision :shell, :path => "bootstrap.sh"
end
