# Remove existing site
rm -rfv ~/public_html/singAlongJam/*
cp -rp ~/SingAlongJam/* ~/public_html/singAlongJam/

# Set Env File
rm -rfv ~/public_html/singAlongJam/.env
cp -rp ~/SingAlongJam/.env ~/public_html/singAlongJam/