## Projet Symfony

Projet Symfony donné par @BaptisteVasseur à l'ESGI

## Installation

```bash
./install.sh
```
et on croise les doigts

## Utilisateur

| Login        |      Pw       |  Role       |
|--------------|:-------------:|------------:|
| admin        |  aaa          | ROLE_ADMIN  |
| user         |  aaa          | ROLE_USER   |
| Ban speedrun |  aaa          | ROLE_BANEND |


## Infos

Mercure est utilisé pour les réactions (aidé par symfony UX qu'on aime tant :p)
Notre meilleur ami GPT est utilisé pour créer des publications (remplir CHATGPT_API_KEY dans le .env.local)
Enfin, rabbitMQ utilisé piur notifier les utilisateurs de la création d'une publication
