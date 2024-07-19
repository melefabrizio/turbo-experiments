### Setup

Al primo avvio, dopo i vari monade stack create, composer install, etc, lanciare:

```bash
./start_mercure.sh
```

una volta avviato quello script, killarlo, e da quel momento si può lanciare il comando:

```bash
monade start
```

per avviare il tutto come sempre.

Ricordarsi la env `POSTGRES_URL` nel file `.env.local`.