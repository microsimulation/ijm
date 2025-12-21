# 1. Use a modern version of Chrome and Selenium
FROM selenium/standalone-chrome:4.1.2

USER seluser
WORKDIR /app

# 2. Update Node.js to a supported version (v18+)
RUN sudo apt-get update && sudo apt-get install -y curl \
 && sudo curl -sL https://deb.nodesource.com/setup_18.x -o nodesource_setup.sh \
 && sudo bash nodesource_setup.sh \
 && sudo apt-get install -y nodejs jq moreutils \
 && sudo chown seluser:seluser /app \
 && mkdir -p /app/downloads \
 && mkdir -p /app/screenshots

COPY --chown=seluser:seluser ./ /app/

# 3. Install dependencies and build
RUN npm install \
 && npm run build

ARG APP_HOSTNAME
ENV HEADLESS_MODE=true
ENV WEB_URL=$APP_HOSTNAME

ENTRYPOINT [ "npm", "run", "ci" ]
