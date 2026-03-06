# Remove existing site
rm -rf ~/public_html/singAlongJam/*
cp -rp ~/SingAlongJam/* ~/public_html/singAlongJam/

# Set up environment
rm -f ~/public_html/singAlongJam/.env
rm -f ~/public_html/singAlongJam/.htaccess
cp -rp ~/SingAlongJam/.env ~/public_html/singAlongJam/
cp -rp ~/SingAlongJam/.htaccess ~/public_html/singAlongJam/