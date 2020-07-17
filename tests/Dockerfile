FROM selenium/standalone-chrome-debug:latest
USER seluser
WORKDIR /app
ARG APP_HOSTNAME
ENV HEADLESS_MODE true
ENV WEB_URL $APP_HOSTNAME
RUN sudo curl -sL https://deb.nodesource.com/setup_12.x -o nodesource_setup.sh; \
sudo bash nodesource_setup.sh; \
sudo apt-get install -y nodejs jq moreutils; \
sudo chown seluser:seluser /app; \
mkdir -p /app/downloads; \
mkdir -p /app/screenshots;
COPY --chown=seluser:seluser ./ /app/
RUN npm install; \
npm run build; 
ENTRYPOINT [ "npm", "run", "ci" ]