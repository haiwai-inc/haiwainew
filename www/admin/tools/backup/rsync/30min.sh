/usr/bin/rsync -a  --delete --password-file=/etc/cronbash/rsync.secrets weiqi@192.168.100.224::ads /pub/bak/adserver
/usr/bin/rsync -a  --delete  /mnt/nfs01/* /pub/bak/NAS/
