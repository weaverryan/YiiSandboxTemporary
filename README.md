This repository is a temporary fork of https://github.com/WebDevPT/Yii-Blog-Enhanced,
which was simplified. The purpose is to have a dummy project to demonstrate
some things using Yii.

### Installation

For ease, everything you need is included. But, there are a few things to do:

1) Create a few writable directories

```sh

mkdir protected/runtime
chmod 777 protected/runtime

mkdir assets
chmod 777 assets
```

2) Create your database

First, create a database called `yii_blog_enhanced`. Next, import the
`protected/data/schema.mysql.sql` into it. One way to do that is:

```sh
mysql -u root yii_blog_enhanced < protected/data/schema.mysql.sql
```

Finally, if your `root` MySQL user has no password, you're done. But if
your root user *does* have a password, modify `protected/config/main.php`
and update the username and password.