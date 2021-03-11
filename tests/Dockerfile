FROM selenium/standalone-chrome-debug:3.141.59-20200730

USER seluser
WORKDIR /app
RUN sudo curl -sL https://deb.nodesource.com/setup_12.x -o nodesource_setup.sh; \
sudo bash nodesource_setup.sh; \
sudo apt-get install -y nodejs jq moreutils; \
sudo chown seluser:seluser /app; \
mkdir -p /app/downloads; \
mkdir -p /app/screenshots;
COPY --chown=seluser:seluser ./ /app/
RUN npm install; \
npm run build; 

ARG APP_HOSTNAME
ENV HEADLESS_MODE true
ENV WEB_URL $APP_HOSTNAME

ENTRYPOINT [ "npm", "run", "ci" ]
