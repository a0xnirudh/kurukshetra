#!/usr/bin/env python2

import os
import sys
import subprocess

sys.path.insert(1, os.path.abspath(os.path.join(os.path.dirname(__file__),
                '..')))

__author__ = 'AnirudhAnand (a0xnirudh) <a0xnirudh@gmail.com'


class Install:

    def __init__(self):
        self.user = os.environ['USER']
        self.file_location = os.path.abspath(os.path.dirname(__file__))
        self.pip_install_tools = self.file_location + "/pip.txt"
        self.os_install_tools = self.file_location + "/os.txt"
        return

    def run_command(self, command):
        print "[+] Running the command: %s" % command
        os.system(command)

    def install_docker(self):

        """
        This function will install docker and if docker is already installed,it
        will skip the installation.

        """
        print("[+] Installing Docker and necessary supporting plugins")
        print("[+] This could take some time. Please wait ...")
        try:
            subprocess.check_call("docker -v", stdout=subprocess.PIPE,
                                  stderr=subprocess.STDOUT, shell=True)
        except (OSError, subprocess.CalledProcessError):
            try:
                subprocess.check_call("wget -qO- https://get.docker.com/ | sudo sh",
                                      stdout=subprocess.PIPE,
                                      stderr=subprocess.STDOUT, shell=True)

                subprocess.check_call("sudo usermod -aG docker " + self.user,
                                      stdout=subprocess.PIPE,
                                      stderr=subprocess.STDOUT, shell=True)

                subprocess.check_call("sudo usermod -aG docker www-data",
                                      stdout=subprocess.PIPE,
                                      stderr=subprocess.STDOUT, shell=True)

            except (OSError, subprocess.CalledProcessError) as exception:
                print(str(exception))
                exit()

    def docker_image(self):

        """
        This will pull the latest ubuntu (~300 MB) from phusion/baseimage repo.

        We are using the Ubuntu based image because we need the init script to
        run since we are running more than 1 process or else it could become
        zombie process. So an image which is configured with init is necessary.

        """
        print("[+] Docker has successfully installed.")
        print("[+] Now Pulling Image. This could take sometime. Please wait..")
        print("[i] If installation failed, please restart the machine and run again.")

        try:
            subprocess.check_call("docker pull phusion/baseimage:latest",
                                  stdout=subprocess.PIPE,
                                  stderr=subprocess.STDOUT, shell=True)

        except (OSError, subprocess.CalledProcessError) as exception:
            print(str(exception))
            exit()

    def build_docker(self):

        """
        This will build the docker image with the specified Dockerfile which
        adds all the challenges and install necessary applications for running
        the challenges.

        Once building is over, try the command " docker images " and if you can
        see animage named 'Hackademic', installation is successful.

        """
        print("[+] Building and Configuring Docker")
        subprocess.call("docker rmi -f kurukshetra", stdout=subprocess.PIPE,
                        stderr=subprocess.STDOUT, shell=True)
        try:
            subprocess.check_call("docker build -t kurukshetra "+self.file_location,
                                  stdout=subprocess.PIPE,
                                  stderr=subprocess.STDOUT, shell=True)

        except (OSError, subprocess.CalledProcessError) as exception:
            print(str(exception))
            exit()



    def install_os_tools(self):
        install_file = open(self.os_install_tools, "r")
        for i in install_file.readlines():
            self.run_command("sudo apt-get install " + i)

    # TODO: MySQL Configuration ?

    def install_finish(self):
        print("[+] Please restart your machine before contining.")
        print("[+] Installation is Successful. Happy hacking !")


def main():
    install = Install()
    install.install_docker()
    install.docker_image()
    install.build_docker()
    install.install_os_tools()

    install.install_finish()


if __name__ == '__main__':
    main()
