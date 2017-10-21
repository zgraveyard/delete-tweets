# Delete Tweets

This is a simple PHP Cli application that can help you to delete your tweets, starting from the first tweet to the current one.

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

The app depends on one main parameter which is the `File` to process mean while all the other parameters are optional.

## Run the APP using Docker

Then run one of the following command :

### Delete all tweets

```bash
docker run --rm -v $(pwd)/config/config.yml:/var/www/config/config.yml \
    -v $(pwd)/tweets.csv:/var/www/tweets.csv zaherg/delete-tweets \
    tweets:delete tweets.csv --all
```

### Delete the first 100 tweets

```bash
docker run --rm -v $(pwd)/config/config.yml:/var/www/config/config.yml \
    -v $(pwd)/tweets.csv:/var/www/tweets.csv zaherg/delete-tweets \
    tweets:delete tweets.csv --offset=0 --limit=100
```

Example for skipping a tweet:

```bash
docker run --rm -v $(pwd)/config/config.yml:/var/www/config/config.yml \
    -v $(pwd)/tweets.csv:/var/www/tweets.csv zaherg/delete-tweets \
    tweets:delete tweets.csv --skip=847336825899278336 --skip=847336825899278331
```

So basically:

1. You will need to map a yml config file (check the config directory for the info you need to supply).
2. You will need to map the csv archive file that you have got from twitter.
3. You can set the offset/limit and adjust them to fill your needs. 
_Please note_: that the limit cant be set more than 4k tweets, other wise it will timeout.
4. You can run the next batch immediately after finishing the set, no need to wait till you can run the command again.
5. remember to change the offset so you can skip the number that you have deleted.
6. provide the ids for the tweets to keep, like the keybase.io verification tweet.
7. you can use the `--all` parameter to delete all your tweets.

## Run the APP using PHP

1. Clone it
2. run `composer install`
3. run `php tweet tweets:delete tweets.csv --offset=0 --limit=100`
