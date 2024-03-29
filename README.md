## crowdfunding

crowdfunding is an open source crownfunding API.It provides the basic rest api's need to start a crownfunding backend system.


## Prerequisites
- Docker
- Docker Compose


## Usage
- Clone the project via git clone or download the zip file.
- Go to project directory - cd crownfunding.
- Create a .env and copy content from .env.example to the new .env file.
- Replace the Database connect section of the .env to your mysql variables.
- Run command below on terminal to setup project dependencies
  - docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
  
- Run ./vendor/bin/sail up -d --build to start up the application and keep it running at the backgound.
- Run ./vendor/bin/sail artisan migrate to setup tables on the database.


## Models Explained
- User
  - This model connects to the users table
- Campaign
  - This model connects to the campaign table. A user creates a campaign which needs donations.
- Donations
  - This model connects to the donation table. The donation table hold all donations made for a campaign and the user that made the donation.
  - Table Columns
    - userId - the id of the user that donates
    - campaignId - the id of the campaign the donation is tied to.
    - amount - sum value of what was donated.

## API Documentation
- POSTMAN was used as the documentation tool below is the link to the documentation
  - https://documenter.getpostman.com/view/25552330/2s9YsFCt7H
