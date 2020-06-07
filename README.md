# Github to Gitlab mirror repository in PHP

The idea here is to mirror the repository using crontab every minute.
- As you all know about [Free Gitlab Mirror Support Ended on March 22 for external Repositories](https://archive.md/44Rv6). 
- Now in new premium update gitlab had stopped the mirroring of external repository making it's a [premium feature](https://about.gitlab.com/releases/2020/03/12/free-period-for-cicd-external-repositories/), so this is a replication of that feature.

## Installation

Just make sure you have self hosted server or local linux environment.

Also make sure you have Github and Gitlab ssh installed.

* ##### Set `crontab -e`

```bash
* * * * * sh /path/to/mirror.sh >/dev/null 2>&1
```
* Edit mirror.php and set appropriate git ssh config for repository on line number 19 and 22

## Any cause of failure on self-hosted server:
 
 - The public ssh key of www-data should have read and write access to source repository.
 - If the SSH server is not trusted the connection will fail.
 - To avoid non trusted connection to server, run `ssh -T git@gitlab.com` and `ssh -T git@github.com` at least once to get its fingerprint into the local SSH configuration or hosted server ssh configuration.

* More info on duplicating the repository [Duplicating Repository](https://help.github.com/en/github/creating-cloning-and-archiving-repositories/duplicating-a-repository)

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)
