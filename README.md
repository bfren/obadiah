# Obadiah

Links data exported from [Church Suite](https://www.churchsuite.com) with a [Baserow](https://baserow.io) online database, to provide calendar and rota feeds, including information about teaching series, Bible readings, service names and types.  Also provides a monthly prayer calendar of church members, and an optional daily Bible reading plan.

It also receives form data from a website and inserts them into a separate database to simplify Safer Recruitment practices.

## Authorisation

Passwords are stored hashed using argon2.  You can either do that yourself and paste into the login::admin / login::pass sections of the config, or use the CLI:

```bash
docker exec <<CONTAINER_NAME>> ob pwhash -p "Fred"
```

## About

Obadiah was the faithful palace administrator during the time of Ahab. He supported Elijah and saved 100 prophets from Jezebel. It seems to me like his is a good name to use for a piece of software that supports effective church administration.

(See [1 Kings 18](https://www.biblegateway.com/passage/?search=1%20Kings%2018&version=NIVUK).)

## Licence

> [MIT](https://mit.bfren.dev/2022)

## Copyright

> Copyright (c) 2022-2024 [bfren](https://bfren.dev) (unless otherwise stated)
