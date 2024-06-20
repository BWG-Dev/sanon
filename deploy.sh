proj="/var/www/sanon.devhabitat.com"
file="$proj/htdocs/deploy.txt"

cd $proj/htdocs/wp-content/themes/sanon
echo "Deployed: $(date)" >> $file
git fetch origin
git pull >> $file
echo '' >> $file
chown -R stage_sanon:www-data .
