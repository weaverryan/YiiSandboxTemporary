This repository is a temporary fork of https://github.com/WebDevPT/Yii-Blog-Enhanced,
which was simplified. The purpose is to have a dummy project that's easy
to test against.

### Installation

For ease, everything you need is included. But, there are a few things to do:

1) Clone the project onto your computer - somewhere under your web server's
    document root (where `~/Sites` is your document root):

```bash

cd ~/Sites
git clone git://github.com/weaverryan/YiiSandboxTemporary.git sandbox
```

2) Move into the new directory and create a few writable directories

```sh

mkdir protected/runtime
chmod 777 protected/runtime

mkdir assets
chmod 777 assets
```

3) Create your database

First, create a database called `yii_blog_enhanced`. Next, import the
`protected/data/schema.mysql.sql` into it. One way to do that is:

```sh
mysql -u root yii_blog_enhanced < protected/data/schema.mysql.sql
```

Finally, if your `root` MySQL user has no password, you're done. But if
your root user *does* have a password, modify `protected/config/main.php`
and update the username and password.