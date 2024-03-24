# Use Node.js as the base image
FROM node:lts-alpine AS build

# Set working directory
WORKDIR /app

# Copy package.json to the working directory
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the application code
COPY . .

# Build the Vue.js application
RUN npm run build  # Or use "yarn build" if using yarn

# Serve the built Vue.js application using nginx
FROM nginx:alpine

# Copy the built files from the build stage to the nginx html directory
COPY --from=build /app/dist /usr/share/nginx/html

# Expose port 80
EXPOSE 80

# Start nginx server in the foreground
CMD ["nginx", "-g", "daemon off;"]
