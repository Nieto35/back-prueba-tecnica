# Project Overview for SDi. Digital Group
This project is designed to establish a connection with Spotify and provide an authentication method. It allows users to interact with Spotify's API to retrieve various data, such as artist albums, while ensuring secure access through authentication.

## Note

Data validations for the request are performed in the respective value object classes. 
This approach helps maintain control over the data. 

I am not using Request->validate. I couldn't create my own Request class because Scramble 
wouldn't recognize it unless it extended the typical Laravel request.

## Spotify API Documentation

This project utilizes the Spotify Web API documentation to establish a connection and interact with 
Spotify's services. For more information, please refer to the official documentation:

- [https://developer.spotify.com/documentation/web-api](https://developer.spotify.com/documentation/web-api)

## Accessing the API Documentation

```bash
http://app.project.local/docs/api
```
```bash
http://auth.project.local/docs/api
```

## Documentation

All documentation and endpoint management are handled through Scramble. You can access the API documentation via the following virtual hosts:

- [http://app.project.local/docs/api](http://app.project.local/docs/api)
- [http://auth.project.local/docs/api](http://auth.project.local/docs/api)

## Features

- **Spotify Connection**: Establishes a connection with Spotify to retrieve data.
- **Authentication**: Provides a secure method for user authentication.

# ¡¡IMPORTANT!!
routes vhost
```bash
Windows: C:\Windows\System32\drivers\etc\hosts
Linux: /etc/hosts
Mac: /etc/hosts
```

Ensure you have the following entries in your `hosts` file:
```plaintext
127.0.0.1 app.project.local
127.0.0.1 auth.project.local
```
