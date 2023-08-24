# You can find the new timestamped tags here: https://hub.docker.com/r/gitpod/workspace-full/tags
FROM gitpod/workspace-full:latest

RUN sudo apt-get install php8.1-mysql -y

# Change your version here
RUN sudo update-alternatives --set php $(which php8.1)