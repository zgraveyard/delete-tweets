# Delete Tweets

This is a simple PHP Cli application that can help you to delete your tweets.

# NO Guarantee USE it at your OWN RISK.



## Have your archive locally:

1. Request your tweet archive from your account settings, more from [here](https://support.twitter.com/articles/20170160).
2. Once you got the email from them and downloaded the file, you will need to extract it.
3. The most important file is `tweets.csv` this file contain all your tweets.

## Register your application:

You will need few information to be able to use this app, first you will need the following:

```YML
  CONSUMER_KEY:
  CONSUMER_SECRET:
  ACCESS_TOKEN:
  ACCESS_TOKEN_SECRET:
```

Which you can get from [Tweeter Developer Apps](https://apps.twitter.com/) website.

## CLI parameter

The app depend on 4 main parameters:

1. File to process.
2. offset: the starting point for the process.
3. limit: the number of records to process.
4. skip: a list of id's for all the tweets that you want to keep, you can pass as many as you want. 

## Run the APP using Docker

Then run the following command :

```bash
docker run --rm -v $(pwd)/config/config.yml:/var/www/config/config.yml \
    -v $(pwd)/tweets.csv:/var/www/tweets.csv zaherg/delete-tweets \
    php tweet tweets:delete tweets.csv --offset=0 --limit=100
```

Example for skipping a tweet:

```bash
docker run --rm -v $(pwd)/config/config.yml:/var/www/config/config.yml \
    -v $(pwd)/tweets.csv:/var/www/tweets.csv zaherg/delete-tweets \
    php tweet tweets:delete tweets.csv --skip=847336825899278336 --skip=847336825899278331
```

So basically:

1. You will need to map a yml config file (check the config directory for the info you need to supply).
2. You will need to map the csv archive file that you have got from twitter.
3. You can set the offset/limit and adjust them to fill your needs. 
_Please note_: that the limit cant be set more than 4k tweets, other wise it will timeout.
4. You can run the next batch immediately after finishing the set, no need to wait till you can run the command again.
5. remember to change the offset so you can skip the number that you have deleted.
6. provide the ids for the tweets to keep, like the keybase.io verification tweet.

## Run the APP using PHP

1. Clone it
2. run `composer install`
3. run `php tweet tweets:delete tweets.csv --offset=0 --limit=100`
