# Rss feed Apache indexing using PHP

Hey everybody!
This project is just a simple Rss feed generator for Apache indexed web server.
I have tested it on a Raspberry Pi Model 3 with Raspbian Buster on it.

For the best reproducibility, it is better you are:
- root on your machine
- using Apache Web Server 2.4 with Indexes enabled for your files and directories
- NOT using a self signed SSL/TLS certificate for your web server


As said above, it is just a simple and easy php script I have created for personal use, but I think it could be useful for anyone else.

Once generated the xml containing the RSS feed, you can pass its URL to a RSS feed reader (e.g. InoReader or Feeder) and there you are!


Credits for initial insights on this to https://gist.github.com/vsoch/4898025919365bf23b6f.
