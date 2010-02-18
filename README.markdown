#Intro

A Twitter OAUTH implementation for CakePHP. This is a simple app that Authenticates with Twitter OAuth, and persists some Twitter data. It's a starting point to any web based Twitter app.

#Requirements 

Apache server recommended, but not enforced.

PHP 5.2 or higher.

MySQL 4.1 or higher.

#Usage

1.To quickly get up and running, create a DB and then import the users.sql found in the root.

2.Modify app/config/database.php to point to your database.

3.Get a Twitter app registered at http://twitter.com/apps and change your app Key and Secret In:
	app/
		controllers/
			components/
				oauth_consumers/
					twitter_consumer.php