# Rtorrent
BT Client

# Turn this on for SELinux so ssh2_connect works
setsebool -P httpd_can_network_connect on

# Change these directories security context
chcon -t httpd_sys_content_t /var/www/html/phoe721.com/ -R
chcon -t httpd_sys_rw_content_t /var/www/html/phoe721.com/project/rtorrent/log -R
chcon -t httpd_sys_rw_content_t /var/www/html/phoe721.com/project/rtorrent/upload -R
