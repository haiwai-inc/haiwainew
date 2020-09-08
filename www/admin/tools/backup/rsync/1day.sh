/usr/bin/rsync -a  --delete --password-file=/etc/cronbash/rsync.secrets weiqi@192.168.100.189::webapps /pub/bak/888estore/new
/usr/bin/rsync -a  --delete --password-file=/etc/cronbash/rsync.secrets weiqi@192.168.100.11::ftp /pub/bak/media.chinagate.com
/usr/bin/rsync -a  --delete --password-file=/etc/cronbash/rsync.secrets weiqi@192.168.100.11::www /pub/bak/www.ttysystems.com
/usr/bin/rsync -a  --delete --password-file=/etc/cronbash/rsync.secrets weiqi@192.168.100.161::svn /pub/bak/svn
/usr/bin/rsync -a  --delete --password-file=/etc/cronbash/rsync.secrets weiqi@192.168.100.161::www /pub/bak/hw
/usr/bin/rsync -a  --delete --password-file=/etc/cronbash/rsync.secrets weiqi@192.168.100.161::demo /pub/bak/haiwai
/usr/bin/rsync -a  --delete --password-file=/etc/cronbash/rsync.secrets weiqi@192.168.100.224::i /pub/bak/i
#/usr/bin/rsync -a  --password-file=/etc/cronbash/rsync.secrets weiqi@192.168.100.212::storage /storage
/bin/sh /etc/cronbash/168talk.sh
