FROM selenium/standalone-chrome-debug:latest
USER seluser
WORKDIR /app
ARG APP_HOSTNAME
RUN sudo curl -sL https://deb.nodesource.com/setup_12.x -o nodesource_setup.sh; \
sudo bash nodesource_setup.sh; \
sudo apt-get install -y nodejs jq moreutils; \
sudo chown seluser:seluser /app; 
COPY --chown=seluser:seluser ./ /app/
RUN npm install; \
npm install chai --save-dev; \
jq --arg URL "http://$APP_HOSTNAME/" '.url = $URL' config.json | sponge config.json;
ENTRYPOINT [ "npm", "run", "test-report" ]