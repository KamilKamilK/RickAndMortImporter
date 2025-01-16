# Użyj obrazu Node.js jako bazowego
FROM node:18-alpine

# Ustaw katalog roboczy
WORKDIR /app

# Skopiuj pliki package.json i package-lock.json z katalogu aplikacji frontendowej
COPY package*.json ./

# Zainstaluj zależności projektu
RUN npm install

# Skopiuj resztę aplikacji
COPY . .

# Exponuj port aplikacji
EXPOSE 5173

# Uruchom aplikację
CMD ["npm", "run", "dev"]
