FROM --platform=linux/amd64 node:22.5.1


WORKDIR /app

COPY ./frontend/package*.json .


RUN rm -rf node_modules package-lock.json
RUN npm install
RUN npm install @rollup/rollup-linux-arm64-gnu --save-optional

COPY ./frontend .

EXPOSE 3000

CMD ["npm", "run", "dev"]
