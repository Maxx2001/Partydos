# Traefik gateway

Reverse proxy to publish containers to your local network using a domain name.

Visit the dashboard at: http://traefik.localhost

Documentation: https://doc.traefik.io/traefik/

## Usage

Start Traefik by running the following command in the project
```
bash ./start.sh
```

To stop Traefik (webserver):
```
bash ./stop.sh
```

## MAMP + Traefik
If you want to run Traefik at the same time as MAMP, you have to update your MAMP config to use port `81` instead of `80`.

If Traefik is unable to find any matches in Docker, it will forward the connection to that port, which means you can still use a MAMP project while Traefik is active.
No need to shut down Traefik anymore if you need a MAMP project. 🥳,
