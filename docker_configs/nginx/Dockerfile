FROM nginx:latest

RUN apt update && \
    apt upgrade -y && \
    apt install -y ssl-cert && \
    apt clean && \
    rm -rf /var/lib/apt/lists/*

EXPOSE 80 443
